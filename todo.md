# Rencana dan Status Perubahan ERP Perusahaan Dagang

Dokumen ini menjadi acuan alur fitur yang telah diubah serta pekerjaan lanjutan. Status menggunakan format rencana kerja agar implementasi dan keputusan bisnis mudah ditelusuri.

## Ringkasan Status

| Area | Status | Hasil |
| --- | --- | --- |
| Daftar Pembelian non-produk jadi | Selesai | Pembelian dipisahkan berdasarkan Barang, Kebutuhan, dan Asset. |
| Dokumen pembelian | Selesai | Tersedia Quotation/PO, Delivery Order, Invoice, Retur, dan Piutang. |
| Daftar Penjualan | Selesai | Penjualan online dan POS/offline dipisahkan ke tab berbeda. |
| POS Kasir | Selesai | Customer opsional dan pembayaran terhubung ke akun Kas & Bank. |
| Daftar Produk | Selesai | Menggunakan tabel dan pagination agar halaman tidak terlalu panjang. |
| Sidebar | Selesai | Urutan dan menu disederhanakan sesuai kebutuhan operasional. |

## 1. Alur Daftar Pembelian

### Tujuan

Menjadikan modul lama “Pembelian Kebutuhan” sebagai pusat pembelian di luar produk jadi.

### Status implementasi

- [x] Mengganti nama menu dan halaman menjadi **Daftar Pembelian**.
- [x] Menambahkan pilihan jenis pembelian:
  - Barang
  - Kebutuhan
  - Asset
- [x] Menambahkan jenis dokumen pada setiap pilihan pembelian:
  - Quotation / PO
  - Delivery Order
  - Invoice
  - Retur
  - Piutang
- [x] Menambahkan tab dokumen dengan tampilan serupa Daftar Penjualan.
- [x] Menghapus angka jumlah data dari tombol tab. Jumlah data hanya diketahui setelah user membuka tab.
- [x] Menambahkan filter Barang/Kebutuhan/Asset pada setiap tab.
- [x] Tombol tambah membawa jenis dokumen sesuai tab yang sedang aktif.
- [x] Menambahkan kartu ringkasan dokumen dan nilai pembelian belum lunas.
- [x] Menyesuaikan form tambah/edit pembelian.
- [x] Menambahkan validasi jenis pembelian dan jenis dokumen.
- [x] Menambahkan kolom `purchase_type` dan `document_type` pada tabel `expense_purchases`.
- [x] Menjalankan migrasi penambahan kolom pada database lokal.

### Alur pengguna

1. User membuka **Daftar Pembelian**.
2. User memilih tab dokumen yang dibutuhkan.
3. User dapat memfilter pembelian berdasarkan Barang, Kebutuhan, atau Asset.
4. User memilih **Tambah Pembelian**.
5. Form otomatis menggunakan jenis dokumen dari tab aktif.
6. Pembelian disimpan beserta supplier, nilai transaksi, pembayaran, dan sisa yang belum dibayar.

## 2. Alur Penjualan Online dan Offline

### Keputusan alur

- **Quotation/PO** hanya untuk penjualan online.
- **Penjualan Offline** hanya untuk transaksi yang dibuat dari POS Kasir.
- Transaksi POS tetap masuk ke Daftar Penjualan, tetapi tidak bercampur dengan Quotation/PO.

### Status implementasi

- [x] Menambahkan tab **Penjualan Offline** pada Daftar Penjualan.
- [x] Memfilter tab Quotation/PO agar hanya menampilkan `quotation` dan `order`.
- [x] Memfilter tab Invoice online agar tidak mencampurkan transaksi POS.
- [x] Menampilkan transaksi `pos` pada tab Penjualan Offline.
- [x] Menampilkan nomor transaksi, tanggal, customer, kasir, total, status, Detail, dan Cetak.
- [x] Mempertahankan tab Penjualan Offline saat user berpindah halaman pagination.

### Alur pengguna

1. Transaksi online dibuat sebagai Quotation atau Order.
2. Transaksi online muncul pada tab **Quotation/PO**.
3. Transaksi langsung dibuat melalui **POS Kasir**.
4. Transaksi POS disimpan dengan tipe `pos`.
5. Transaksi POS muncul pada tab **Penjualan Offline**.

## 3. POS Kasir

### Posisi menu

- [x] Memindahkan menu **POS Kasir** tepat di bawah Dashboard agar mudah diakses.

