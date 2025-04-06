<?php

namespace App\Http\Controllers;

use App\Models\JenisKendaraan;
use App\Models\Lokasi;
use App\Models\Supplier;
use Illuminate\Http\Request;

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
}
