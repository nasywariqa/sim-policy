# UI/UX DESIGN SPECIFICATION

## Sistem Informasi Manajemen UKM Polytechnic Linux Community

  ---------------------------------------------------------------------
  Informasi                          Keterangan
  ---------------------------------- ----------------------------------
  Nama produk                        Sistem Informasi Manajemen UKM
                                     Polytechnic Linux Community

  Dokumen                            Design System & UI/UX
                                     Specification

  Versi                              0.2

  Status                             Draft revisi v0.2; enam keputusan
                                     visual utama LOCKED dan selaras
                                     dengan PRD/SRS/ARCHITECTURE v0.2

  Tanggal                            4 November 2026

  Product requirements               `PRD.md`

  Software requirements              `SRS.md`

  System architecture                `ARCHITECTURE.md`

  Referensi visual                   Referensi dashboard dark dan
                                     landing page dark-orange yang
                                     diberikan pemilik proyek
  ---------------------------------------------------------------------

## Riwayat Revisi

  -----------------------------------------------------------------------
  Versi                   Tanggal                 Perubahan
  ----------------------- ----------------------- -----------------------
  0.1                     4 November 2026         Baseline design system
                                                  dan enam keputusan
                                                  visual terkunci.

  0.2                     4 November 2026         Landing page dipetakan
                                                  ke requirement PRD/SRS;
                                                  visibilitas ranking,
                                                  status
                                                  `Dalam Evaluasi`, dan
                                                  aksi edit/hapus catatan
                                                  diselaraskan dengan TBD
                                                  bisnis.
  -----------------------------------------------------------------------

------------------------------------------------------------------------

> **Aturan status:** `LOCKED` berarti keputusan telah disetujui pemilik
> proyek. `PROPOSED` berarti rekomendasi desain yang dapat dievaluasi
> pada prototype. `TBD` berarti belum diputuskan. Dokumen ini mengatur
> tampilan dan interaksi; dokumen ini tidak boleh mengubah business
> rules, permission, atau scope fungsional pada PRD/SRS.

------------------------------------------------------------------------

# 1. Tujuan Dokumen

Dokumen ini menjadi acuan visual dan interaksi untuk membangun antarmuka
Sistem Informasi Manajemen UKM Polytechnic Linux Community.

DESIGN.md menjelaskan: 1. Identitas visual dan prinsip desain. 2.
Struktur public landing page sebelum login. 3. Struktur halaman login.
4. Layout aplikasi setelah login. 5. Navigasi dan tampilan berdasarkan
role/permission. 6. Spesifikasi halaman fitur. 7. Design tokens,
komponen, state, dan responsive behavior. 8. Kriteria penerimaan UI/UX.

Dokumen ini dibaca bersama: - `PRD.md`: tujuan produk, aktor, ruang
lingkup, dan business rules. - `SRS.md`: functional requirements, use
case, dan kebutuhan antarmuka. - `ARCHITECTURE.md`: React + TypeScript,
Laravel, REST API, Sanctum, dan same-origin deployment.

## 1.1 Prinsip Sumber Kebenaran

-   PRD menjadi acuan tujuan produk dan ruang lingkup.
-   SRS menjadi acuan perilaku sistem dan requirement yang dapat diuji.
-   ARCHITECTURE menjadi acuan batas teknis antarkomponen.
-   DESIGN menjadi acuan visual, layout, dan interaksi antarmuka.
-   DESIGN tidak boleh mengubah permission atau mengasumsikan suatu role
    dapat melakukan tindakan yang belum diizinkan pada PRD/SRS.

## 1.2 Penambahan Lingkup Antarmuka

Public landing page singkat sebelum login telah dimasukkan ke ruang
lingkup PRD v0.2 dan diturunkan menjadi FR-PUB-01 s.d. FR-PUB-05 serta
UC-PUB-01 pada SRS v0.2. Landing page bersifat informatif, dapat diakses
tanpa autentikasi, tidak menyediakan self-registration, dan CTA utamanya
mengarahkan pengguna ke halaman Login.

------------------------------------------------------------------------

# 2. Keputusan Desain yang Telah Dikunci

## 2.1 Design Decision Log

  -----------------------------------------------------------------------
  ID                Keputusan         Status            Konsekuensi
  ----------------- ----------------- ----------------- -----------------
  DD-001            Landing page      LOCKED            Konten dibatasi
                    singkat (*short*)                   pada navigation,
                                                        hero, feature
                                                        highlights, CTA,
                                                        dan footer.

  DD-002            Dashboard         LOCKED            Seluruh halaman
                    menggunakan dark                    internal
                    UI                                  menggunakan
                                                        fondasi dark
                                                        theme.

  DD-003            Aplikasi          LOCKED            Tidak ada light
                    menggunakan                         mode dan tidak
                    dark-only theme                     ada theme toggle
                                                        pada versi
                                                        pertama.

  DD-004            Hero landing page LOCKED            Preview dashboard
                    menggunakan                         menjadi visual
                    preview dashboard                   utama hero, bukan
                                                        ilustrasi abstrak
                                                        sebagai visual
                                                        utama.

  DD-005            Animasi subtle    LOCKED            Hanya transisi
                                                        ringan, hover,
                                                        dan fade-in
                                                        seperlunya.

  DD-006            Navigasi landing  LOCKED            Item navigasi
                    page menggunakan                    publik mengarah
                    scroll/anchor                       ke section pada
                                                        halaman yang
                                                        sama; CTA Masuk
                                                        mengarah ke
                                                        login.
  -----------------------------------------------------------------------

## 2.2 Identitas Visual yang Dikunci

Berdasarkan referensi yang diberikan, arah visual produk: - Dark,
modern, dan profesional. - Dominasi hitam/charcoal. - Aksen merah dan
orange. - Teks putih/off-white dengan kontras yang jelas. - Gradient
merah-orange digunakan secara selektif. - Kartu dan panel menggunakan
border halus, bukan garis tebal. - Tipografi tegas pada judul, tetapi
tetap mudah dibaca untuk data operasional.

Kedua referensi digunakan dengan pembagian: - **Referensi A ---
Dashboard:** acuan komposisi dashboard internal, sidebar, topbar, KPI
cards, grafik, dan tabel. - **Referensi B --- Landing Page:** acuan hero
yang kuat, tipografi besar, bidang gradient merah-orange, dan section
fitur yang ringkas.

Referensi hanya menjadi inspirasi visual. Konten finansial, AI
Assistant, transaksi, layanan agensi, video promosi, angka revenue, dan
elemen lain dari referensi tidak menjadi fitur PLC.

------------------------------------------------------------------------

# 3. Design Direction

## 3.1 Konsep

**Dark Technology Platform --- Structured, Bold, and Focused**

Antarmuka harus terasa seperti platform manajemen organisasi teknologi
yang modern. Tampilan tidak boleh terlalu ramai atau dekoratif karena
pengguna akan banyak membaca data, mengisi form, mengevaluasi program
kerja, dan membandingkan hasil SAW.

