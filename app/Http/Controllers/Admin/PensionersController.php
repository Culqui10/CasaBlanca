<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pensioner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PensionersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pensioners = DB::select("

        SELECT
        pensioners.id,
        CONCAT(pensioners.name, ' ', pensioners.lastname) AS names,
        pensioners.phone,
        pensioners.location,
        pensioners.name_representative,
        pensioners.phone_representative,
        pensioners.date
        FROM pensioners
        ");
        
        return view('admin.pensioners.index', compact('pensioners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Obtener la fecha actual usando Carbon
        //$currentDate = \Carbon\Carbon::now()->format('Y-m-d');
        return view('admin.pensioners.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'phone' => 'required|regex:/^[0-9]{9,15}$/',
            'location' => 'required|string|max:255',
            'name_representative' => 'nullable|string|max:255',
            'phone_representative' => 'nullable|regex:/^[0-9]{9,15}$/',
            'date' => 'required|date',
        ]);
        Pensioner::create($validatedData);
        //Pensioner::create($request->all());
        return redirect()->route('admin.pensioners.index')->with('success', 'Pensionista registrado correctamente');
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

        $pensioner = Pensioner::findOrFail($id);
        return view('admin.pensioners.edit', compact('pensioner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pens = Pensioner::find($id);
        $pens->update($request->all());
        return redirect()->route('admin.pensioners.index')->with('success', 'Pensionista actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pens =  Pensioner::find($id);
        $pens->delete();
        return redirect()->route('admin.pensioners.index')->with('success', 'Pensionista eliminado');
    }
    
    public function search(Request $request)
{
    Log::info('Search Query:', ['query' => $request->input('query')]);

    $query = $request->input('query');
    $pensioner = Pensioner::where('name', 'LIKE', "%$query%")
        ->orWhere('lastname', 'LIKE', "%$query%")
        ->first();

    if ($pensioner) {
        Log::info('Pensionista encontrado:', ['pensioner' => $pensioner]);
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $pensioner->id,
                'name' => $pensioner->name . ' ' . $pensioner->lastname
            ]
        ]);
    } else {
        Log::warning('Pensionista no encontrado.', ['query' => $query]);
        return response()->json(['success' => false, 'message' => 'Pensionista no encontrado']);
    }
}

}
