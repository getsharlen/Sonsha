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

        [$fileName, $headers, $rows] = $type === 'activity-logs'
            ? $this->activityLogExportPayload()
            : $this->borrowingHistoryExportPayload();

        return response()->streamDownload(function () use ($headers, $rows): void {
            $output = fopen('php://output', 'wb');

            fputcsv($output, $headers);

            foreach ($rows as $row) {
                fputcsv($output, $row);
            }

            fclose($output);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function download(): StreamedResponse
    {
        return Pdf::loadView('reports.technical-pdf')->download('laporan-teknis-peminjaman-fashion.pdf');
    }

    private function activityLogExportPayload(): array
    {
        $headers = ['Tanggal', 'User', 'Module', 'Action', 'Deskripsi'];

        $rows = ActivityLog::with('user')
            ->latest()
            ->get()
            ->map(fn (ActivityLog $log) => [
                optional($log->created_at)->format('Y-m-d H:i:s'),
                $log->user?->name ?? '-',
                $log->module,
                $log->action,
                $log->description,
            ]);

        return ['laporan-activity-logs.csv', $headers, $rows];
    }

    private function borrowingHistoryExportPayload(): array
    {
        $headers = [
            'Kode',
            'Peminjam',
            'Status',
            'Tanggal Buat',
            'Tanggal Pinjam',
            'Jatuh Tempo',
            'Tanggal Kembali',
            'Total Item',
            'Total Denda',
            'Tujuan',
        ];

        $rows = Borrowing::with(['user', 'items'])
            ->latest('created_at')
            ->get()
            ->map(fn (Borrowing $borrowing) => [
                $borrowing->borrowing_code,
                $borrowing->user?->name ?? '-',
                $borrowing->status,
                optional($borrowing->created_at)->format('Y-m-d H:i:s'),
                optional($borrowing->borrowed_at)->format('Y-m-d H:i:s'),
                optional($borrowing->due_at)->format('Y-m-d H:i:s'),
                optional($borrowing->returned_at)->format('Y-m-d H:i:s'),
                (string) $borrowing->items->sum('quantity'),
                (string) $borrowing->total_fine,
                $borrowing->purpose ?? '-',
            ]);

        return ['laporan-borrowing-history.csv', $headers, $rows];
    }
}