<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JenisKendaraanController extends Controller
{
    public function index()
    {


        return view('MD.IndexJeniskendaraan');
    }
}
