<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $payments = DB::select("
        // SELECT
        //     payments.id,
        //     CONCAT(pen.name, ' ', pen.lastname) AS names,
        //     payments.date,
        //     mp.name AS mpaym,
        //     payments.total,
        //     payments.description
        // FROM payments
        // JOIN pensioners AS pen ON payments.pensioner_id = pen.id
        // JOIN paymentmethods AS mp ON payments.paymentmethod_id = mp.id
        // ");
        $payments = Payment::select(
            'payments.id',
             DB::raw("CONCAT(pen.name, ' ', pen.lastname) as names"), // nombre y apellido
            'payments.date',
            'mp.name as mpaym',
            'payments.total',
            'payments.description'
        )
        ->join('pensioners as pen','payments.pensioner_id','=','pen.id')
        ->join('paymentmethods as mp','payments.paymentmethod_id','=','mp.id')
        ->get();
        return  view('admin.payments.index', compact('payments'));
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
        //
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
