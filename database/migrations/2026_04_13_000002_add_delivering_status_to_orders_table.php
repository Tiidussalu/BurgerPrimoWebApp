<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Puhasta eelmisest katkisest katsest (SQLite jätab indeksid alles)
        // SQLite: indeksid kuuluvad tabelile, DROP TABLE kustutab ka indeksid
        DB::statement('DROP TABLE IF EXISTS "orders_new"');

        // SQLite ei toeta MODIFY COLUMN — loome tabeli uuesti koos 'delivering' staatusega
        Schema::create('orders_new', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', ['pending', 'confirmed', 'preparing', 'ready', 'delivering', 'completed', 'cancelled', 'rejected'])->default('pending');
            $table->text('customer_notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->foreignId('confirmed_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->string('delivery_method')->default('takeaway');
            $table->string('payment_intent_id')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('payment_method')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->string('courier_token', 64)->nullable()->unique();
            $table->decimal('courier_lat', 10, 7)->nullable();
            $table->decimal('courier_lng', 10, 7)->nullable();
            $table->timestamp('courier_updated_at')->nullable();
        });

        DB::statement('
            INSERT INTO orders_new (
                id, user_id, order_number, total_amount, status,
                customer_notes, admin_notes, confirmed_at, confirmed_by,
                created_at, updated_at, delivery_method,
                payment_intent_id, payment_status, payment_method, paid_at,
                courier_token, courier_lat, courier_lng, courier_updated_at
            )
            SELECT
                id, user_id, order_number, total_amount, status,
                customer_notes, admin_notes, confirmed_at, confirmed_by,
                created_at, updated_at, delivery_method,
                payment_intent_id, payment_status, payment_method, paid_at,
                courier_token, courier_lat, courier_lng, courier_updated_at
            FROM orders
        ');

        Schema::dropIfExists('orders');
        Schema::rename('orders_new', 'orders');
    }

    public function down(): void
    {
        Schema::create('orders_new', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', ['pending', 'confirmed', 'preparing', 'ready', 'completed', 'cancelled', 'rejected'])->default('pending');
            $table->text('customer_notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->foreignId('confirmed_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->string('delivery_method')->default('takeaway');
            $table->string('payment_intent_id')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('payment_method')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->string('courier_token', 64)->nullable()->unique();
            $table->decimal('courier_lat', 10, 7)->nullable();
            $table->decimal('courier_lng', 10, 7)->nullable();
            $table->timestamp('courier_updated_at')->nullable();
        });

        DB::statement('INSERT INTO orders_new SELECT * FROM orders WHERE status != \'delivering\'');

        Schema::dropIfExists('orders');
        Schema::rename('orders_new', 'orders');
    }
};