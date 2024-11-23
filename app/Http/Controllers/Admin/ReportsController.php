<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pensioner;
use App\Models\Report;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function index()
    {
        $pensioners = Pensioner::with('accountStatus')->get();

        return view('admin.reports.index', compact('pensioners'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'pensioner_id' => 'required|exists:pensioners,id',
            'month' => 'required|date_format:Y-m',
        ]);

        $pensioner = Pensioner::with('accountStatus')->findOrFail($request->pensioner_id);

        $reportModel = new Report();
        $reportData = $reportModel->generateConsumptionReport($request->pensioner_id, $request->month);

        $report = $reportData['report'];
        $monthlyTotal = $reportData['monthly_total'];

        // Generar el PDF
        $pdf = Pdf::loadView('admin.reports.pdf', [
            'pensioner' => $pensioner,
            'report' => $report,
            'month' => $request->month,
            'monthlyTotal' => $monthlyTotal,
        ]);

        return $pdf->download("reporte_consumos_{$pensioner->lastname}_{$request->month}.pdf");
    }
}
