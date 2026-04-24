<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_courier')->default(false)->after('is_admin');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('courier_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->after('courier_token');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['courier_user_id']);
            $table->dropColumn('courier_user_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_courier');
        });
    }
};
