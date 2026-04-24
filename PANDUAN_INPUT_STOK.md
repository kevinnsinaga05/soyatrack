# 📖 PANDUAN LENGKAP INPUT DATA STOK - GreSOY INVENTORY

## 🎯 ALUR UMUM INPUT STOK (Step by Step)

```
┌─────────────────────────────────────────────────────────────┐
│ 1️⃣ LOGIN KE WEBSITE ADMIN                                   │
│    ↓                                                         │
│ 2️⃣ SETUP AWAL: BUAT DAFTAR BAHAN (Stok Bahan)               │
│    ↓                                                         │
│ 3️⃣ INPUT STOK MASUK (Pengiriman barang dari supplier)       │
│    ↓                                                         │
│ 4️⃣ INPUT UPDATE STOK (Pemakaian saat produksi)              │
│    ↓                                                         │
│ 5️⃣ LIHAT RIWAYAT STOK (Tracking & laporan)                  │
└─────────────────────────────────────────────────────────────┘
```

---

## 📋 STEP 1: SETUP AWAL - DAFTAR BAHAN (STOK BAHAN)

**Tujuan:** Mendaftarkan semua bahan yang akan dikelola di gudang

**Akses:** Menu → Stok Bahan → Tambah Stok

### **LANGKAH INPUT:**

#### **FORM INPUT:**
```
✓ Nama Bahan         : Kremer Susu (nama bahan sesuai yang dibeli)
✓ Kategori           : Bahan Baku (Powder, Bahan Baku, Packaging, Gula, dll)
✓ Jumlah Stok Awal   : 50 (stok awal saat didaftarkan)
✓ Satuan             : kg (gram, kg, ml, liter, pcs, pack, box, dll)
✓ Stok Minimum       : 10 (jika stok kurang dari ini, muncul warning)
✓ Harga Beli/Satuan  : 50000 (Rp 50.000 per kg)
✓ Track Expired      : ✓ Centang jika bahan ada tanggal kadaluarsa
```

### **CONTOH INPUT KONKRET:**

| # | Nama Bahan | Kategori | Stok Awal | Satuan | Min | Harga | Track Expired |
|---|---|---|---|---|---|---|---|
| 1 | Kremer Susu | Bahan Baku | 0 | kg | 10 | 50000 | ✓ |
| 2 | Gula Pasir | Gula | 0 | kg | 20 | 12000 | ✗ |
| 3 | Powder Vanilla | Powder | 0 | kg | 5 | 80000 | ✓ |
| 4 | Cup Large | Packaging | 0 | pcs | 500 | 500 | ✗ |
| 5 | Botol 250ml | Packaging | 0 | pcs | 100 | 300 | ✗ |

### **HASIL:**
✅ Semua bahan terdaftar dengan stok = 0 (nanti akan diisi via Stok Masuk)

---

## 📦 STEP 2: INPUT STOK MASUK (PENERIMAAN BARANG)

**Tujuan:** Input ketika ada pengiriman barang dari supplier/pabrik

**Akses:** Menu → Stok Masuk → Tambah Barang Masuk

### **LANGKAH INPUT:**

#### **FORM INPUT:**
```
Tanggal Masuk: 24-04-2026

Row 1:
  ✓ Pilih Bahan       : Kremer Susu (kg)
  ✓ Jumlah Masuk      : 25
  ✓ Tanggal Expired   : 24-04-2027 (optional, jika ada)
  ✓ Harga Per Satuan  : 50000

Row 2:
  ✓ Pilih Bahan       : Gula Pasir (kg)
  ✓ Jumlah Masuk      : 50
  ✓ Tanggal Expired   : -
  ✓ Harga Per Satuan  : 12000

Row 3:
  ✓ Pilih Bahan       : Powder Vanilla (kg)
  ✓ Jumlah Masuk      : 10
  ✓ Tanggal Expired   : 24-04-2027
  ✓ Harga Per Satuan  : 80000

Row 4:
  ✓ Pilih Bahan       : Cup Large (pcs)
  ✓ Jumlah Masuk      : 1000
  ✓ Tanggal Expired   : -
  ✓ Harga Per Satuan  : 500
```

### **CONTOH SKENARIO NYATA:**

**📋 Tanggal: 24 April 2026**
**🚚 Pengiriman dari Supplier PT JAYA BAHAN**

| No | Bahan | Qty | Satuan | Expired | Harga/Satuan | Total |
|---|---|---|---|---|---|---|
| 1 | Kremer Susu | 25 | kg | 24-04-2027 | 50.000 | 1.250.000 |
| 2 | Gula Pasir | 50 | kg | - | 12.000 | 600.000 |
| 3 | Powder Vanilla | 10 | kg | 24-04-2027 | 80.000 | 800.000 |
| 4 | Cup Large | 1000 | pcs | - | 500 | 500.000 |

