<x-app-layout>
<div x-data="posKasir()" class="min-h-screen bg-[#0a0f1d] text-slate-100 p-3 lg:p-5">

    <!-- Top Navigation Tabs -->
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3 border-b border-[#1e293b] pb-3">
        <nav class="flex items-center gap-2 text-sm font-semibold">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-1.5 rounded-lg px-3 py-2 text-slate-400 transition hover:bg-[#1e293b] hover:text-white">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Home
            </a>
            <a href="{{ route('sales.create', $isOnline ? [] : ['mode' => 'online']) }}" class="flex items-center gap-1.5 rounded-lg bg-[#1e293b] px-4 py-2 text-indigo-400 shadow-inner border border-indigo-500/30">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h11m0 0l-3-3m3 3l-3 3M17 17H6m0 0l3 3m-3-3l3-3"/></svg>
                {{ $isOnline ? 'Ganti ke POS Offline' : 'Ganti ke POS Online' }}
            </a>
            <a href="{{ route('sales.index') }}" class="flex items-center gap-1.5 rounded-lg px-3 py-2 text-slate-400 transition hover:bg-[#1e293b] hover:text-white">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                Order
            </a>
            <a href="{{ route('reports.daily-financial') }}" class="flex items-center gap-1.5 rounded-lg px-3 py-2 text-slate-400 transition hover:bg-[#1e293b] hover:text-white">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Laporan
            </a>
        </nav>

        <!-- Open Bills Bar -->
        @if($openBills->isNotEmpty())
            <div class="flex items-center gap-2 overflow-x-auto text-xs">
                <span class="font-bold text-amber-400 shrink-0">Open Bill:</span>
                @foreach($openBills as $bill)
                    <a href="{{ route('sales.show', $bill) }}" class="inline-flex items-center gap-1.5 rounded-md border border-amber-600/40 bg-amber-950/40 px-2.5 py-1 text-amber-200 hover:bg-amber-900/60">
                        <span>{{ $bill->bill_name ?: $bill->number }}</span>
                        <span class="font-semibold text-amber-400">Rp {{ number_format((float)$bill->total, 0, ',', '.') }}</span>
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    <div class="mb-4 flex items-center justify-between rounded-xl border px-4 py-3 {{ $isOnline ? 'border-blue-500/40 bg-blue-950/50' : 'border-emerald-500/40 bg-emerald-950/40' }}">
        <div>
            <b class="block text-sm {{ $isOnline ? 'text-blue-300' : 'text-emerald-300' }}">{{ $isOnline ? 'POS Online — Quotation / PO' : 'POS Offline — Kasir Biasa' }}</b>
            <span class="text-xs text-slate-400">{{ $isOnline ? 'Transaksi masuk ke tab Quotation / PO dan tidak langsung memotong stok.' : 'Transaksi masuk ke tab Penjualan Offline dan pembayaran langsung tercatat.' }}</span>
        </div>
    </div>

    @if($errors->any())
        <div class="mb-4 rounded-xl border border-red-500/40 bg-red-950/60 p-3 text-sm text-red-200">
            {{ $errors->first() }}
        </div>
    @endif
    @if(session('success'))
        <div class="mb-4 rounded-xl border border-emerald-500/40 bg-emerald-950/60 p-3 text-sm text-emerald-200">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('sales.store') }}" id="posForm" class="grid gap-4 lg:grid-cols-[1fr_1.15fr] xl:grid-cols-[480px_1fr]">
        @csrf
        <input type="hidden" name="number" value="{{ $isOnline ? 'WEB' : 'POS' }}-{{ now()->format('YmdHis') }}">
        <input type="hidden" name="date" value="{{ now()->toDateString() }}">
        @unless($isOnline)<input type="hidden" name="document_type" value="pos">@endunless
        <input type="hidden" name="sales_user_id" value="{{ auth()->id() }}">
        <input type="hidden" name="discount_type" value="fixed">
        <input type="hidden" name="discount_value" value="0">

        <!-- LEFT PANEL: CART & CHECKOUT -->
        <section class="flex flex-col justify-between rounded-xl border border-[#1e293b] bg-[#0f172a] shadow-xl overflow-hidden min-h-[680px]">
            
            <!-- Table Header -->
            <div class="grid grid-cols-[1.5fr_1fr_1fr_30px] border-b border-[#1e293b] bg-[#0d1527] px-4 py-2.5 text-xs font-bold uppercase tracking-wider text-slate-400">
                <span>Product</span>
                <span class="text-center">Quantity</span>
                <span class="text-right">Price</span>
                <span></span>
            </div>

            <!-- Cart Items List -->
            <div class="flex-1 overflow-y-auto divide-y divide-[#1e293b]/60">
                <template x-if="cart.length === 0">
                    <div class="flex h-56 flex-col items-center justify-center text-slate-500">
                        <p class="text-sm font-medium">No products added</p>
                    </div>
                </template>

                <template x-for="(item, index) in cart" :key="item.id">
                    <div class="grid grid-cols-[1.5fr_1fr_1fr_30px] items-center gap-2 px-4 py-3 hover:bg-[#131f38]/40 transition">
                        <div class="min-w-0 pr-2">
                            <b class="block truncate text-xs font-semibold text-white" x-text="item.name"></b>
                            <span class="text-[11px] text-indigo-400" x-text="item.sku"></span>
                            <input type="hidden" :name="itemName(index, 0)" :value="item.id">
                            <input type="hidden" :name="itemName(index, 1)" :value="item.price">
                            <input type="hidden" :name="itemName(index, 2)" value="0">
                        </div>
                        <div class="flex items-center justify-center gap-1">
                            <button type="button" @click="decrease(item)" class="grid h-6 w-6 place-items-center rounded bg-[#1e293b] text-xs font-bold text-slate-200 hover:bg-slate-700">−</button>
                            <input x-model.number="item.quantity" :name="itemName(index, 3)" type="number" min="1" :max="item.stock" class="h-6 w-9 rounded border border-[#1e293b] bg-[#0a0f1d] p-0 text-center text-xs font-bold text-white focus:outline-none">
                            <button type="button" @click="increase(item)" class="grid h-6 w-6 place-items-center rounded bg-indigo-600 text-xs font-bold text-white hover:bg-indigo-500">+</button>
                        </div>
                        <div class="text-right text-xs font-semibold text-slate-100" x-text="rupiah(item.price * item.quantity)"></div>
                        <button type="button" @click="remove(item.id)" class="text-sm text-red-400 hover:text-red-300">×</button>
                    </div>
                </template>
            </div>

            <!-- Customer & Tax Settings Bar -->
            <div class="border-t border-[#1e293b] bg-[#0d1527] px-4 py-3 space-y-2">
                @if($isOnline)
                    <label class="block text-[11px] text-slate-400">Dokumen Online
                        <select name="document_type" class="mt-1 w-full rounded-md border border-[#1e293b] bg-[#0a0f1d] px-2.5 py-1.5 text-xs text-white focus:border-indigo-500">
                            <option value="quotation">Quotation</option>
                            <option value="order">Purchase Order (PO)</option>
                        </select>
                    </label>
                @endif
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div>
                        <label class="mb-1 block text-[11px] text-slate-400">Customer {{ $isOnline ? '(wajib)' : '(opsional)' }}</label>
                        <select name="customer_id" x-model="selectedCustomer" class="w-full rounded-md border border-[#1e293b] bg-[#0a0f1d] px-2.5 py-1.5 text-xs text-white focus:border-indigo-500">
                            <option value="">{{ $isOnline ? '-- Pilih Customer --' : 'Pelanggan Umum' }}</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @unless($isOnline)<div>
                        <label class="block text-[11px] text-slate-400 mb-1">Nama Meja / Bill</label>
                        <input name="bill_name" type="text" placeholder="Contoh: Meja 01" class="w-full rounded-md border border-[#1e293b] bg-[#0a0f1d] px-2.5 py-1.5 text-xs text-white focus:border-indigo-500">
                    </div>@endunless
                </div>

                <!-- Payment Details (Metode & Uang Diterima) -->
                @unless($isOnline)<div class="grid grid-cols-2 gap-2 text-xs pt-1 border-t border-[#1e293b]/50">
                    <div>
                        <label class="mb-1 block text-[11px] text-slate-400">Metode Bayar</label>
                        <select x-model="selectedBankAccount" name="bank_account_id" @change="syncPaymentMethod()" class="w-full rounded-md border border-[#1e293b] bg-[#0a0f1d] px-2.5 py-1.5 text-xs text-white">
                            @foreach($bankAccounts as $account)
                                <option value="{{ $account->id }}">{{ $account->name === 'Tunai' ? 'Tunai' : str_replace('Bank ', '', $account->name) }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="payment_method" :value="paymentMethod">
                    </div>
                    <div x-show="paymentMethod === 'cash'">
                        <label class="block text-[11px] text-slate-400 mb-1">Uang Diterima</label>
                        <input x-model.number="paid" type="number" min="0" placeholder="0" class="w-full rounded-md border border-[#1e293b] bg-[#0a0f1d] px-2.5 py-1.5 text-xs text-white font-semibold">
                    </div>
                    <div x-show="paymentMethod !== 'cash'" class="flex items-center text-[11px] text-indigo-300 pt-3">
                        Auto-nominal sesuai total
                    </div>
                    <input type="hidden" name="paid_amount" :value="effectivePaid">
                </div>@endunless
            </div>

            <!-- Two-Column Order Summary Box -->
            <div class="border-t border-[#1e293b] bg-[#0a0f1d] p-4">
                <div class="grid grid-cols-2 gap-4 text-xs">
                    <!-- Left Summary Column -->
                    <div class="space-y-1.5 border-r border-[#1e293b] pr-4">
                        <div class="flex justify-between text-slate-400">
                            <span>Customer:</span>
                            <span class="font-medium text-slate-200" x-text="customerName"></span>
                        </div>
                        <div class="flex justify-between text-slate-400">
                            <span>Tax(10%):</span>
                            <span class="font-medium text-slate-200" x-text="rupiah(taxAmount)"></span>
                        </div>
                        <div class="flex justify-between text-slate-400">
                            <span>Service:</span>
                            <span class="font-medium text-slate-200">Rp.0</span>
                        </div>
                    </div>

                    <!-- Right Summary Column -->
                    <div class="space-y-1.5">
                        <div class="flex justify-between text-slate-400">
                            <span>Sub Total</span>
                            <span class="font-medium text-slate-200" x-text="rupiah(subtotal)"></span>
                        </div>
                        <div class="flex justify-between rounded bg-[#132247] px-2 py-1.5 text-sm font-bold text-white">
                            <span>Total</span>
                            <span x-text="rupiah(grandTotal)"></span>
                        </div>
                    </div>
                </div>

                <!-- Kembalian Bar for Cash Payment -->
                <template x-if="paymentMethod === 'cash' && paid > 0">
                    <div class="mt-2 flex justify-between border-t border-[#1e293b]/60 pt-2 text-xs font-semibold">
                        <span class="text-slate-400">Kembalian:</span>
                        <span :class="change >= 0 ? 'text-emerald-400' : 'text-red-400'" x-text="rupiah(Math.max(0, change))"></span>
                    </div>
                </template>
            </div>

            <!-- Action Buttons -->
            @if($isOnline)
                <div class="border-t border-[#1e293b] bg-[#0d1527] p-3">
                    <button type="submit" :disabled="cart.length === 0 || ! selectedCustomer" class="flex w-full items-center justify-center gap-1.5 rounded-lg bg-indigo-600 py-3 text-xs font-extrabold uppercase tracking-wider text-white shadow transition hover:bg-indigo-500 disabled:cursor-not-allowed disabled:opacity-40">
                        Simpan Quotation / PO
                    </button>
                </div>
            @else
            <div class="grid grid-cols-2 gap-2 p-3 bg-[#0d1527] border-t border-[#1e293b]">
                <button type="submit" name="submit_action" value="transaction" :disabled="cart.length === 0 || (paymentMethod === 'cash' && paid < grandTotal)" class="flex items-center justify-center gap-1.5 rounded-lg bg-[#10b981] py-3 text-xs font-extrabold uppercase tracking-wider text-white shadow transition hover:bg-[#059669] disabled:cursor-not-allowed disabled:opacity-40">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Order
                </button>
                <button type="submit" name="submit_action" value="bill" :disabled="cart.length === 0" class="flex items-center justify-center gap-1.5 rounded-lg bg-[#f59e0b] py-3 text-xs font-extrabold uppercase tracking-wider text-slate-950 shadow transition hover:bg-[#d97706] disabled:cursor-not-allowed disabled:opacity-40">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                    Simpan Bill
                </button>
            </div>
            @endif
        </section>

        <!-- RIGHT PANEL: CATALOG & SEARCH -->
        <section class="flex flex-col rounded-xl border border-[#1e293b] bg-[#0f172a] shadow-xl overflow-hidden min-h-[680px]">
            
            <!-- Search Header Bar -->
            <div class="border-b border-[#1e293b] bg-[#111c38] p-3">
                <div class="flex items-center gap-2">
                    <input x-model="search" type="text" placeholder="Cari nama produk atau SKU..." class="flex-1 rounded-lg border border-[#1e293b] bg-[#0a0f1d] px-4 py-2 text-xs text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-none">
                    <button type="button" class="flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-white shadow hover:bg-indigo-500">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </button>
                </div>
            </div>

            <!-- Breadcrumb Category Filter -->
            <div class="flex items-center justify-between border-b border-[#1e293b] bg-[#0d1527] px-4 py-2.5 text-xs">
                <div class="flex items-center gap-2 font-medium text-slate-400">
                    <span>Home /</span>
                    <button type="button" @click="selectedCategory = 'all'" :class="selectedCategory === 'all' ? 'text-indigo-400 font-bold' : 'hover:text-white'" class="transition">All</button>
                </div>
                <span class="text-[11px] text-slate-500" x-text="filteredProducts.length + ' item tersedia'"></span>
            </div>

            <!-- Products Monogram Grid (Matching screenshot with square cards) -->
            <div class="flex-1 overflow-y-auto p-4">
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 xl:grid-cols-3">
                    <template x-for="product in filteredProducts" :key="product.id">
                        <button type="button" @click="add(product)" class="group flex flex-col items-center justify-between rounded-xl border border-[#1e293b] bg-[#131d35] p-3 transition hover:-translate-y-1 hover:border-indigo-500 hover:shadow-lg focus:outline-none">
                            
                            <!-- Large Monogram Square (Light Gray card matching mockup) -->
                            <div class="flex aspect-square w-full items-center justify-center rounded-lg bg-[#e2e8f0] text-3xl font-black tracking-wider text-slate-800 shadow transition group-hover:bg-white">
                                <span x-text="monogram(product.name)"></span>
                            </div>

                            <!-- Product Title & Price -->
                            <div class="mt-2.5 w-full text-center">
                                <b class="block truncate text-xs font-semibold text-slate-100 group-hover:text-indigo-300" x-text="product.name"></b>
                                <div class="mt-1 flex items-center justify-between text-[11px]">
                                    <span class="text-slate-400" x-text="'Stok: ' + Number(product.stock)"></span>
                                    <span class="font-bold text-emerald-400" x-text="rupiah(product.selling_price)"></span>
                                </div>
                            </div>
                        </button>
                    </template>
                </div>

                <template x-if="filteredProducts.length === 0">
                    <div class="flex h-64 flex-col items-center justify-center text-slate-500">
                        <p class="text-sm">Produk tidak ditemukan</p>
                    </div>
                </template>
            </div>
        </section>
    </form>
</div>

<script>
function posKasir() {
    var products = {{ Js::from($products) }};
    var customers = {{ Js::from($customers) }};
    var bankAccounts = {{ Js::from($bankAccounts) }};
    var defaultBankAccount = bankAccounts.find(account => ['Tunai', 'Cash'].includes(account.name) || account.name.startsWith('Kas')) || bankAccounts[0];

    return {
        products: products,
        customers: customers,
        bankAccounts: bankAccounts,
        cart: [],
        search: '',
        paid: 0,
        selectedBankAccount: defaultBankAccount ? String(defaultBankAccount.id) : '',
        paymentMethod: defaultBankAccount && (['Tunai', 'Cash'].includes(defaultBankAccount.name) || defaultBankAccount.name.startsWith('Kas')) ? 'cash' : 'bank_transfer',
        selectedCustomer: '',
        selectedCategory: 'all',

        get customerName() {
            if (!this.selectedCustomer) return 'Pelanggan Umum';
            var cust = this.customers.find(c => String(c.id) === String(this.selectedCustomer));
            return cust ? cust.name : 'Pelanggan Umum';
        },

        syncPaymentMethod() {
            var account = this.bankAccounts.find(item => String(item.id) === String(this.selectedBankAccount));
            this.paymentMethod = account && (['Tunai', 'Cash'].includes(account.name) || account.name.startsWith('Kas')) ? 'cash' : 'bank_transfer';
        },

        get filteredProducts() {
            var keyword = this.search.toLowerCase();
            return this.products.filter(function(p) {
                return p.name.toLowerCase().includes(keyword) || (p.sku && p.sku.toLowerCase().includes(keyword));
            });
        },

        get subtotal() {
            return this.cart.reduce((total, item) => total + (Number(item.price) * Number(item.quantity)), 0);
        },

        get taxAmount() {
            // Optional tax if needed, currently 0 for standard retail or toggleable
            return 0;
        },

        get grandTotal() {
            return Math.max(0, this.subtotal + this.taxAmount);
        },

        get effectivePaid() {
            return this.paymentMethod === 'cash' ? (Number(this.paid) || 0) : this.grandTotal;
        },

        get change() {
            return this.effectivePaid - this.grandTotal;
        },

        add(product) {
            var item = this.cart.find(c => c.id === product.id);
            if (item) {
                this.increase(item);
                return;
            }
            this.cart.push(Object.assign({}, product, {
                price: Number(product.selling_price),
                stock: Number(product.stock),
                quantity: 1
            }));
        },

        increase(item) {
            if (item.quantity < item.stock) {
                item.quantity++;
            }
        },

        decrease(item) {
            if (item.quantity > 1) {
                item.quantity--;
            }
        },

        remove(id) {
            this.cart = this.cart.filter(item => item.id !== id);
        },

        clearCart() {
            this.cart = [];
            this.paid = 0;
        },

        monogram(name) {
            if (!name) return 'PR';
            var words = name.trim().split(/\s+/);
            if (words.length >= 2) {
                return (words[0].charAt(0) + words[1].charAt(0)).toUpperCase();
            }
            return name.substring(0, 2).toUpperCase();
        },

        itemName(index, field) {
            var fields = ['product_id', 'price', 'discount', 'quantity'];
            return 'items[' + index + '][' + fields[field] + ']';
        },

        money(value) {
            return new Intl.NumberFormat('id-ID').format(Number(value) || 0);
        },

        rupiah(value) {
            return 'Rp.' + this.money(value);
        }
    };
}
</script>
</x-app-layout>
