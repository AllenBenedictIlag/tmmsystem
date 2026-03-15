<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalRoles = Role::count();

        $roleCounts = Role::withCount('users')->orderBy('name')->get();

        return view('admin.dashboard', compact('totalUsers', 'totalRoles', 'roleCounts'));
    }
}