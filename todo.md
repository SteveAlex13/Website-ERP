# Project Roadmap & Progress — ERP Perusahaan Dagang

Dokumen ini melacak status pengerjaan, arsitektur sistem, dan daftar rencana pengembangan (TODO) untuk aplikasi website **ERP Perusahaan Dagang**.

---

## 📊 Status Fitur & Modul Aplikasi

| Modul / Fitur | Status | Detail Implementasi |
| :--- | :---: | :--- |
| **Autentikasi & Multi-Role** | ✅ Lengkap | Role: `superadmin`, `admin`, `cashier`, `purchasing`, `warehouse`, `sales`, `finance`. Route protection & Policy. |
| **Master Data** | ✅ Lengkap | Produk (SKU, harga beli/jual, stok minimum), Kontak (Customer & Supplier), Akun Bank. |
| **Pembelian (Purchasing)** | ✅ Lengkap | Pembuatan PO -> Penerimaan Barang (Goods Receipt) via `DB::transaction()` -> Pencatatan Ongkir/Expense. |
| **Penjualan (Sales & POS)** | ✅ Lengkap | Quotation -> Sales Order -> POS Kasir (checkout langsung) -> Pengiriman Parsial / Delivery Order. |
| **Pengiriman (Delivery Order)** | ✅ Lengkap | Validasi sisa kuantitas PO, pemotongan stok otomatis, cetak Surat Jalan (Delivery Order). |
| **Retur Produk (Returns)** | ✅ Lengkap | Retur Jual (stok kembali, piutang berkurang) & Retur Beli (stok keluar, hutang berkurang). |
| **Keuangan & Kas/Bank** | ✅ Lengkap | 4 Rekening (Kas Kecil, BCA, Mandiri, BRI), Catatan Pengeluaran Operasional, tracking saldo real-time. |
| **Asset Management** | ✅ Lengkap | Perhitungan depresiasi otomatis metode garis lurus `(Harga Perolehan - Nilai Residu) / Masa Manfaat`. |
| **Laporan & Ringkasan** | ✅ Lengkap | Laporan Penjualan/Pembelian kustom periode, Laporan Stok Menipis, Laporan Kas/Bank, dan Harian. |
| **Halaman Cetak (Print Views)** | ✅ Lengkap | Invoice Penjualan & Surat Jalan Pengiriman dengan layout khusus `@media print`. |
| **API Integration** | ✅ Lengkap | Endpoint REST API terproteksi Laravel Sanctum untuk master produk & ringkasan stok. |
| **Testing Suite** | ✅ Lengkap | Automated Feature Tests (`ErpWorkflowTest`, `PosPaymentWorkflowTest`, dll.). |

---

## 🎯 Rencana Pengembangan & Penyempurnaan Website

### 1. Penyelarasan UI/UX dengan Mockup (`mockup/`)
- [ ] **Dashboard Overview**:
  - [ ] Samakan visual card metrik (Total Penjualan, Pembelian, Saldo Kas, Stok Kritis) dengan template mockup.
  - [ ] Tambahkan shortcut cepat untuk transaksi baru (Quick Action buttons).
- [ ] **Modul Penjualan & POS**:
  - [ ] Perhalus tampilan form kasir (POS) agar interaksi scan/pilih produk dan hitung kembalian lebih responsif (Alpine.js).
  - [ ] Standardisasi badge status transaksi (`Draft`, `Open`, `Partial`, `Completed`, `Cancelled`).
- [ ] **Modul Pembelian & Gudang**:
  - [ ] Rapikan form penerimaan barang (Goods Receipt) agar status item yang sudah diterima vs sisa terlihat jelas.
- [ ] **Tampilan Cetak (Print Layouts)**:
  - [ ] Uji kerapihan cetak Invoice dan Surat Jalan pada format kertas A4 dan thermal/struk.

### 2. Peningkatan Fitur & Pengalaman Pengguna (Feature Enhancements)
- [ ] **Ekspor Data & Laporan**:
  - [ ] Tambahkan opsi download laporan dalam format Excel (`.xlsx`) dan PDF untuk mempermudah operasional finance.
- [ ] **Notifikasi Stok Kritis**:
  - [ ] Indikator visual real-time di navbar / topbar saat ada produk dengan stok di bawah batas minimum.
- [ ] **Integrasi Pembayaran (Payment Gateway Sandbox)**:
  - [ ] Tambahkan opsi pembayaran non-tunai (QRIS / Virtual Account) via webhook sandbox.
- [ ] **Log Aktivitas & Riwayat Transaksi**:
  - [ ] Riwayat pergerakan stok (Stock Mutation Log / Kartu Stok) per produk.

---

## 🛠️ Standar & Alur Pengerjaan

- **Analisis & Rancang (High Effort)**:
  - Sebelum memodifikasi UI atau alur data, teliti kecocokan dengan komponen Blade yang sudah ada dan logika Action di `app/Actions`.
- **Implementasi & Refactor (Medium Effort)**:
  - Gunakan class utility Tailwind CSS yang konsisten dengan tema mockup.
  - Jaga integritas database transaction (`DB::transaction`) dan jalankan pengujian fitur setelah setiap pembaruan.

