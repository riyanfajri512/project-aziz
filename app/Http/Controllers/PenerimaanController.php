<?php

namespace App\Http\Controllers;

use App\Models\Penerimaan;
use App\Models\PenerimaanItem;
use App\Models\Permintaan;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PenerimaanController extends Controller
{
    // Menampilkan halaman utama penerimaan
    public function index()
    {
        return view('penerimaan.indexpenerimaan');
    }

    // Mengambil daftar penerimaan untuk DataTables
    public function getListPenerimaan()
    {
        $penerimaan = Penerimaan::with(['user', 'permintaan.status', 'items'])
            ->select([
                'tbl_penerimaan.*',
                'tbl_permintaan.status_id',
                'tbl_status.nama as status_name',
                'tbl_permintaan.kode_pemesanan'
            ])
            ->leftJoin('tbl_permintaan', 'tbl_penerimaan.permintaan_id', '=', 'tbl_permintaan.id')
            ->leftJoin('tbl_status', 'tbl_permintaan.status_id', '=', 'tbl_status.id')
            ->orderBy('tbl_penerimaan.created_at', 'desc')
            ->get();

        return datatables()->of($penerimaan)
            ->addIndexColumn()
            ->addColumn('tanggal', function($data) {
                return $data->tanggal ? \Carbon\Carbon::parse($data->tanggal)->format('d/m/Y') : '-';
            })
            ->addColumn('kode_penerimaan', function($data) {
                return $data->kode_penerimaan ?? '-';
            })
            ->addColumn('user', function($data) {
                return optional($data->user)->name ?? '-';
            })
            ->addColumn('permintaan', function($data) {
                return $data->kode_pemesanan ?? '-';
            })
            ->addColumn('grand_total', function($data) {
                return $data->grand_total ? 'Rp ' . number_format($data->grand_total, 0, ',', '.') : 'Rp 0';
            })
            ->addColumn('status', function($data) {
                $statusConfig = [
                    'Pending' => ['color' => 'warning', 'icon' => 'fa-clock'],
                    'Approved' => ['color' => 'success', 'icon' => 'fa-check'],
                    'Rejected' => ['color' => 'danger', 'icon' => 'fa-times'],
                    'BTB' => ['color' => 'primary', 'icon' => 'fa-truck'],
                    'SP Final' => ['color' => 'info', 'icon' => 'fa-file-signature']
                ];

                $statusName = $data->status_name ?? 'Pending';
                $config = $statusConfig[$statusName] ?? ['color' => 'secondary', 'icon' => 'fa-question'];

                return '<span class="badge bg-'.$config['color'].'">
                        <i class="fas '.$config['icon'].' me-1"></i>
                        '.strtoupper($statusName).'
                    </span>';
            })
            ->addColumn('action', function($data) {
                return '<div class="btn-group btn-group-sm">
                    <button class="btn btn-info view-btn" data-id="'.$data->id.'" title="Detail" data-bs-toggle="modal" data-bs-target="#detailModal">
                        <i class="fas fa-eye"></i>
                    </button>
                    <a href="'.route('penerimaan.export', $data->id).'" class="btn btn-secondary" title="Export PDF" target="_blank">
                        <i class="fas fa-file-pdf"></i>
                    </a>
                </div>';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }


    public function showDetail($id)
    {
        try {
            $penerimaan = Penerimaan::with(['user', 'permintaan', 'items'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diambil',
                'data' => [
                    'penerimaan' => $penerimaan,
                    'tanggal_formatted' => optional($penerimaan->tanggal)->format('d/m/Y') ?? '-',
                    'grand_total_formatted' => 'Rp ' . number_format($penerimaan->grand_total, 0, ',', '.')
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }


    public function getListItembyId(Request $request)
    {
        try {
            // Validasi bahwa id ada
            if (!$request->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID permintaan tidak valid'
                ], 400);
            }

            // Ambil data permintaan beserta relasinya
            $permintaan = Permintaan::with([
                'items',
            ])->find($request->id);

            // Jika permintaan tidak ditemukan
            if (!$permintaan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data permintaan tidak ditemukan'
                ], 404);
            }

            // Format response
            return response()->json([
                'success' => true,
                'data' => $permintaan
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // Menampilkan form tambah penerimaan
    public function tambah()
    {
        $permintaanList = Permintaan::select('id', 'kode_pemesanan', 'tanggal_dibuat')
            ->where('status_id', 2) // Status "Diterima"
            ->get();

            $lastPenerimaan = Penerimaan::orderBy('id', 'desc')->first();
            $nextNumber = $lastPenerimaan ? (int)explode('/', $lastPenerimaan->kode_pemesanan)[0] + 1 : 1;
            $kodePemesanan = sprintf('%04d', $nextNumber) . '/voum-2';


        return view('penerimaan.indextambahpenerimaan', [
            'kodePeneriman' => $kodePemesanan,
            'permintaanList' => $permintaanList
        ]);
    }

    // Menyimpan data penerimaan
    public function store(Request $request)
    {

        // dd($request->all());
        DB::beginTransaction();

        try {
            // 1. Update status Permintaan terlebih dahulu
            $permintaan = Permintaan::findOrFail($request->permintaan_id);
            $permintaan->update(['status_id' => 4]);

            // 2. Create the main Penerimaan record
            $penerimaan = Penerimaan::create([
                'kode_penerimaan' => $request->kode_penerimaan,
                'permintaan_id' => $request->permintaan_id,
                'user_id' => auth()->id(),
                'tanggal' => $request->tanggal,
                'grand_total' => $this->calculateGrandTotal($request->items),
            ]);

            // 3. Create PenerimaanItem records
            foreach ($request->items as $itemId => $itemData) {
                PenerimaanItem::create([
                    'penerimaan_id' => $penerimaan->id,
                    'kode_sparepart' => $itemData['kode_sparepart'],
                    'jenis_kendaraan' => $itemData['jenis_kendaraan'],
                    'nama_sparepart' => $itemData['nama_sparepart'],
                    'qty' => $itemData['qty'],
                    'qty_diterima' => $itemData['qty_diterima'],
                    'harga' => $itemData['harga'],
                    'total_harga' => $itemData['qty_diterima'] * $itemData['harga'],
                    'belance' => $itemData['qty_diterima'],
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => [
                    'penerimaan' => $penerimaan,
                    'permintaan' => $permintaan
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    private function calculateGrandTotal($items)
    {
        return array_reduce($items, function($total, $item) {
            return $total + ($item['qty_diterima'] * $item['harga']);
        }, 0);
    }

    // Mengekspor data penerimaan ke PDF
    public function exportPdf($id)
    {
        $penerimaan = Penerimaan::with(['items', 'user'])->findOrFail($id);
        $pdf = Pdf::loadView('penerimaan.export', compact('penerimaan'));
        return $pdf->download('penerimaan-'.$penerimaan->kode_penerimaan.'.pdf');
    }

    // Menghapus data penerimaan
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $penerimaan = Penerimaan::findOrFail($id);
            $penerimaan->items()->delete();
            $penerimaan->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Penerimaan berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus penerimaan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $penerimaan = Penerimaan::with(['items'])->findOrFail($id);
        return view('penerimaan.indexedit', compact('penerimaan'));
    }


}
