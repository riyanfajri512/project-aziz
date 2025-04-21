<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $baseQuery = DB::table('tbl_pendistribusian')
        ->join('tbl_pendistribusian_items', 'tbl_pendistribusian.id', '=', 'tbl_pendistribusian_items.pendistribusian_id')
        ->select(
            'tbl_pendistribusian.tanggal',
            DB::raw("'keluar' as jenis_transaksi"),
            'tbl_pendistribusian.kode_distribusi as kode_transaksi',
            'tbl_pendistribusian_items.kode_sparepart',
            'tbl_pendistribusian_items.nama_sparepart',
            'tbl_pendistribusian_items.jenis_kendaraan',
            'tbl_pendistribusian_items.qty_distribusi as qty',
            'tbl_pendistribusian_items.harga'
        )
        ->unionAll(
            DB::table('tbl_penerimaan')
                ->join('tbl_penerimaan_items', 'tbl_penerimaan.id', '=', 'tbl_penerimaan_items.penerimaan_id')
                ->select(
                    'tbl_penerimaan.tanggal',
                    DB::raw("'masuk' as jenis_transaksi"),
                    'tbl_penerimaan.kode_penerimaan as kode_transaksi',
                    'tbl_penerimaan_items.kode_sparepart',
                    'tbl_penerimaan_items.nama_sparepart',
                    'tbl_penerimaan_items.jenis_kendaraan',
                    'tbl_penerimaan_items.qty_diterima as qty',
                    'tbl_penerimaan_items.harga'
                )
        );

    // Filter tanggal jika ada
    if (request('tanggal_awal')) {
        $baseQuery->whereDate('tanggal', '>=', request('tanggal_awal'));
    }
    if (request('tanggal_akhir')) {
        $baseQuery->whereDate('tanggal', '<=', request('tanggal_akhir'));
    }

    $history = $baseQuery->orderBy('tanggal', 'desc')
        ->get()
        ->when(request('jenis'), function($collection, $jenis) {
            return $collection->where('jenis_transaksi', $jenis);
        });

        return view('history.indexhistory',   compact('history')  );
    }

    public function exportPDF(Request $request)
    {
        $baseQuery = DB::table('tbl_pendistribusian')
            ->join('tbl_pendistribusian_items', 'tbl_pendistribusian.id', '=', 'tbl_pendistribusian_items.pendistribusian_id')
            ->select(
                'tbl_pendistribusian.tanggal',
                DB::raw("'keluar' as jenis_transaksi"),
                'tbl_pendistribusian.kode_distribusi as kode_transaksi',
                'tbl_pendistribusian_items.kode_sparepart',
                'tbl_pendistribusian_items.nama_sparepart',
                'tbl_pendistribusian_items.jenis_kendaraan',
                'tbl_pendistribusian_items.qty_distribusi as qty',
                'tbl_pendistribusian_items.harga'
            )
            ->unionAll(
                DB::table('tbl_penerimaan')
                    ->join('tbl_penerimaan_items', 'tbl_penerimaan.id', '=', 'tbl_penerimaan_items.penerimaan_id')
                    ->select(
                        'tbl_penerimaan.tanggal',
                        DB::raw("'masuk' as jenis_transaksi"),
                        'tbl_penerimaan.kode_penerimaan as kode_transaksi',
                        'tbl_penerimaan_items.kode_sparepart',
                        'tbl_penerimaan_items.nama_sparepart',
                        'tbl_penerimaan_items.jenis_kendaraan',
                        'tbl_penerimaan_items.qty_diterima as qty',
                        'tbl_penerimaan_items.harga'
                    )
            );

        // Filter tanggal
        if ($request->filled('tanggal_awal')) {
            $baseQuery->whereDate('tanggal', '>=', $request->tanggal_awal);
        }

        if ($request->filled('tanggal_akhir')) {
            $baseQuery->whereDate('tanggal', '<=', $request->tanggal_akhir);
        }

        $history = $baseQuery->orderBy('tanggal', 'desc')->get();

        // Filter jenis transaksi setelah get
        if ($request->filled('jenis')) {
            $history = $history->where('jenis_transaksi', $request->jenis);
        }

        $pdf = Pdf::loadView('exportPDF.historyPdf', compact('history'));
        return $pdf->stream('history-transaksi.pdf');
    }
}
