<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class VoucherCard extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_USED_OUT = 'used_out';
    public const GENERATED_CUSTOMER_NAME = 'Generated Card';

    protected $fillable = [
        'voucher_package_id',
        'code',
        'customer_name',
        'phone',
        'telegram',
        'status',
        'total_credits',
        'remaining_credits',
        'activated_at',
        'expires_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'total_credits' => 'integer',
            'remaining_credits' => 'integer',
            'activated_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public static function statusOptions(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
            self::STATUS_USED_OUT => 'Used Out',
        ];
    }

    public static function generateUniqueCode(): string
    {
        do {
            $code = 'VB-'.Str::upper(Str::random(10));
        } while (static::query()->where('code', $code)->exists());

        return $code;
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(VoucherPackage::class, 'voucher_package_id');
    }
}
