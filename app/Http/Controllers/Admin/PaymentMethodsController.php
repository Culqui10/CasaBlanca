<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Paymentmethod;
use Illuminate\Http\Request;

class PaymentMethodsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paymentmethods = Paymentmethod::all();
        return view('admin.paymentmethods.index', compact('paymentmethods'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.paymentmethods.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Paymentmethod::create($request->all());
        return redirect()->route('admin.paymentmethods.index')->with('success', 'Metodo de pago registrado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $paymeth =  Paymentmethod::find($id);
        return  view('admin.paymentmethods.edit', compact('paymeth'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $paymeth  = Paymentmethod::find($id);
        $paymeth->update($request->all());
        return redirect()->route('admin.paymentmethods.index')->with('success', 'Actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //dd($id);
        $paymeth  = Paymentmethod::find($id);
        $paymeth->delete();
        return redirect()->route('admin.paymentmethods.index')->with('success', 'Eliminado correctamente');
    }
}
