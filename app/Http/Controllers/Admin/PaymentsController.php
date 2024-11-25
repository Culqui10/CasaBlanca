<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accountstatus;
use App\Models\Payment;
use App\Models\Paymentmethod;
use App\Models\Pensioner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener los pagos ordenados por fecha e ID
        $payments = Payment::select(
            'payments.id',
            DB::raw("CONCAT(pen.name, ' ', pen.lastname) as names"), // nombre y apellido
            DB::raw("DATE_FORMAT(payments.date, '%Y-%m-%d') as formatted_date"),
            'mp.name as mpaym',
            'payments.total',
            'payments.description',
            'payments.pensioner_id'
        )
            ->join('pensioners as pen', 'payments.pensioner_id', '=', 'pen.id')
            ->join('paymentmethods as mp', 'payments.paymentmethod_id', '=', 'mp.id')
            ->orderBy('payments.pensioner_id') // Ordenar por pensionista
            ->orderBy('payments.date') // Ordenar por fecha
            ->orderBy('payments.id') // Asegurar el orden
            ->get();

        // Calcular el saldo acumulativo para cada registro
        $saldoPorPensionista = []; // Array para rastrear el saldo de cada pensionista
        foreach ($payments as $payment) {
            // Inicializar saldo para cada nuevo pensionista
            if (!isset($saldoPorPensionista[$payment->pensioner_id])) {
                $saldoPorPensionista[$payment->pensioner_id] = 0;
            }

            // Sumar el monto del pago al saldo acumulativo del pensionista
            $saldoPorPensionista[$payment->pensioner_id] += $payment->total;
        }

        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $methodpayment = Paymentmethod::pluck('name', 'id');
        return view('admin.payments.create', compact('methodpayment'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Validar los datos de entrada
    $request->validate([
        'pensioner_id' => 'required|exists:pensioners,id',
        'paymentmethod_id' => 'required|exists:paymentmethods,id',
        'price' => 'required|numeric|min:0',
        'date' => 'required|date',
        'description' => 'nullable|string|max:255',
    ]);

    // Crear el pago
    $payment = Payment::create([
        'pensioner_id' => $request->pensioner_id,
        'paymentmethod_id' => $request->paymentmethod_id,
        'total' => $request->price,
        'date' => $request->date,
        'description' => $request->description,
    ]);

    // Crear o actualizar el estado de cuenta del pensionista
    $accountStatus = Accountstatus::firstOrCreate(
        ['pensioner_id' => $request->pensioner_id],
        [
            'current_balance' => 0, // Inicialmente en 0
            'status' => 'pendiente',
        ]
    );

    // Sumar el pago al saldo actual
    $accountStatus->current_balance += $request->price;

    // Calcular el estado basado en el nuevo saldo
    if ($accountStatus->current_balance < 0) {
        $accountStatus->status = 'pendiente';
    } elseif ($accountStatus->current_balance <= 20) {
        $accountStatus->status = 'agotándose';
    } else {
        $accountStatus->status = 'suficiente';
    }

    $accountStatus->save();

    return redirect()->route('admin.payments.index')->with('success', 'Pago registrado correctamente.');
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Obtener el pago a editar
        $payment = Payment::with('pensioner', 'paymentmethod')->findOrFail($id);
        $methodpayment = Paymentmethod::pluck('name', 'id');

        //dd($payment);
        return view('admin.payments.edit', compact('payment', 'methodpayment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validar los datos del formulario
        $request->validate([
            'paymentmethod_id' => 'required|exists:paymentmethods,id',
            'price' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'nullable|string|max:255',
        ]);

        // Encontrar el pago
        $payment = Payment::findOrFail($id);

        // Calcular la diferencia en el monto para actualizar el saldo
        $difference = $request->price - $payment->total;

        // Actualizar el pago
        $payment->update([
            'paymentmethod_id' => $request->paymentmethod_id,
            'total' => $request->price,
            'date' => $request->date,
            'description' => $request->description,
        ]);

        // Actualizar el estado de cuenta
        $accountstatus = Accountstatus::where('pensioner_id', $payment->pensioner_id)->firstOrFail();
        $accountstatus->current_balance += $difference;

        // Actualizar el estado basado en el saldo
        if ($accountstatus->current_balance < 0) {
            $accountstatus->status = 'pendiente';
        } elseif ($accountstatus->current_balance <= 20) {
            $accountstatus->status = 'agotándose';
        } else {
            $accountstatus->status = 'suficiente';
        }

        // Guardar cambios
        $accountstatus->save();

        return redirect()->route('admin.payments.index')->with('success', 'Pago actualizado correctamente y saldo ajustado.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Buscar el pago
        $payment = Payment::findOrFail($id);

        // Actualizar el estado de cuenta restando el monto del pago
        $accountstatus = Accountstatus::where('pensioner_id', $payment->pensioner_id)->firstOrFail();
        $accountstatus->current_balance -= $payment->total;

        // Actualizar el estado basado en el saldo restante
        if ($accountstatus->current_balance < 0) {
            $accountstatus->status = 'pendiente';
        } elseif ($accountstatus->current_balance <= 20) {
            $accountstatus->status = 'agotándose';
        } else {
            $accountstatus->status = 'suficiente';
        }

        // Guardar cambios en el estado de cuenta
        $accountstatus->save();

        $accountstatus = Accountstatus::where('payment_id', $id)->count();
        if ($accountstatus > 0) {
            return redirect()->route('admin.payments.index')->with('error', 'El pago solo puede ser editado');
        } else {
            $payment->delete();
            return redirect()->route('admin.payments.index')->with('success', 'Pago eliminado correctamente y saldo ajustado.');
        }
    }
}
