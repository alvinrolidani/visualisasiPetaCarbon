<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        return view('templates.layouts.master_auth');
    }

    public function register()
    {
        return view('templates.layouts.master_register');
    }

    public function doRegist(Request $request)
    {
        $validasi = $request->validate([
            'name' => 'required|max:255',
            'username' => 'required|max:255|unique:users',
            'password' => 'required_with:password_confirmation|min:3|same:password_confirmation',
            'password_confirmation' => 'min:3|same:password'
        ], [
            'username.unique' => 'Username Sudah Digunakan, gunakan username lain!',
            'username.required' => 'Harap Masukkan Username!',
            'name.required' => 'Harap Masukkan Nama!',
            'password.required' => 'Harap Masukkan Password!',
            'password.min' => 'Kata Sandi Minimal 3 Karakter!',
            'password_confirmation.min' => 'Kata Sandi Minimal 3 Karakter!',
            'password_confirmation.same' => 'Password dan Konfirmasi Password Harus sama!',
            'password.same' => 'Password dan Konfirmasi Password Harus sama!',
        ]);

        $validasi['password'] = bcrypt($validasi['password']);
        $password1 =  $validasi['password_confirmation'] = bcrypt($validasi['password_confirmation']);
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => $password1,
        ]);
        return redirect('/')->with('success', 'Berhasil mendaftar silahkan login');
    }
    public function doLogin(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'username' => ['required'],
            'password' => ['required'],


        ], [

            'username.required' => 'Harap Masukkan Username!',
            'password.required' => 'Harap Masukkan Password!',

        ]);
        $username = $request->username;
        $password = $request->password;

        if (Auth::attempt(['username' =>  $username, 'password' => $password])) {
            $request->session()->regenerate();
            return redirect()->intended('home');
        }
        return back()->with('error', 'Email atau Kata sandi salah');
    }
    public function logout(Request $request)
    {
        User::where('id', auth()->user()->id)->first();
        Auth::logout();
        $request->session()->invalidate();

        $request->session()->regenerateToken();



        return redirect('/')->with('success', 'Anda Berhasil Logout');
    }
}
