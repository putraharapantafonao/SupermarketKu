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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel categories (Jika kategori dihapus, produk ikut terhapus)
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();

            // Relasi ke tabel suppliers (Jika supplier dihapus, kolom supplier_id jadi NULL, produk aman)
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();

            $table->string('barcode')->unique(); // Tambah unique() agar barcode tidak ganda
            $table->string('name');
            $table->integer('purchase_price');
            $table->integer('selling_price');
            $table->integer('stock')->default(0);
            $table->integer('minimum_stock')->default(5);
            $table->date('expired_date')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
