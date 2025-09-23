<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'  => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6|confirmed',
            ]);

            $validated['password'] = Hash::make($validated['password']);

            if (User::where('email', $validated['email'])->exists()) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Email sudah terdaftar!');
            }

            $user = User::create($validated);

            $user->assignRole('user');

            return redirect()
                ->route('users.index')
                ->with('success', 'User dengan nama ' . $validated['name'] . ' berhasil ditambahkan!');
        } catch (\Throwable $th) {
            return redirect()
                ->route('users.store')
                ->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        try {
            $validated = $request->validate([
                'name'  => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'password' => 'nullable|min:6|confirmed',
            ]);

            if (!empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']); 
            }


            $user->fill($validated);

            if (!$user->isDirty()) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Tidak ada perubahan data.');
            }

            $user->save();

            return redirect()->route('users.index')->with('success', 'User berhasil diperbarui!');
        } catch (\Throwable $th) {
            return redirect()
                ->route('users.index')
                ->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user = User::findOrFail($user->id);
        $user->delete();
        return redirect()
            ->route('users.index')
            ->with('success', 'User dengan nama ' . $user->name . ' berhasil dihapus.');
    }
}
