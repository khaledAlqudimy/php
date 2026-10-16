<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // unpaid | paid | failed
            $table->string('payment_status')->default('unpaid')->after('payment_method');
            $table->string('payment_reference')->nullable()->after('payment_status');  // رقم فاتورة Moyasar
            $table->timestamp('paid_at')->nullable()->after('payment_reference');
            $table->index(['payment_method', 'payment_status']);
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['payment_method', 'payment_status']);
            $table->dropColumn(['payment_status', 'payment_reference', 'paid_at']);
        });
    }
};
