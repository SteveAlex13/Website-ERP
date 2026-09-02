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
        Schema::table('sales', function (Blueprint $table) {
            $table->string('bill_name')->nullable()->after('number');
            $table->string('payment_method')->nullable()->index()->after('paid_amount');
            $table->foreignId('bank_account_id')->nullable()->after('payment_method')->constrained()->nullOnDelete();
        });

        Schema::create('bank_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bank_account_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sale_id')->nullable()->constrained()->nullOnDelete();
            $table->dateTime('transaction_at')->index();
            $table->string('type')->index();
            $table->string('payment_method')->index();
            $table->string('description');
            $table->decimal('amount', 15, 2);
            $table->timestamps();
            $table->index(['bank_account_id', 'transaction_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_transactions');
        Schema::table('sales', function (Blueprint $table) {
            $table->dropConstrainedForeignId('bank_account_id');
            $table->dropColumn(['bill_name', 'payment_method']);
        });
    }
};
