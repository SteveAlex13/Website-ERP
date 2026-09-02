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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->string('document_type')->default('order')->index();
            $table->unsignedBigInteger('customer_id')->index();
            $table->unsignedBigInteger('sales_user_id')->index();
            $table->date('date')->index();
            $table->string('status')->default('open')->index();
            $table->string('discount_type')->nullable();
            $table->decimal('discount_value', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
