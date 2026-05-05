<?php

use App\Enums\PaymentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 64)->unique();          // internal reference
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('gateway', 32);                      // card | mobile_money | bank_transfer
            $table->string('provider', 32)->nullable();         // stripe | cinetpay | orange | mtn | wave
            $table->string('provider_reference', 128)->nullable()->index();
            $table->unsignedInteger('amount_cents');
            $table->string('currency', 8)->default('XOF');
            $table->string('status', 24)->default(PaymentStatus::PENDING->value)->index();
            $table->json('metadata')->nullable();
            $table->json('gateway_response')->nullable();
            $table->string('failure_reason')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
