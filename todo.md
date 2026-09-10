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
| Kontak Customer & Supplier | Selesai | Form seragam seperti produk, toolbar tambah pada daftar, dan pagination 5/10/25. |
| Penyelesaian Invoice Online | Selesai | Quotation/PO penjualan online dapat diselesaikan langsung di Tab Invoice & Detail Transaksi. |

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

- [x] Menempatkan POS Kasir di atas Dashboard dan memindahkan Manajemen Role ke posisi paling bawah.
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
- [x] Menyediakan pilihan tampilan Grafik Garis (Line Chart) dan Grafik Batang (Bar Chart) yang dapat beralih secara dinamis.
- [x] Grafik garis dilengkapi kurva halus, gradien area, grid horizontal, indikator hover vertikal, filter metrik (Semua / Omzet / Transaksi), serta kartu ringkasan perubahan penjualan per jam (Harian), per hari (Bulanan), atau per bulan (Tahunan).
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

## 12. Key Otorisasi Quotation dan Refund

### Keputusan keamanan

- Quotation hanya dapat dihapus saat status masih Open.
- Penghapusan quotation dan refund offline wajib menggunakan key yang dibuat oleh Super Admin.
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
- [x] Refund Penjualan Offline wajib memasukkan key otorisasi Super Admin yang valid.
- [x] Open Bill offline tidak dapat dihapus atau direfund sebelum transaksi diselesaikan.

### Alur pengguna

1. Super Admin membuka dropdown profil pada header.
2. Super Admin menekan **Generate Key Otorisasi**, lalu menyimpan key yang ditampilkan di dropdown.
3. User berwenang membuka detail quotation berstatus Open.
4. User memasukkan key dari Super Admin pada bagian Hapus Quotation.
5. Sistem mencocokkan key dengan hash yang tersimpan.
6. Quotation hanya dihapus jika role, status, jenis dokumen, dan key semuanya valid.
7. Untuk refund Penjualan Offline, user berwenang memasukkan alasan dan key otorisasi yang sama.
8. Refund hanya diproses setelah key valid, lalu stok FIFO serta mutasi Kas/Bank dikembalikan.

## 13. Kontak Customer dan Supplier
 
### Status implementasi
 
- [x] Mempertahankan menu dropdown Kontak di sidebar tetap bersih dan sederhana (hanya tautan Customer dan Supplier).
- [x] Tombol tambah kontak disediakan melalui toolbar daftar (**Tambah Customer** dan **Tambah Supplier**) pada halaman masing-masing.
- [x] Menyeragamkan layout dan styling form Tambah/Edit Kontak (`contacts/form.blade.php`) agar identik dengan form Produk (`products/form.blade.php`), lengkap dengan header slot, tombol kembali, deskripsi form, card grid, label bintang merah, dan tombol aksi berikon.
- [x] Menambahkan pilihan pagination `5, 10, 25` dengan default 5 data per halaman pada daftar Kontak (`contacts/index.blade.php`) dan `ContactController`.
- [x] Memperluas pencarian daftar kontak agar mendukung pencarian berdasarkan nama, email, dan nomor telepon.
- [x] Memastikan pengalihan setelah tambah, edit, atau hapus kontak tetap mempertahankan parameter `type` agar user kembali ke tab yang sesuai (Customer atau Supplier).
 
## 14. Penyelesaian Invoice Penjualan Online (Quotation / PO)

### Keputusan alur

- Transaksi penjualan online yang dibuat dari POS Online (`quotation` dan `order`) otomatis masuk ke tab **Quotation/PO** serta memiliki dokumen tagihan di tab **Invoice**.
- Transaksi online yang belum lunas atau belum berstatus `completed` dapat diselesaikan langsung melalui tombol **Selesaikan** di tab **Invoice** atau melalui form pembayaran di halaman detail transaksi.
- Penyelesaian invoice memotong stok FIFO (hanya untuk kuantitas item yang belum pernah dikirim melalui Delivery Order), mencatat `paid_amount`, mengubah status menjadi `completed`, dan membuat transaksi kredit pada akun Kas & Bank yang dipilih.

### Status implementasi

