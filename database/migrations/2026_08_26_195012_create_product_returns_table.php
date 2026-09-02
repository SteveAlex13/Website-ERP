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
        Schema::create('product_returns', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->string('type')->index();
            $table->string('reference_type');
            $table->unsignedBigInteger('reference_id');
            $table->date('date')->index();
            $table->string('status')->default('draft')->index();
            $table->decimal('total', 15, 2)->default(0);
            $table->timestamps();
            $table->index(['reference_type', 'reference_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_returns');
    }
};
