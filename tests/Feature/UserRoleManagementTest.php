<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class UserRoleManagementTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_superadmin_can_view_role_management_and_change_user_role(): void
    {
        $superadmin = User::factory()->create(['role' => 'superadmin']);
        $user = User::factory()->create(['role' => 'sales']);

        $this->actingAs($superadmin)
            ->get(route('user-roles.index'))
            ->assertSee('Manajemen Role')
            ->assertSee($user->email);

        $this->actingAs($superadmin)
            ->patch(route('user-roles.update', $user), ['role' => 'cashier'])
            ->assertRedirect(route('user-roles.index'))
            ->assertSessionHas('success');

        $this->assertSame('cashier', $user->refresh()->role);
    }

    public function test_non_superadmin_cannot_view_or_update_roles(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'sales']);

        $this->actingAs($admin)->get(route('user-roles.index'))->assertForbidden();
        $this->actingAs($admin)
            ->patch(route('user-roles.update', $user), ['role' => 'cashier'])
            ->assertForbidden();

        $this->assertSame('sales', $user->refresh()->role);
    }

    public function test_role_must_be_from_the_supported_role_list(): void
    {
        $superadmin = User::factory()->create(['role' => 'superadmin']);
        $user = User::factory()->create(['role' => 'sales']);

        $this->actingAs($superadmin)
            ->patch(route('user-roles.update', $user), ['role' => 'owner'])
            ->assertSessionHasErrors('role');

        $this->assertSame('sales', $user->refresh()->role);
    }

    public function test_last_superadmin_cannot_remove_their_own_superadmin_role(): void
    {
        $superadmin = User::factory()->create(['role' => 'superadmin']);

        $this->actingAs($superadmin)
            ->patch(route('user-roles.update', $superadmin), ['role' => 'admin'])
            ->assertSessionHasErrors('role');

        $this->assertSame('superadmin', $superadmin->refresh()->role);
    }
}