- [x] Memperbarui `CompleteSalePayment` agar mendukung pelunasan/penyelesaian invoice tipe `quotation` dan `order` di samping transaksi POS.
- [x] Memastikan pemotongan stok FIFO tidak berulang jika sebagian/seluruh item transaksi sudah dikirim melalui Delivery Order (`$unconsumed = quantity - deliveredQuantity()`).
- [x] Menambahkan role `finance` pada otorisasi form request pembayaran `PaySaleRequest`.
- [x] Menangani redirect kembali ke tab Invoice (`sales.index?tab=invoice`) setelah invoice diselesaikan dari modal.
- [x] Melewatkan daftar akun Kas & Bank ke `SaleController@index` untuk pilihan rekening pelunasan invoice.
- [x] Menambahkan tombol **Selesaikan** pada baris invoice yang belum selesai/lunas di Tab Invoice (`sales.index`).
- [x] Menambahkan modal interaktif `pay-invoice-modal` lengkap dengan rincian total tagihan, sudah dibayar, sisa tagihan, pilihan metode bayar, rekening Kas & Bank tujuan, dan input nominal pelunasan.
- [x] Menampilkan form penyelesaian invoice pada detail transaksi (`sales.show`) untuk transaksi online yang belum berstatus `completed`.
- [x] Mengeluarkan transaksi lunas, `cancelled`, atau `refunded` dari tab dan ringkasan Piutang; status pengiriman `completed` tidak dianggap lunas tanpa nilai `paid_amount` yang cukup.
- [x] Menyamakan query tabel Piutang, total sisa piutang, dan jumlah invoice belum lunas agar memakai kriteria yang sama.
- [x] Menampilkan status Lunas/Belum Lunas pada tab Quotation/PO tanpa menyediakan tombol atau pilihan pembayaran.
- [x] Memusatkan proses pelunasan hanya melalui tab Invoice dan Piutang.
- [x] Mempertahankan status PO berdasarkan progres pengiriman, sedangkan status pembayaran ditentukan dari `paid_amount`.
- [x] Mengizinkan PO yang pengirimannya sudah selesai tetapi belum lunas untuk tetap diproses pembayarannya.
- [x] Mencegah stok FIFO terpotong dua kali ketika PO dibayar sebelum Delivery Order dibuat.
- [x] Memasukkan PO baru secara otomatis ke tab Delivery Order sebagai pesanan yang menunggu pengiriman.
- [x] Menampilkan progres pengiriman, status pembayaran, dan tombol Buat Delivery Order pada daftar PO menunggu pengiriman.
- [x] Menyederhanakan pembayaran Invoice dan Piutang menjadi pilihan Tunai, BCA, BNI, atau Mandiri tanpa pilihan rekening tujuan terpisah.
- [x] Menghubungkan pilihan metode pembayaran langsung ke akun Kas & Bank yang sesuai.
- [x] Menampilkan dan mewajibkan input nilai pembayaran hanya ketika metode Tunai dipilih; pembayaran bank otomatis memakai sisa tagihan.
- [x] Merapikan kolom Aksi Piutang dengan tombol pembayaran berikon, satu baris, lebar konsisten, dan efek interaktif.
- [x] Menambahkan filter sumber Piutang untuk Semua, Transaksi Online, dan Offline/Open Bill.
- [x] Menampilkan label sumber transaksi pada setiap baris Piutang.
- [x] Menampilkan nama meja atau nama bill pada piutang Offline sebagai pembeda transaksi kasir.

### Alur pengguna

1. User membuat Quotation atau Purchase Order (PO) melalui POS Online di Daftar Penjualan.
2. Dokumen tercatat di tab **Quotation/PO** dan tagihannya tercatat di tab **Invoice**.
3. Pada tab **Invoice**, invoice yang belum lunas menampilkan status tagihan ("Belum Bayar" atau "Sebagian") beserta tombol **Selesaikan**.
4. User mengklik tombol **Selesaikan**, modal dialog muncul menampilkan sisa tagihan.
5. User memilih metode pembayaran dan rekening Kas & Bank penerima, lalu mengonfirmasi pembayaran.
6. Sistem memotong stok produk secara FIFO untuk sisa kuantitas yang belum dikirim, mencatat pembayaran, membukukan mutasi kredit ke Kas & Bank, dan memperbarui status invoice menjadi **Completed / Lunas**.

## Aturan Pengerjaan Berikutnya

- Implementasi mengikuti pola Laravel dan Blade/Tailwind yang sudah digunakan proyek.
- Perubahan database menggunakan migration; data awal akun menggunakan seeder.
- Route atau data tidak dihapus hanya karena menu sidebar disembunyikan.
- Pengujian otomatis tidak dijalankan kecuali user memintanya secara langsung.
- Setiap perubahan alur berikutnya harus diperbarui pada dokumen ini agar tetap menjadi sumber rencana bersama.
- [x] Menghapus fitur Coupon beserta input kode dan perhitungan diskon dari tampilan POS.
- [x] Mengurutkan katalog produk secara natural berdasarkan abjad dan angka pada nama produk.
- [x] Mengubah indikator mode POS menjadi tombol untuk berpindah langsung antara POS Offline dan POS Online.
- [x] Memperbaiki cetak invoice transaksi non-tunai dengan memuat data rekening pembayaran dan menangani transaksi tanpa customer sebagai Pelanggan Umum.
- [x] Membuat semua tombol cetak invoice membuka dialog print langsung tanpa tab baru dan merapikan desain invoice A4.
- [x] Menambahkan tombol Kembali pada modal dan halaman pembayaran tagihan/piutang tanpa memproses pembayaran.
- [x] Menambahkan pilihan visualisasi Line Chart, Bar Chart, dan Pie Chart pada grafik penjualan Dashboard.
- [x] Menambahkan tombol dan form Tambah User/Akun pada Manajemen Role yang hanya dapat digunakan Superadmin.
- [x] Mengganti halaman index bawaan Laravel dengan landing page Dagang ERP mengikuti struktur mockup/index.html.
- [x] Menambahkan tema terang dan gelap yang konsisten beserta tombol pengubah tema pada landing page.
- [x] Merapikan halaman login dengan desain responsif bertema biru-indigo serta dukungan mode terang dan gelap.
