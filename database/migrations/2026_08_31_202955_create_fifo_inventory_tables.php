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
        Schema::create('inventory_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->foreignId('purchase_item_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained('contacts')->restrictOnDelete();
            $table->date('received_at')->index();
            $table->decimal('initial_quantity', 15, 2);
            $table->decimal('remaining_quantity', 15, 2);
            $table->decimal('unit_cost', 15, 2);
            $table->timestamps();
            $table->index(['product_id', 'received_at', 'id']);
            $table->index(['product_id', 'remaining_quantity']);
        });

        Schema::create('inventory_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('inventory_batch_id')->constrained()->restrictOnDelete();
            $table->decimal('quantity', 15, 2);
            $table->decimal('unit_cost', 15, 2);
            $table->decimal('total_cost', 15, 2);
            $table->timestamps();
        });

        Schema::table('sale_items', fn (Blueprint $table) => $table->decimal('cost_of_goods_sold', 15, 2)->default(0));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sale_items', fn (Blueprint $table) => $table->dropColumn('cost_of_goods_sold'));
        Schema::dropIfExists('inventory_allocations');
        Schema::dropIfExists('inventory_batches');
    }
};
