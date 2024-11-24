<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accountstatus;
use App\Models\Consumption;
use App\Models\Pensioner;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Total de pensionistas
        $totalPensionistas = Pensioner::count();

        // Pagos pendientes y agotándose
        $pagosPendientes = Accountstatus::where('status', 'pendiente')->count();
        $pagosAgotandose = AccountStatus::where('status', 'agotándose')->count();
        $pagosSuficientes = AccountStatus::where('status', 'suficiente')->count();

        // Obtener los años únicos de los registros
        $anios = Pensioner::selectRaw('YEAR(date) as anio')
            ->distinct()
            ->orderBy('anio', 'desc')
            ->pluck('anio');

        // Filtrar por el año seleccionado (o por defecto, el actual)
        $anioSeleccionado = $request->input('anio', date('Y')); // Obtener el año del filtro o usar el actual

        // Gráfico de barras: cantidad de pensionistas por mes para el año seleccionado
        $pensionistasPorMes = Pensioner::selectRaw('MONTH(date) as mes, COUNT(*) as total')
            ->whereYear('date', $anioSeleccionado) // Filtrar por año
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total', 'mes');

        $datosPensionistas = [
            'labels' => collect(range(1, 12))->map(function ($mes) {
                return Carbon::create()->month($mes)->translatedFormat('F');
            })->toArray(), // Etiquetas de todos los meses en español
            'datasets' => [
                [
                    'label' => 'Pensionistas',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.5)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'data' => collect(range(1, 12))->map(function ($mes) use ($pensionistasPorMes) {
                        return $pensionistasPorMes[$mes] ?? 0; // Devolver 0 si no hay datos para el mes
                    })->toArray(),
                ],
            ],
        ];

        // Obtener el tipo de comida seleccionado (por defecto "todos")
        $tipoComidaSeleccionado = $request->input('tipoComida', 'todos');

        // Gráfico de barras: menús más consumidos
        $query = Consumption::join('consumptiondetails', 'consumptions.id', '=', 'consumptiondetails.consumption_id')
            ->join('menus', 'consumptiondetails.menu_id', '=', 'menus.id')
            ->join('typefoods', 'menus.typefood_id', '=', 'typefoods.id')
            ->selectRaw('menus.name as menu, COUNT(*) as total');

        // Filtrar por tipo de comida si no es "todos"
        if ($tipoComidaSeleccionado !== 'todos') {
            $query->where('typefoods.name', $tipoComidaSeleccionado);
        }

        $menusMasConsumidos = $query
            ->groupBy('menus.name')
            ->orderByDesc('total')
            ->limit(5)
            ->pluck('total', 'menu');

        $datosMenus = [
            'labels' => $menusMasConsumidos->keys()->toArray(),
            'datasets' => [
                [
                    'label' => 'Menús Más Consumidos',
                    'backgroundColor' => 'rgba(255, 99, 132, 0.5)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'data' => $menusMasConsumidos->values()->toArray(),
                ],
            ],
        ];

        // Tipos de comida para el filtro
        $tiposDeComida = DB::table('typefoods')->pluck('name');

        return view('admin.index', compact(
            'totalPensionistas',
            'pagosPendientes',
            'pagosAgotandose',
            'pagosSuficientes',
            'datosPensionistas',
            'datosMenus',
            'anios',
            'anioSeleccionado',
            'tipoComidaSeleccionado',
            'tiposDeComida'
        ));
    }


    public function getPensionersByAnio(Request $request)
    {
        $anio = $request->input('anio', date('Y')); // Año seleccionado o actual

        // Obtener los años únicos de los pensionistas desde el campo `date`
        $anios = Pensioner::selectRaw('YEAR(date) as anio')
            ->distinct()
            ->orderBy('anio', 'desc')
            ->pluck('anio');

        // Obtener los registros agrupados por mes del año seleccionado desde `date`
        $pensionistasPorMes = Pensioner::selectRaw('MONTH(date) as mes, COUNT(*) as total')
            ->whereYear('date', $anio)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get()
            ->keyBy('mes');

        // Crear un arreglo con todos los meses (llenar con 0 donde no haya datos)
        $meses = collect(range(1, 12))->mapWithKeys(function ($mes) use ($pensionistasPorMes) {
            $mesEncontrado = $pensionistasPorMes->firstWhere('mes', $mes); // Buscar datos para el mes actual
            return [
                \Carbon\Carbon::create()->month($mes)->translatedFormat('F') => $mesEncontrado ? $mesEncontrado->total : 0,
            ];
        });

        return response()->json([
            'anios' => $anios, // Años disponibles
            'meses' => $meses->keys(), // Nombres de los meses
            'totales' => $meses->values(), // Totales de pensionistas por mes
        ]);
    }

    public function getMenusByTipoComida(Request $request)
    {
        $tipoComida = $request->input('tipoComida', 'todos'); // 'todos' por defecto

        // Consulta base para obtener los menús más consumidos
        $query = Consumption::join('consumptiondetails', 'consumptions.id', '=', 'consumptiondetails.consumption_id')
            ->join('menus', 'consumptiondetails.menu_id', '=', 'menus.id')
            ->join('typefoods', 'menus.typefood_id', '=', 'typefoods.id')
            ->selectRaw('menus.name as menu, COUNT(*) as total');

        // Filtrar por tipo de comida si no es "todos"
        if ($tipoComida !== 'todos') {
            $query->where('typefoods.name', $tipoComida);
        }

        // Agrupar y ordenar los resultados
        $menusMasConsumidos = $query
            ->groupBy('menus.name')
            ->orderByDesc('total')
            ->limit(5)
            ->pluck('total', 'menu');

        return response()->json([
            'labels' => $menusMasConsumidos->keys(), // Nombres de los menús
            'totales' => $menusMasConsumidos->values(), // Totales de consumo
        ]);
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