Karakter visual: 1. **Bold:** judul dan heading jelas, dengan hierarki
visual kuat. 2. **Structured:** sidebar, topbar, grid, tabel, dan form
tersusun konsisten. 3. **Focused:** warna aksen digunakan untuk tindakan
penting dan informasi prioritas. 4. **Premium dark:** panel charcoal,
border halus, dan depth melalui perbedaan surface. 5. **Functional:**
dekorasi tidak mengganggu keterbacaan informasi organisasi.

## 3.2 Perbedaan Public dan Internal UI

  -----------------------------------------------------------------------
  Area                    Public Landing Page     Authenticated
                                                  Application
  ----------------------- ----------------------- -----------------------
  Tujuan                  Memperkenalkan sistem   Menyelesaikan pekerjaan
                          dan mengarahkan         organisasi
                          pengguna ke login       

  Gaya                    Editorial, bold,        Dashboard, data-led,
                          visual-led              terstruktur

  Konten                  Ringkas dan statis      Dinamis sesuai data dan
                                                  permission

  Navigasi                Anchor scroll           Sidebar dan navigasi
                                                  halaman

  CTA                     Masuk ke Sistem         Aksi sesuai hak akses

  Visual utama            Preview dashboard       Data, status, tabel,
                                                  form, chart

  Kepadatan               Rendah                  Menyesuaikan kebutuhan
                                                  fitur
  -----------------------------------------------------------------------

------------------------------------------------------------------------

# 4. Color System

## 4.1 Color Direction

Keluarga warna berikut menjadi arah desain yang disepakati: - Background
hitam/dark charcoal. - Surface charcoal yang sedikit lebih terang dari
background. - Teks putih atau off-white. - Aksen merah dan orange. -
Warna semantik terpisah untuk status dan feedback.

**Catatan status:** keluarga warna dark + red/orange merupakan LOCKED.
Nilai HEX berikut adalah **PROPOSED design tokens** untuk prototype
pertama; nilai final dapat disesuaikan setelah review visual tanpa
mengubah arah visual yang sudah disepakati.

## 4.2 Proposed Color Tokens

  ----------------------------------------------------------------------
  Token                      Nilai awal            Penggunaan
  -------------------------- --------------------- ---------------------
  `--color-bg`               `#090909`             Background utama

  `--color-surface`          `#171414`             Card dan panel

  `--color-surface-raised`   `#211B19`             Modal, dropdown,
                                                   panel yang lebih
                                                   menonjol

  `--color-surface-hover`    `#2A211E`             Hover pada elemen
                                                   interaktif

  `--color-border`           `#342C29`             Border halus

  `--color-text-primary`     `#F5F1ED`             Judul dan teks utama

  `--color-text-secondary`   `#B8ADA7`             Deskripsi dan
                                                   metadata

  `--color-text-muted`       `#817771`             Placeholder dan
                                                   informasi sekunder

  `--color-brand-red`        `#F04432`             Aksen merah

  `--color-brand-orange`     `#FF641F`             Aksen orange

  `--color-focus`            `#FF8A4C`             Focus ring

  `--color-link`             `#FF8A4C`             Link pada dark
                                                   background
  ----------------------------------------------------------------------

## 4.3 Brand Gradient

Gradient utama yang direkomendasikan:

``` css
linear-gradient(120deg, #F04432 0%, #FF641F 100%)
```

Penggunaan: - Tombol primary. - Highlight visual pada hero. - Satu kartu
KPI atau elemen yang memang perlu ditonjolkan. - Aksen grafik. - Ornamen
background yang tidak mengganggu konten.

Jangan menggunakan gradient pada semua card, semua tombol, atau setiap
heading. Gradient harus menjadi aksen, bukan warna dasar seluruh
antarmuka.

## 4.4 Semantic Colors

Warna semantik dipakai untuk status dan feedback. Warna ini tidak
menggantikan identitas merah-orange.

  Makna     Arah warna   Penggunaan
  --------- ------------ --------------------------------------
  Success   Green        Berhasil, disetujui, selesai
  Warning   Amber        Perlu perhatian, menunggu, revisi
  Danger    Red          Ditolak, gagal, tindakan berisiko
  Info      Blue/cyan    Informasi dan status netral tertentu
  Neutral   Gray         Draft, belum diproses, metadata

Status program kerja yang ditampilkan harus mengikuti status bisnis pada
SRS: - Draft - Diajukan - Revisi - Disetujui - Ditolak - Selesai

Pemetaan warna per status adalah detail visual. Label status harus tetap
ditampilkan sebagai teks; jangan mengandalkan warna saja.

------------------------------------------------------------------------

# 5. Typography

## 5.1 Prinsip Tipografi

-   Gunakan sans-serif modern dan mudah dibaca.
-   Heading landing page boleh lebih besar, bold, dan editorial.
-   Heading dashboard lebih ringkas dan fungsional.
-   Teks form, tabel, dan metadata harus nyaman dibaca dalam durasi
    lama.
-   Angka statistik menggunakan angka yang jelas dan konsisten.

## 5.2 Font Recommendation

**PROPOSED:** gunakan `Inter` sebagai font utama dengan fallback system
sans-serif. Jika repository telah menyediakan font yang sesuai,
prioritaskan asset lokal yang tersedia. Jangan menambahkan dependency
atau mengandalkan font eksternal sebelum audit repository.

``` css
font-family: Inter, ui-sans-serif, system-ui, -apple-system,
  BlinkMacSystemFont, "Segoe UI", sans-serif;
```

## 5.3 Type Scale Awal

  Token          Ukuran awal     Weight Penggunaan
  ------------ ------------- ---------- ------------------------------
  Display          48--64 px   700--800 Hero landing page
  H1               30--36 px        700 Judul halaman utama
  H2               24--28 px   650--700 Judul section
  H3               18--20 px        600 Judul card/panel
  Body             14--16 px        400 Teks isi
  Body small       13--14 px        400 Tabel dan deskripsi sekunder
  Caption          11--12 px   400--500 Metadata dan label tambahan
  KPI              28--36 px   650--700 Angka statistik

Ukuran dapat disesuaikan terhadap breakpoint dan kepadatan data. Jangan
mengecilkan teks tabel sampai sulit dibaca hanya agar seluruh kolom
muat.

------------------------------------------------------------------------

# 6. Layout, Grid, and Spacing

## 6.1 Prinsip Layout

-   Gunakan sistem spacing konsisten berbasis kelipatan 4 px.
-   Berikan ruang kosong yang cukup antarsection.
-   Gunakan grid untuk KPI dan dashboard cards.
-   Form panjang dipecah menjadi section yang logis.
-   Tabel menggunakan lebar yang memadai dan tidak dipaksakan menjadi
    terlalu padat.
-   Layout internal harus konsisten di seluruh modul.

