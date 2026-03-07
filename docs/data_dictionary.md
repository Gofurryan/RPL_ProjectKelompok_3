## 1. Tabel: `items` (Aset Inventaris)
| Kolom | Tipe Data | Deskripsi |
| :--- | :--- | :--- |
| `id` | INT (PK) | ID unik barang. |
| `name` | VARCHAR | Nama barang (Contoh: Vacuum Cleaner, Mic Wireless). |
| `status` | ENUM | `Available` (Bisa dipinjam), `Borrowed` (Sedang dipinjam), `Damaged` (Rusak). |
| `location` | VARCHAR | Lokasi penyimpanan (Gudang, Ruang Utama, dll). |

## 2. Tabel: `users` (Pengguna)
| Kolom | Tipe Data | Deskripsi |
| :--- | :--- | :--- |
| `id` | INT (PK) | ID unik pengguna. |
| `name` | VARCHAR | Nama lengkap (Admin atau Warga). |
| `role` | ENUM | `Admin` atau `Warga`. |
| `phone` | VARCHAR | Nomor WhatsApp (Unique). |

## 3. Tabel: `loans` (Peminjaman)
| Kolom | Tipe Data | Deskripsi |
| :--- | :--- | :--- |
| `id` | INT (PK) | ID transaksi peminjaman. |
| `user_id` | INT (FK) | Relasi ke tabel pengguna. |
| `item_id` | INT (FK) | Relasi ke tabel barang. |
| `loan_date` | DATETIME | Waktu pengambilan barang. |
| `due_date` | DATETIME | Tenggat waktu pengembalian. |
| `status` | ENUM | `Pending`, `Approved`, `Active`, `Returned`, `Overdue`. |

## 4. Tabel: `penalties` (Denda)
| Kolom | Tipe Data | Deskripsi |
| :--- | :--- | :--- |
| `amount` | DECIMAL | Jumlah denda (Default Rp 5.000/hari). |
| `status` | ENUM | `Unpaid` atau `Paid`. |
