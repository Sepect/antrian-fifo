# Desain: Pencarian Pasien via Nama (Dropdown Search)

Tanggal: 2026-07-12
Status: Disetujui

## Masalah

Pendaftaran antrean dan cek status antrean saat ini mengharuskan pasien mengetik Nomor Rekam Medis (RM), mis. `RM-0042`. Pasien jarang hafal atau membawa nomor RM-nya, sehingga proses ini menjadi hambatan. Nama pasien jauh lebih mudah diingat.

## Tujuan

Mengganti input No. RM dengan pencarian **nama pasien** berbentuk dropdown search di tiga titik:

1. Pendaftaran guest, tab "Pasien Lama / Kontrol" — `resources/views/guest/register.blade.php`
2. Cek Antrian guest — `resources/views/guest/status.blade.php`
3. Pendaftaran Walk-In staff, tab "Pasien Lama / Kontrol" — `resources/views/staff/register-patient.blade.php`

### Di luar cakupan

- No. RM **tetap** dibuat otomatis untuk pasien baru dan tetap ditampilkan di halaman hasil serta kartu sukses pendaftaran. Yang dihapus hanya kewajiban *mengetik* RM sebagai input.
- Halaman Direktori Pasien staff (`/staff/patients`) sudah punya pencarian sendiri dan tidak diubah.

## Arsitektur

Tiga komponen: satu endpoint pencarian, satu komponen Blade yang dipakai ulang, dan penukaran field di tiga handler form.

### 1. Endpoint pencarian

Route publik baru:

```
GET /patients/search?q=<query>
```

- Diproses oleh `PatientController::search`.
- Route publik (tanpa `auth`) karena dua dari tiga halaman pemakai adalah halaman guest.
- Dibatasi middleware `throttle:30,1` untuk meredam enumerasi daftar pasien.
- Mengembalikan array kosong bila `q` kurang dari 2 karakter.
- Mencari dengan `LIKE %q%` case-insensitive pada kolom `name` saja.
- Maksimal 10 hasil, diurutkan berdasarkan `name`.

Response JSON per item — **sengaja tidak menyertakan `medical_record_number` maupun `nik`** agar tidak bocor di halaman publik:

```json
[
  {
    "id": 42,
    "name": "Budi Santoso",
    "gender_label": "Laki-laki",
    "birth_date_label": "12 Mar 1990"
  }
]
```

`gender_label` bernilai `null` bila `gender` kosong; `birth_date_label` bernilai `null` bila `birth_date` kosong.

### 2. Komponen Blade `<x-patient-search>`

File baru: `resources/views/components/patient-search.blade.php`. Dikendalikan Alpine.js (sudah termuat di `layouts/guest.blade.php` dan `layouts/app.blade.php`).

Atribut yang diterima:

| Atribut | Default | Keterangan |
|---|---|---|
| `label` | `"Nama Pasien"` | Teks label di atas input |
| `placeholder` | `"Ketik nama pasien..."` | Placeholder input |
| `required` | `false` | Menandai field wajib (bintang merah + atribut `required` pada hidden input) |

Struktur: satu input teks (pencarian, `name` tidak diset sehingga tidak ikut terkirim) + satu `<input type="hidden" name="patient_id">` + panel hasil absolut di bawah input.

Perilaku:

- Mengetik → debounce 250ms → `fetch` ke `/patients/search` → panel hasil terbuka.
- Tiap baris hasil: nama (tebal) + baris kecil berisi `gender_label` dan `birth_date_label` dipisah `·`. Baris kecil disembunyikan bila kedua nilai `null`.
- Klik/Enter pada hasil → input terisi nama, hidden `patient_id` terisi, panel tertutup, muncul tombol "×" untuk menghapus pilihan.
- **Mengubah teks input setelah memilih akan mengosongkan kembali `patient_id`.** Mencegah kondisi di mana teks yang terlihat tidak cocok dengan ID yang terkirim.
- Navigasi keyboard: panah atas/bawah menyorot hasil, Enter memilih hasil tersorot, Esc menutup panel.
- Klik di luar komponen menutup panel.
- State yang ditampilkan di panel: `"Ketik minimal 2 huruf"` (query terlalu pendek), `"Mencari..."` (fetch berjalan), `"Pasien tidak ditemukan"` (hasil kosong).

### 3. Penukaran field di handler form

