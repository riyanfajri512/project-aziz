<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::all();
        return view('MD.IndexKategori', compact('kategori'));
    }

    public function store(Request $request)
    {
        Kategori::create($request->all());
        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->update($request->all());
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        Kategori::destroy($id);
        return response()->json(['success' => true]);
    }
}