## 6.2 Proposed Spacing Tokens

  Token          Nilai
  ------------ -------
  `space-1`       4 px
  `space-2`       8 px
  `space-3`      12 px
  `space-4`      16 px
  `space-5`      20 px
  `space-6`      24 px
  `space-8`      32 px
  `space-10`     40 px
  `space-12`     48 px
  `space-16`     64 px

## 6.3 Radius and Border

  Elemen                Radius awal
  ------------------ --------------
  Small control                8 px
  Input dan button        10--12 px
  Card                    14--16 px
  Modal                   16--20 px
  Pill/badge           Full rounded

Gunakan border 1 px dengan warna low-contrast. Hindari shadow terang
berlebihan. Glow merah-orange hanya digunakan secara terbatas pada hero
atau elemen highlight.

## 6.4 Internal Application Layout

**PROPOSED layout desktop:** - Sidebar persistent di sisi kiri. - Topbar
berada di atas area konten. - Main content memiliki padding konsisten. -
Lebar konten mengikuti ruang yang tersedia. - Sidebar dapat memiliki
mode collapsed jika dibutuhkan dan sesuai implementasi.

Dimensi awal untuk prototype: - Sidebar expanded: sekitar 240--260 px. -
Topbar: sekitar 64--72 px. - Main content padding: sekitar 24--32 px.

Angka ini merupakan titik awal, bukan constraint teknis final.

------------------------------------------------------------------------

# 7. Public Landing Page

## 7.1 Tujuan

Landing page memperkenalkan Sistem Informasi Manajemen UKM PLC dan
mengarahkan pengguna yang sudah memiliki akun menuju halaman login.

Landing page: - Dapat diakses tanpa autentikasi. - Bersifat singkat dan
informatif. - Tidak memerlukan data privat. - Tidak menyediakan
pendaftaran akun publik. - Tidak menampilkan dashboard operasional yang
berisi data asli pengguna.

## 7.2 Page Structure

Urutan section yang dikunci:

1.  Navigation.
2.  Hero.
3.  Feature Highlights.
4.  Closing CTA.
5.  Footer.

## 7.3 Navigation

Elemen: - Logo/wordmark PLC di kiri. - Menu anchor di kanan/tengah:
Beranda, Fitur, Tentang. - Tombol `Masuk` yang mengarah ke halaman
login.

Perilaku: - `Beranda` scroll ke hero. - `Fitur` scroll ke feature
highlights. - `Tentang` scroll ke bagian pengenalan singkat/footer. -
`Masuk` melakukan navigasi ke halaman login, bukan scroll. - Smooth
scrolling digunakan jika tidak bertentangan dengan preferensi reduced
motion. - Navigation dapat menjadi sticky selama scroll jika hasil
prototype tetap ringan dan tidak menutupi konten.

**TBD asset:** gunakan logo/wordmark resmi PLC yang disediakan pemilik
proyek. Jangan membuat ulang logo organisasi atau menggantinya dengan
logo institusi lain tanpa persetujuan.

## 7.4 Hero Section

**Keputusan LOCKED:** visual utama hero adalah preview dashboard.

Komposisi: - Eyebrow/label kecil yang memperkenalkan UKM PLC atau
sistem. - Headline besar, singkat, dan tegas. - Deskripsi satu sampai
dua kalimat. - CTA utama `Masuk ke Sistem`. - Preview dashboard sebagai
visual dominan. - Background dark dengan aksen gradient merah-orange di
area tertentu.

Contoh copy awal (PROPOSED, dapat disunting): - Headline:
`SATU SISTEM. PROGRAM KERJA LEBIH TERARAH.` - Deskripsi:
`Kelola program kerja, evaluasi, dan prioritas kegiatan UKM PLC dalam satu platform terintegrasi.` -
CTA: `Masuk ke Sistem`

Preview dashboard: - Dapat berupa mockup/screenshot statis yang
disiapkan khusus untuk landing page. - Gunakan data ilustratif, bukan
data privat atau informasi anggota sebenarnya. - Preview bukan dashboard
interaktif dan tidak menggantikan autentikasi. - Pastikan preview tetap
terbaca pada ukuran layar kecil; pada mobile, preview dapat diperkecil
atau dipotong secara terkontrol.

## 7.5 Feature Highlights

Tampilkan tiga kelompok manfaat yang benar-benar sesuai ruang lingkup
produk:

### Feature 1 --- Manajemen Program Kerja

Ringkasan: penyusunan draft, pengajuan, evaluasi, revisi, dan pemantauan
status program kerja.

### Feature 2 --- Evaluasi dan Prioritas SAW

Ringkasan: penilaian program kerja menggunakan lima kriteria dan
pemeringkatan SAW sebagai bahan pertimbangan rapat pleno.

### Feature 3 --- Manajemen Organisasi

Ringkasan: pengelolaan pengguna, periode kepengurusan, bidang,
keanggotaan, dan hak akses sesuai kewenangan.

Gaya kartu: - Dark surface. - Icon outline sederhana. - Judul tegas. -
Deskripsi pendek. - Aksen orange/red kecil. - Hover ringan tanpa
mengubah layout secara berlebihan.

## 7.6 Closing CTA

-   Headline singkat yang mengajak pengguna mengakses sistem.
-   Tombol `Masuk ke Sistem`.
-   CTA menuju login.

Jangan menambahkan formulir kontak, newsletter, pendaftaran anggota,
atau integrasi eksternal karena belum menjadi scope produk.

## 7.7 Footer

Isi minimal: - Logo/wordmark PLC. - Nama sistem atau identitas UKM. -
Copyright. - Link anchor yang relevan jika dibutuhkan.

Tidak perlu menambahkan social media links atau alamat kontak jika data
resminya belum diberikan.

## 7.8 Landing Page States

  State              Perilaku
  ------------------ ------------------------------------------------
  Default            Menampilkan seluruh section statis
  Hover navigation   Perubahan warna/underline ringan
  Hover CTA          Aksen gradient sedikit lebih terang
  Mobile             Section ditumpuk vertikal; navigasi diringkas
  Reduced motion     Scroll dan transisi tanpa animasi non-esensial

------------------------------------------------------------------------

# 8. Login Page

## 8.1 Tujuan

Halaman login menjadi pintu autentikasi untuk pengguna yang akunnya
telah dibuat oleh pihak berwenang.

Tidak ada self-registration publik pada versi pertama.

## 8.2 Layout

Rekomendasi: - Dark background. - Panel login terfokus di tengah atau
komposisi dua kolom yang tetap sederhana. - Logo/wordmark PLC. - Judul
`Masuk ke Sistem`. - Input email dan password. - Tombol primary
`Masuk`. - Pesan validasi dan error yang jelas.

Jangan menambahkan social login, OTP, forgot password, atau invitation
flow sebagai fitur aktif sebelum requirement terkait ditetapkan.

