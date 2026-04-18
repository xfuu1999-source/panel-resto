<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashierAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'email',
        'pin_hash',
        'shift_label',
        'is_active',
        'notes',
    ];

    protected $hidden = [
        'pin_hash',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
