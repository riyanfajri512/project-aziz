<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $supplier = Supplier::all();
        return view('MD.supplier', compact('supplier'));
    }
    public function store(Request $request)
    {
        Supplier::create($request->all());
        return response()->json(['success' => true]);
    }
    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->update($request->all());
        return response()->json(['success' => true]);
    }
    public function destroy($id)
    {
        Supplier::destroy($id);
        return response()->json(['success' => true]);
    }
}