### **HASIL OTOMATIS SETELAH SIMPAN:**
```
Stok di sistem TERUPDATE otomatis:

Kremer Susu        : 0 + 25 = 25 kg ✓
Gula Pasir         : 0 + 50 = 50 kg ✓
Powder Vanilla     : 0 + 10 = 10 kg ✓
Cup Large          : 0 + 1000 = 1000 pcs ✓

✅ SISTEM MENCATAT:
   - Tanggal masuk: 24 April 2026
   - Supplier: PT JAYA BAHAN
   - Batch/lot barang dengan tanggal expired masing-masing
   - Harga beli untuk keperluan laporan
```

---

## 🏭 STEP 3: INPUT UPDATE STOK (PEMAKAIAN SAAT PRODUKSI)

**Tujuan:** Input penggunaan bahan ketika ada produksi/penjualan

**Akses:** Menu → Update Stok → Tambah Update Stok

### **LANGKAH INPUT:**

#### **FORM INPUT:**
```
Tanggal        : 24-04-2026

Produk 1:
  ✓ Nama Produk  : Minuman Susu Coklat
  ✓ Jumlah Diproduksi : 50 cup
  ✓ Pilih Gula   : Gula Pasir
  
  (Sistem OTOMATIS hitung kebutuhan bahan berdasarkan resep produk)

Produk 2:
  ✓ Nama Produk  : Minuman Susu Vanilla
  ✓ Jumlah Diproduksi : 30 cup
  ✓ Pilih Gula   : Gula Pasir
```

### **CONTOH SKENARIO NYATA:**

**📋 Tanggal: 24 April 2026 - PRODUKSI PAGI**

Input apa yang dimasukkan:
```
Produk yang akan diproduksi:
1. Minuman Susu Coklat → 50 cup
2. Minuman Susu Vanilla → 30 cup
3. Minuman Susu Gula → 20 cup

Pilih Gula:
- Untuk semua produk = Gula Pasir
```

### **SISTEM OTOMATIS HITUNG KEBUTUHAN:**

Berdasarkan **Resep Produk** yang sudah didaftarkan:

```
MINUMAN SUSU COKLAT (50 cup):
  × Kremer Susu     : 50 cup × 0.2 kg/cup = 10 kg ✓
  × Powder Coklat   : 50 cup × 0.04 kg/cup = 2 kg ✓
  × Gula Pasir      : 50 cup × 0.1 kg/cup = 5 kg ✓
  × Cup Large       : 50 cup × 1 pcs/cup = 50 pcs ✓

MINUMAN SUSU VANILLA (30 cup):
  × Kremer Susu     : 30 cup × 0.2 kg/cup = 6 kg ✓
  × Powder Vanilla  : 30 cup × 0.04 kg/cup = 1.2 kg ✓
  × Gula Pasir      : 30 cup × 0.1 kg/cup = 3 kg ✓
  × Cup Large       : 30 cup × 1 pcs/cup = 30 pcs ✓

MINUMAN SUSU GULA (20 cup):
  × Kremer Susu     : 20 cup × 0.2 kg/cup = 4 kg ✓
  × Gula Pasir      : 20 cup × 0.2 kg/cup = 4 kg ✓
  × Cup Large       : 20 cup × 1 pcs/cup = 20 pcs ✓

────────────────────────────────────────────────
TOTAL KEBUTUHAN BAHAN (OTOMATIS):
  × Kremer Susu     : 10 + 6 + 4 = 20 kg
  × Powder Coklat   : 2 kg
  × Powder Vanilla  : 1.2 kg
  × Gula Pasir      : 5 + 3 + 4 = 12 kg
  × Cup Large       : 50 + 30 + 20 = 100 pcs
```

### **HASIL SETELAH SIMPAN:**

```
STOK SEBELUM:
  Kremer Susu       : 25 kg
  Gula Pasir        : 50 kg
  Powder Vanilla    : 10 kg
  Powder Coklat     : 0 kg (belum ada, perlu INPUT STOK MASUK dulu!)
  Cup Large         : 1000 pcs

STOK SESUDAH (Otomatis berkurang):
  Kremer Susu       : 25 - 20 = 5 kg ✓
  Gula Pasir        : 50 - 12 = 38 kg ✓
  Powder Vanilla    : 10 - 1.2 = 8.8 kg ✓
  Powder Coklat     : 0 - 2 = ❌ ERROR! (Stok tidak cukup)
  Cup Large         : 1000 - 100 = 900 pcs ✓
```

⚠️ **CATATAN:** Jika bahan tidak cukup, sistem akan ERROR dan tidak boleh lanjut. Harus INPUT STOK MASUK dulu!

---

## 📊 STEP 4: LIHAT RIWAYAT STOK (TRACKING & LAPORAN)

**Tujuan:** Melihat log semua transaksi stok untuk audit dan laporan

