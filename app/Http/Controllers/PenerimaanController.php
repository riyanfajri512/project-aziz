<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PenerimaanController extends Controller
{
    public function index()
    {
        return view('penerimaan.indexpenerimaan');
    }

    public function tambah()
    {
        return view('penerimaan.indextambahpenerimaan');
    }
}
