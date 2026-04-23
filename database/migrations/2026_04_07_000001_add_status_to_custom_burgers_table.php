<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('custom_burgers', function (Blueprint $table) {
            $table->string('status')->default('draft')->after('is_favorite');
            // draft = salvestatud, pending = ootab kinnitust, approved = kinnitatud, rejected = tagasi lükatud
            $table->text('admin_note')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('custom_burgers', function (Blueprint $table) {
            $table->dropColumn(['status', 'admin_note']);
        });
    }
};