**Akses:** Menu → Riwayat Stok

### **TABEL RIWAYAT YANG TERLIHAT:**

```
Tanggal     | Tipe Transaksi | Bahan | Qty Keluar/Masuk | Qty Sisa | Keterangan
───────────────────────────────────────────────────────────────────────────────
24-04-2026  | MASUK          | Kremer Susu | +25 kg | 25 kg | Barang masuk
24-04-2026  | MASUK          | Gula Pasir | +50 kg | 50 kg | Barang masuk
24-04-2026  | KELUAR         | Kremer Susu | -20 kg | 5 kg | Produksi 100 cup
24-04-2026  | KELUAR         | Gula Pasir | -12 kg | 38 kg | Produksi 100 cup
24-04-2026  | KELUAR         | Cup Large | -100 pcs | 900 pcs | Produksi 100 cup
```

### **FITUR YANG TERSEDIA:**
- ✅ Lihat semua transaksi (masuk/keluar)
- ✅ Filter berdasarkan tanggal
- ✅ Filter berdasarkan tipe bahan
- ✅ Export laporan ke PDF/Excel
- ✅ Lihat stok real-time per bahan

---

## 📌 RINGKASAN WORKFLOW SINGKAT

```
📅 HARI PERTAMA (24 April 2026):

1. ⏰ PAGI SETUP (Jam 08:00)
   └─ Input Daftar Bahan (Stok Bahan)
      • Kremer Susu (0 kg)
      • Gula Pasir (0 kg)
      • Powder Vanilla (0 kg)
      • Cup Large (0 pcs)

2. ⏰ PAGI PUKUL 09:00 - BARANG DATANG
   └─ Input Stok Masuk (Barang Masuk)
      • Terima Kremer Susu 25 kg
      • Terima Gula Pasir 50 kg
      • Terima Powder Vanilla 10 kg
      • Terima Cup Large 1000 pcs

3. ⏰ SIANG PUKUL 11:00 - MULAI PRODUKSI
   └─ Input Update Stok (Pemakaian)
      • Produksi Minuman Susu Coklat 50 cup
      • Produksi Minuman Susu Vanilla 30 cup
      • Produksi Minuman Susu Gula 20 cup

4. ⏰ SORE PUKUL 16:00 - LIHAT LAPORAN
   └─ Cek Riwayat Stok
      • Lihat semua transaksi hari ini
      • Cek stok sisa
      • Export laporan untuk evaluasi
```

---

## 🔄 CONTOH FULL CYCLE (LENGKAP 1 HARI)

### **SCENARIO: HARI OPERASIONAL NORMAL**

**📍 PUKUL 08:00 - SETUP AWAL (DI LAKUKAN 1x SAJA SAAT PERTAMA)**

Sistem kosong, kita daftarkan bahan:

```
INPUT STOK BAHAN:
┌────────────────────────────────────────────────┐
│ Nama Bahan    : Kremer Susu                    │
│ Kategori      : Bahan Baku                     │
│ Jumlah Stok   : 0                              │
│ Satuan        : kg                             │
│ Stok Minimum  : 10                             │
│ Harga Beli    : 50000                          │
│ Track Expired : ✓ Ya                           │
└────────────────────────────────────────────────┘
(Klik SIMPAN) ✓ BERHASIL

HASIL: Sistem punya data "Kremer Susu" (stok 0 kg)
```

**📍 PUKUL 09:00 - PENGIRIMAN BARANG DARI SUPPLIER**

Datang email/WA: "Barang sudah dikirim PT JAYA"

```
INPUT STOK MASUK:
┌────────────────────────────────────────────────┐
│ Tanggal Masuk      : 24-04-2026                │
│ Pilih Bahan        : Kremer Susu               │
│ Jumlah Masuk       : 25                        │
│ Satuan             : kg                        │
│ Tanggal Expired    : 24-04-2027                │
│ Harga Per Satuan   : 50000                     │
└────────────────────────────────────────────────┘
(Klik SIMPAN) ✓ BERHASIL

HASIL OTOMATIS:
✓ Kremer Susu stok = 0 + 25 = 25 kg
✓ Sistem catat: 25 kg masuk dari supplier
✓ Batch barang dengan expired 24-04-2027
```

**📍 PUKUL 11:00 - MULAI PRODUKSI**

Manajer bilang: "Hari ini produksi 50 cup Susu Coklat"

