<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookcatController extends Controller
{
    public function loginform()
    {
        return view('main.login');
    }

    public function loginProcess(Request $request) 
    {
        $email = $request->input('email');
        $password = $request->input('password');

        // proses login dan autentikasi pengguna
    }
}