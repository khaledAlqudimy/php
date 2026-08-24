<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('short_desc')->nullable();
            $table->text('description')->nullable();
            $table->text('story')->nullable();          // القصة التاريخية
            $table->decimal('price', 10, 2);
            $table->decimal('compare_at_price', 10, 2)->nullable();
            $table->string('country_of_origin')->nullable();
            $table->string('era')->nullable();          // الحقبة الزمنية
            $table->string('material')->nullable();     // المادة
            $table->string('dimensions')->nullable();   // الأبعاد
            $table->string('condition')->nullable();    // الحالة
            $table->integer('stock')->default(1);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_rare')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('views_count')->default(0);
            $table->string('video_url')->nullable();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
