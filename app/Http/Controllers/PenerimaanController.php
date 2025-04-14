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
        $penerimaan = Penerimaan::with(['user', 'permintaan'])
            ->orderBy('created_at', 'desc')
            ->get();

        return datatables()->of($penerimaan)
            ->addIndexColumn()
            ->addColumn('tanggal', function($data) {
                return $data->tanggal->format('d/m/Y');
            })
            ->addColumn('kode_penerimaan', function($data) {
                return $data->kode_penerimaan;
            })
            ->addColumn('user', function($data) {
                return $data->user->name ?? '-';
            })
            ->addColumn('grand_total', function($data) {
                return 'Rp ' . number_format($data->grand_total, 0, ',', '.');
            })
            ->addColumn('action', function($data) {
                $btn = '<div class="btn-group">';
                $btn .= '<button class="btn btn-sm btn-info view-btn" data-id="'.$data->permintaan_id.'" data-bs-toggle="modal" data-bs-target="#detailModal" title="View">
                            <i class="fas fa-eye"></i>
                         </button>';
                $btn .= '<a href="'.route('penerimaan.export', $data->permintaan_id).'" class="btn btn-sm btn-secondary" title="Export PDF">
                            <i class="fas fa-file-pdf"></i>
                         </a>';
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    // Menampilkan detail penerimaan
    public function show($id)
    {
        $penerimaan = Penerimaan::with(['user', 'items'])->findOrFail($id);
        return view('penerimaan._show', compact('penerimaan'));
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
        $validated = $request->validate([
            'permintaan_id' => 'required|exists:tbl_permintaan,id',
            'tanggal' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.kode_sparepart' => 'required|string',
            'items.*.jenis_kendaraan' => 'required|string',
            'items.*.nama_sparepart' => 'required|string',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0',
            'items.*.total_harga' => 'required|numeric|min:0',
            'items.*.balance' => 'nullable|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            $kodePenerimaan = strtoupper(Str::random(10));
            $grandTotal = collect($validated['items'])->sum('total_harga');

            $penerimaan = Penerimaan::create([
                'permintaan_id' => $validated['permintaan_id'],
                'kode_penerimaan' => $kodePenerimaan,
                'user_id' => auth()->id(),
                'tanggal' => $validated['tanggal'],
                'grand_total' => $grandTotal,
            ]);

            foreach ($validated['items'] as $item) {
                $penerimaan->items()->create([
                    'kode_sparepart' => $item['kode_sparepart'],
                    'jenis_kendaraan' => $item['jenis_kendaraan'],
                    'nama_sparepart' => $item['nama_sparepart'],
                    'qty' => $item['qty'],
                    'harga' => $item['harga'],
                    'total_harga' => $item['total_harga'],
                    'balance' => $item['balance'] ?? 0,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Penerimaan berhasil disimpan',
                'data' => $penerimaan
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
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
}
