<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total stok tersedia (stok > 0)
        $totalStokTersedia = DB::table('tbl_sp')->where('stok', '>', 0)->sum('stok');

        // Jumlah permintaan dengan status pending (status_id = 1)
        $totalPermintaanPending = DB::table('tbl_permintaan')->where('status_id', 1)->count();

        // Jumlah barang yang stoknya habis (stok = 0)
        $totalStokHabis = DB::table('tbl_sp')->where('stok', 0)->count();


        // Ambil data Sparepart Masuk (dari penerimaan)
        $masuk = DB::table('tbl_penerimaan_items as items')
            ->join('tbl_penerimaan as p', 'items.penerimaan_id', '=', 'p.id')
            ->selectRaw('MONTH(p.tanggal) as bulan, SUM(items.qty_diterima) as total')
            ->groupByRaw('MONTH(p.tanggal)')
            ->pluck('total', 'bulan');

        // Ambil data Sparepart Keluar (dari distribusi)
        $keluar = DB::table('tbl_pendistribusian_items as items')
            ->join('tbl_pendistribusian as d', 'items.pendistribusian_id', '=', 'd.id')
            ->selectRaw('MONTH(d.tanggal) as bulan, SUM(items.qty_distribusi) as total')
            ->groupByRaw('MONTH(d.tanggal)')
            ->pluck('total', 'bulan');

        // Format agar hasilnya punya 12 bulan, jika tidak ada data = 0
        $masukPerBulan = [];
        $keluarPerBulan = [];
        for ($i = 1; $i <= 12; $i++) {
            $masukPerBulan[] = $masuk[$i] ?? 0;
            $keluarPerBulan[] = $keluar[$i] ?? 0;
        }

        return view('dashboard.dashboardindex', compact(
            'totalStokTersedia',
            'totalPermintaanPending',
            'totalStokHabis',
            'masukPerBulan',
            'keluarPerBulan'
        ));
    }

}
