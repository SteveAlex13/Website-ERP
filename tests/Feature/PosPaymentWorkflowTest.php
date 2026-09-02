<?php

namespace Tests\Feature;

use App\Models\BankAccount;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class PosPaymentWorkflowTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_open_bill_does_not_reduce_stock_until_paid_and_payment_enters_selected_bank(): void
    {
        $cashier = User::factory()->create(['role' => 'cashier']);
        $customer = Contact::factory()->create(['type' => 'customer']);
        $product = Product::factory()->create(['selling_price' => 30000, 'stock' => 5]);
        $bankAccount = BankAccount::factory()->create(['name' => 'Bank Test', 'opening_balance' => 0]);

        $this->actingAs($cashier)->post(route('sales.store'), [
            'number' => 'BILL-TEST-001',
            'bill_name' => 'Meja 7',
            'document_type' => 'pos',
            'submit_action' => 'bill',
            'customer_id' => $customer->id,
            'sales_user_id' => $cashier->id,
            'date' => now()->toDateString(),
            'discount_type' => 'fixed',
            'discount_value' => 0,
            'items' => [['product_id' => $product->id, 'quantity' => 2, 'price' => 1, 'discount' => 0]],
        ])->assertRedirect(route('sales.create'));

        $sale = Sale::query()->where('number', 'BILL-TEST-001')->firstOrFail();
        $this->assertSame('open', $sale->status);
        $this->assertSame('5.00', $product->refresh()->stock);
        $this->assertDatabaseCount('bank_transactions', 0);

        $this->actingAs($cashier)->post(route('sales.payment', $sale), [
            'payment_method' => 'bank_transfer',
            'bank_account_id' => $bankAccount->id,
            'paid_amount' => 60000,
        ])->assertRedirect(route('sales.show', $sale));

        $this->assertSame('completed', $sale->refresh()->status);
        $this->assertSame('3.00', $product->refresh()->stock);
        $this->assertDatabaseHas('bank_transactions', [
            'sale_id' => $sale->id,
            'bank_account_id' => $bankAccount->id,
            'type' => 'credit',
            'payment_method' => 'bank_transfer',
            'amount' => 60000,
        ]);

        $finance = User::factory()->create(['role' => 'finance']);
        $this->actingAs($finance)
            ->get(route('cash-expenses.index', ['period' => 'day', 'date' => now()->toDateString()]))
            ->assertSee('Bank Test')
            ->assertSee('60.000')
            ->assertSee('Transfer Bank');
    }

    public function test_refund_returns_fifo_stock_and_records_money_out(): void
    {
        $cashier = User::factory()->create(['role' => 'cashier']);
        $customer = Contact::factory()->create(['type' => 'customer']);
        $product = Product::factory()->create(['selling_price' => 25000, 'stock' => 4]);
        $bankAccount = BankAccount::factory()->create(['opening_balance' => 0]);

        $this->actingAs($cashier)->post(route('sales.store'), [
            'number' => 'POS-REFUND-001',
            'document_type' => 'pos',
            'submit_action' => 'transaction',
            'customer_id' => $customer->id,
            'sales_user_id' => $cashier->id,
            'date' => now()->toDateString(),
            'discount_type' => 'fixed',
            'discount_value' => 0,
            'payment_method' => 'qris',
            'items' => [['product_id' => $product->id, 'quantity' => 2, 'price' => 1, 'discount' => 0]],
        ])->assertRedirect();

        $sale = Sale::query()->where('number', 'POS-REFUND-001')->firstOrFail();
        $this->assertSame('2.00', $product->refresh()->stock);

        $this->actingAs($cashier)
            ->post(route('sales.refund', $sale), ['reason' => 'Pesanan dibatalkan pelanggan'])
            ->assertRedirect(route('sales.show', $sale));

        $this->assertSame('refunded', $sale->refresh()->status);
        $this->assertSame('4.00', $product->refresh()->stock);
        $this->assertDatabaseHas('bank_transactions', [
            'sale_id' => $sale->id,
            'type' => 'debit',
            'payment_method' => 'qris',
            'amount' => 50000,
        ]);
    }
}
