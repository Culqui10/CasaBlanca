<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Typefood;
use Illuminate\Http\Request;

class MenusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $menus = Menu::select(
            'menus.id',
            'menus.name',
            'menus.price',
            'tf.name as tfood',
            'menus.description'
        )
            ->join('typefoods as tf', 'menus.typefood_id', '=', 'tf.id')->get();
        return view('admin.menus.index', compact('menus'));
    }


    public function create()
    {
        $typefood = Typefood::pluck('name', 'id');
        return view('admin.menus.create', compact('typefood'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Menu::create($request->all());
        return redirect()->route('admin.menus.index')->with('success', 'Menu registrado correctamente');
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
        $menus = Menu::find($id);
        $typefood = Typefood::pluck('name', 'id');
        return  view('admin.menus.edit', compact('menus', 'typefood'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $menus  = Menu::find($id);
        $menus->update($request->all());
        return  redirect()->route('admin.menus.index')->with('success', 'Menu actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $menus  = Menu::find($id);
        $menus->delete();
        return  redirect()->route('admin.menus.index')->with('success', 'Menu eliminado');
    }

    public function filterMenus(Request $request)
    {
        $typefood = $request->get('typefood');

        $menus = Menu::whereHas('typefood', function ($query) use ($typefood) {
            $query->where('name', $typefood);
        })->get();

        return response()->json([
            'success' => true,
            'menus' => $menus
        ]);
    }

    public function getMenuPrice($menuId)
    {
        $menu = Menu::find($menuId);

        if ($menu) {
            return response()->json([
                'success' => true,
                'price' => $menu->price,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Menú no encontrado.',
        ]);
    }
}
