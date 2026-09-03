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
        Schema::table('expense_purchases', function (Blueprint $table) {
            $table->string('purchase_type')->default('kebutuhan')->index()->after('date');
            $table->string('document_type')->default('invoice')->index()->after('purchase_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expense_purchases', function (Blueprint $table) {
            $table->dropIndex(['purchase_type']);
            $table->dropIndex(['document_type']);
            $table->dropColumn(['purchase_type', 'document_type']);
        });
    }
};
