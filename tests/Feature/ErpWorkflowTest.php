<?php

namespace Tests\Feature;

use App\Actions\CreateDeliveryOrder;
use App\Actions\ProcessProductReturn;
use App\Actions\ReceiveGoods;
use App\Models\Asset;
use App\Models\BankAccount;
use App\Models\Contact;
use App\Models\ExpensePurchase;
use App\Models\InventoryBatch;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class ErpWorkflowTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guest_is_redirected_from_sales_to_login(): void
    {
        $this->get('/sales')->assertRedirect('/login');
    }

    public function test_cashier_can_complete_point_of_sale_transaction_and_reduce_stock(): void
    {
        $cashier = User::factory()->create(['role' => 'cashier']);
        $customer = Contact::factory()->create(['type' => 'customer']);
        $product = Product::factory()->create(['selling_price' => 25000, 'stock' => 10]);
        $bankAccount = BankAccount::factory()->create();

        $this->actingAs($cashier)
            ->post(route('sales.store'), [
                'number' => 'POS-TEST-001',
                'document_type' => 'pos',
                'customer_id' => $customer->id,
                'sales_user_id' => $cashier->id,
                'date' => now()->toDateString(),
                'discount_type' => 'fixed',
                'discount_value' => 5000,
                'paid_amount' => 50000,
                'submit_action' => 'transaction',
                'payment_method' => 'cash',
                'bank_account_id' => $bankAccount->id,
                'items' => [[
                    'product_id' => $product->id,
                    'quantity' => 2,
                    'price' => 1,
                    'discount' => 0,
                ]],
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('sales', [
            'number' => 'POS-TEST-001',
            'status' => 'completed',
            'total' => 45000,
            'paid_amount' => 45000,
        ]);
        $this->assertSame('8.00', $product->refresh()->stock);
    }

    public function test_point_of_sale_rejects_quantity_above_available_stock(): void
    {
        $cashier = User::factory()->create(['role' => 'cashier']);
        $customer = Contact::factory()->create(['type' => 'customer']);
        $product = Product::factory()->create(['selling_price' => 25000, 'stock' => 1]);

        $this->actingAs($cashier)
            ->from(route('sales.create'))
            ->post(route('sales.store'), [
                'number' => 'POS-TEST-002',
                'document_type' => 'pos',
                'customer_id' => $customer->id,
                'sales_user_id' => $cashier->id,
                'date' => now()->toDateString(),
                'discount_type' => 'fixed',
                'discount_value' => 0,
                'paid_amount' => 50000,
                'items' => [[
                    'product_id' => $product->id,
                    'quantity' => 2,
                    'price' => 25000,
                    'discount' => 0,
                ]],
            ])
            ->assertRedirect(route('sales.create'))
            ->assertSessionHasErrors('items');

        $this->assertDatabaseMissing('sales', ['number' => 'POS-TEST-002']);
        $this->assertSame('1.00', $product->refresh()->stock);
    }

    public function test_point_of_sale_rejects_payment_below_total_without_reducing_stock(): void
    {
        $cashier = User::factory()->create(['role' => 'cashier']);
        $customer = Contact::factory()->create(['type' => 'customer']);
        $product = Product::factory()->create(['selling_price' => 25000, 'stock' => 5]);

        $this->actingAs($cashier)
            ->post(route('sales.store'), [
                'number' => 'POS-TEST-003',
                'document_type' => 'pos',
                'customer_id' => $customer->id,
                'sales_user_id' => $cashier->id,
                'date' => now()->toDateString(),
                'discount_type' => 'fixed',
                'discount_value' => 0,
                'paid_amount' => 10000,
                'items' => [[
                    'product_id' => $product->id,
                    'quantity' => 1,
                    'price' => 25000,
                    'discount' => 0,
                ]],
            ])
            ->assertSessionHasErrors('paid_amount');

        $this->assertDatabaseMissing('sales', ['number' => 'POS-TEST-003']);
        $this->assertSame('5.00', $product->refresh()->stock);
    }

    public function test_cashier_can_view_products_but_cannot_manage_stock_in_or_products(): void
    {
        $cashier = User::factory()->create(['role' => 'cashier']);

        $this->actingAs($cashier)->get(route('products.index'))->assertOk();
        $this->actingAs($cashier)->get(route('products.create'))->assertForbidden();
        $this->actingAs($cashier)->get(route('purchases.index'))->assertForbidden();
    }

    public function test_stock_page_shows_fifo_batch_cost_and_pos_catalog_only_shows_available_products(): void
    {
        $cashier = User::factory()->create(['role' => 'cashier']);
        $supplier = Contact::factory()->create(['type' => 'supplier', 'name' => 'Supplier FIFO']);
        $availableProduct = Product::factory()->create([
            'name' => 'Produk Tersedia',
            'purchase_price' => 15000,
            'selling_price' => 25000,
            'stock' => 4,
        ]);
        Product::factory()->create(['name' => 'Produk Kosong', 'stock' => 0, 'minimum_stock' => -1]);
        InventoryBatch::factory()->create([
            'product_id' => $availableProduct->id,
            'supplier_id' => $supplier->id,
            'remaining_quantity' => 4,
            'unit_cost' => 15000,
        ]);

        $this->actingAs($cashier)
            ->get(route('products.index'))
            ->assertSee('Produk Tersedia')
            ->assertSee('Supplier FIFO')
            ->assertSee('15.000')
            ->assertSee('25.000');

        $this->actingAs($cashier)
            ->get(route('sales.create'))
            ->assertSee('Produk Tersedia')
            ->assertDontSee('Produk Kosong');
    }

    public function test_superadmin_can_access_stock_in_and_asset_management(): void
    {
        $superadmin = User::factory()->create(['role' => 'superadmin']);

        $this->actingAs($superadmin)->get(route('purchases.index'))->assertOk();
        $this->actingAs($superadmin)->get(route('assets.index'))->assertOk();
    }

    public function test_low_stock_product_appears_in_header_notification(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Product::factory()->create([
            'name' => 'Produk Hampir Habis',
            'stock' => 2,
            'minimum_stock' => 5,
        ]);

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Stok Menipis')
            ->assertSee('Produk Hampir Habis');
    }

    public function test_contact_menu_filters_customers_and_suppliers_separately(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Contact::factory()->create(['type' => 'customer', 'name' => 'Customer Khusus']);
        Contact::factory()->create(['type' => 'supplier', 'name' => 'Supplier Khusus']);

        $this->actingAs($admin)->get(route('contacts.index', ['type' => 'customer']))
            ->assertOk()->assertSee('Customer Khusus')->assertDontSee('Supplier Khusus');
        $this->actingAs($admin)->get(route('contacts.index', ['type' => 'supplier']))
            ->assertOk()->assertSee('Supplier Khusus')->assertDontSee('Customer Khusus');
    }

    public function test_sales_role_is_forbidden_from_creating_delivery_order(): void
    {
        $salesUser = User::factory()->create(['role' => 'sales']);
        $customer = Contact::factory()->create(['type' => 'customer']);
        $sale = Sale::create([
            'number' => 'SO-TEST-001',
            'document_type' => 'order',
            'customer_id' => $customer->id,
            'sales_user_id' => $salesUser->id,
            'date' => now(),
            'status' => 'open',
        ]);

        $this->actingAs($salesUser)
            ->post(route('sales.deliveries.store', $sale), [])
            ->assertForbidden();
    }

    public function test_delivery_order_reduces_stock_and_marks_sale_partial(): void
    {
        $product = Product::factory()->create(['stock' => 10]);
        $salesUser = User::factory()->create(['role' => 'sales']);
        $customer = Contact::factory()->create(['type' => 'customer']);
        $sale = Sale::create([
            'number' => 'SO-TEST-002',
            'document_type' => 'order',
            'customer_id' => $customer->id,
            'sales_user_id' => $salesUser->id,
            'date' => now(),
            'status' => 'open',
        ]);
        $item = $sale->items()->create([
            'product_id' => $product->id,
            'quantity' => 5,
            'price' => 100000,
            'subtotal' => 500000,
        ]);

        app(CreateDeliveryOrder::class)->handle($sale, [
            'number' => 'DO-TEST-001',
            'date' => now()->toDateString(),
            'items' => [['sale_item_id' => $item->id, 'quantity' => 3]],
        ]);

        $this->assertSame('7.00', $product->refresh()->stock);
        $this->assertSame('partial', $sale->refresh()->status);
        $this->assertDatabaseHas('delivery_order_items', ['sale_item_id' => $item->id, 'quantity' => 3]);
    }

    public function test_goods_receipt_increases_stock_and_marks_purchase_partial(): void
    {
        $product = Product::factory()->create(['stock' => 0]);
        $supplier = Contact::factory()->create(['type' => 'supplier']);
        $purchase = Purchase::create([
            'number' => 'PO-TEST-001',
            'supplier_id' => $supplier->id,
            'date' => now(),
            'status' => 'open',
        ]);
        $item = $purchase->items()->create([
            'product_id' => $product->id,
            'quantity' => 5,
            'price' => 80000,
            'subtotal' => 400000,
        ]);

        app(ReceiveGoods::class)->handle($purchase, [$item->id => 2]);

        $this->assertSame('2.00', $product->refresh()->stock);
        $this->assertSame('partial', $purchase->refresh()->status);
        $this->assertDatabaseHas('inventory_batches', [
            'purchase_item_id' => $item->id,
            'supplier_id' => $supplier->id,
            'remaining_quantity' => 2,
            'unit_cost' => 80000,
        ]);
    }

    public function test_stock_in_immediately_increases_stock_and_creates_fifo_batch(): void
    {
        $purchasing = User::factory()->create(['role' => 'purchasing']);
        $supplier = Contact::factory()->create(['type' => 'supplier']);
        $product = Product::factory()->create(['stock' => 2, 'purchase_price' => 10000]);

        $this->actingAs($purchasing)->post(route('purchases.store'), [
            'number' => 'STK-TEST-001',
            'supplier_id' => $supplier->id,
            'date' => now()->toDateString(),
            'shipping_cost' => 0,
            'items' => [['product_id' => $product->id, 'quantity' => 5, 'price' => 12000]],
        ])->assertRedirect(route('purchases.index'));

        $this->assertSame('7.00', $product->refresh()->stock);
        $this->assertDatabaseHas('purchases', ['number' => 'STK-TEST-001', 'status' => 'completed']);
        $this->assertDatabaseHas('inventory_batches', ['product_id' => $product->id, 'supplier_id' => $supplier->id, 'remaining_quantity' => 5, 'unit_cost' => 12000]);
    }

    public function test_unpaid_needs_purchase_is_recorded_as_payable(): void
    {
        $finance = User::factory()->create(['role' => 'finance']);
        $supplier = Contact::factory()->create(['type' => 'supplier']);

        $this->actingAs($finance)->post(route('expense-purchases.store'), [
            'number' => 'EXP-TEST-001',
            'supplier_id' => $supplier->id,
            'date' => now()->toDateString(),
            'description' => 'Kebutuhan ATK',
            'total' => 500000,
            'paid_amount' => 100000,
        ])->assertRedirect(route('expense-purchases.index'));

        $purchase = ExpensePurchase::query()->where('number', 'EXP-TEST-001')->firstOrFail();
        $this->assertSame('partial', $purchase->payment_status);
        $this->assertSame(400000.0, $purchase->outstandingBalance());
    }

    public function test_point_of_sale_consumes_different_supplier_costs_in_fifo_order(): void
    {
        $cashier = User::factory()->create(['role' => 'cashier']);
        $customer = Contact::factory()->create(['type' => 'customer']);
        $firstSupplier = Contact::factory()->create(['type' => 'supplier']);
        $secondSupplier = Contact::factory()->create(['type' => 'supplier']);
        $product = Product::factory()->create(['purchase_price' => 10000, 'selling_price' => 30000, 'stock' => 10]);
        $bankAccount = BankAccount::factory()->create();
        $firstBatch = InventoryBatch::factory()->create([
            'product_id' => $product->id,
            'supplier_id' => $firstSupplier->id,
            'received_at' => '2026-01-01',
            'initial_quantity' => 5,
            'remaining_quantity' => 5,
            'unit_cost' => 10000,
        ]);
        $secondBatch = InventoryBatch::factory()->create([
            'product_id' => $product->id,
            'supplier_id' => $secondSupplier->id,
            'received_at' => '2026-01-02',
            'initial_quantity' => 5,
            'remaining_quantity' => 5,
            'unit_cost' => 20000,
        ]);

        $this->actingAs($cashier)->post(route('sales.store'), [
            'number' => 'POS-FIFO-001',
            'document_type' => 'pos',
            'customer_id' => $customer->id,
            'sales_user_id' => $cashier->id,
            'date' => now()->toDateString(),
            'discount_type' => 'fixed',
            'discount_value' => 0,
            'paid_amount' => 210000,
            'submit_action' => 'transaction',
            'payment_method' => 'bank_transfer',
            'bank_account_id' => $bankAccount->id,
            'items' => [['product_id' => $product->id, 'quantity' => 7, 'price' => 1, 'discount' => 0]],
        ])->assertRedirect();

        $sale = Sale::query()->where('number', 'POS-FIFO-001')->firstOrFail();
        $saleItem = $sale->items()->firstOrFail();
        $this->assertSame('90000.00', $saleItem->cost_of_goods_sold);
        $this->assertSame('0.00', $firstBatch->refresh()->remaining_quantity);
        $this->assertSame('3.00', $secondBatch->refresh()->remaining_quantity);
        $this->assertSame('3.00', $product->refresh()->stock);
        $this->assertDatabaseHas('inventory_allocations', ['sale_item_id' => $saleItem->id, 'inventory_batch_id' => $firstBatch->id, 'quantity' => 5, 'total_cost' => 50000]);
        $this->assertDatabaseHas('inventory_allocations', ['sale_item_id' => $saleItem->id, 'inventory_batch_id' => $secondBatch->id, 'quantity' => 2, 'total_cost' => 40000]);
    }

    public function test_sales_return_restores_stock_and_reduces_receivable(): void
    {
        $product = Product::factory()->create(['stock' => 7]);
        $salesUser = User::factory()->create(['role' => 'sales']);
        $customer = Contact::factory()->create(['type' => 'customer']);
        $sale = Sale::create([
            'number' => 'SO-TEST-003',
            'customer_id' => $customer->id,
            'sales_user_id' => $salesUser->id,
            'date' => now(),
            'status' => 'completed',
            'total' => 300000,
        ]);

        app(ProcessProductReturn::class)->handle([
            'number' => 'RT-TEST-001',
            'type' => 'sale',
            'reference_type' => Sale::class,
            'reference_id' => $sale->id,
            'date' => now()->toDateString(),
            'items' => [['product_id' => $product->id, 'quantity' => 1, 'price' => 100000]],
        ]);

        $this->assertSame('8.00', $product->refresh()->stock);
        $this->assertSame('200000.00', $sale->refresh()->total);
    }

    public function test_asset_uses_straight_line_depreciation(): void
    {
        $asset = Asset::factory()->make([
            'acquired_at' => now()->subYear(),
            'acquisition_cost' => 120000000,
            'residual_value' => 0,
            'useful_life_months' => 60,
        ]);

        $this->assertSame(2000000.0, $asset->monthlyDepreciation());
        $this->assertSame(96000000.0, $asset->bookValueAt($asset->acquired_at->copy()->addMonths(12)));
    }
}
