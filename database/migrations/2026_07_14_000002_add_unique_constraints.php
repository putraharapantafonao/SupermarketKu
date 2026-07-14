<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Remove duplicates first (if any) before adding unique
        DB::statement('DELETE FROM roles WHERE id NOT IN (SELECT MIN(id) FROM roles GROUP BY name)');
        DB::statement('DELETE FROM categories WHERE id NOT IN (SELECT MIN(id) FROM categories GROUP BY name)');

        Schema::table('roles', function (Blueprint $table) {
            $table->string('name')->unique()->change();
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->string('name')->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });
    }
};
