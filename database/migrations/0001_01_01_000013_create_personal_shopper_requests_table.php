<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personal_shopper_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('item_type');                    // نوع القطعة
            $table->string('era')->nullable();
            $table->string('country_of_origin')->nullable();
            $table->decimal('budget', 10, 2)->nullable();
            $table->text('description');
            $table->text('reference_images')->nullable();   // JSON array من روابط الصور
            $table->enum('status', ['NEW', 'IN_PROGRESS', 'FULFILLED', 'CLOSED'])
                  ->default('NEW');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personal_shopper_requests');
    }
};
