<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PermintaanController extends Controller
{
    public function index()
    {



        return view('permintaan.indexpermintaan');
    }


    public function tambah()
    {



        return view('permintaan.tambahperminntaan');
    }
}
