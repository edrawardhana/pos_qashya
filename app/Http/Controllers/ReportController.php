<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportRequest;
use App\Services\SalesReportService;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(private SalesReportService $reports) {}

    public function index(ReportRequest $request): View
    {
        $from = $request->from();
        $to = $request->to();

        return view('reports.index', [
            'from' => $from,
            'to' => $to,
            'summary' => $this->reports->summary($from, $to),
            'daily' => $this->reports->daily($from, $to),
            'topProducts' => $this->reports->topProducts($from, $to),
            'payments' => $this->reports->byPaymentMethod($from, $to),
        ]);
    }

    public function export(ReportRequest $request): StreamedResponse
    {
        $from = $request->from();
        $to = $request->to();
        $reports = $this->reports;

        $filename = 'laporan-penjualan-'.$from->format('Ymd').'-'.$to->format('Ymd').'.csv';

        return response()->streamDownload(function () use ($reports, $from, $to) {
            $out = fopen('php://output', 'w');

            // BOM UTF-8 agar Excel membaca huruf non-ASCII dengan benar.
            fwrite($out, "\xEF\xBB\xBF");

            fputcsv($out, [
                'Kode', 'Tanggal', 'Kasir', 'Pelanggan', 'Metode',
                'Produk', 'Kategori', 'Harga', 'Qty', 'Subtotal',
            ]);

            foreach ($reports->exportCursor($from, $to) as $trx) {
                foreach ($trx->items as $item) {
                    fputcsv($out, [
                        $trx->code,
                        $trx->created_at->format('d/m/Y H:i'),
                        $trx->cashier->name,
                        $trx->customer_name ?? '-',
                        $trx->payment_method === 'cash' ? 'Tunai' : 'Transfer',
                        $item->product_name,
                        $item->category,
                        $item->price,
                        $item->qty,
                        $item->subtotal,
                    ]);
                }
            }

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}