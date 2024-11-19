<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accountstatu;
use App\Models\Consumption;
use Illuminate\Http\Request;

class ConsumptionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Validar los datos
    $request->validate([
        'pensioner_id' => 'required|exists:pensioners,id',
        'total' => 'required|numeric|min:0',
        'date' => 'required|date',
    ]);

    // Crear el consumo
    $consumption = Consumption::create([
        'pensioner_id' => $request->pensioner_id,
        'total' => $request->total,
        'date' => $request->date,
    ]);

    // Reducir el saldo en accountstatus
    $accountStatus = Accountstatu::where('pensioner_id', $request->pensioner_id)->first();

    if ($accountStatus) {
        $accountStatus->current_balance -= $request->total;
        $accountStatus->save();
    } else {
        return back()->withErrors('No se encontró un estado de cuenta para este pensionista.');
    }

    return redirect()->route('admin.consumptions.index')->with('success', 'Consumo registrado y saldo actualizado.');
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
