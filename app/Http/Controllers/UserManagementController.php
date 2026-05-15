<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserManagementController extends Controller
{
    // Menampilkan daftar user
    public function index(Request $request)
    {
        $query = DB::table('users')
            ->leftJoin('mahasiswas', 'users.mahasiswa_id', '=', 'mahasiswas.id')
            ->leftJoin('dosens', 'users.dosen_id', '=', 'dosens.id')
            ->select(
                'users.*',
                'mahasiswas.nim',
                'mahasiswas.nama_mahasiswa as mahasiswa_name',
                'dosens.nidn',
                'dosens.nama_dosen as dosen_name'
            );
        
        if ($request->has('search') && $request->search != '') {
            $query->where('users.name', 'like', '%' . $request->search . '%')
                  ->orWhere('users.email', 'like', '%' . $request->search . '%');
        }
        
        if ($request->has('role') && $request->role != '') {
            $query->where('users.role', $request->role);
        }
        
        $users = $query->orderBy('users.id', 'desc')->paginate(10);
        
        return view('user-management.index', compact('users'));
    }

    // Form tambah user
    public function create()
    {
        $mahasiswas = DB::table('mahasiswas')
            ->whereNotIn('id', function($query) {
                $query->select('mahasiswa_id')->from('users')->whereNotNull('mahasiswa_id');
            })
            ->get();
            
        $dosens = DB::table('dosens')
            ->whereNotIn('id', function($query) {
                $query->select('dosen_id')->from('users')->whereNotNull('dosen_id');
            })
            ->get();
            
        return view('user-management.create', compact('mahasiswas', 'dosens'));
    }

    // Menyimpan user baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'email' => 'required|email|unique:users',
            'role' => 'required|in:admin,dosen,mahasiswa',
            'password' => 'required|min:6',
            'mahasiswa_id' => 'required_if:role,mahasiswa|nullable|exists:mahasiswas,id',
            'dosen_id' => 'required_if:role,dosen|nullable|exists:dosens,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        
        if ($request->role == 'mahasiswa') {
            $userData['mahasiswa_id'] = $request->mahasiswa_id;
        } elseif ($request->role == 'dosen') {
            $userData['dosen_id'] = $request->dosen_id;
        }
        
        DB::table('users')->insert($userData);

        return redirect()->route('user-management.index')->with('success', 'User berhasil ditambahkan');
    }

    // Menampilkan detail user
    public function show($id)
    {
        $user = DB::table('users')
            ->leftJoin('mahasiswas', 'users.mahasiswa_id', '=', 'mahasiswas.id')
            ->leftJoin('dosens', 'users.dosen_id', '=', 'dosens.id')
            ->where('users.id', $id)
            ->select('users.*', 'mahasiswas.nama_mahasiswa', 'dosens.nama_dosen')
            ->first();
            
        if (!$user) {
            abort(404);
        }
        
        return view('user-management.show', compact('user'));
    }

    // Form edit user
    public function edit($id)
    {
        $user = DB::table('users')->where('id', $id)->first();
        if (!$user) {
            abort(404);
        }
        
        $mahasiswas = DB::table('mahasiswas')->get();
        $dosens = DB::table('dosens')->get();
        
        return view('user-management.edit', compact('user', 'mahasiswas', 'dosens'));
    }

    // Update user
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,dosen,mahasiswa',
            'mahasiswa_id' => 'required_if:role,mahasiswa|nullable|exists:mahasiswas,id',
            'dosen_id' => 'required_if:role,dosen|nullable|exists:dosens,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'updated_at' => now(),
        ];
        
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }
        
        if ($request->role == 'mahasiswa') {
            $userData['mahasiswa_id'] = $request->mahasiswa_id;
            $userData['dosen_id'] = null;
        } elseif ($request->role == 'dosen') {
            $userData['dosen_id'] = $request->dosen_id;
            $userData['mahasiswa_id'] = null;
        } else {
            $userData['mahasiswa_id'] = null;
            $userData['dosen_id'] = null;
        }
        
        DB::table('users')->where('id', $id)->update($userData);

        return redirect()->route('user-management.index')->with('success', 'User berhasil diupdate');
    }

    // Hapus user
    public function destroy($id)
    {
        // Prevent deleting own account
        if (session('user_id') == $id) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus akun sendiri!');
        }
        
        DB::table('users')->where('id', $id)->delete();
        return redirect()->route('user-management.index')->with('success', 'User berhasil dihapus');
    }
    
    // Reset password
    public function resetPassword($id)
    {
        DB::table('users')->where('id', $id)->update([
            'password' => Hash::make('password123'),
            'updated_at' => now(),
        ]);
        
        return redirect()->back()->with('success', 'Password berhasil direset menjadi: password123');
    }
    
    // Profile user yang login
    public function profile()
    {
        if (!session()->has('user_id')) {
            return redirect('/login');
        }
        
        $user = DB::table('users')->where('id', session('user_id'))->first();
        
        if ($user->role == 'mahasiswa') {
            $profile = DB::table('mahasiswas')->where('id', $user->mahasiswa_id)->first();
        } elseif ($user->role == 'dosen') {
            $profile = DB::table('dosens')->where('id', $user->dosen_id)->first();
        } else {
            $profile = null;
        }
        
        return view('user-management.profile', compact('user', 'profile'));
    }
    
    // Update profile
    public function updateProfile(Request $request)
    {
        if (!session()->has('user_id')) {
            return redirect('/login');
        }
        
        $user_id = session('user_id');
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'email' => 'required|email|unique:users,email,' . $user_id,
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        DB::table('users')->where('id', $user_id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'updated_at' => now(),
        ]);
        
        // Update session name
        session(['user_name' => $request->name]);
        
        if ($request->filled('password')) {
            DB::table('users')->where('id', $user_id)->update([
                'password' => Hash::make($request->password),
            ]);
        }
        
        return redirect()->back()->with('success', 'Profile berhasil diupdate');
    }
    
    // Proses login
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('/login')->withErrors($validator)->withInput();
        }
        
        $user = DB::table('users')->where('email', $request->email)->first();
        
        if ($user && Hash::check($request->password, $user->password)) {
            session()->flush();
            session()->regenerate();
            
            session([
                'user_id' => $user->id,
                'user_role' => $user->role,
                'user_name' => $user->name
            ]);
            
            return redirect('/')->with('success', 'Login berhasil! Selamat datang, ' . $user->name . '!');
        }
        
        return redirect('/login')->with('error', 'Email atau password salah!');
    }
    
    // Form login
    public function loginForm()
    {
        if (session()->has('user_id')) {
            return redirect('/');
        }
        return view('user-management.login');
    }
    
    // Logout
    public function logout()
    {
        session()->flush();
        return redirect('/login')->with('success', 'Logout berhasil!');
    }
}