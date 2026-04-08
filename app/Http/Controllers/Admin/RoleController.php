<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RoleController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Role/Index', [
            'roles' => Role::latest()->paginate(20),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Role/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:roles,slug',
        ]);

        Role::create($validated);

        return to_route('admin.roles.index')
            ->with('success', 'Role "' . $validated['name'] . '" created successfully.');
    }

    public function show(Role $role)
    {
        return Inertia::render('Admin/Role/Show', [
            'role' => $role->load('rights'),
        ]);
    }

    public function destroy(Role $role)
    {
        $name = $role->name;
        $role->delete();

        return to_route('admin.roles.index')
            ->with('success', 'Role "' . $name . '" deleted successfully.');
    }
}
