<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('phone')->nullable()->unique()->change();
        });
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('name')->unique()->change();
            $table->string('phone')->nullable()->unique()->change();
        });
        Schema::table('promotions', function (Blueprint $table) {
            $table->string('name')->unique()->change();
        });
        Schema::table('transactions', function (Blueprint $table) {
            $table->index('created_at');
        });
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->index('created_at');
            $table->index('type');
        });
        Schema::table('promotions', function (Blueprint $table) {
            $table->index(['start_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropUnique(['phone']);
        });
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropUnique(['name']);
            $table->dropUnique(['phone']);
        });
        Schema::table('promotions', function (Blueprint $table) {
            $table->dropUnique(['name']);
            $table->dropIndex(['start_date', 'end_date']);
        });
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
            $table->dropIndex(['type']);
        });
    }
};
