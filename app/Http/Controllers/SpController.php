<?php

namespace App\Http\Controllers;

use App\Models\Sp;
use Illuminate\Http\Request;

class SpController extends Controller
{
    public function index()
    {
        $sp = Sp::all();
        return view('MD.sp', compact('sp'));
    }

    public function store(Request $request)
    {
        Sp::create($request->all());
        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        $sp = Sp::findOrFail($id);
        $sp->update($request->all());
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        Sp::destroy($id);
        return response()->json(['success' => true]);
    }

}
