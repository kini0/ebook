<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ebook_id')->constrained()->restrictOnDelete();
            $table->string('title_snapshot');           // store title at purchase time
            $table->string('author_snapshot');
            $table->unsignedInteger('unit_price_cents');
            $table->unsignedInteger('quantity')->default(1);
            $table->unsignedInteger('total_cents');
            $table->timestamps();

            $table->index(['order_id', 'ebook_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