### Customer opsional

- [x] Customer tidak wajib dipilih untuk transaksi POS.
- [x] Pilihan kosong ditampilkan sebagai **Pelanggan Umum**.
- [x] Ringkasan transaksi menampilkan Pelanggan Umum saat customer kosong.
- [x] Customer tetap wajib untuk transaksi penjualan online.
- [x] Mengubah `sales.customer_id` menjadi nullable.
- [x] Menjalankan migrasi perubahan customer pada database lokal.

### Pembayaran dan Kas & Bank

- [x] Mengganti pilihan metode pembayaran POS menjadi akun tujuan:
  - Tunai
  - BCA
  - BNI
  - Mandiri
- [x] Mengganti nama akun **Kas Kecil** menjadi **Tunai**.
- [x] Pembayaran Tunai masuk ke akun Tunai.
- [x] Pembayaran BCA masuk ke akun Bank BCA.
- [x] Pembayaran BNI masuk ke akun Bank BNI.
- [x] Pembayaran Mandiri masuk ke akun Bank Mandiri.
- [x] Pembayaran bank otomatis menggunakan nilai total transaksi.
- [x] Pembayaran Tunai tetap menggunakan input uang diterima dan menghitung kembalian.
- [x] Membuat mutasi kredit pada Kas & Bank sesuai akun yang dipilih.
- [x] Menambahkan seeder akun wajib `RequiredBankAccountsSeeder`.
- [x] Menambahkan akun BNI dan memperbarui nama akun Tunai pada database lokal.

### Alur pembayaran POS

1. Kasir menambahkan produk ke keranjang.
2. Kasir boleh memilih customer atau membiarkannya sebagai Pelanggan Umum.
3. Kasir memilih Tunai, BCA, BNI, atau Mandiri.
4. Sistem menyelesaikan transaksi dan mengurangi stok menggunakan FIFO.
5. Sistem membuat mutasi uang masuk pada akun Kas & Bank yang dipilih.
6. Transaksi tampil pada tab Penjualan Offline.

## 4. Daftar Produk dan Pagination

### Status implementasi

- [x] Mengubah Daftar Produk dari kartu panjang menjadi tabel.
- [x] Menambahkan toolbar jumlah data dan pencarian di bagian atas tabel.
- [x] Menghapus tombol tambah/buat dari toolbar Daftar Produk.
- [x] Menggunakan default 5 produk per halaman.
- [x] Menyediakan pilihan 5, 10, atau 25 produk per halaman.
- [x] Mempertahankan parameter pencarian saat berpindah halaman.
- [x] Mempertahankan tombol Edit sesuai hak akses.
- [x] Mempertahankan detail Batch FIFO yang dapat dibuka dari setiap baris produk.

## 5. Penyederhanaan Sidebar

### Status implementasi

- [x] Memindahkan POS Kasir ke bagian atas setelah Dashboard.
- [x] Menghapus menu **Retur** dari sidebar.
- [x] Menghapus menu **Laporan ERP** dari sidebar.
- [x] Menghapus menu **Pembelian Aset** dari sidebar.
- [x] Menghapus menu **Laporan Bank** dari sidebar.
- [x] Mempertahankan route dan data modul yang dihapus dari sidebar agar data lama tidak ikut terhapus.

## 6. Perubahan Teknis

### Database

- [x] Migration `add_purchase_type_and_document_type_to_expense_purchases_table`.
- [x] Migration `make_customer_id_nullable_on_sales_table`.
- [x] Seeder `RequiredBankAccountsSeeder`.

### Backend

- [x] Memperbarui `ExpensePurchaseController`, model, dan form request.
- [x] Memperbarui `SaleController` untuk pemisahan penjualan online/offline.
- [x] Memperbarui `StoreSaleRequest` untuk customer POS opsional dan pilihan akun pembayaran.
- [x] Memperbarui `CompleteSalePayment` agar memakai akun Kas & Bank yang dipilih user.
- [x] Memperbarui `ProductController` untuk pagination 5/10/25.

### Frontend

- [x] Memperbarui halaman Daftar Pembelian dan form pembelian.
- [x] Memperbarui Daftar Penjualan dengan tab Penjualan Offline.
- [x] Memperbarui POS Kasir untuk customer opsional dan pilihan akun pembayaran.
- [x] Memperbarui Daftar Produk menjadi tabel dengan pagination.
- [x] Memperbarui komponen toolbar agar pilihan jumlah data dapat dikonfigurasi.
- [x] Memperbarui sidebar.

