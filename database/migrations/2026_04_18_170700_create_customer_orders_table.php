<?php

declare(strict_types=1);

use App\Models\CustomerOrder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique();
            $table->string('customer_name');
            $table->string('phone')->nullable();
            $table->string('telegram')->nullable();
            $table->string('payment_method')->default(CustomerOrder::PAYMENT_CASH);
            $table->string('payment_status')->default(CustomerOrder::PAYMENT_STATUS_UNPAID);
            $table->string('order_status')->default(CustomerOrder::ORDER_STATUS_PENDING);
            $table->string('voucher_card_code')->nullable();
            $table->string('source')->default('web');
            $table->json('items_payload')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('subtotal_riel')->default(0);
            $table->unsignedBigInteger('discount_riel')->default(0);
            $table->unsignedBigInteger('total_riel')->default(0);
            $table->timestamp('ordered_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_orders');
    }
};
