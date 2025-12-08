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
        Schema::create('penalty_settings', function (Blueprint $table) {
            $table->id();
            $table->enum('penalty_type', ['late_return', 'damaged_book', 'lost_book'])->unique();
            $table->enum('value_type', ['points', 'money'])->default('money');
            $table->decimal('value_per_day', 10, 2)->nullable(); // For late returns
            $table->decimal('fixed_value', 10, 2)->nullable(); // For damaged/lost books
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penalty_settings');
    }
};
