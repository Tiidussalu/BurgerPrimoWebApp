<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'courier_lat')) {
                $table->decimal('courier_lat', 10, 7)->nullable()->after('delivery_method');
            }
            if (!Schema::hasColumn('orders', 'courier_lng')) {
                $table->decimal('courier_lng', 10, 7)->nullable()->after('courier_lat');
            }
            if (!Schema::hasColumn('orders', 'courier_updated_at')) {
                $table->timestamp('courier_updated_at')->nullable()->after('courier_lng');
            }
            if (!Schema::hasColumn('orders', 'courier_token')) {
                $table->string('courier_token', 64)->nullable()->unique()->after('courier_updated_at');
            }
        });

        // MySQL/MariaDB: lisa 'delivering' enum-i (SQLite ei vajab seda — see käsitleb kõiki stringe)
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending','confirmed','preparing','ready','delivering','completed','cancelled','rejected') DEFAULT 'pending'");
        }
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['courier_lat', 'courier_lng', 'courier_updated_at', 'courier_token']);
        });

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending','confirmed','preparing','ready','completed','cancelled','rejected') DEFAULT 'pending'");
        }
    }
};
