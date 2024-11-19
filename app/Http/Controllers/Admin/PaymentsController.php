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
        $payments = Payment::select(
            'payments.id',
            DB::raw("CONCAT(pen.name, ' ', pen.lastname) as names"), // nombre y apellido
            'payments.date',
            'mp.name as mpaym',
            'payments.total',
            'ast.current_balance as saldo',
            'payments.description'
        )
            ->join('pensioners as pen', 'payments.pensioner_id', '=', 'pen.id')
            ->join('paymentmethods as mp', 'payments.paymentmethod_id', '=', 'mp.id')
            ->join('accountstatus as ast', 'payments.id', '=', 'ast.payment_id')
            ->get();
        return  view('admin.payments.index', compact('payments'));
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
        // Validar los datos del formulario
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

        // Crear o actualizar el estado de cuenta
        $accountstatus = Accountstatus::firstOrCreate(
            ['pensioner_id' => $request->pensioner_id], // Buscar por pensioner_id
            [
                'current_balance' => 0, // Saldo inicial
                'payment_id' => $payment->id, // Relacionar con el pago recién creado
                'status' => 'todos', // Estado inicial
            ]
        );

        // Actualizar el saldo
        $accountstatus->current_balance += $request->price;

        // Actualizar el estado según el saldo actual
        if ($accountstatus->current_balance < 0) {
            $accountstatus->status = 'pendiente';
        } elseif ($accountstatus->current_balance <= 20) {
            $accountstatus->status = 'agotándose';
        } else {
            $accountstatus->status = 'todos';
        }
        // Guardar los cambios
        $accountstatus->save();

        return redirect()->route('admin.payments.index')->with('success', 'Pago registrado correctamente y saldo actualizado.');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