## 8.3 Form Elements

  ---------------------------------------------------------------------
  Elemen                             Ketentuan
  ---------------------------------- ----------------------------------
  Email                              Input email dengan label jelas

  Password                           Input password dengan opsi
                                     show/hide

  Submit                             Tombol primary

  Loading                            Tombol menampilkan state loading
                                     saat request berjalan

  Validation                         Error ditampilkan dekat field
                                     terkait

  Authentication error               Pesan umum yang tidak membocorkan
                                     apakah akun tertentu terdaftar
  ---------------------------------------------------------------------

## 8.4 Authentication Behavior

-   Frontend mengirim request ke Laravel.
-   Session cookie dan CSRF mengikuti Laravel Sanctum.
-   Frontend tidak menyimpan JWT karena autentikasi JWT tidak digunakan.
-   Setelah berhasil login, pengguna diarahkan ke halaman internal
    sesuai implementasi akses.
-   Jika pengguna yang sudah login membuka halaman login, perilaku
    redirect mengikuti session aktif.
-   Error 401/422 ditampilkan dengan pesan yang sesuai.

------------------------------------------------------------------------

# 9. Authenticated Application Shell

## 9.1 Main Layout

Mengikuti referensi dashboard pertama: - Sidebar kiri. - Topbar. - Main
content. - Card/panel. - Table dan chart sesuai kebutuhan.

Dark-only berlaku pada seluruh halaman setelah login, termasuk modal,
dropdown, form, dan halaman administrasi.

## 9.2 Sidebar

Kelompok navigasi konseptual:

  -----------------------------------------------------------------------
  Group                   Menu                    Akses
  ----------------------- ----------------------- -----------------------
  Overview                Dashboard               Pengguna terautentikasi
                                                  sesuai kebijakan

  Program Kerja           Daftar Program Kerja    Sesuai permission dan
                                                  cakupan bidang

  Program Kerja           Draft Saya / Draft      Jika sesuai kewenangan
                          Bidang                  

  Evaluasi                Evaluasi dan Catatan    Pejabat Teras yang
                                                  berwenang

  Penilaian               Penilaian SAW           Pejabat Teras yang
                                                  berwenang

  Penilaian               Hasil Ranking           Pejabat Teras (termasuk
                                                  Ketua Umum); role lain
                                                  mengikuti PRD/SRS
                                                  TBD-015

  Konfigurasi SAW         Kriteria dan Bobot      Pengguna dengan
                                                  permission terkait;
                                                  pengelolaan bobot
                                                  memerlukan
                                                  `saw.weights.manage`

  Organisasi              Periode Kepengurusan    Super Admin

  Organisasi              Bidang                  Sesuai permission
                                                  administrasi yang
                                                  ditetapkan

  Organisasi              Keanggotaan             Super Admin

  Administrasi            Pengguna                Super Admin

  Administrasi            Role dan Permission     Super Admin sesuai
                                                  permission

  Akun                    Profil                  Pengguna aktif

  Akun                    Logout                  Pengguna aktif
  -----------------------------------------------------------------------

Catatan: - Daftar di atas adalah pengelompokan UI, bukan definisi
permission baru. - Menu hanya ditampilkan jika relevan dengan
permission/context pengguna. - Backend tetap wajib memvalidasi setiap
request. - Jika akses halaman tidak tersedia, jangan hanya
menyembunyikan menu; direct URL juga harus ditolak backend. - Menu dan
label final dapat disesuaikan setelah seluruh permission catalog
difinalisasi.

Khusus menu `Hasil Ranking`: - Pejabat Teras, termasuk Ketua Umum,
tercantum memiliki akses pada matriks kewenangan PRD. - Hak Super Admin,
Ketua Bidang, dan Pengurus Bidang masih TBD-015. - Sampai keputusan
tersebut dikunci, jangan tampilkan menu atau widget ranking kepada role
yang visibilitasnya belum ditetapkan. - Backend tetap menjadi penentu
akses; menyembunyikan menu bukan pengganti policy/permission.

## 9.3 Topbar

Komponen yang direkomendasikan: - Breadcrumb atau nama halaman. -
Konteks periode kepengurusan jika diperlukan. - Profil pengguna dan menu
akun.

Jangan menambahkan search global, notifikasi, AI assistant, atau theme
toggle sebagai elemen aktif tanpa requirement yang disetujui.

## 9.4 Content Header

Setiap halaman internal sebaiknya memiliki: - Page title. - Deskripsi
singkat jika dibutuhkan. - Breadcrumb jika hierarki halaman cukup
dalam. - Primary action jika pengguna memiliki permission. - Filter atau
konteks periode bila relevan.

## 9.5 Dashboard

Dashboard menggunakan komposisi referensi A: - Greeting dan judul
halaman. - KPI cards. - Chart/visual ringkasan jika data tersedia. -
Daftar program kerja terbaru atau yang membutuhkan tindakan. - Panel
aktivitas atau ringkasan status jika didukung requirement/data.

**Catatan penting:** PRD/SRS belum mengunci angka KPI dan seluruh widget
dashboard. Karena itu: - Jangan menampilkan metrik palsu atau
hardcoded. - Jangan menyalin metrik keuangan dari referensi. - Widget
harus menggunakan data aktual dari API. - Widget yang belum memiliki
sumber data tidak ditampilkan. - Susunan dashboard dapat disesuaikan per
role dan permission. - Pemilihan KPI final menjadi TBD sampai kebutuhan
dashboard per role disepakati.

### Rekomendasi konten dashboard per role (PROPOSED)

  ---------------------------------------------------------------------
  Role                               Fokus dashboard yang
                                     direkomendasikan
  ---------------------------------- ----------------------------------
  Super Admin                        Ringkasan administrasi pengguna,
                                     periode, dan organisasi sesuai
                                     data yang tersedia. Jangan
                                     tampilkan ranking sampai hak akses
                                     ditetapkan

  Pejabat Teras                      Program kerja yang menunggu
                                     evaluasi, penilaian yang belum
                                     lengkap, hasil ranking, dan
                                     keputusan terbaru

  Ketua Umum                         Mengikuti dashboard Pejabat Teras;
                                     konfigurasi bobot hanya tersedia
                                     jika memiliki `saw.weights.manage`

  Ketua Bidang                       Draft bidang, program kerja yang
                                     diajukan, dan program kerja yang
                                     perlu diperbaiki. Jangan tampilkan
                                     ranking sampai TBD-015 diputuskan

  Pengurus Bidang                    Draft yang dibuat dan status
                                     program kerja yang relevan. Jangan
                                     tampilkan ranking sampai TBD-015
                                     diputuskan
  ---------------------------------------------------------------------

Rekomendasi ini tidak membuat permission baru dan tidak mengubah proses
bisnis.

------------------------------------------------------------------------

# 10. Page-Level UI Specification

## 10.1 Daftar Program Kerja

Tujuan: membantu pengguna menemukan dan memantau program kerja sesuai
kewenangannya.

Komponen: - Judul dan deskripsi halaman. - Tombol buat draft jika
pengguna berwenang. - Filter periode. - Filter bidang jika relevan. -
Filter status. - Search berdasarkan nama program kerja jika tersedia. -
Tabel/list program kerja.

