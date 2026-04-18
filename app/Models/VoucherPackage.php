<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VoucherPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'credits',
        'price_riel',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'credits' => 'integer',
            'price_riel' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function cards(): HasMany
    {
        return $this->hasMany(VoucherCard::class, 'voucher_package_id');
    }
}
