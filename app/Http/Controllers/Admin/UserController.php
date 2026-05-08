<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado == '1');
        }

        // El rol de Ing. Sistemas es incógnito para los demás
        if (auth()->user()->role !== 'ing_sistemas') {
            $query->where('role', '!=', 'ing_sistemas');
        }

        $users = $query->orderBy('created_at', 'desc')->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:admin,supervisor,ing_sistemas',
            'permissions' => 'nullable|array'
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'estado' => true,
            'permissions' => $request->permissions ?? [],
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Usuario creado exitosamente.');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|string|in:admin,supervisor,ing_sistemas',
            'permissions' => 'nullable|array'
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->permissions = $request->permissions ?? [];

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function toggleStatus(Request $request, User $user)
    {
        if (auth()->user()->role !== 'ing_sistemas') {
            return redirect()->back()->with('error', 'Solo el Ingeniero de Sistemas puede realizar esta acción.');
        }

        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'No puedes bloquearte a ti mismo.');
        }

        $user->estado = !$user->estado;
        $user->save();

        $action = $user->estado ? 'unblocked' : 'blocked';
        
        \App\Models\UserBlockHistory::create([
            'user_id' => $user->id,
            'admin_id' => auth()->id(),
            'action' => $action,
            'reason' => $request->reason ?? 'Sin motivo especificado'
        ]);

        $statusText = $user->estado ? 'desbloqueado' : 'bloqueado';
        return redirect()->back()->with('success', "Usuario {$statusText} correctamente.");
    }

    public function blockHistory(User $user)
    {
        if (auth()->user()->role !== 'ing_sistemas') {
            abort(403);
        }
        $user->load('blockHistories.admin');
        return view('admin.users.block_history', compact('user'));
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'No puedes eliminarte a ti mismo.');
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
