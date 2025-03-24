<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use Illuminate\Http\Request;

class LokasiController extends Controller
{
    public function index()
    {
        $lokasi = Lokasi::all();
        return view('MD.lokasi', compact('lokasi'));
    }
    public function store(Request $request)
    {
        Lokasi::create($request->all());
        return response()->json(['success' => true]);
    }
    public function update(Request $request, $id)
    {
        $lokasi = Lokasi::findOrFail($id);
        $lokasi->update($request->all());
        return response()->json(['success' => true]);
    }
    public function destroy($id)
    {
        Lokasi::destroy($id);
        return response()->json(['success' => true]);
    }

}
