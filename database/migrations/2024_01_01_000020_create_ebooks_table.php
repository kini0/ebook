<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ebooks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()
                ->constrained('categories')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('subtitle')->nullable();
            $table->string('author', 180);
            $table->text('short_description')->nullable();
            $table->longText('description');
            $table->string('isbn', 30)->nullable()->index();
            $table->string('language', 10)->default('fr');
            $table->unsignedSmallInteger('pages')->nullable();
            $table->unsignedInteger('price_cents');         // FCFA stored as integer
            $table->unsignedInteger('compare_at_cents')->nullable();
            $table->string('cover_path');
            $table->string('file_path');                    // protected disk path
            $table->string('file_format', 10)->default('pdf');
            $table->unsignedInteger('file_size_bytes')->default(0);
            $table->unsignedInteger('download_count')->default(0);
            $table->unsignedInteger('view_count')->default(0);
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('is_published')->default(false)->index();
            $table->timestamp('published_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['is_published', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ebooks');
    }
};
