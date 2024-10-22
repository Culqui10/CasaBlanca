<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Familia;
use App\Models\Producto;
use Illuminate\Http\Request;

class CategoriasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categorias = Categoria::select(
            'categorias.id', 
            'categorias.name', 
            'b.name as familianame',  
            'categorias.description'
            )
            ->join('familias as b','categorias.familia_id','=','b.id')->get();
        return view('admin.categorias.index', compact('categorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $familias = Familia::pluck('name','id');
        return view('admin.categorias.create', compact('familias'));
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Categoria::create($request->all());
        return redirect()->route('admin.categorias.index')->with('success', 'Categoria registrada');
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
        $categorias = Categoria::find($id);
        $familias = Familia::pluck('name','id');
        return view('admin.categorias.edit', compact('categorias','familias'));


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $categorias = Categoria::find($id);
        $categorias->update($request->all());
        return redirect()->route('admin.categorias.index')->with('success', 'Categoria editada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $categorias = Categoria::find($id);
        $productos = Producto::where('categoria_id',$id)->count();
        if($productos > 0){
            return redirect('admin/categoria')->with('error','Categoria contiene Products');
        }else{
            $categorias->delete();
            return redirect('admin/categoria')->with('Success','Categoria eliminada');
            
        }

    }
    public function categoriabyfamilia(String $id){
        $categorias = Categoria::where('familia_id', $id)->get();
        return $categorias;
    }
}