Kolom yang direkomendasikan: - Nama program kerja. - Bidang. -
Periode. - Waktu pelaksanaan. - PIC. - Anggaran. - Status. - Aksi.

Perilaku: - Data ditampilkan dari API. - Filter dan pagination mengikuti
API contract. - Aksi hanya ditampilkan sesuai permission dan status. -
Pada layar kecil, tabel dapat menggunakan horizontal scroll atau berubah
menjadi card list.

## 10.2 Form Draft Program Kerja

Mengacu pada atribut baseline program kerja: - Nama program kerja. -
Tujuan. - Deskripsi. - Waktu pelaksanaan. - Estimasi anggaran. -
Target. - Periode dan bidang mengikuti konteks/kewenangan. - PIC dapat
lebih dari satu.

Layout: - Pecah field menjadi kelompok informasi dasar, pelaksanaan, dan
penanggung jawab. - Label selalu terlihat; jangan hanya mengandalkan
placeholder. - Field wajib diberi penanda. - Pesan validasi tampil dekat
field. - Tombol simpan draft dan aksi pengajuan hanya ditampilkan sesuai
kewenangan/status.

Jangan menambahkan field bisnis baru sebagai mandatory tanpa revisi
SRS/data dictionary.

## 10.3 Detail Program Kerja

Tampilkan: - Informasi program kerja. - Status terkini. - Bidang dan
periode. - Daftar PIC. - Tujuan, deskripsi, waktu, anggaran, dan
target. - Catatan evaluasi yang dapat dilihat pengguna sesuai akses. -
Riwayat keputusan. - Aksi yang tersedia untuk pengguna dan status
tersebut.

Gunakan status badge dan timeline/riwayat untuk memperjelas perjalanan
program kerja. Jangan menyembunyikan keputusan lama jika pengguna
berwenang melihat riwayat.

## 10.4 Evaluasi dan Revisi

Untuk Pejabat Teras: - Daftar program kerja yang perlu dievaluasi. -
Detail program kerja. - Catatan evaluasi. - Aksi memberikan catatan
revisi sesuai permission.

Untuk bidang: - Daftar catatan revisi yang relevan. - Detail bagian
program kerja yang perlu diperbaiki. - Aksi memperbarui dan mengajukan
kembali jika diizinkan.

Pada desain v0.2, UI hanya menyediakan tampilan catatan dan aksi membuat
catatan revisi bagi Pejabat Teras yang berwenang. Tombol edit dan hapus
tidak ditampilkan sampai PRD/SRS `TBD-002` diputuskan. Pembatasan aksi
pada mockup ini bukan keputusan bahwa catatan bersifat append-only;
kebijakan bisnis tetap TBD dan API tidak boleh mengasumsikan operasi
update/delete.

## 10.5 Penilaian SAW

Tujuan: memungkinkan Pejabat Teras yang berwenang memberi nilai program
kerja berdasarkan lima kriteria.

Tampilan: - Informasi program kerja yang dinilai. - Daftar lima
kriteria. - Deskripsi/definisi kriteria bila tersedia. - Jenis kriteria
benefit/cost jika telah ditetapkan. - Input nilai dengan skala/format
yang sesuai kriteria. - Indikator kelengkapan penilaian. - Tombol simpan
penilaian.

Ketentuan: - Nilai dan batas input mengikuti skala final yang
disetujui. - Anggaran menggunakan nominal rupiah sesuai baseline
dokumen. - Jangan mengarang pilihan skala atau batas nilai yang belum
ditentukan. - Tampilkan penanda kriteria yang belum dinilai. - Tombol
hitung ranking tidak aktif jika data belum lengkap, dan backend tetap
melakukan validasi.

## 10.6 Konfigurasi Kriteria dan Bobot SAW

Tampilan: - Konteks periode. - Daftar lima kriteria. - Jenis
benefit/cost. - Bobot masing-masing kriteria. - Aksi simpan konfigurasi.

Akses: - Pengelolaan bobot memerlukan permission `saw.weights.manage`. -
Ketua Umum menggunakan role Pejabat Teras dengan permission tambahan
tersebut pada keanggotaan terkait. - Pejabat Teras tanpa permission
tidak boleh melihat atau menjalankan aksi perubahan bobot. - UI bukan
pengganti validasi backend.

TBD: - Aturan total bobot. - Apakah perubahan jenis kriteria
diperbolehkan setelah penilaian dimulai. - Riwayat perubahan konfigurasi
dan tampilan audit detail.

## 10.7 Hasil Ranking SAW

Tampilan: - Periode dan identitas proses perhitungan. - Waktu
perhitungan. - Tabel ranking. - Nama program kerja. - Nilai
preferensi. - Peringkat. - Informasi kriteria/bobot yang digunakan jika
snapshot tersedia. - Akses ke detail program kerja.

Gunakan tabel sebagai representasi utama agar hasil numerik mudah
dibandingkan. Chart dapat menjadi visual pendukung, bukan pengganti
tabel.

Ketentuan: - Ranking menggunakan hasil dari Laravel setelah Python
mengembalikan hasil dan Laravel menyimpan hasil yang tervalidasi. -
Jangan menghitung ulang ranking di frontend. - Jangan menampilkan hasil
palsu saat layanan Python gagal. - Penanganan nilai sama/tie mengikuti
aturan SAW yang telah difinalisasi.

## 10.8 Keputusan dan Riwayat

Tampilan: - Detail program kerja. - Hasil ranking sebagai bahan
pertimbangan jika tersedia. - Catatan revisi atau keputusan. - Jenis
keputusan: Revisi, Disetujui, atau Ditolak sesuai baseline. - Riwayat
keputusan dan waktu pencatatan.

Keputusan baru harus tampil sebagai entri riwayat, bukan mengganti
seluruh jejak keputusan sebelumnya.

## 10.9 Administrasi Pengguna

Untuk Super Admin: - Tabel pengguna. - Pencarian/filter jika didukung
API. - Tambah pengguna. - Edit data pengguna. - Nonaktifkan pengguna. -
Indikator status aktif/nonaktif.

Jangan menampilkan password. Alur reset password dan aktivasi akun belum
ditentukan.

## 10.10 Periode, Bidang, dan Keanggotaan

Halaman administrasi mengikuti pola tabel + form: - Periode: nama,
tanggal mulai, tanggal selesai, status aktif. - Bidang: data bidang
sesuai atribut final. - Keanggotaan: user, role, periode, bidang bila
relevan. - Permission override: daftar permission catalog dengan pilihan
INHERIT/GRANT/DENY.

Untuk permission override, gunakan label dan bantuan singkat agar Super
Admin memahami bahwa override berlaku pada keanggotaan/periode tersebut,
bukan otomatis pada seluruh akun User.

------------------------------------------------------------------------

# 11. Component Design System

