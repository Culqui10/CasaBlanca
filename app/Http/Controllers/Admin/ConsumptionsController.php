<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accountstatu;
use App\Models\Accountstatus;
use App\Models\Consumption;
use App\Models\Consumptiondetail;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConsumptionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $consumptions = Consumption::select(
            'consumptions.id',
            DB::raw("CONCAT(pen.name, ' ', pen.lastname) as names"),
            DB::raw("DATE_FORMAT(consumptions.date,'%Y-%m-%d') as formatted_date"),
            DB::raw("CASE 
                WHEN EXISTS (
                    SELECT 1 
                    FROM consumptiondetails cd
                    INNER JOIN menus m ON cd.menu_id = m.id
                    INNER JOIN typefoods tf ON m.typefood_id = tf.id
                    WHERE cd.consumption_id = consumptions.id AND tf.name = 'desayuno'
                ) THEN 'Sí' 
                ELSE 'No' 
            END as desayuno"), // Verifica si tiene desayuno
            DB::raw("CASE 
                WHEN EXISTS (
                    SELECT 1 
                    FROM consumptiondetails cd
                    INNER JOIN menus m ON cd.menu_id = m.id
                    INNER JOIN typefoods tf ON m.typefood_id = tf.id
                    WHERE cd.consumption_id = consumptions.id AND tf.name = 'almuerzo'
                ) THEN 'Sí' 
                ELSE 'No' 
            END as almuerzo"), // Verifica si tiene almuerzo
            DB::raw("CASE 
                WHEN EXISTS (
                    SELECT 1 
                    FROM consumptiondetails cd
                    INNER JOIN menus m ON cd.menu_id = m.id
                    INNER JOIN typefoods tf ON m.typefood_id = tf.id
                    WHERE cd.consumption_id = consumptions.id AND tf.name = 'cena'
                ) THEN 'Sí' 
                ELSE 'No' 
            END as cena"), // Verifica si tiene cena
            'consumptions.total'
        )
            ->join('pensioners as pen', 'consumptions.pensioner_id', '=', 'pen.id')
            ->get()

            ->map(function ($consumption) {
                // Carga detalles de menú para desayuno, almuerzo y cena
                $consumption->desayuno_details = $this->getMenuDetails($consumption->id, 'desayuno');
                $consumption->almuerzo_details = $this->getMenuDetails($consumption->id, 'almuerzo');
                $consumption->cena_details = $this->getMenuDetails($consumption->id, 'cena');
                return $consumption;
            });

        return view('admin.consumptions.index', compact('consumptions'));
    }

    // Método privado para obtener detalles del menú
    private function getMenuDetails($consumptionId, $typefoodName)
    {
        return DB::table('consumptiondetails as cd')
            ->join('menus as m', 'cd.menu_id', '=', 'm.id')
            ->join('typefoods as tf', 'm.typefood_id', '=', 'tf.id')
            ->where('cd.consumption_id', $consumptionId)
            ->where('tf.name', $typefoodName)
            ->select(
                'm.name as menu_name',
                'cd.aditional as adicional',
                'cd.aditional_cost as aditional_cost',
                'm.price as price',
                DB::raw('(m.price + IFNULL(cd.aditional_cost, 0)) as total')
            )
            ->first();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $menu = Menu::pluck('name', 'id');
        return view('admin.consumptions.create', compact('menu'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar los datos
        $request->validate([
            'pensioner_id' => 'required|exists:pensioners,id',
            'menu_id' => 'required|exists:menus,id',
            'date' => 'required|date',
            'aditional_cost' => 'nullable|numeric|min:0',
            'aditional' => 'nullable|string|max:255',
        ]);

        // Buscar si ya existe un consumo para la fecha y el pensionista
        $consumption = Consumption::where('pensioner_id', $request->pensioner_id)
            ->where('date', $request->date)
            ->first();

        if (!$consumption) {
            // Si no existe, crear un nuevo registro de consumo
            $consumption = Consumption::create([
                'pensioner_id' => $request->pensioner_id,
                'total' => 0, // El total se calculará al sumar los detalles
                'date' => $request->date,
            ]);
        }

        // Crear un detalle para el consumo
        $menu = Menu::find($request->menu_id);

        ConsumptionDetail::create([
            'consumption_id' => $consumption->id,
            'menu_id' => $menu->id,
            'aditional' => $request->aditional,
            'aditional_cost' => $request->aditional_cost ?? 0,
        ]);

        // Actualizar el total del consumo
        $consumption->total += $menu->price + ($request->aditional_cost ?? 0);
        $consumption->save();

        // Reducir el saldo en accountstatus
        $accountStatus = Accountstatus::where('pensioner_id', $request->pensioner_id)->first();

        if ($accountStatus) {
            $accountStatus->current_balance -= $menu->price + ($request->aditional_cost ?? 0);

            // Actualizar el estado según el saldo actual
            if ($accountStatus->current_balance < 0) {
                $accountStatus->status = 'pendiente';
            } elseif ($accountStatus->current_balance <= 20) {
                $accountStatus->status = 'agotándose';
            } else {
                $accountStatus->status = 'suficiente';
            }

            $accountStatus->save();
        } else {
            return back()->withErrors('No se encontró un estado de cuenta para este pensionista.');
        }

        return redirect()->route('admin.consumptions.index')->with('success', 'Consumo registrado correctamente.');
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
    public function edit($id)
{
    $consumption = Consumption::with('details.menu.typefood')->findOrFail($id);

    // Cargar los datos de los detalles clasificados por tipo de comida
    $details = ['desayuno' => [], 'almuerzo' => [], 'cena' => []]; // Valores predeterminados

    foreach ($consumption->details as $detail) {
        $typefood = strtolower($detail->menu->typefood->name);
        $details[$typefood] = [
            'menu_id' => $detail->menu_id,
            'aditional' => $detail->aditional,
            'aditional_cost' => $detail->aditional_cost,
            'price' => $detail->menu->price,
            'total' => $detail->menu->price + ($detail->aditional_cost ?? 0),
        ];
    }

    // Agrupar menús por tipo de comida y asegurarse de que las claves estén completas
    $menus = Menu::with('typefood')->get()->groupBy(fn($menu) => strtolower($menu->typefood->name));

    // Asegurar que las claves 'desayuno', 'almuerzo', y 'cena' existan en $menus
    $menus = [
        'desayuno' => $menus['desayuno'] ?? collect(),
        'almuerzo' => $menus['almuerzo'] ?? collect(),
        'cena' => $menus['cena'] ?? collect(),
    ];

    return view('admin.consumptions.edit', compact('consumption', 'details', 'menus'));
}


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validar los datos
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'aditional' => 'nullable|string|max:255',
            'aditional_cost' => 'nullable|numeric|min:0',
        ]);

        $consumption = Consumption::findOrFail($id);

        // Obtener el detalle correspondiente
        $detail = ConsumptionDetail::where('consumption_id', $consumption->id)
            ->where('menu_id', $request->menu_id)
            ->first();

        if (!$detail) {
            return back()->withErrors('No se encontró el detalle de consumo para actualizar.');
        }

        $menu = Menu::find($request->menu_id);

        // Actualizar los detalles del consumo
        $previousTotal = $detail->aditional_cost + $menu->price;

        $detail->aditional = $request->aditional;
        $detail->aditional_cost = $request->aditional_cost ?? 0;
        $detail->save();

        // Actualizar el total del consumo
        $consumption->total = $consumption->total - $previousTotal + ($menu->price + ($request->aditional_cost ?? 0));
        $consumption->save();

        // Actualizar el estado de cuenta
        $accountStatus = Accountstatus::where('pensioner_id', $consumption->pensioner_id)->first();
        if ($accountStatus) {
            $accountStatus->current_balance = $accountStatus->current_balance + $previousTotal - ($menu->price + ($request->aditional_cost ?? 0));

            // Actualizar el estado según el saldo actual
            if ($accountStatus->current_balance < 0) {
                $accountStatus->status = 'pendiente';
            } elseif ($accountStatus->current_balance <= 20) {
                $accountStatus->status = 'agotándose';
            } else {
                $accountStatus->status = 'suficiente';
            }

            $accountStatus->save();
        }

        return redirect()->route('admin.consumptions.index')->with('success', 'Consumo actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $consumption = Consumption::findOrFail($id);

        // Eliminar los detalles del consumo
        $details = ConsumptionDetail::where('consumption_id', $consumption->id)->get();
        foreach ($details as $detail) {
            // Revertir los totales en la tabla estado de cuenta
            $menu = Menu::find($detail->menu_id);
            $totalDetail = $menu->price + $detail->aditional_cost;

            $accountStatus = Accountstatus::where('pensioner_id', $consumption->pensioner_id)->first();
            if ($accountStatus) {
                $accountStatus->current_balance += $totalDetail;

                // Actualizar el estado según el saldo actual
                if ($accountStatus->current_balance < 0) {
                    $accountStatus->status = 'pendiente';
                } elseif ($accountStatus->current_balance <= 20) {
                    $accountStatus->status = 'agotándose';
                } else {
                    $accountStatus->status = 'suficiente';
                }

                $accountStatus->save();
            }

            // Eliminar el detalle
            $detail->delete();
        }

        // Eliminar el consumo
        $consumption->delete();

        return redirect()->route('admin.consumptions.index')->with('success', 'Consumo eliminado correctamente.');
    }
}
