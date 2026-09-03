<?php

namespace Database\Seeders;

use App\Models\BankAccount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RequiredBankAccountsSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $legacyCashAccount = BankAccount::query()->where('name', 'Kas Kecil')->first();

        if ($legacyCashAccount && ! BankAccount::query()->where('name', 'Tunai')->exists()) {
            $legacyCashAccount->update(['name' => 'Tunai']);
        }

        foreach ([
            ['name' => 'Tunai', 'account_number' => null],
            ['name' => 'Bank BCA', 'account_number' => '10000001'],
            ['name' => 'Bank BNI', 'account_number' => '10000004'],
            ['name' => 'Bank Mandiri', 'account_number' => '10000002'],
        ] as $account) {
            BankAccount::query()->firstOrCreate(
                ['name' => $account['name']],
                ['account_number' => $account['account_number'], 'opening_balance' => 100000000],
            );
        }
    }
}
