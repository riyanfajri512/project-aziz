<?php

namespace App\Http\Controllers;

use App\Models\Penerimaan;
use App\Models\PenerimaanItem;
use App\Models\Permintaan;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

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
            ->addColumn('tanggal', function ($data) {
                return $data->tanggal ? \Carbon\Carbon::parse($data->tanggal)->format('d/m/Y') : '-';
            })
            ->addColumn('kode_penerimaan', function ($data) {
                return $data->kode_penerimaan ?? '-';
            })
            ->addColumn('user', function ($data) {
                return optional($data->user)->name ?? '-';
            })
            ->addColumn('permintaan', function ($data) {
                return $data->kode_pemesanan ?? '-';
            })
            ->addColumn('grand_total', function ($data) {
                return $data->grand_total ? 'Rp ' . number_format($data->grand_total, 0, ',', '.') : 'Rp 0';
            })
            ->addColumn('status', function ($data) {
                $statusConfig = [
                    'Pending' => ['color' => 'warning', 'icon' => 'fa-clock'],
                    'Approved' => ['color' => 'success', 'icon' => 'fa-check'],
                    'Rejected' => ['color' => 'danger', 'icon' => 'fa-times'],
                    'BTB' => ['color' => 'primary', 'icon' => 'fa-truck'],
                    'SP Final' => ['color' => 'info', 'icon' => 'fa-file-signature']
                ];
                $statusName = $data->status_name ?? 'Pending';
                $config = $statusConfig[$statusName] ?? ['color' => 'secondary', 'icon' => 'fa-question'];
                return '<span class="badge bg-' . $config['color'] . '">
                    <i class="fas ' . $config['icon'] . ' me-1"></i>
                    ' . strtoupper($statusName) . '
                </span>';
            })
            ->addColumn('action', function ($data) {
                return '<div class="btn-group btn-group-sm">
                    <button class="btn btn-info view-btn" data-id="' . $data->id . '" title="Detail" data-bs-toggle="modal" data-bs-target="#detailModal">
                        <i class="fas fa-eye"></i>
                    </button>
                    <a href="' . route('penerimaan.export', $data->id) . '" class="btn btn-secondary" title="Export PDF" target="_blank">
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
        $nextNumber = $lastPenerimaan ? (int) explode('/', $lastPenerimaan->kode_pemesanan)[0] + 1 : 1;
        $kodePemesanan = sprintf('%04d', $nextNumber) . '/voum-2';
        return view('penerimaan.indextambahpenerimaan', [
            'kodePeneriman' => $kodePemesanan,
            'permintaanList' => $permintaanList
        ]);
    }

    // Menyimpan data penerimaan
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'permintaan_id' => 'required|exists:tbl_permintaan,id',
            'items' => 'required|array',
            'items.*.kode_sparepart' => 'required|string',
            'items.*.jenis_kendaraan' => 'required|string',
            'items.*.nama_sparepart' => 'required|string',
            'items.*.qty' => 'required|numeric|min:0',
            'items.*.qty_diterima' => 'required|numeric|min:0',
            'items.*.harga' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // 1. Update status Permintaan terlebih dahulu
            $permintaan = Permintaan::findOrFail($validated['permintaan_id']);
            $permintaan->update(['status_id' => 4]);

            // 2. Create the main Penerimaan record
            $penerimaan = Penerimaan::create([
                'kode_penerimaan' => $request->kode_penerimaan,
                'permintaan_id' => $validated['permintaan_id'],
                'user_id' => auth()->id(),
                'tanggal' => $validated['tanggal'],
                'grand_total' => $this->calculateGrandTotal($validated['items']),
            ]);

            // 3. Create PenerimaanItem records
            foreach ($validated['items'] as $itemData) {
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
        return array_reduce($items, function ($total, $item) {
            return $total + ($item['qty_diterima'] * $item['harga']);
        }, 0);
    }

    // Mengekspor data penerimaan ke PDF
    public function exportPdf($id, PDF $pdf)
    {
        $penerimaan = Penerimaan::with(['user', 'permintaan', 'items'])
                ->findOrFail($id);
        $pdf = Pdf::loadView('exportPDF.penerimaanPdf', compact('penerimaan'));
        return $pdf->stream('penerimaan.pdf');
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
        $penerimaan = Penerimaan::findOrFail($id);
        $permintaanList = Permintaan::all(); // Jika kamu butuh daftar permintaan
        // Kalau kamu ingin generate ulang kode penerimaan
        $kodePenerimaan = $penerimaan->kode_penerimaan; // Atau generate baru jika diinginkan
        return view('penerimaan.penerimaanedit', [
            'penerimaan' => $penerimaan,
            'permintaanList' => $permintaanList,
            'kodePenerimaan' => $kodePenerimaan,
        ]);
    }
    public function update(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'tanggal_dibuat' => 'required|date',
            'supplier_id' => 'required|exists:tbl_supplier,id',
            'deskripsi' => 'nullable|string',
            'items' => 'required|json',
            'file' => 'nullable|file|mimes:pdf|max:2048'
        ]);
    
        try {
            DB::beginTransaction();
    
            // Decode items
            $items = json_decode($validated['items'], true);
            
            if (!is_array($items)) {
                throw ValidationException::withMessages([
                    'items' => ['Format items tidak valid']
                ]);
            }
    
            if (count($items) === 0) {
                throw ValidationException::withMessages([
                    'items' => ['Minimal harus ada 1 item']
                ]);
            }
    
            // Validasi setiap item
            foreach ($items as $item) {
                $validator = Validator::make($item, [
                    'kode_sparepart' => 'required',
                    'jenis_kendaraan' => 'required',
                    'nama_sparepart' => 'required',
                    'qty' => 'required|numeric|min:1',
                    'harga' => 'required|numeric|min:0'
                ]);
    
                if ($validator->fails()) {
                    throw new ValidationException($validator);
                }
            }
    
            // Update permintaan
            $permintaan = Permintaan::findOrFail($id);
            $permintaan->update([
                'tanggal_dibuat' => $validated['tanggal_dibuat'],
                'supplier_id' => $validated['supplier_id'],
                'deskripsi' => $validated['deskripsi'],
                'unit_pembuat' => auth()->user()->name // Sesuaikan dengan kebutuhan
            ]);
    
            // Proses items
            $itemIds = [];
            $totalPayment = 0;
            
            foreach ($items as $item) {
                $itemData = [
                    'kode_sparepart' => $item['kode_sparepart'],
                    'jenis_kendaraan' => $item['jenis_kendaraan'],
                    'nama_sparepart' => $item['nama_sparepart'],
                    'qty' => $item['qty'],
                    'harga' => $item['harga'],
                    'total_harga' => $item['qty'] * $item['harga']
                ];
    
                if (!empty($item['id'])) {
                    // Update existing item
                    $permintaan->items()->where('id', $item['id'])->update($itemData);
                    $itemIds[] = $item['id'];
                } else {
                    // Create new item
                    $newItem = $permintaan->items()->create($itemData);
                    $itemIds[] = $newItem->id;
                }
                
                $totalPayment += $itemData['total_harga'];
            }
    
            // Hapus items yang tidak ada dalam request
            $permintaan->items()->whereNotIn('id', $itemIds)->delete();
    
            // Update total payment
            $permintaan->update(['total_payment' => $totalPayment]);
    
            // Handle file upload
            if ($request->hasFile('file')) {
                // Hapus file lama jika ada
                if ($permintaan->file_path && Storage::exists($permintaan->file_path)) {
                    Storage::delete($permintaan->file_path);
                }
                
                // Simpan file baru
                $path = $request->file('file')->store('permintaan_files');
                $permintaan->update(['file_path' => $path]);
            }
    
            DB::commit();
    
            return response()->json([
                'success' => true,
                'message' => 'Permintaan berhasil diperbarui',
                'redirect' => route('permintaan.index')
            ]);
    
        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
        
                        
}