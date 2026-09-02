<?php

namespace Database\Seeders;

use App\Models\BankAccount;
use App\Models\Contact;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach ([
            ['name' => 'Super Administrator', 'email' => 'superadmin@erp.test', 'role' => 'superadmin'],
            ['name' => 'Administrator', 'email' => 'admin@erp.test', 'role' => 'admin'],
            ['name' => 'Kasir', 'email' => 'kasir@erp.test', 'role' => 'cashier'],
            ['name' => 'Purchasing', 'email' => 'purchasing@erp.test', 'role' => 'purchasing'],
            ['name' => 'Sales', 'email' => 'sales@erp.test', 'role' => 'sales'],
            ['name' => 'Finance', 'email' => 'finance@erp.test', 'role' => 'finance'],
            ['name' => 'Warehouse', 'email' => 'warehouse@erp.test', 'role' => 'warehouse'],
        ] as $account) {
            User::query()->updateOrCreate(
                ['email' => $account['email']],
                [...$account, 'password' => 'password', 'email_verified_at' => now()],
            );
        }

        foreach (['Kas Kecil', 'Bank BCA', 'Bank Mandiri', 'Bank BRI'] as $index => $name) {
            BankAccount::query()->firstOrCreate(
                ['name' => $name],
                ['account_number' => $index === 0 ? null : '1000000'.$index, 'opening_balance' => 100000000],
            );
        }

        Contact::query()->firstOrCreate(['type' => 'supplier', 'name' => 'PT Sumber Niaga']);
        Contact::query()->firstOrCreate(['type' => 'customer', 'name' => 'Toko Sejahtera']);

        foreach (range(1, 10) as $number) {
            Product::query()->firstOrCreate(
                ['sku' => 'PRD-'.str_pad((string) $number, 3, '0', STR_PAD_LEFT)],
                ['name' => 'Produk '.$number, 'unit' => 'pcs', 'purchase_price' => 50000 * $number, 'selling_price' => 65000 * $number, 'stock' => 20, 'minimum_stock' => 5],
            );
        }

        $this->call(TransactionSeeder::class);
    }
}