## 7. Pekerjaan Lanjutan

- [ ] Tinjau istilah **Piutang Pembelian**; secara akuntansi sisa tagihan kepada supplier biasanya disebut **Utang Pembelian**.
- [ ] Tentukan apakah akun lama Bank BRI tetap dipakai pada Kas & Bank atau perlu dinonaktifkan.
- [ ] Tentukan apakah tombol tambah produk perlu ditempatkan di lokasi lain setelah dihapus dari toolbar Daftar Produk.
- [ ] Tinjau alur konversi Quotation menjadi PO, PO menjadi Delivery Order, dan Delivery Order menjadi Invoice jika setiap dokumen harus saling memiliki referensi.
- [ ] Jalankan pengujian lengkap hanya jika diminta oleh user.

## 8. Filter Periode Laporan dan Kas & Bank

### Status implementasi

- [x] Mengganti nama menu **Laporan Keuangan Harian** menjadi **Laporan**.
- [x] Menambahkan pilihan periode Harian, Bulanan, dan Tahunan pada halaman Laporan.
- [x] Periode Harian menampilkan input tanggal.
- [x] Periode Bulanan menampilkan input bulan.
- [x] Periode Tahunan menampilkan pilihan tahun.
- [x] Menyesuaikan perhitungan dan rincian penjualan berdasarkan rentang periode aktif.
- [x] Menerapkan pola filter periode yang sama pada halaman Kas & Bank.
- [x] Menyesuaikan mutasi, uang masuk, dan uang keluar pada Kas & Bank berdasarkan rentang periode aktif.

## 9. Mode POS Online dan Offline

### Keputusan alur

- POS Kasir dari sidebar adalah **POS Offline/Kasir Biasa**.
- Tombol Buat Quotation/PO pada Daftar Penjualan membuka **POS Online**.
- Kedua mode memakai katalog dan keranjang yang sama, tetapi menghasilkan alur dokumen berbeda.

### Status implementasi

- [x] Menambahkan penanda POS Online dan POS Offline pada halaman kasir.
- [x] Mengarahkan tombol tambah pada tab Quotation/PO ke POS Online.
- [x] Menyediakan pilihan dokumen Quotation atau Purchase Order pada POS Online.
- [x] Mewajibkan customer pada POS Online.
- [x] Menyembunyikan metode pembayaran dan Simpan Bill pada POS Online.
- [x] Menyimpan transaksi POS Online ke tab Quotation/PO tanpa langsung memotong stok.
- [x] Mempertahankan customer opsional, pembayaran langsung, dan Simpan Bill pada POS Offline.
- [x] Menyimpan transaksi POS Offline ke tab Penjualan Offline.
- [x] Setelah transaksi POS Offline disimpan, tetap berada di halaman POS Kasir dan menampilkan notifikasi sukses tanpa membuka detail transaksi.

### Alur pengguna

1. User membuka Daftar Penjualan dan memilih tab Quotation/PO.
2. User menekan **Buat Quotation/PO** untuk membuka POS Online.
3. User memilih Quotation atau PO, memilih customer, dan menambahkan produk.
4. Dokumen tersimpan pada tab Quotation/PO tanpa dicampur dengan transaksi kasir biasa.
5. Jika user membuka POS Kasir langsung dari sidebar, sistem menggunakan mode POS Offline.
6. POS Offline menyelesaikan pembayaran, mencatat Kas & Bank, memotong stok FIFO, dan tampil pada tab Penjualan Offline.

## 10. Dashboard Penjualan

### Status implementasi

- [x] Mengubah dashboard menjadi ringkasan penjualan yang lebih detail dengan tetap memakai tema biru gelap ERP.
- [x] Menambahkan filter periode Harian, Bulanan, dan Tahunan beserta input tanggal yang sesuai.
- [x] Menampilkan Total Penjualan, Pembayaran Diterima, Total Transaksi, dan Produk Terjual.
- [x] Tidak menampilkan kartu atau ringkasan Open Bill pada dashboard.
- [x] Menambahkan grafik omzet dan transaksi berdasarkan periode terpilih.
- [x] Menambahkan tabel transaksi selesai terbaru pada periode terpilih.
- [x] Mengecualikan quotation, transaksi belum selesai, dan transaksi refund dari perhitungan omzet dashboard.

