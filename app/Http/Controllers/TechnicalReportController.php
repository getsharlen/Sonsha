<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Borrowing;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TechnicalReportController extends Controller
{
    public function preview(): View
    {
        $activityLogs = ActivityLog::with('user')
            ->latest()
            ->limit(50)
            ->get();

        $borrowingHistory = Borrowing::with(['user', 'items'])
            ->latest('created_at')
            ->limit(50)
            ->get();

        $summary = [
            'activity_logs' => ActivityLog::count(),
            'borrowing_history' => Borrowing::count(),
            'active_borrowings' => Borrowing::whereIn('status', ['requested', 'approved', 'borrowed', 'late'])->count(),
            'returned_borrowings' => Borrowing::where('status', 'returned')->count(),
        ];

        return view('reports.technical', compact('activityLogs', 'borrowingHistory', 'summary'));
    }

    public function exportExcel(Request $request): StreamedResponse
    {
        $type = $request->string('type')->toString();

        if (! in_array($type, ['activity-logs', 'borrowing-history'], true)) {
            abort(404);
        }

        $fileName = $type === 'activity-logs'
            ? 'laporan-activity-logs.xls'
            : 'laporan-borrowing-history.xls';

        return response()->streamDownload(function () use ($type): void {
            echo $this->buildExcelDocument($type);
        }, $fileName, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
        ]);
    }

    public function download(): StreamedResponse
    {
        return Pdf::loadView('reports.technical-pdf')->download('laporan-teknis-peminjaman-fashion.pdf');
    }

    private function buildExcelDocument(string $type): string
    {
        $title = $type === 'activity-logs'
            ? 'Laporan Activity Logs - Sonsha Fashion Rental'
            : 'Laporan History Peminjaman - Sonsha Fashion Rental';

        $rowsHtml = $type === 'activity-logs'
            ? $this->buildActivityLogRows()
            : $this->buildBorrowingHistoryRows();

        $headersHtml = $type === 'activity-logs'
            ? '<tr><th>Tanggal</th><th>User</th><th>Module</th><th>Action</th><th>Deskripsi</th></tr>'
            : '<tr><th>Kode</th><th>Peminjam</th><th>Status</th><th>Tanggal Buat</th><th>Tanggal Pinjam</th><th>Jatuh Tempo</th><th>Tanggal Kembali</th><th>Total Item</th><th>Total Harga</th><th>Durasi (Hari)</th><th>Total Denda</th><th>Tujuan</th></tr>';

        $colspan = $type === 'activity-logs' ? 5 : 12;

        return '<html><head><meta charset="UTF-8"></head><body>'
            . '<table border="1">'
            . '<tr><th colspan="'.$colspan.'">'.$this->escapeExcel($title).'</th></tr>'
            . $headersHtml
            . $rowsHtml
            . '</table></body></html>';
    }

    private function buildActivityLogRows(): string
    {
        $logs = ActivityLog::with('user')
            ->latest()
            ->get();

        $html = '';

        foreach ($logs as $log) {
            $html .= '<tr>'
                . '<td>'.$this->escapeExcel(optional($log->created_at)->format('Y-m-d H:i:s')).'</td>'
                . '<td>'.$this->escapeExcel($log->user?->name ?? '-').'</td>'
                . '<td>'.$this->escapeExcel($log->module).'</td>'
                . '<td>'.$this->escapeExcel($log->action).'</td>'
                . '<td>'.$this->escapeExcel($log->description).'</td>'
                . '</tr>';
        }

        return $html;
    }

    private function buildBorrowingHistoryRows(): string
    {
        $borrowings = Borrowing::with(['user', 'items'])
            ->latest('created_at')
            ->get();

        $html = '';

        foreach ($borrowings as $borrowing) {
            $totalPrice = $borrowing->items->sum(function ($item) {
                return $item->quantity * $item->unit_fee;
            });
            $duration = $borrowing->due_at ? $borrowing->created_at->diffInDays($borrowing->due_at) : 0;

            $html .= '<tr>'
                . '<td>'.$this->escapeExcel($borrowing->borrowing_code).'</td>'
                . '<td>'.$this->escapeExcel($borrowing->user?->name ?? '-').'</td>'
                . '<td>'.$this->escapeExcel($borrowing->status).'</td>'
                . '<td>'.$this->escapeExcel(optional($borrowing->created_at)->format('Y-m-d H:i:s')).'</td>'
                . '<td>'.$this->escapeExcel(optional($borrowing->borrowed_at)->format('Y-m-d H:i:s')).'</td>'
                . '<td>'.$this->escapeExcel(optional($borrowing->due_at)->format('Y-m-d H:i:s')).'</td>'
                . '<td>'.$this->escapeExcel(optional($borrowing->returned_at)->format('Y-m-d H:i:s')).'</td>'
                . '<td>'.$this->escapeExcel((string) $borrowing->items->sum('quantity')).'</td>'
                . '<td>'.$this->escapeExcel('Rp ' . number_format($totalPrice, 0, ',', '.')).'</td>'
                . '<td>'.$this->escapeExcel($duration . ' hari').'</td>'
                . '<td>'.$this->escapeExcel('Rp ' . number_format($borrowing->total_fine, 0, ',', '.')).'</td>'
                . '<td>'.$this->escapeExcel($borrowing->purpose ?? '-').'</td>'
                . '</tr>';
        }

        return $html;
    }

    private function escapeExcel(?string $value): string
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}