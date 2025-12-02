<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Modify to VARCHAR to allow data manipulation
        DB::statement("ALTER TABLE transactions MODIFY COLUMN status VARCHAR(255)");
        
        // 2. Update data: 'borrowed' -> 'approved'
        DB::statement("UPDATE transactions SET status = 'approved' WHERE status = 'borrowed'");
        
        // 3. Modify back to ENUM with new values
        DB::statement("ALTER TABLE transactions MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'returned', 'lost') DEFAULT 'pending'");

        Schema::table('transactions', function (Blueprint $table) {
            // Add approval fields
            $table->timestamp('approved_at')->nullable()->after('status');
            $table->foreignId('approved_by')->nullable()->after('approved_at')->constrained('users')->onDelete('set null');
            $table->text('rejection_reason')->nullable()->after('approved_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Remove approval fields
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['approved_at', 'approved_by', 'rejection_reason']);
            
            // Revert status enum to original values
            $table->enum('status', ['borrowed', 'returned', 'lost'])
                  ->default('borrowed')
                  ->change();
        });
    }
};
