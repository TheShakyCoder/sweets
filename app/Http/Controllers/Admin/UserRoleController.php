<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserRoleController extends Controller
{
    public function index(User $user)
    {
        return Inertia::render('Admin/User/Roles/Index', [
            'user'  => $user->load('roles'),
            'roles' => Role::orderBy('name')->get(['id', 'name', 'slug']),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'role_ids'   => ['array'],
            'role_ids.*' => ['uuid', 'exists:roles,id'],
        ]);

        $user->roles()->sync($request->input('role_ids', []));

        return to_route('admin.user_roles.index', ['user' => $user])
            ->with('success', 'Roles for "' . $user->name . '" updated successfully.');
    }
}
