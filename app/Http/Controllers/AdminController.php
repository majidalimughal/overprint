<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        dd(User::where('role','!=','store')->get()->toArray());
    }
}
