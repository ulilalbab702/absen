<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Lokasi;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class authController extends Controller
{
    public function index()
    {
        return view('auth.login',[
            "title" => "Log In"
        ]);
    }

    public function register()
    {
        return view('auth.register', [
            "title" => "Register Account",
            "data_jabatan" => Jabatan::all(),
            "data_lokasi" => Lokasi::where('status', 'approved')->get()
        ]);
    }

    public function registerProses(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'foto_karyawan' => 'image|file|max:10240',
            'email' => 'required|email:dns|unique:users',
            'telepon' => 'required',
            'username' => 'required|max:255|unique:users',
            'password' => 'required|confirmed|min:6|max:255',
            'tgl_lahir' => 'required',
            'gender' => 'required',
            'tgl_join' => 'required',
            'status_nikah' => 'required',
            'alamat' => 'required',
            'cuti_dadakan' => 'required',
            'cuti_bersama' => 'required',
            'cuti_menikah' => 'required',
            'cuti_diluar_tanggungan' => 'required',
            'cuti_khusus' => 'required',
            'cuti_melahirkan' => 'required',
            'izin_telat' => 'required',
            'izin_pulang_cepat' => 'required',
            'is_admin' => 'required',
            'jabatan_id' => 'required',
            'lokasi_id' => 'required'
        ]);

        if ($request->file('foto_karyawan')) {
            $validatedData['foto_karyawan'] = $request->file('foto_karyawan')->store('foto_karyawan');
        }

        $validatedData['password'] = Hash::make($validatedData['password']);
        User::create($validatedData);
        return redirect('/')->with('success', 'Berhasil Register! Silahkan Login');
    }

    public function loginProses(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);
        
        $remember_me = $request->has('remember') ? true : false;

        if (Auth::attempt($credentials, $remember_me)) {
            $request->session()->regenerate();

            return redirect()->intended('/dashboard');
        }

        return back()->with('loginError', 'Login Gagal!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
