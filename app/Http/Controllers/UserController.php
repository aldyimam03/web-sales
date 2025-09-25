<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::latest()->paginate(10);
        $pageTitle = 'Halaman User';
        return view('users.index', compact('users', 'pageTitle'));
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
    public function update(Request $request)
    {
        try {
            $user = Auth::user(); // ambil user login

            $validated = $request->validate([
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|unique:users,email,' . $user->id,
                'password' => 'nullable|min:6|confirmed',
                'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            // password baru (opsional)
            if (!empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            // foto profil (opsional)
            if ($request->hasFile('profile_photo')) {
                // hapus foto lama kalau ada
                if ($user->profile_photo_url && Storage::exists(str_replace('storage/', '', $user->profile_photo_url))) {
                    Storage::delete(str_replace('storage/', '', $user->profile_photo_url));
                }

                $path = $request->file('profile_photo')->store('profile_photos', 'public');
                $validated['profile_photo_url'] = 'storage/' . $path; 
            }

            // sekarang isi user dengan data baru
            $user->fill($validated);

            if (!$user->isDirty()) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Tidak ada perubahan data.');
            }

            $user->save();

            return redirect()
                ->route('dashboard')
                ->with('success', 'Profil berhasil diperbarui!');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('error', 'Gagal update profil: ' . $th->getMessage());
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