```
INPUT UPDATE STOK:
┌────────────────────────────────────────────────┐
│ Tanggal              : 24-04-2026              │
│ Produk ke-1          : Minuman Susu Coklat     │
│ Jumlah Diproduksi    : 50 cup                  │
│ Pilih Gula           : Gula Pasir              │
└────────────────────────────────────────────────┘
(Klik SIMPAN) ✓ BERHASIL

SISTEM OTOMATIS HITUNG:
Per 1 cup Minuman Susu Coklat butuh:
  × Kremer Susu    : 0.2 kg
  × Powder Coklat  : 0.04 kg
  × Gula Pasir     : 0.1 kg
  × Cup Large      : 1 pcs

Total untuk 50 cup:
  × Kremer Susu    : 50 × 0.2 = 10 kg ✓
  × Powder Coklat  : 50 × 0.04 = 2 kg ✓
  × Gula Pasir     : 50 × 0.1 = 5 kg ✓
  × Cup Large      : 50 × 1 = 50 pcs ✓

STOK OTOMATIS BERKURANG:
  Kremer Susu      : 25 - 10 = 15 kg ✓
  Powder Coklat    : 0 - 2 = ❌ ERROR! Tidak ada stok!
```

**⚠️ ERROR:** Powder Coklat belum ada! Harus INPUT STOK MASUK dulu.

```
FIX: INPUT STOK MASUK (URGENT)
┌────────────────────────────────────────────────┐
│ Tanggal Masuk      : 24-04-2026                │
│ Pilih Bahan        : Powder Coklat             │
│ Jumlah Masuk       : 5                         │
│ Satuan             : kg                        │
│ Tanggal Expired    : 24-04-2028                │
│ Harga Per Satuan   : 75000                     │
└────────────────────────────────────────────────┘
(Klik SIMPAN) ✓ BERHASIL

Coba produksi lagi...

INPUT UPDATE STOK (LANJUT):
┌────────────────────────────────────────────────┐
│ Tanggal              : 24-04-2026              │
│ Produk               : Minuman Susu Coklat     │
│ Jumlah Diproduksi    : 50 cup                  │
│ Pilih Gula           : Gula Pasir              │
└────────────────────────────────────────────────┘
(Klik SIMPAN) ✓ BERHASIL

STOK OTOMATIS BERKURANG:
  Kremer Susu      : 25 - 10 = 15 kg ✓
  Powder Coklat    : 5 - 2 = 3 kg ✓
  Gula Pasir       : 50 - 5 = 45 kg ✓
  Cup Large        : 1000 - 50 = 950 pcs ✓
```

**✅ PRODUKSI BERHASIL!**

**📍 PUKUL 16:00 - LIHAT LAPORAN AKHIR HARI**

Buka: Menu → Riwayat Stok

```
TABEL RIWAYAT TRANSAKSI:
Tanggal | Tipe | Bahan | Qty | Sisa | Keterangan
──────────────────────────────────────────────────
24-Apr  | IN   | Kremer Susu | +25 kg | 25 kg | Barang masuk
24-Apr  | IN   | Powder Coklat | +5 kg | 5 kg | Barang masuk
24-Apr  | OUT  | Kremer Susu | -10 kg | 15 kg | Produksi 50 cup
24-Apr  | OUT  | Powder Coklat | -2 kg | 3 kg | Produksi 50 cup
24-Apr  | OUT  | Gula Pasir | -5 kg | 45 kg | Produksi 50 cup
24-Apr  | OUT  | Cup Large | -50 pcs | 950 pcs | Produksi 50 cup

STOK AKHIR HARI:
✓ Kremer Susu: 15 kg
✓ Powder Coklat: 3 kg
✓ Gula Pasir: 45 kg
✓ Cup Large: 950 pcs
```

---

## ⚠️ COMMON MISTAKES & SOLUSI

| Masalah | Penyebab | Solusi |
|---|---|---|
| ❌ Produksi ERROR "Stok tidak cukup" | Belum INPUT STOK MASUK | Harus masukkan bahan dulu via Stok Masuk |
| ❌ Stok jadi negatif | - | Sistem tidak boleh sampai negatif (validasi) |
| ❌ Lupa input kadaluarsa | Bahan perishable | Centang "Track Expired" saat daftar bahan |
| ❌ Harga beli salah | Input typo | Bisa edit di Stok Masuk (klik edit/hapus) |
| ❌ Tidak tahu stok berapa | Tidak lihat riwayat | Buka Riwayat Stok untuk tracking |

---

## 🎓 TIPS UNTUK PEMULA

1. **JANGAN LANGSUNG PRODUKSI** - Setup semua bahan dulu via Stok Bahan
2. **SELALU INPUT STOK MASUK DULU** - Sebelum ada barang yang dipakai
3. **PERHATIKAN SATUAN** - 1 kg ≠ 1000 gram (pilih satuan yang tepat)
4. **CEK RIWAYAT SETIAP HARI** - Untuk tahu stok real-time
5. **BACKUP DATA REGULAR** - Export laporan setiap minggu

---

**✅ Selesai! Anda sudah tahu alur lengkap input stok dari awal sampai akhir!**

Butuh bantuan lebih? Tanya admin atau tim developer.

