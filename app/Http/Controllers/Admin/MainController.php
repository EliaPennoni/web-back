<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MainController extends Controller
{

    public function register()
    {
        return view('auth.register');
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }
    public function form()
    {
        return view('photos.form');
    }
}
