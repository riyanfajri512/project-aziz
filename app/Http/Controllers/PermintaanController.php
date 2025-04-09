<?php

namespace App\Http\Controllers;

use App\Models\JenisKendaraan;
use App\Models\Lokasi;
use App\Models\Permintaan;
use App\Models\Supplier;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermintaanController extends Controller
{
    public function index()
    {



        return view('permintaan.indexpermintaan');
    }


    public function getListPermintaan()
    {
        $permintaan = Permintaan::with(['user', 'suplier', 'items'])
            ->orderBy('created_at', 'desc')
            ->get();

        return datatables()->of($permintaan)
            ->addIndexColumn()
            ->addColumn('tanggal', function($data) {
                return $data->tanggal_dibuat->format('d/m/Y');
            })
            ->addColumn('suplier', function($data) {
                return $data->supplier->nama ?? '-';
            })
            ->addColumn('total', function($data) {
                return 'Rp ' . number_format($data->total_payment, 0, ',', '.');
            })
            ->addColumn('status', function($data) {
                $badge = [
                    'pending' => 'warning',
                    'approved' => 'success',
                    'rejected' => 'danger'
                ];
                return '<span class="badge bg-'.$badge[$data->status].'">'.strtoupper($data->status).'</span>';
            })
            ->addColumn('action', function($data) {
                $btn = '<div class="btn-group">';

                // View Button
                $btn .= '<a href="'.route('permintaan.show', $data->id).'" class="btn btn-sm btn-info" title="View">
                            <i class="fas fa-eye"></i>
                         </a>';

                // Edit Button (only for pending status)
                if($data->status == 'pending') {
                    $btn .= '<a href="'.route('permintaan.edit', $data->id).'" class="btn btn-sm btn-primary" title="Edit">
                                <i class="fas fa-edit"></i>
                             </a>';
                }

                // Approve Button (only for pending status)
                if($data->status == 'pending') {
                    $btn .= '<button class="btn btn-sm btn-success approve-btn" data-id="'.$data->id.'" title="Approve">
                                <i class="fas fa-check"></i>
                             </button>';
                }

                // Export Button
                $btn .= '<a href="'.route('permintaan.export', $data->id).'" class="btn btn-sm btn-secondary" title="Export PDF">
                            <i class="fas fa-file-pdf"></i>
                         </a>';

                // Delete Button (only for pending status)
                if($data->status == 'pending') {
                    $btn .= '<button class="btn btn-sm btn-danger delete-btn" data-id="'.$data->id.'" title="Delete">
                                <i class="fas fa-trash"></i>
                             </button>';
                }

                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }


    public function tambah()
    {

        $lokasiList = Lokasi::all();
        $jenisList = JenisKendaraan::all();
        $suplierList = Supplier::all();

        return view('permintaan.tambahperminntaan', [
            'lokasiList' => $lokasiList,
            'jenisList' => $jenisList,
            'suplierList' => $suplierList
        ]);
    }

    public function store(Request $request)
    {

        // dd($request->all());
        // Validasi input dasar
        $validated = $request->validate([
            'kode_pemesanan' => 'required',
            'unit' => 'required',
            'lokasi_id' => 'required',
            'tanggal_dibuat' => 'required|date',
            'supplier_id' => 'required',
            'deskripsi' => 'nullable',
            'file' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // Handle file upload
            if ($request->hasFile('file')) {
                $filePath = $request->file('file')->store('permintaan_files');
                $validated['file_path'] = $filePath;
            }

            // Proses items - gunakan format array jika ada, jika tidak gunakan JSON
            $items = $request->has('kode_sparepart')
                ? $this->formatItemsFromArrays($request)
                : json_decode($request->items, true);

            // Validasi items
            if (empty($items)) {
                throw new \Exception('Minimal 1 item sparepart harus dimasukkan');
            }

            // Hitung total payment
            $totalPayment = array_reduce($items, function($carry, $item) {
                return $carry + (float) str_replace('.', '', $item['total_harga']);
            }, 0);

            // Simpan data permintaan
            $permintaan = Permintaan::create(array_merge($validated, [
                'user_id' => auth()->id(),
                'unit_pembuat' => auth()->user()->name,
                'total_payment' => $totalPayment
            ]));

            // Simpan items dan update sparepart
            foreach ($items as $item) {
                // Bersihkan format angka
                $harga = (float) str_replace('.', '', $item['harga']);
                $qty = (int) $item['qty'];
                $totalHarga = (float) str_replace('.', '', $item['total_harga']);

                // Simpan item permintaan
                $permintaan->items()->create([
                    'kode_sparepart' => $item['kode_sparepart'],
                    'jenis_kendaraan' => $item['jenis_kendaraan'],
                    'nama_sparepart' => $item['nama_sparepart'],
                    'qty' => $qty,
                    'harga' => $harga,
                    'total_harga' => $totalHarga
                ]);

                // Update atau create sparepart
                DB::table('tbl_sp')->updateOrInsert(
                    ['kode' => $item['kode_sparepart']],
                    [
                        'jenis' => $item['jenis_kendaraan'],
                        'nama' => $item['nama_sparepart'],
                        'harga' => $harga,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                );
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Permintaan berhasil disimpan',
                'data' => $permintaan
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // Helper method untuk format items dari array
    protected function formatItemsFromArrays($request)
    {
        $items = [];
        foreach ($request->kode_sparepart as $index => $kode) {
            $items[] = [
                'kode_sparepart' => $kode,
                'jenis_kendaraan' => $request->jenis_kendaraan[$index],
                'nama_sparepart' => $request->nama_sparepart[$index],
                'qty' => $request->qty[$index],
                'harga' => $request->harga[$index],
                'total_harga' => $request->total_harga[$index]
            ];
        }
        return $items;
    }


    public function approve($id)
    {
        $permintaan = Permintaan::findOrFail($id);
        $permintaan->update(['status' => 'approved']);

        return response()->json([
            'success' => true,
            'message' => 'Permintaan berhasil disetujui'
        ]);
    }

    public function exportPdf($id)
    {
        $permintaan = Permintaan::with(['items', 'supplier'])->findOrFail($id);
        $pdf = PDF::loadView('permintaan.export', compact('permintaan'));

        return $pdf->download('permintaan-'.$permintaan->kode_pemesanan.'.pdf');
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $permintaan = Permintaan::findOrFail($id);

            // Hanya boleh hapus jika status pending
            if ($permintaan->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya permintaan dengan status pending yang bisa dihapus'
                ], 403);
            }

            // Hapus items terkait terlebih dahulu
            $permintaan->items()->delete();

            // Hapus permintaan
            $permintaan->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Permintaan berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus permintaan: ' . $e->getMessage()
            ], 500);
        }
    }
}
