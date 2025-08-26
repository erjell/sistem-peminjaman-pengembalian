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
        Schema::table('items', function (Blueprint $table) {
            $table->string('code')->nullable()->unique()->after('id');
            $table->string('serial_number')->nullable()->after('name');
            $table->integer('procurement_year')->nullable()->after('serial_number');
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete()->after('procurement_year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn(['code', 'serial_number', 'procurement_year', 'category_id']);
        });
    }
};
