<?php

declare(strict_types=1);

use App\Models\VoucherCard;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voucher_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voucher_package_id')->constrained('voucher_packages')->cascadeOnDelete();
            $table->string('code')->unique();
            $table->string('customer_name');
            $table->string('phone')->nullable();
            $table->string('telegram')->nullable();
            $table->string('status')->default(VoucherCard::STATUS_PENDING);
            $table->unsignedInteger('total_credits')->default(0);
            $table->unsignedInteger('remaining_credits')->default(0);
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voucher_cards');
    }
};
