<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity')->default(1);
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['user_id', 'product_id']); // منتج واحد فقط لكل مستخدم
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart');
    }
};
