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
            $table->id('paymentId'); // primary key
            $table->unsignedBigInteger('userid'); // user who made payment
            $table->string('orderid'); // related order
            $table->string('payment_method')->comment('e.g. cash, card, upi');
            $table->string('transaction_id')->nullable()->unique(); // gateway txn ID
            $table->string('ip', 45)->nullable(); // IP address of payer
            $table->decimal('amount', 10, 2);
            $table->string('currency', 10)->default('INR');
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending')->comment('e.g. pending, completed, failed, refunded');
            $table->json('meta')->nullable(); // store raw gateway response or extra info
            $table->timestamps(); // ✅ correct way
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
