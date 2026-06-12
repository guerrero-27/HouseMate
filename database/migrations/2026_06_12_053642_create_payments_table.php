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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reservation_id')->constrained()->cascadeOnDelete();
            $table->string('billing_month');
            $table->decimal('amount', 8, 2);
            $table->string('payment_type')->default('rent');
            $table->enum('status', ['unpaid', 'pending_verifacation', 'paid', 'overdue'])->default('unpaid');
            $table->string('receipt_path')->nullable();
            $table->date('due_date');
            $table->date('paid_date')->nullable();
            $table->text('admin_note')->nullable();
            $table->timestamp('receipt_uploaded_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
