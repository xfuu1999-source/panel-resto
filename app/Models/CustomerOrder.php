<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerOrder extends Model
{
    use HasFactory;

    public const PAYMENT_CASH = 'cash';
    public const PAYMENT_QR_ABA = 'qr_aba';

    public const PAYMENT_STATUS_UNPAID = 'unpaid';
    public const PAYMENT_STATUS_PAID = 'paid';
    public const PAYMENT_STATUS_REFUNDED = 'refunded';

    public const ORDER_STATUS_PENDING = 'pending';
    public const ORDER_STATUS_PROCESSING = 'processing';
    public const ORDER_STATUS_READY = 'ready';
    public const ORDER_STATUS_COMPLETED = 'completed';
    public const ORDER_STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'order_code',
        'customer_name',
        'phone',
        'telegram',
        'payment_method',
        'payment_status',
        'order_status',
        'voucher_card_code',
        'source',
        'items_payload',
        'notes',
        'subtotal_riel',
        'discount_riel',
        'total_riel',
        'ordered_at',
    ];

    protected function casts(): array
    {
        return [
            'items_payload' => 'array',
            'subtotal_riel' => 'integer',
            'discount_riel' => 'integer',
            'total_riel' => 'integer',
            'ordered_at' => 'datetime',
        ];
    }

    public static function paymentMethodOptions(): array
    {
        return [
            self::PAYMENT_CASH => 'Cash / COD',
            self::PAYMENT_QR_ABA => 'QR ABA',
        ];
    }

    public static function paymentStatusOptions(): array
    {
        return [
            self::PAYMENT_STATUS_UNPAID => 'Unpaid',
            self::PAYMENT_STATUS_PAID => 'Paid',
            self::PAYMENT_STATUS_REFUNDED => 'Refunded',
        ];
    }

    public static function orderStatusOptions(): array
    {
        return [
            self::ORDER_STATUS_PENDING => 'Pending',
            self::ORDER_STATUS_PROCESSING => 'Processing',
            self::ORDER_STATUS_READY => 'Ready',
            self::ORDER_STATUS_COMPLETED => 'Completed',
            self::ORDER_STATUS_CANCELLED => 'Cancelled',
        ];
    }
}
