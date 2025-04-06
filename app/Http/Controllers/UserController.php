<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all(); 
        return view('MD.user', compact('users'));
    }
    public function store(Request $request)
{
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',  // Ganti 'nama' menjadi 'name'
        'email' => 'required|email|unique:users,email',
        'role' => 'required|string'
    ]);

    User::create($validatedData);
    return response()->json(['success' => true, 'message' => 'User berhasil ditambahkan']);
}

public function update(Request $request, $id)
{
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',  // Ganti 'nama' menjadi 'name'
        'email' => 'required|email|unique:users,email,'.$id,
        'role' => 'required|string'
    ]);

    $user = User::findOrFail($id);
    $user->update($validatedData);
    return response()->json(['success' => true, 'message' => 'User berhasil diperbarui']);
}
}

