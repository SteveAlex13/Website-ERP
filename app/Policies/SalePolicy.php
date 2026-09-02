<?php

namespace App\Policies;

use App\Models\Sale;
use App\Models\User;

class SalePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['superadmin', 'admin', 'sales', 'cashier', 'finance', 'warehouse'], true);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Sale $sale): bool
    {
        return in_array($user->role, ['superadmin', 'admin', 'sales', 'cashier', 'finance', 'warehouse'], true);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['superadmin', 'admin', 'sales', 'cashier'], true);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Sale $sale): bool
    {
        return in_array($user->role, ['admin', 'sales'], true) && ! in_array($sale->status, ['completed', 'cancelled'], true);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Sale $sale): bool
    {
        return $user->role === 'admin' && $sale->status === 'open';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Sale $sale): bool
    {
        return in_array($user->role, ['admin', 'sales'], true) && $sale->status === 'cancelled';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Sale $sale): bool
    {
        return false;
    }
}
