<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Report extends Model
{
    use HasFactory;
    // Indicar que no usa una tabla en la base de datos
    public $table = false;

    // Deshabilitar timestamps, ya que no está relacionado con una tabla
    public $timestamps = false;

    /**
     * Generar el reporte de consumos por pensionista y mes.
     *
     * @param int $pensionerId
     * @param string $month
     * @return array
     */
    public function generateConsumptionReport(int $pensionerId, string $month)
{
    // Separar el año y el mes del parámetro recibido
    $year = substr($month, 0, 4);
    $month = substr($month, 5, 2);

    // Obtener los consumos filtrados por pensionista y mes
    $consumptions = DB::table('consumptions')
        ->where('pensioner_id', $pensionerId)
        ->whereYear('date', $year)
        ->whereMonth('date', $month)
        ->join('consumptiondetails', 'consumptions.id', '=', 'consumptiondetails.consumption_id')
        ->join('menus', 'consumptiondetails.menu_id', '=', 'menus.id')
        ->join('typefoods', 'menus.typefood_id', '=', 'typefoods.id')
        ->select(
            'consumptions.date',
            'typefoods.name as typefood',
            'menus.name as menu',
            'menus.price as menu_price',
            'consumptiondetails.aditional as additional',
            'consumptiondetails.aditional_cost as additional_cost'
        )
        ->orderBy('consumptions.date')
        ->get();

    // Agrupar los consumos por fecha
    $groupedConsumptions = $consumptions->groupBy('date');

    // Transformar los datos para el reporte
    $report = [];
    $monthlyTotal = 0;

    foreach ($groupedConsumptions as $date => $items) {
        $dailyTotal = 0;

        $details = [
            'Desayuno' => 'No consumo',
            'Almuerzo' => 'No consumo',
            'Cena' => 'No consumo',
        ];

        foreach ($items as $item) {
            $totalCost = $item->menu_price + $item->additional_cost;
            $dailyTotal += $totalCost;

            $details[$item->typefood] = sprintf(
                "%s (S/. %s) %s%s",
                $item->menu,
                number_format($item->menu_price, 2),
                $item->additional ? ", Adicional: {$item->additional}" : '',
                $item->additional_cost > 0 ? " (S/. {$item->additional_cost})" : ''
            );
        }

        $report[] = [
            'date' => $date,
            'details' => $details,
            'daily_total' => $dailyTotal,
        ];        

        $monthlyTotal += $dailyTotal;
    }

    return [
        'report' => $report,
        'monthly_total' => $monthlyTotal,
    ];
}

}
