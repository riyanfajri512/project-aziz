<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use App\Models\Pendistribusian;
use App\Models\PendistribusianItem;
use App\Models\Sp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class PendistibusianController extends Controller
{
    public function index()
    {
        $pendistribusian = Pendistribusian::all();
        return view('pendistribusian.indexpendistribusian', compact('pendistribusian'));
    }


    public function getlistPendistribusian()
    {
        $query = Pendistribusian::with(['user', 'unit'])
            ->select([
                'id',
                'kode_distribusi',
                'tanggal',
                'user_id',
                'unit_id',
                'nik_user',
                'nopol',
                'departemen',
                'total_harga',
                'created_at'
            ]);

        return DataTables::of($query)
            ->addColumn('action', function($pendistribusian) {
                return '<button class="btn btn-sm btn-primary view-items" data-id="'.$pendistribusian->id.'">
                    <i class="fa fa-eye"></i> Lihat Items
                </button>';
            })
            ->editColumn('tanggal', function($pendistribusian) {
                return date('d/m/Y', strtotime($pendistribusian->tanggal));
            })
            ->editColumn('total_harga', function($pendistribusian) {
                return 'Rp '.number_format($pendistribusian->total_harga, 2, ',', '.');
            })
            ->addColumn('user_name', function($pendistribusian) {
                return $pendistribusian->user->name;
            })
            ->addColumn('unit_name', function($pendistribusian) {
                return $pendistribusian->unit->nama;
            })
            ->addColumn('nik_user', fn($row) => $row->nik_user)
            ->addColumn('nopol', fn($row) => $row->nopol)
            ->addColumn('departemen', fn($row) => $row->departemen)
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getItems($id)
    {
        $items = PendistribusianItem::with(['sparepart'])
                    ->where('pendistribusian_id', $id)
                    ->get();

        return response()->json([
            'success' => true,
            'data' => $items
        ]);
    }

    public function tambah()
    {

        $spareparts = Sp::all();
        $lokasiList = Lokasi::all();
        $lastPenerimaan = Pendistribusian::orderBy('id', 'desc')->first();
        $nextNumber = $lastPenerimaan ? (int) explode('/', $lastPenerimaan->kode_distribusi)[0] + 1 : 1;
        $kodeDistribusi = sprintf('%04d', $nextNumber) . '/voum-3';

        return view('pendistribusian.tambahpendistribusian', [
            'spareparts' => $spareparts,
            'lokasiList' => $lokasiList,
            'kodeDistribusi' => $kodeDistribusi,
        ]);
    }

    public function store(Request $request)
    {

        // dd($request->all());
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'kode_distribusi' => 'required',
            'nopol' => 'required',
            'nik_user' => 'required',
            'departemen' => 'required',
            'unit_id' => 'required|exists:tbl_lokasi,id',
            'items' => 'required|array',
            'items.*.sparepart_id' => 'required|exists:tbl_sp,id',
            'items.*.qty_distribusi' => 'required|integer|min:1',
            'items.*.jenis_kerusakan' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            // Simpan header distribusi
            $distribusi = Pendistribusian::create([
                'kode_distribusi' => $validated['kode_distribusi'],
                'tanggal' => $validated['tanggal'],
                'user_id' => auth()->id(),
                'unit_id' => $validated['unit_id'],
                'nopol' => $validated['nopol'],
                'nik_user' => $validated['nik_user'],
                'departemen' => $validated['departemen'],
                'total_harga' => 0
            ]);

            // Simpan items
            $totalHarga = 0;
            foreach ($validated['items'] as $item) {
                $sparepart = Sp::find($item['sparepart_id']);

                $distribusiItem = PendistribusianItem::create([
                    'pendistribusian_id' => $distribusi->id,
                    'sparepart_id' => $sparepart->id,
                    'kode_sparepart' => $sparepart->kode,
                    'jenis_kendaraan' => $sparepart->jenis,
                    'nama_sparepart' => $sparepart->nama,
                    'stok_tersedia' => $sparepart->stok,
                    'qty_distribusi' => $item['qty_distribusi'],
                    'harga' => $sparepart->harga,
                    'total' => $sparepart->harga * $item['qty_distribusi'],
                    'jenis_kerusakan' => $item['jenis_kerusakan'] ?? null,
                ]);

                $totalHarga += $distribusiItem->total;

                // Update stok sparepart (optional)
                $sparepart->decrement('stok', $item['qty_distribusi']);
            }

            // Update total harga
            $distribusi->update(['total_harga' => $totalHarga]);

            DB::commit();

            return response()->json([
                'success' => true,
                'kode_distribusi' => $validated['kode_distribusi']
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
        
    }


    public function edit($id)
    {
        $distribusi = Pendistribusian::findOrFail($id);
        $spareparts = Sp::all();
        $lokasiList = Lokasi::all();

        return view('pendistribusian.editpendistribusian', [
            'distribusi' => $distribusi,
            'spareparts' => $spareparts,
            'lokasiList' => $lokasiList,
        ]);
    }
}
