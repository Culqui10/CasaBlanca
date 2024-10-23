<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Typefood;
use Illuminate\Http\Request;

class TypeFoodsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $typefoods = Typefood::all();
        return  view('admin.typefoods.index', compact('typefoods'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return  view('admin.typefoods.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Typefood::create($request->all());
        return redirect()->route('admin.typefoods.index')->with('success', 'Tipo de comida registrado correctamente');
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
        $typefoods = Typefood::find($id);
        return view('admin.typefoods.edit', compact('typefoods'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $typefoods = Typefood::find($id);
        $typefoods->update($request->all());
        return redirect()->route('admin.typefoods.index')->with('success', 'Actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $typefoods  = Typefood::find($id);
        $menus = Menu::where('typefood_id', $id)->count();
        if ($menus > 0) {
            return redirect()->route('admin.typefoods.index')->with('error', 'Tipo de comida contiene menus');
        } else {
            $typefoods->delete();
            return redirect()->route('admin.typefoods.index')->with('success', 'Eliminado correctamente');
        }
    }
}
