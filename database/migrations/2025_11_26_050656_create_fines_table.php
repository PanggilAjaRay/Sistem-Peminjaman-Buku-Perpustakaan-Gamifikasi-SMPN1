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
        Schema::create('fines', function (Blueprint $table) {
            $table->id();
            // We'll add the foreign key constraint after creating transactions table or use a string if circular dependency issues arise, 
            // but usually migrations run in order. Transactions is created after fines in my list? 
            // Wait, 2025_11_26_050659_create_transactions_table.php is AFTER 2025_11_26_050656_create_fines_table.php.
            // So I cannot reference transactions here if I use constrained().
            // I should reorder them or just use unsignedBigInteger and add constraint later, or just change the order.
            // For now, I'll use unsignedBigInteger and not constrained() immediately, or I'll just assume I can change the timestamp of the file.
            // Actually, fines depend on transactions. So transactions should be created BEFORE fines.
            // I will rename the migration files to reorder them or just remove the constraint for now and add it in a separate migration or just rely on logical order if I can.
            // Let's just use unsignedBigInteger for now.
            $table->unsignedBigInteger('transaction_id');
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['paid', 'unpaid'])->default('unpaid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fines');
    }
};