| Lokasi | Sebelum | Sesudah |
|---|---|---|
| `GuestController::processRegister` (cabang `lama`) | `medical_record_number` wajib, `exists:patients,medical_record_number` | `patient_id` wajib, `exists:patients,id`, ambil via `Patient::findOrFail` |
| `GuestController::trackDisplay` | `medical_record_number` wajib; cari queue via `whereHas('patient', ...)` cocokkan RM | terima `patient_id`; cari queue via `where('patient_id', ...)` |
| Closure `POST /staff/register-patient` di `routes/web.php` (cabang `lama`) | sama seperti baris pertama | sama seperti baris pertama |

Redirect setelah pendaftaran guest berhasil berubah dari
`/status-display?medical_record_number=RM-0042` menjadi `/status-display?patient_id=42`.

**Kompatibilitas mundur:** `trackDisplay` tetap menerima `medical_record_number` bila `patient_id` tidak ada di request, agar URL/bookmark lama tetap berfungsi. Aturan validasinya: `patient_id` wajib *kecuali* `medical_record_number` terisi, dan sebaliknya. Bila keduanya kosong, validasi gagal.

Logika perhitungan posisi antrean (Smart FIFO) di `trackDisplay` tidak berubah sama sekali.

## Alur data

Pendaftaran pasien lama (guest):

1. Pasien mengetik nama → komponen fetch `/patients/search?q=...` → memilih satu hasil → hidden `patient_id=42` terisi.
2. Submit `POST /register` dengan `patient_type=lama`, `patient_id=42`, `complaint=...`.
3. `processRegister` memvalidasi, mengambil `Patient` #42, mengecek antrean aktif hari ini, membuat `Queue`.
4. Redirect ke `/status-display?patient_id=42`, halaman hasil menampilkan nomor antrean, No. RM, dan kode booking seperti sebelumnya.

Cek antrean: pasien memilih nama → submit `GET /status-display?patient_id=42` → `trackDisplay` mencari antrean hari ini milik pasien tersebut.

## Penanganan error

- Submit tanpa memilih dari dropdown (mengetik nama saja) → `patient_id` kosong → validasi gagal dengan pesan **"Silakan pilih nama pasien dari daftar pencarian."** Pesan ditampilkan di blok `$errors` yang sudah ada di tiap halaman.
- `patient_id` menunjuk pasien yang tidak ada → gagal di aturan `exists`, pesan yang sama.
- Pasien sudah punya antrean aktif hari ini → perilaku lama dipertahankan (redirect dengan pesan "Pasien sudah memiliki antrean aktif hari ini.").
- Cek antrean untuk pasien tanpa antrean hari ini → perilaku lama dipertahankan (pesan "Kredensial Antrian tidak valid atau antrian bukan untuk hari ini.").
- Endpoint pencarian gagal / jaringan mati → panel menampilkan "Pasien tidak ditemukan"; form tetap bisa disubmit tapi akan ditolak validasi server. Tidak ada jalur di mana kegagalan fetch menghasilkan antrean yang salah.

## Testing

Feature test (PHPUnit, `tests/Feature`):

1. `/patients/search?q=bud` mengembalikan pasien yang namanya cocok, dan response **tidak** memuat `medical_record_number` maupun `nik`.
2. `/patients/search?q=b` (1 karakter) mengembalikan array kosong.
3. Hasil pencarian dibatasi maksimal 10 item.
4. `POST /register` dengan `patient_type=lama` + `patient_id` valid membuat `Queue` untuk pasien tersebut.
5. `POST /register` dengan `patient_type=lama` tanpa `patient_id` ditolak dengan error validasi.
6. `GET /status-display?patient_id=<id>` menemukan antrean hari ini milik pasien tersebut.
7. `GET /status-display?medical_record_number=RM-XXXX` (jalur kompatibilitas mundur) tetap berfungsi.
8. `POST /staff/register-patient` dengan `patient_id` valid membuat antrean pada poliklinik terpilih.

## Keputusan yang diambil

- **Endpoint AJAX, bukan preload semua pasien ke HTML.** Preload 149 nama akan membuat seluruh daftar pasien terbaca di source halaman publik, dan tidak akan berskala saat data pasien bertambah.
- **Dropdown menampilkan jenis kelamin + tanggal lahir, bukan No. RM.** Cukup untuk membedakan pasien bernama sama tanpa membocorkan No. RM di halaman guest.
- **Form mengirim `patient_id`, bukan teks nama.** Nama kembar tidak pernah ambigu di sisi server.