## 11.1 Buttons

  -----------------------------------------------------------------------
  Variant                 Gaya                    Penggunaan
  ----------------------- ----------------------- -----------------------
  Primary                 Gradient red-orange     Aksi utama seperti
                          atau solid brand        Masuk, Simpan, Ajukan

  Secondary               Dark surface + border   Aksi sekunder

  Tertiary/Ghost          Transparan              Aksi ringan pada
                                                  toolbar

  Danger                  Semantic red            Tindakan berisiko yang
                                                  telah diizinkan

  Disabled                Kontras lebih rendah    Aksi tidak tersedia
  -----------------------------------------------------------------------

Ketentuan: - Label tombol menggunakan kata kerja yang jelas. - Setiap
aksi submit memiliki loading state. - Hindari beberapa tombol primary
dalam satu area tanpa hierarki yang jelas. - Tombol destructive
memerlukan konfirmasi jika dampaknya signifikan.

## 11.2 Cards

-   Dark surface.
-   Border 1 px low contrast.
-   Radius konsisten.
-   Padding 16--24 px.
-   Hover hanya untuk card yang benar-benar clickable.
-   Jangan memberi glow pada semua card.

Jenis: - KPI card. - Feature card. - Information card. - Chart card. -
Empty-state card.

## 11.3 Inputs

Komponen: - Text input. - Email input. - Password input. - Textarea. -
Number/currency input. - Date/time input. - Select. - Multi-select untuk
PIC jika diperlukan.

State: - Default. - Hover. - Focus. - Filled. - Disabled. - Invalid. -
Loading bila relevan.

Setiap input memiliki label, petunjuk bila perlu, dan pesan error yang
dapat dibaca.

## 11.4 Tables

-   Header jelas dan konsisten.
-   Row divider tipis.
-   Alignment angka konsisten.
-   Status menggunakan badge + label teks.
-   Aksi per baris diletakkan pada kolom yang konsisten.
-   Pagination dan empty state disediakan jika daftar dapat kosong.
-   Jangan memadatkan tabel sampai teks sulit dibaca.

## 11.5 Status Badges

Badge harus: - Menggunakan label status lengkap. - Memiliki warna
semantik. - Tetap terbaca pada dark background. - Tidak menggunakan
warna sebagai satu-satunya pembeda.

## 11.6 Modal and Drawer

-   Dark raised surface.
-   Judul dan tujuan jelas.
-   Tombol close mudah ditemukan.
-   Fokus keyboard tidak keluar dari modal saat modal aktif.
-   Form di dalam modal tidak terlalu panjang; form kompleks lebih baik
    memakai halaman penuh.

## 11.7 Charts

-   Chart hanya ditampilkan jika data aktual tersedia.
-   Gunakan orange/red untuk series utama dengan kontras memadai.
-   Gridline tipis dan label ringkas.
-   Tooltip harus membantu membaca angka.
-   Jangan menggunakan chart dekoratif dengan angka hardcoded.
-   Ranking SAW tetap menyediakan tabel sebagai sumber informasi utama.

## 11.8 Icons

-   Gunakan satu keluarga icon outline secara konsisten.
-   Icon harus memiliki makna yang mudah dikenali.
-   Icon dekoratif tidak menggantikan label pada tindakan penting.
-   Gunakan icon library yang telah tersedia pada repository; jangan
    menambahkan library baru sebelum audit.

------------------------------------------------------------------------

# 12. Interaction and Motion

## 12.1 Motion Direction

**LOCKED:** subtle motion.

Contoh: - Hover button: perubahan brightness/gradient ringan. - Hover
card clickable: border sedikit lebih terang. - Dropdown/modal: fade atau
scale ringan. - Section landing page: fade-in singkat jika tidak
mengganggu. - Smooth anchor scroll pada landing page.

Hindari: - Parallax berat. - Animasi scroll yang panjang. - Auto-playing
video. - Animasi berulang yang mengganggu. - Motion yang menghambat form
dan tabel.

## 12.2 Timing Recommendation

**PROPOSED:** - Hover transition: 120--180 ms. - Dropdown/modal
transition: 160--220 ms. - Section reveal: 200--300 ms.

## 12.3 Reduced Motion

Hormati `prefers-reduced-motion`. Jika pengguna memilih reduced
motion: - Matikan scroll animation yang tidak esensial. - Hilangkan
section reveal. - Pertahankan feedback visual yang diperlukan. - Jangan
menyampaikan informasi hanya melalui animasi.

------------------------------------------------------------------------

# 13. Responsive Behavior

Target ukuran layar final belum ditentukan sebagai keputusan tersendiri.
Rekomendasi awal adalah desktop-first karena aplikasi berisi dashboard,
tabel, administrasi, dan form organisasi, dengan adaptasi yang tetap
layak pada tablet dan mobile.

  ------------------------------------------------------------------------
  Area              Desktop           Tablet            Mobile
  ----------------- ----------------- ----------------- ------------------
  Landing           Menu horizontal   Menu diringkas    Menu
  navigation                                            compact/drawer

  Hero              Copy + preview    Copy dan preview  Satu kolom,
                    dashboard dominan disusun ulang     preview di bawah
                                                        copy

  Feature           3 kolom           2 kolom           1 kolom
  highlights                                            

  Sidebar           Persistent        Collapsible       Drawer

  KPI               4 kolom bila      2 kolom           2 kolom atau 1
                    cukup ruang                         jika sempit

  Dashboard cards   Multi-column      1--2 kolom        1 kolom

  Tables            Full table        Horizontal scroll Horizontal scroll
                                                        atau card list

  Forms             2 kolom jika      1--2 kolom        1 kolom
                    relevan                             

  Modal             Centered          Centered          Full-screen atau
                                                        near-full-screen
  ------------------------------------------------------------------------

Responsive tidak boleh menghilangkan informasi atau tindakan penting.
Jika tabel memakai horizontal scroll, pengguna harus dapat memahami
bahwa tabel dapat digeser.

------------------------------------------------------------------------

# 14. Accessibility and Usability

1.  Pastikan kontras teks dan background memadai.
2.  Semua input memiliki label.
3.  Semua tombol dapat diakses melalui keyboard.
4.  Focus ring terlihat pada dark background.
5.  Status tidak hanya dibedakan berdasarkan warna.
6.  Icon-only button memiliki accessible label.
7.  Error form menjelaskan masalah dan cara memperbaikinya.
8.  Ukuran target sentuh pada perangkat mobile memadai.
9.  Heading memiliki hierarki yang konsisten.
10. Hormati reduced motion.
11. Tabel memiliki header kolom yang jelas.
12. Jangan menggunakan teks abu-abu terlalu redup untuk informasi
    penting.

------------------------------------------------------------------------

# 15. UI States

