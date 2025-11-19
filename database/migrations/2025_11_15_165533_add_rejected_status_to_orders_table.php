<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite doesn't support modifying enums, so we'll use a workaround
        // The rejected status will be stored as text and validated in the application
        // For now, just document that 'rejected' is a valid status
        
        // If you need strict validation, you would need to:
        // 1. Create new table with updated enum
        // 2. Copy data
        // 3. Drop old table
        // 4. Rename new table
        
        // But for SQLite, we can just use it - Laravel's validation will handle it
    }

    public function down(): void
    {
        //
    }
};