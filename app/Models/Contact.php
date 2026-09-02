<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['type', 'name', 'phone', 'email', 'address'])]
class Contact extends Model
{
    use HasFactory;

    #[Scope]
    protected function customers(Builder $query): Builder
    {
        return $query->where('type', 'customer');
    }

    #[Scope]
    protected function suppliers(Builder $query): Builder
    {
        return $query->where('type', 'supplier');
    }
}
