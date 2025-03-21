<?php

namespace App\Http\Controllers;

use App\Models\JenisKendaraan;
use Illuminate\Http\Request;

class JenisKendaraanController extends Controller
{
    public function index()
    {
        $jenisKendaraan = JenisKendaraan::all();
        return view('MD.IndexJeniskendaraan', compact('jenisKendaraan'));
    }


    public function store(Request $request)
    {
        JenisKendaraan::create($request->all());
        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        $kendaraan = JenisKendaraan::findOrFail($id);
        $kendaraan->update($request->all());
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        JenisKendaraan::destroy($id);
        return response()->json(['success' => true]);
    }
}
