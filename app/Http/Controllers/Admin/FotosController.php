<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Foto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FotosController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $fotos = Foto::where('producto_id', $id)->get();
        return view('admin.productos.fotos', compact('fotos'));
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
        $idd = explode("-", $id);
        $p = [
            $idd[0]
        ];
        DB::update('update fotos set perfil="0" where producto_id=?', $p);

        $producto = Foto::find($idd[1]);
        $producto->update([
            'perfil' => '1'
        ]);
        return redirect()->route('admin.productos.index')->with('success', 'Producto actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $idd = explode("-", $id);

        $fotos = Foto::where('producto_id', $idd[0])->where('perfil','1')->count();

        if ($fotos == 1) {
            $p = [
                $idd[0]
            ];
            DB::update('update fotos set perfil="1" where url_foto="-" and producto_id=?', $p);
            $photo = Foto::find($idd[1]);
            $photo->delete();
        } else {
            $photo = Foto::find($idd[1]);
            $photo->delete();
        }
        return redirect()->route('admin.productos.index')->with('success', 'Foto eliminada correctamente');
    }
}
