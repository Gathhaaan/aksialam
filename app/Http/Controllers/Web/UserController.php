<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Report;
use App\Models\Campaign;
use App\Models\User;

class UserController extends Controller
{
    public function dashboard()
    {
        // User biasa langsung ke beranda
        return redirect()->route('home');
    }

    private function redirectByRole()
    {
        $role = Auth::user()->role;
        if ($role === 'admin') return redirect()->route('admin.dashboard');
        if ($role === 'organizer') return redirect()->route('organizer.dashboard');
        return redirect()->route('landing');
    }
}
