<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'email', 'password', 'role', 'sales_target', 'commission_type', 'commission_value'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'sales_target' => 'decimal:2',
            'commission_value' => 'decimal:2',
        ];
    }

    public function commissionFor(float $saleTotal, float $itemQuantity): float
    {
        if ($saleTotal < (float) $this->sales_target) {
            return 0;
        }

        return $this->commission_type === 'fixed_per_item'
            ? $itemQuantity * (float) $this->commission_value
            : $saleTotal * ((float) $this->commission_value / 100);
    }
}
