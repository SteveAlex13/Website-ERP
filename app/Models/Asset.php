<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['code', 'name', 'acquired_at', 'acquisition_cost', 'residual_value', 'useful_life_months'])]
class Asset extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return ['acquired_at' => 'date', 'acquisition_cost' => 'decimal:2', 'residual_value' => 'decimal:2'];
    }

    public function monthlyDepreciation(): float
    {
        return ((float) $this->acquisition_cost - (float) $this->residual_value) / $this->useful_life_months;
    }

    public function bookValueAt(\DateTimeInterface $date): float
    {
        $months = min($this->useful_life_months, max(0, $this->acquired_at->diffInMonths($date)));

        return max((float) $this->residual_value, (float) $this->acquisition_cost - ($this->monthlyDepreciation() * $months));
    }
}
