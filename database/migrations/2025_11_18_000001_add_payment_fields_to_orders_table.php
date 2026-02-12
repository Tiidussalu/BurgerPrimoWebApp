<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_intent_id')->nullable()->after('delivery_method');
            $table->string('payment_status')->default('pending')->after('payment_intent_id'); // pending, succeeded, failed, refunded
            $table->string('payment_method')->nullable()->after('payment_status'); // card, cash
            $table->timestamp('paid_at')->nullable()->after('payment_method');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_intent_id', 'payment_status', 'payment_method', 'paid_at']);
        });
    }
};
