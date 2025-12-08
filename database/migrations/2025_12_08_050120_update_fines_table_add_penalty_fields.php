<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('fines', function (Blueprint $table) {
            $table->enum('penalty_type', ['late_return', 'damaged_book', 'lost_book'])->nullable()->after('transaction_id');
            $table->integer('points_deducted')->nullable()->after('amount');
            $table->text('description')->nullable()->after('points_deducted');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fines', function (Blueprint $table) {
            $table->dropColumn(['penalty_type', 'points_deducted', 'description']);
        });
    }
};
