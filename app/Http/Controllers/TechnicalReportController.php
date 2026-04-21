<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\View\View;

class TechnicalReportController extends Controller
{
    public function preview(): View
    {
        return view('reports.technical');
    }

    public function download(): Response
    {
        return Pdf::loadView('reports.technical')->download('laporan-teknis-peminjaman-fashion.pdf');
    }
}