## 11. Tema Terang dan Gelap

### Status implementasi

- [x] Menambahkan tombol pengubah tema di sebelah kiri notifikasi.
- [x] Menggunakan ikon matahari dan bulan dengan transisi interaktif.
- [x] Mempertahankan tema biru gelap sebagai tema default.
- [x] Menambahkan tema terang dengan palet putih dan biru.
- [x] Menyimpan pilihan tema pada browser agar tetap digunakan setelah halaman dimuat ulang.
- [x] Menerapkan tema pada header, sidebar, konten utama, tabel, formulir, dan modal.
- [x] Memperluas tema terang ke seluruh panel POS, toolbar, kartu produk, dropdown, status, dan komponen lama yang memakai warna gelap khusus.
- [x] Meningkatkan kontras seluruh tulisan pada tema terang dan menebalkan outline kartu agar batas setiap bagian lebih jelas.

## 12. Key Penghapusan Quotation

### Keputusan keamanan

- Quotation hanya dapat dihapus saat status masih Open.
- Penghapusan wajib menggunakan key yang dibuat oleh Super Admin.
- Key disimpan sebagai hash dan nilai aslinya hanya ditampilkan satu kali setelah dibuat.
- Generate key baru langsung menonaktifkan key sebelumnya.

### Status implementasi

- [x] Menambahkan penyimpanan hash key pada akun user.
- [x] Menyembunyikan key dari serialisasi model User.
- [x] Menambahkan tombol Generate Key langsung pada dropdown profil Super Admin.
- [x] Mengganti konfirmasi browser dengan modal aplikasi di tengah layar.
- [x] Menyamakan warna tombol Generate Key dengan menu dropdown lainnya.
- [x] Menampilkan key pada modal tengah setelah berhasil dibuat.
- [x] Key dapat ditutup dan dibuka kembali dari dropdown selama 5 menit.
- [x] Menyembunyikan tombol Tampilkan Key dan modal setelah masa 5 menit berakhir.
- [x] Membatasi endpoint Generate Key dengan role Super Admin.
- [x] Menambahkan validasi key khusus sebelum quotation dihapus.
- [x] Membatasi policy penghapusan hanya untuk quotation berstatus Open.
- [x] Menambahkan form key dan tombol Hapus Quotation pada detail transaksi.
- [x] Menambahkan tombol Hapus langsung pada kolom Aksi di daftar Quotation/PO.
- [x] Menampilkan modal input key saat tombol Hapus pada daftar ditekan.
- [x] Menambahkan label Quotation atau PO pada setiap nomor dokumen agar aturan hapus terlihat jelas.
- [x] Menolak key yang salah tanpa menghapus quotation.
- [x] Membatasi fitur hapus dan key Super Admin hanya untuk Quotation berstatus Open.
- [x] Penjualan Offline tidak menyediakan fitur hapus.
- [x] Menyediakan tombol Refund untuk Penjualan Offline berstatus Completed.
- [x] Refund Penjualan Offline meminta alasan serta mengembalikan stok FIFO dan membalik mutasi pembayaran Kas/Bank.
- [x] Open Bill offline tidak dapat dihapus atau direfund sebelum transaksi diselesaikan.

### Alur pengguna

1. Super Admin membuka dropdown profil pada header.
2. Super Admin membuka dropdown profil, menekan **Generate Key Quotation**, lalu menyimpan key yang ditampilkan satu kali di dropdown.
3. User berwenang membuka detail quotation berstatus Open.
4. User memasukkan key dari Super Admin pada bagian Hapus Quotation.
5. Sistem mencocokkan key dengan hash yang tersimpan.
6. Quotation hanya dihapus jika role, status, jenis dokumen, dan key semuanya valid.

## Aturan Pengerjaan Berikutnya

- Implementasi mengikuti pola Laravel dan Blade/Tailwind yang sudah digunakan proyek.
- Perubahan database menggunakan migration; data awal akun menggunakan seeder.
- Route atau data tidak dihapus hanya karena menu sidebar disembunyikan.
- Pengujian otomatis tidak dijalankan kecuali user memintanya secara langsung.
- Setiap perubahan alur berikutnya harus diperbarui pada dokumen ini agar tetap menjadi sumber rencana bersama.
