<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('roles');

        if ($request->has('email')) {
            $email = $request->email;
            $query->whereRaw("email LIKE ?", ["%{$email}%"]);
        }

        if ($request->has('name')) {
            $name = $request->name;
            $query->whereRaw("name LIKE ?", ["%{$name}%"]);
        }

        $query->where('status', 1);
        $users = $query->get();

        return response()->json($users);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:5',
            'role' => 'required'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        $user->roles()->attach($validated['role']);
        return response()->json($user->load('roles'), 201);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user, 200);
    }

    public function update(Request $request, $id)
    {
        // Validar los datos de entrada, la contraseña es opcional
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255',
            'password' => 'sometimes|required|string|min:5',
            'role' => 'required'
        ]);

        $user = User::findOrFail($id);

        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }

        $user->update($validated);

        if (isset($validated['role'])) {
            $user->roles()->sync([$validated['role']]);
        }

        return response()->json($user->load('roles'), 200);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->status = false;
        $user->save();
        return response()->json([]);
    }
}
