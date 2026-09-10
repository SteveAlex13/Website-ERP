<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice {{ $sale->number }}</title>
    <link rel="stylesheet" href="{{ asset('css/print-invoice.css') }}">
</head>
<body>
    <main class="invoice">
        <header class="invoice-header">
            <div class="brand">
                <div class="brand-mark">ERP</div>
                <div><strong>Dagang ERP</strong><span>Sistem Operasional Perusahaan</span></div>
            </div>
            <div class="document-title"><h1>INVOICE</h1><span>{{ $sale->number }}</span></div>
        </header>

        <section class="invoice-meta">
            <div>
                <span class="label">Ditagihkan kepada</span>
                <strong>{{ $sale->customer?->name ?? 'Pelanggan Umum' }}</strong>
                @if ($sale->bill_name)<small>{{ $sale->bill_name }}</small>@endif
            </div>
            <div><span class="label">Tanggal transaksi</span><strong>{{ $sale->date->translatedFormat('d F Y') }}</strong></div>
            <div>
                <span class="label">Metode pembayaran</span>
                <strong>{{ $sale->bankAccount?->name ?? ($sale->payment_method === 'cash' ? 'Tunai' : 'Belum ditentukan') }}</strong>
                @if ($sale->bankAccount?->account_number)<small>{{ $sale->bankAccount->account_number }}</small>@endif
            </div>
        </section>

        <table>
            <thead><tr><th>Produk</th><th class="number">Jumlah</th><th class="number">Harga</th><th class="number">Subtotal</th></tr></thead>
            <tbody>
                @foreach ($sale->items as $item)
                    <tr>
                        <td><strong>{{ $item->product?->name ?? 'Produk tidak tersedia' }}</strong>@if ($item->product?->sku)<small>SKU: {{ $item->product->sku }}</small>@endif</td>
                        <td class="number">{{ number_format((float) $item->quantity, 0, ',', '.') }} {{ $item->product?->unit }}</td>
                        <td class="number">Rp {{ number_format((float) $item->price, 0, ',', '.') }}</td>
                        <td class="number"><strong>Rp {{ number_format((float) $item->subtotal, 0, ',', '.') }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <section class="totals">
            <div><span>Total tagihan</span><strong>Rp {{ number_format((float) $sale->total, 0, ',', '.') }}</strong></div>
            <div><span>Sudah dibayar</span><strong>Rp {{ number_format((float) $sale->paid_amount, 0, ',', '.') }}</strong></div>
            <div class="grand-total"><span>Sisa pembayaran</span><strong>Rp {{ number_format($sale->outstandingBalance(), 0, ',', '.') }}</strong></div>
        </section>

        <footer><p>Terima kasih atas transaksi Anda.</p><span>Dicetak pada {{ now()->translatedFormat('d F Y, H:i') }}</span></footer>
    </main>
</body>
</html>