Setiap halaman yang mengambil data dari backend harus mendesain state
berikut sesuai relevansi:

  ---------------------------------------------------------------------
  State                              Perilaku UI
  ---------------------------------- ----------------------------------
  Loading                            Skeleton atau indikator loading
                                     yang tidak mengubah layout secara
                                     drastis

  Success                            Konfirmasi singkat setelah aksi
                                     berhasil

  Empty                              Penjelasan bahwa belum ada data
                                     dan CTA jika pengguna berwenang
                                     membuat data

  Validation error                   Error dekat field terkait

  Unauthorized (401)                 Arahkan ke login atau tampilkan
                                     sesi berakhir

  Forbidden (403)                    Tampilkan akses tidak tersedia

  Not found (404)                    Tampilkan resource tidak ditemukan

  Conflict (409)                     Jelaskan konflik state/aturan
                                     bisnis

  Server error                       Pesan umum dan opsi mencoba
                                     kembali bila aman

  Upstream error                     Jelaskan layanan perhitungan tidak
                                     tersedia tanpa membocorkan detail
                                     teknis
  ---------------------------------------------------------------------

Untuk perhitungan SAW: - Tampilkan loading state selama request
synchronous berjalan. - Cegah double-submit selama request
berlangsung. - Jika Python gagal/timeout, tampilkan error yang jelas. -
Jangan menampilkan ranking sebagai hasil sukses jika Laravel belum
memvalidasi dan menyimpan hasil.

------------------------------------------------------------------------

# 16. Content and Microcopy

## 16.1 Bahasa

-   Bahasa utama UI: Bahasa Indonesia.
-   Istilah teknis yang sudah menjadi istilah produk dapat
    dipertahankan, misalnya Dashboard, SAW, PIC, dan Permission.
-   Gunakan kalimat pendek dan langsung.
-   Gunakan enam status baseline pada PRD/SRS v0.2: Draft, Diajukan,
    Revisi, Disetujui, Ditolak, Selesai.
-   `Dalam Evaluasi` masih berstatus TBD-001 dan belum menjadi status
    operasional. Jangan menambahkan badge, filter, enum, atau microcopy
    status tersebut sebelum keputusan dikunci.

## 16.2 Microcopy Examples

  ---------------------------------------------------------------------
  Kondisi                            Contoh copy
  ---------------------------------- ----------------------------------
  Login                              Masuk ke Sistem

  Empty program work                 Belum ada program kerja yang
                                     ditampilkan.

  Create draft                       Buat Draft Program Kerja

  Submit                             Ajukan Program Kerja

  Revision                           Program kerja memerlukan
                                     perbaikan. Periksa catatan
                                     evaluasi.

  SAW incomplete                     Penilaian belum lengkap. Lengkapi
                                     seluruh kriteria sebelum
                                     menghitung ranking.

  SAW loading                        Sedang menghitung prioritas
                                     program kerja...

  SAW failure                        Perhitungan belum berhasil. Data
                                     penilaian tetap tersimpan; silakan
                                     coba kembali setelah layanan
                                     tersedia.

  Unauthorized                       Sesi Anda telah berakhir. Silakan
                                     masuk kembali.

  Forbidden                          Anda tidak memiliki akses untuk
                                     melakukan tindakan ini.
  ---------------------------------------------------------------------

Copy final dapat disunting, tetapi jangan menjanjikan hasil yang belum
dikonfirmasi backend.

------------------------------------------------------------------------

# 17. Asset and Visual Guidelines

## 17.1 Logo

-   Gunakan logo resmi UKM PLC yang diberikan pemilik proyek.
-   Pertahankan proporsi, warna, dan clear space logo.
-   Jangan mengubah logo menjadi gradient atau memodifikasi bentuk tanpa
    persetujuan.
-   Jika asset logo belum tersedia dalam repository, gunakan wordmark
    teks sementara dan tandai sebagai placeholder.
-   Jangan mengganti logo UKM dengan logo institusi lain secara
    otomatis.

## 17.2 Dashboard Preview Asset

-   Siapkan preview khusus yang konsisten dengan dashboard PLC.
-   Gunakan data dummy yang jelas bersifat ilustratif.
-   Jangan memakai screenshot dashboard produk lain secara langsung.
-   Hindari menampilkan nama, email, atau data organisasi yang bersifat
    privat.
-   Pastikan preview tidak mengandung menu atau fitur yang tidak ada
    pada scope PLC.

## 17.3 Image and Decoration

-   Gunakan gradient glow sebagai aksen.
-   Hindari stock photo yang tidak memiliki hubungan dengan produk.
-   Gunakan icon dan visual abstrak seperlunya.
-   Landing page tidak membutuhkan video autoplay.
-   Dashboard internal mengutamakan data daripada ilustrasi.

------------------------------------------------------------------------

# 18. Page Inventory and Requirement Traceability

  -----------------------------------------------------------------------
  Page / UI               Sumber kebutuhan        Catatan
  ----------------------- ----------------------- -----------------------
  Public Landing Page     FR-PUB-01 s.d.          Public route tanpa
                          FR-PUB-05, UC-PUB-01    autentikasi; lima
                                                  section sesuai DD-001
                                                  s.d. DD-006

  Login                   FR-AUTH-01 s.d.         Sanctum session, tanpa
                          FR-AUTH-05, UC-01       JWT

  Dashboard               SRS Bagian 3.1 dan      Widget/KPI per role
                          laporan proyek Bagian   masih perlu
                          3.7.2                   difinalisasi

  Administrasi Pengguna   FR-ADM-01 s.d.          Super Admin
                          FR-ADM-04, UC-03        

  Periode dan Keanggotaan FR-ADM-05, FR-MEM-01    Super Admin
                          s.d. FR-MEM-05, UC-04   

  Role dan Permission     FR-ADM-06 s.d.          Permission catalog
                          FR-ADM-07, UC-05        tetap, override pada
                                                  keanggotaan

  Daftar/Detail Program   FR-PRO-01 s.d.          Cakupan data mengikuti
  Kerja                   FR-PRO-12               permission

  Form Draft/Pengajuan    FR-PRO-01 s.d.          Multiple PIC
                          FR-PRO-06, UC-06 s.d.   
                          UC-08                   

  Evaluasi/Revisi         FR-PRO-07 s.d.          Catatan mengikuti
                          FR-PRO-09, UC-09        aturan final

  Penilaian SAW           FR-SAW-04 s.d.          Validasi kelengkapan
                          FR-SAW-05, UC-11        

  Konfigurasi SAW         FR-SAW-01 s.d.          Bobot memerlukan
                          FR-SAW-03, UC-10        `saw.weights.manage`

  Ranking SAW             FR-SAW-06 s.d.          Hasil dari Laravel
                          FR-SAW-09, UC-12        setelah Python

  Keputusan dan Riwayat   FR-PRO-10 s.d.          Riwayat keputusan
                          FR-PRO-12, FR-DEC-01    dipertahankan
                          s.d. FR-DEC-03, UC-13   
                          s.d. UC-14              
  -----------------------------------------------------------------------

------------------------------------------------------------------------

