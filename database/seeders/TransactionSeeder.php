<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\BankAccount;
use App\Models\CashExpense;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $supplier = Contact::suppliers()->firstOrFail();
        $customer = Contact::customers()->firstOrFail();
        $product = Product::query()->firstOrFail();
        $salesUser = User::query()->where('role', 'sales')->firstOrFail();

        $salesUser->update(['sales_target' => 1000000, 'commission_type' => 'percentage_of_sale', 'commission_value' => 2.5]);

        $purchase = Purchase::query()->firstOrCreate(
            ['number' => 'PO-DEMO-001'],
            ['supplier_id' => $supplier->id, 'date' => now(), 'status' => 'open', 'shipping_cost' => 100000, 'total' => 5000000],
        );
        $purchase->items()->firstOrCreate(['product_id' => $product->id], ['quantity' => 10, 'price' => 500000, 'subtotal' => 5000000]);

        $sale = Sale::query()->firstOrCreate(
            ['number' => 'SO-DEMO-001'],
            ['document_type' => 'order', 'customer_id' => $customer->id, 'sales_user_id' => $salesUser->id, 'date' => now(), 'status' => 'open', 'total' => 1300000],
        );
        $sale->items()->firstOrCreate(['product_id' => $product->id], ['quantity' => 2, 'price' => 650000, 'discount' => 0, 'subtotal' => 1300000]);

        $account = BankAccount::query()->firstOrFail();
        CashExpense::query()->firstOrCreate(
            ['description' => 'Biaya operasional gudang'],
            ['bank_account_id' => $account->id, 'date' => now(), 'category' => 'Operasional', 'amount' => 250000],
        );

        Asset::query()->firstOrCreate(
            ['code' => 'AST-DEMO-001'],
            ['name' => 'Kendaraan Operasional', 'acquired_at' => now()->subYear(), 'acquisition_cost' => 180000000, 'residual_value' => 30000000, 'useful_life_months' => 60],
        );
    }
}
