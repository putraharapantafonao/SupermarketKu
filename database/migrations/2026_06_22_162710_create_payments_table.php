<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->cascadeOnDelete();
            $table->enum('method', ['cash', 'qris', 'transfer', 'ewallet', 'debit']);
            $table->integer('paid_amount');
            $table->integer('change_amount')->default(0);

            // TAMBAHAN BARIS BARU: Menampung detail trace number pembayaran Non-Tunai Debit
            $table->string('card_bank')->nullable();
            $table->string('trace_number')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
