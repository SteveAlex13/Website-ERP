<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreManagedUserRequest;
use App\Http\Requests\UpdateUserRoleRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class UserRoleController extends Controller
{
    public function index(): View
    {
        return view('user-roles.index', [
            'users' => User::query()->select(['id', 'name', 'email', 'role'])->orderBy('name')->paginate(12),
            'roles' => [
                'superadmin' => 'Superadmin',
                'admin' => 'Administrator',
                'cashier' => 'Kasir',
                'sales' => 'Sales',
                'purchasing' => 'Purchasing',
                'warehouse' => 'Warehouse',
                'finance' => 'Finance',
            ],
        ]);
    }

    public function update(UpdateUserRoleRequest $request, User $user): RedirectResponse
    {
        $user->update($request->validated());

        return to_route('user-roles.index')->with('success', sprintf('Role %s berhasil diperbarui.', $user->name));
    }

    public function store(StoreManagedUserRequest $request): RedirectResponse
    {
        $user = User::create($request->validated());

        return to_route('user-roles.index')->with('success', sprintf('Akun %s berhasil ditambahkan.', $user->name));
    }
}
