<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Right;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class RoleRightController extends Controller
{
    public function index(Role $role): Response
    {
        $internalRoutes = collect(Route::getRoutes()->getRoutes())
            ->filter(function($item) {
                $as = explode('.', $item->action['as'] ?? '');
                // Only include top-level resource routes (exactly 3 segments: internal.resource.method)
                return
                    str_starts_with($item->uri, 'internal')
                    && count($as) === 3
                    && (
                        str_ends_with($item->action['controller'], 'index')
                        || str_ends_with($item->action['controller'], 'show')
                        || str_ends_with($item->action['controller'], 'store')
                        || str_ends_with($item->action['controller'], 'update')
                        || str_ends_with($item->action['controller'], 'destroy')
                    )
                    ;
            })
            ->map(function($i) {
                $as = explode('.', $i->action['as']);
                $controller = $as[1];

                return [
                    'method' => $as[2],
                    'controller_group' => $controller,
                    'controller' => last(explode('\\', $i->action['controller'])),
                    'route' => $i->action['as'],
                ];
            })
            ->groupBy('controller_group');

        $adminRoutes = collect(Route::getRoutes()->getRoutes())
            ->filter(function($item) {
                $as = explode('.', $item->action['as'] ?? '');
                return
                    str_starts_with($item->uri, 'admin')
                    && count($as) === 3
                    && (
                        str_ends_with($item->action['controller'], 'index')
                        || str_ends_with($item->action['controller'], 'show')
                        || str_ends_with($item->action['controller'], 'store')
                        || str_ends_with($item->action['controller'], 'update')
                        || str_ends_with($item->action['controller'], 'destroy')
                    )
                    ;
            })
            ->map(function($i) {
                $as = explode('.', $i->action['as']);
                $controller = $as[1];

                return [
                    'method' => $as[2],
                    'controller_group' => $controller,
                    'controller' => last(explode('\\', $i->action['controller'])),
                    'route' => $i->action['as'],
                ];
            })
            ->groupBy('controller_group');

        return Inertia::render('Admin/Role/Right/Index', [
            'role' => $role->load('rights'),
            'internal' => $internalRoutes,
            'admin' => $adminRoutes,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Role $role)
    {
        $role->rights()->updateOrCreate(['controller_method_name' => $request->get('controller_method_name')]);

        return to_route('admin.roles.rights.index', ['role' => $role]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        //  remove all current rights
        Right::query()->where('role_id', $role->id)->delete();

        collect($request->get('controller_method_names'))->each(function($state, $controllerMethodName) use($role) {
            if($state) $role->rights()->create(['controller_method_name' => $controllerMethodName]);
        });

        return to_route('admin.role_rights.index', ['role' => $role])
            ->with('success', 'Rights for "' . $role->name . '" updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
