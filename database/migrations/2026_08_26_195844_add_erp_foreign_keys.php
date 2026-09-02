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
        Schema::table('cash_expenses', fn (Blueprint $table) => $table->foreign('bank_account_id')->references('id')->on('bank_accounts')->cascadeOnDelete());
        Schema::table('delivery_orders', fn (Blueprint $table) => $table->foreign('sale_id')->references('id')->on('sales')->cascadeOnDelete());
        Schema::table('delivery_order_items', function (Blueprint $table) {
            $table->foreign('delivery_order_id')->references('id')->on('delivery_orders')->cascadeOnDelete();
            $table->foreign('sale_item_id')->references('id')->on('sale_items')->restrictOnDelete();
        });
        Schema::table('sale_items', function (Blueprint $table) {
            $table->foreign('sale_id')->references('id')->on('sales')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('products')->restrictOnDelete();
        });
        Schema::table('sales', function (Blueprint $table) {
            $table->foreign('customer_id')->references('id')->on('contacts')->restrictOnDelete();
            $table->foreign('sales_user_id')->references('id')->on('users')->restrictOnDelete();
        });
        Schema::table('purchase_items', function (Blueprint $table) {
            $table->foreign('purchase_id')->references('id')->on('purchases')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('products')->restrictOnDelete();
        });
        Schema::table('purchases', fn (Blueprint $table) => $table->foreign('supplier_id')->references('id')->on('contacts')->restrictOnDelete());
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cash_expenses', fn (Blueprint $table) => $table->dropForeign(['bank_account_id']));
        Schema::table('delivery_order_items', function (Blueprint $table) {
            $table->dropForeign(['delivery_order_id']);
            $table->dropForeign(['sale_item_id']);
        });
        Schema::table('delivery_orders', fn (Blueprint $table) => $table->dropForeign(['sale_id']));
        Schema::table('sale_items', function (Blueprint $table) {
            $table->dropForeign(['sale_id']);
            $table->dropForeign(['product_id']);
        });
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropForeign(['sales_user_id']);
        });
        Schema::table('purchase_items', function (Blueprint $table) {
            $table->dropForeign(['purchase_id']);
            $table->dropForeign(['product_id']);
        });
        Schema::table('purchases', fn (Blueprint $table) => $table->dropForeign(['supplier_id']));
    }
};
