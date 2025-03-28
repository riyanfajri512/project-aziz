<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PendistibusianController extends Controller
{
    public function index()
    {
        return view('pendistribusian.indexpendistribusian');
    }

    public function tambah()
    {
        return view('pendistribusian.tambahpendistribusian');
    }
}