# 19. Open Design Decisions

  -----------------------------------------------------------------------
  ID               Topik              Status           Catatan
  ---------------- ------------------ ---------------- ------------------
  UI-TBD-01        Nilai HEX final    Proposed         Review setelah
                   color tokens                        prototype

  UI-TBD-02        Font final dan     Proposed         Periksa
                   asset lokal                         asset/dependency
                                                       repository

  UI-TBD-03        Logo resmi PLC dan TBD              Memerlukan asset
                   format asset                        resmi

  UI-TBD-04        KPI dan widget     TBD              Harus didukung
                   final per role                      kebutuhan dan API

  UI-TBD-05        Dashboard default  TBD              Rekomendasi
                   untuk setiap role                   role-aware sudah
                                                       dicantumkan

  UI-TBD-06        Detail breakpoint  Proposed         Validasi saat
                   dan target minimum                  prototype
                   viewport                            

  UI-TBD-07        Bentuk menu mobile Proposed         Drawer/compact
                                                       menu

  UI-TBD-08        Exact copy landing Proposed         Copy awal
                   page                                disediakan

  UI-TBD-09        Asset preview      TBD              Perlu dibuat
                   dashboard                           setelah desain
                                                       dashboard
                                                       disetujui

  UI-TBD-10        Pemetaan warna     Proposed         Uji kontras dan
                   final tiap status                   konsistensi

  UI-TBD-11        Detail filter dan  TBD              Ikuti API dan
                   kolom tabel per                     kebutuhan tiap
                   halaman                             modul

  UI-TBD-12        Widget aktivitas   TBD              Jangan diasumsikan
                   dashboard                           sebelum ada
                                                       requirement
  -----------------------------------------------------------------------

Catatan dependency keputusan desain yang masih terbuka:

-   UI-TBD-13 --- Representasi status `Dalam Evaluasi` bergantung pada
    PRD/SRS `TBD-001`.
-   UI-TBD-14 --- Visibilitas ranking per role mengikuti PRD/SRS
    `TBD-015`.
-   UI-TBD-15 --- Aksi edit/hapus catatan evaluasi mengikuti PRD/SRS
    `TBD-002`.

------------------------------------------------------------------------

# 20. UI Acceptance Criteria

Desain dan implementasi UI dianggap memenuhi DESIGN.md apabila:

## 20.1 Visual Consistency

-   Semua halaman menggunakan dark-only theme.
-   Warna dasar, surface, teks, border, dan aksen konsisten.
-   Red-orange gradient digunakan selektif.
-   Heading, body, caption, spacing, dan radius mengikuti sistem yang
    sama.
-   Tidak ada light theme atau theme toggle.

## 20.2 Landing Page

-   Landing page dapat dibuka tanpa login.
-   Landing page singkat dan hanya memiliki navigation, hero, feature
    highlights, CTA, dan footer.
-   Hero menggunakan preview dashboard sebagai visual utama.
-   Navigasi section menggunakan anchor scroll.
-   CTA Masuk mengarah ke halaman login.
-   Tidak ada self-registration publik.
-   Konten tidak menampilkan data privat atau angka ilustratif
    seolah-olah data aktual.

## 20.3 Authenticated Application

-   Layout menggunakan sidebar, topbar, dan main content.
-   Dashboard menggunakan komposisi dark dashboard.
-   Konten dashboard berasal dari data aktual.
-   Menu dan aksi mengikuti permission dari backend.
-   Pengguna tidak dapat mengakses tindakan terlarang hanya dengan
    mengetik URL langsung.
-   Halaman program kerja, evaluasi, SAW, keputusan, dan administrasi
    konsisten.

## 20.4 Interaction

-   Animasi subtle dan tidak mengganggu.
-   Loading, empty, error, success, dan disabled states tersedia sesuai
    kebutuhan.
-   Form menampilkan validasi dengan jelas.
-   Status selalu memiliki label teks.
-   UI hanya menampilkan enam status baseline sampai PRD/SRS `TBD-001`
    diputuskan; `Dalam Evaluasi` belum menjadi status operasional.
-   Menu/widget ranking hanya tampil untuk role yang aksesnya telah
    ditetapkan; Pejabat Teras termasuk Ketua Umum memiliki akses
    baseline, sedangkan role lain mengikuti `TBD-015`.
-   UI tidak menyediakan edit/hapus catatan sebelum kebijakan `TBD-002`
    diputuskan.
-   Tabel dan form tetap usable pada ukuran layar yang lebih kecil.
-   Reduced motion dihormati.

## 20.5 Technical Alignment

-   UI menggunakan React + TypeScript sesuai ARCHITECTURE.
-   Data dinamis diambil dari Laravel REST API.
-   Autentikasi mengikuti Sanctum cookie-based session.
-   Frontend tidak mengimplementasikan authorization sebagai
    satu-satunya perlindungan.
-   Frontend tidak menghitung SAW sebagai sumber hasil resmi.
-   Same-origin deployment tetap dipertahankan.

------------------------------------------------------------------------

# 21. Instruksi untuk AI Coding Agent

Sebelum implementasi UI:

1.  Audit repository dan struktur React + TypeScript aktual.
2.  Periksa asset logo, font, icon, dan dependency yang sudah tersedia.
3.  Periksa route, layout, component, API client, dan pola styling yang
    sudah ada.
4.  Cocokkan halaman dengan PRD, SRS, dan ARCHITECTURE.
5.  Laporkan komponen yang sudah tersedia dan yang perlu dibuat.
6.  Jangan mengganti stack, routing, state management, atau styling
    framework tanpa alasan dan persetujuan.
7.  Jangan menambah package hanya untuk mengikuti contoh desain.
8.  Jangan membuat data dummy seolah-olah data production.
9.  Jangan menampilkan menu atau tombol yang tidak sesuai permission.
10. Jangan menambahkan fitur yang hanya terlihat pada referensi tetapi
    tidak termasuk scope PLC.
11. Implementasikan halaman dan komponen secara bertahap melalui patch
    terfokus.
12. Sertakan screenshot atau hasil visual setelah implementasi jika
    lingkungan memungkinkan.
13. Jalankan test/build yang relevan dan laporkan hasil sebenarnya.

## 21.1 Format Review UI

Setiap tahap UI perlu ditinjau menggunakan: - Kesesuaian dengan
DESIGN.md. - Kesesuaian konten dengan SRS. - Kesesuaian akses dengan
permission. - Konsistensi komponen. - Responsiveness. - Accessibility. -
Loading/error/empty states. - Tidak adanya fitur atau data yang dibuat
berdasarkan asumsi.

------------------------------------------------------------------------

**Akhir Dokumen --- DESIGN v0.2**

Status: Draft spesifikasi UI/UX v0.2 dengan enam keputusan visual utama
terkunci dan landing page telah diturunkan ke PRD/SRS. Hak ranking per
role (`TBD-015`), status `Dalam Evaluasi` (`TBD-001`), dan edit/hapus
catatan (`TBD-002`) tetap mengikuti keputusan bisnis yang belum
ditetapkan. Token warna rinci, font, logo, KPI dashboard, dan beberapa
detail responsive tetap Proposed/TBD sampai direview melalui prototype
dan audit repository.
