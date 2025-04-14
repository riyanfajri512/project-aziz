<?php

namespace App\Http\Controllers;

use App\Models\JenisKendaraan;
use App\Models\Lokasi;
use App\Models\Permintaan;
use App\Models\Sp;
use App\Models\Status;
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
        $permintaan = Permintaan::with(['user', 'suplier', 'items', 'lokasi', 'status'])
            ->orderBy('created_at', 'desc')
            ->get();

        return datatables()->of($permintaan)
            ->addIndexColumn()
            ->addColumn('tanggal', function($data) {
                return $data->tanggal_dibuat->format('d/m/Y');
            })
            ->addColumn('suplier', function($data) {
                return $data->suplier->nama ?? '-';
            })
            ->addColumn('total_payment', function($data) {
                return 'Rp ' . number_format($data->total_payment, 0, ',', '.');
            })
            ->addColumn('total', function($data) {
                return 'Rp ' . number_format($data->total_payment, 0, ',', '.');
            })
            ->addColumn('lokasi_nama', function($data) {
                return $data->lokasi->nama ?? '-';
            })
            ->addColumn('lokasi_unit', function($data) {
                return $data->lokasi->unit ?? '-';
            })
            ->addColumn('status', function($data) {
                $badge = [
                    'Pending' => 'warning',
                    'Approved' => 'success',
                    'Rejected' => 'danger',
                    'BTB' => 'primary',
                    'SP Final' => 'info'
                ];

                $statusName = $data->status->nama ?? 'Pending';
                return '<span class="badge bg-'.($badge[$statusName] ?? 'secondary').'">'
                    .strtoupper($statusName).
                '</span>';
            })
            ->addColumn('action', function($data) {
                $btn = '<div class="btn-group">';

                // View Button
                $btn .= '<button class="btn btn-sm btn-info view-btn" data-id="'.$data->id.'" data-bs-toggle="modal" data-bs-target="#detailModal" title="View">
                            <i class="fas fa-eye"></i>
                         </button>';

                // Approve & Reject Buttons (only for pending status)
                if(($data->status->nama ?? 'Pending') == 'Pending') {
                    $btn .= '<button class="btn btn-sm btn-success approve-btn" data-id="'.$data->id.'" title="Approve">
                                <i class="fas fa-check"></i>
                             </button>';
                    $btn .= '<button class="btn btn-sm btn-danger reject-btn"
                             data-id="'.$data->id.'"
                             data-bs-toggle="modal"
                             data-bs-target="#rejectModal"
                             title="Reject">
                                <i class="fas fa-times"></i>
                             </button>';
                }
                // Show Rejection Reason Button (if status is Rejected)
                elseif(($data->status->nama ?? '') == 'Rejected') {
                    $btn .= '<button class="btn btn-sm btn-warning show-reason-btn"
                             data-id="'.$data->id.'"
                             data-reason="'.htmlspecialchars($data->alasan_reject ?? 'No reason provided').'"
                             data-bs-toggle="modal"
                             data-bs-target="#showReasonModal"
                             title="View Rejection Reason">
                                <i class="fas fa-comment-alt"></i>
                             </button>';
                }

                // Export Button
                $btn .= '<a href="'.route('permintaan.export', $data->id).'" class="btn btn-sm btn-secondary" target="_blank" title="Export PDF">
                            <i class="fas fa-file-pdf"></i>
                         </a>';

                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function show($id)
    {
        $permintaan = Permintaan::with(['user', 'suplier', 'items', 'lokasi', 'status'])
                      ->findOrFail($id);

        if (!$permintaan->status) {
            $permintaan->setRelation('status', Status::find(1));
        }

        return view('permintaan._show', compact('permintaan'));
    }

    public function tambah()
    {
        $lokasiList = Lokasi::all();
        $jenisList = JenisKendaraan::all();
        $suplierList = Supplier::all();
        $spareparts = Sp::all();

        // Generate kode pemesanan
        $lastPermintaan = Permintaan::orderBy('id', 'desc')->first();
        $nextNumber = $lastPermintaan ? (int)explode('/', $lastPermintaan->kode_pemesanan)[0] + 1 : 1;
        $kodePemesanan = sprintf('%04d', $nextNumber) . '/voum-1';

        return view('permintaan.tambahperminntaan', [
            'lokasiList' => $lokasiList,
            'jenisList' => $jenisList,
            'suplierList' => $suplierList,
            'spareparts' => $spareparts,
            'kodePemesanan' => $kodePemesanan
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
                $filePath = $request->file('file')->store('public/permintaan_files');
                $validated['file_path'] = str_replace('public/', '', $filePath); // Simpan path tanpa 'public/'
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

            // Dapatkan ID status Pending (asumsi ID 1 adalah status Pending)
            $pendingStatusId = Status::where('nama', 'Pending')->first()->id;

            // Simpan data permintaan
            $permintaan = Permintaan::create(array_merge($validated, [
                'user_id' => auth()->id(),
                'unit_pembuat' => auth()->user()->name,
                'total_payment' => $totalPayment,
                'status_id' => $pendingStatusId // Gunakan ID status
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

        $permintaan->update(['status_id' => 2]);

        return response()->json([
            'success' => true,
            'message' => 'Permintaan berhasil disetujui'
        ]);
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan_reject' => 'required|string|max:2000'
        ]);

        $permintaan = Permintaan::findOrFail($id);

        $permintaan->update([
            'status_id' => 3,
            'alasan_reject' => $request->alasan_reject
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permintaan berhasil ditolak'
        ]);
    }

    public function exportPdf($id, PDF $pdf)
    {
        $permintaan = Permintaan::with(['user', 'suplier', 'items', 'lokasi', 'status'])
        ->findOrFail($id);

        if (!$permintaan->status) {
        $permintaan->setRelation('status', Status::find(1));
        }

        $pdf = $pdf->loadView('exportPDF.penerimaanPdf', compact('permintaan'))->setPaper('A4', 'portrait');
        return $pdf->stream('permintaan-' . $permintaan->id . '.pdf');
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $permintaan = Permintaan::findOrFail($id);

            // Hanya boleh hapus jika status pending
            if ($permintaan->status_id !== 1) {
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
