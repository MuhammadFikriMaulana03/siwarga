<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Controller berhasil dibuat oleh Hermes Agent!',
            'timestamp' => now(),
            'status' => 'success'
        ]);
    }

    public function hello($name = 'World')
    {
        return "Hello $name! Ini adalah test controller dari Hermes Agent.";
    }

    public function dashboard()
    {
        return view('test.dashboard', [
            'title' => 'Dashboard Test',
            'welcome' => 'Selamat datang di fitur test'
        ]);
    }
}