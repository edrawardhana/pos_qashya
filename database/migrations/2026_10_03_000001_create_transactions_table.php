<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30)->unique();
            $table->foreignId('cashier_id')->constrained('users')->restrictOnDelete();
            $table->string('customer_name', 100)->nullable();
            $table->unsignedBigInteger('total');
            $table->unsignedBigInteger('paid');
            $table->unsignedBigInteger('change');
            $table->string('payment_method', 10); // cash, transfer
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};