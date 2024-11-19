<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accountstatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accountstatus = Accountstatus::select(
            'accountstatus.id',
            DB::raw("CONCAT(pen.name, ' ', pen.lastname) as names"), 
            DB::raw("(SELECT MAX(payments.id) 
                  FROM payments 
                  WHERE payments.pensioner_id = accountstatus.pensioner_id) as last_payment_id"), // Último pago ID para buscar la ultima fecha y metodo de ago
            DB::raw("(SELECT DATE_FORMAT(date, '%Y-%m-%d') 
                  FROM payments 
                  WHERE id = (SELECT MAX(id) 
                              FROM payments 
                              WHERE payments.pensioner_id = accountstatus.pensioner_id)) as formatted_date"), // Fecha del último pago
            DB::raw("(SELECT name 
                  FROM paymentmethods 
                  WHERE id = (SELECT paymentmethod_id 
                              FROM payments 
                              WHERE id = (SELECT MAX(id) 
                                          FROM payments 
                                          WHERE payments.pensioner_id = accountstatus.pensioner_id))) as metodo"), // Método de pago del último pago
            'accountstatus.current_balance as saldo',
            'accountstatus.status'
        )
            ->join('pensioners as pen', 'accountstatus.pensioner_id', '=', 'pen.id')
            ->get();

        return view('admin.accountstatus.index', compact('accountstatus'));
    }

    // Metodo para filtrar los pensionistas segun su estado de cuenta
    public function filter(Request $request)
    {
        $query = Accountstatus::select(
            'accountstatus.id',
            DB::raw("CONCAT(pen.name, ' ', pen.lastname) as names"), 
            DB::raw("(SELECT MAX(payments.id) 
                  FROM payments 
                  WHERE payments.pensioner_id = accountstatus.pensioner_id) as last_payment_id"), 
            DB::raw("(SELECT DATE_FORMAT(date, '%Y-%m-%d') 
                  FROM payments 
                  WHERE id = (SELECT MAX(id) 
                              FROM payments 
                              WHERE payments.pensioner_id = accountstatus.pensioner_id)) as formatted_date"), 
            DB::raw("(SELECT name 
                  FROM paymentmethods 
                  WHERE id = (SELECT paymentmethod_id 
                              FROM payments 
                              WHERE id = (SELECT MAX(id) 
                                          FROM payments 
                                          WHERE payments.pensioner_id = accountstatus.pensioner_id))) as metodo"), 
            'accountstatus.current_balance as saldo',
            'accountstatus.status'
        )
            ->join('pensioners as pen', 'accountstatus.pensioner_id', '=', 'pen.id');

        // Aplicar el filtro si se envía un parámetro `status`
        if ($request->has('status')) {
            $query->where('accountstatus.status', $request->status);
        }

        $accountstatus = $query->get();

        return view('admin.accountstatus.index', compact('accountstatus'));
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
