<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('menu_categories')->onDelete('cascade');
            $table->string('name'); // e.g., "Kebabipraad"
            $table->text('description'); // Ingredients description
            $table->decimal('price', 8, 2);
            $table->decimal('original_price', 8, 2)->nullable(); // For discounted items
            $table->string('image')->nullable(); // Image path
            $table->boolean('is_featured')->default(false); // Star icon for featured
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->string('size')->nullable(); // e.g., "väike/suur"
            $table->integer('discount_percentage')->nullable(); // e.g., 20
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};