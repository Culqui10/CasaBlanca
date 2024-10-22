<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Familia;
use App\Models\Foto;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = DB::select("
        SELECT p.id, fo.url_foto, p.name, p.price, b.name as categorianame, p.description
            FROM productos p
            INNER JOIN categorias as b ON p.categoria_id=b.id
            LEFT JOIN fotos fo ON (fo.producto_id=p.id AND fo.perfil=1)
        ");
        return view('admin.productos.index', compact('productos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $familiaSQL = Familia::whereRaw('id IN (SELECT familia_id FROM categorias)')->get();
        $familias = Familia::pluck('name', 'id');
        $categorias = Categoria::where('familia_id', $familiaSQL->first()->id)->pluck('name', 'id');

        return view('admin.productos.create', compact('familias', 'categorias'));
    }

    public function store(Request $request)
    {
        $producto = Producto::create([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'familia_id' => $request->familia_id,
            'categoria_id' => $request->categoria_id,
        ]);
        $id = $producto->id;
        Foto::create([
            'url_foto' => '-',
            'perfil' => '1',
            'producto_id' => $id
        ]);
        return redirect()->route('admin.productos.index')->with('success', 'Producto registrado');
    }

    public function edit(string $id)
    {
        $productos = Producto::find($id);
        $categorias = Categoria::pluck('name', 'id');
        $familias = Familia::pluck('name', 'id');
        return view('admin.productos.edit', compact('productos', 'categorias', 'familias'));
    }

    function update(Request $request, string $id)
    {
        if ($request->categoria_id != '') {
            $producto = Producto::find($id);
            $url = '';

            if ($request->file('url_foto') != '') {
                $img = $request->file('url_foto')->store('public/productos');
                $url = Storage::url($img);
                $p = [
                    $id
                ];
                DB::update('update fotos set perfil="0" where producto_id=?', $p);
                Foto::create([
                    'url_foto' => $url,
                    'perfil' => 1,
                    'producto_id' => $producto->id
                ]);
            }
            $producto->update([
                'name' => $request->name,
                'price' => $request->price,
                'description' => $request->description,
                'familia_id' => $request->familia_id,
                'categoria_id' => $request->categoria_id,
            ]);
            return redirect()->route('admin.productos.index')->with('success', 'Producto actualizado correctamente');
        } else {
            return redirect()->route('admin.productos.index')->with('error', 'Seleccione una familia');
        }
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $foto = Foto::where('producto_id', $id);
        $foto->delete();
        $producto = Producto::find($id);
        $producto->delete();
        return redirect()->route('admin.productos.index')->with('success', 'Producto eliminado correctamente');
    }
}
