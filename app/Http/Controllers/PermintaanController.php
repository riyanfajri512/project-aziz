<?php

namespace App\Http\Controllers;

use App\Models\JenisKendaraan;
use App\Models\Lokasi;
use App\Models\Permintaan;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermintaanController extends Controller
{
    public function index()
    {



        return view('permintaan.indexpermintaan');
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
}
