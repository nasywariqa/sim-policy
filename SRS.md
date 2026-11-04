# SOFTWARE REQUIREMENTS SPECIFICATION (SRS)

## Sistem Informasi Manajemen UKM Polytechnic Linux Community

  ---------------------------------------------------------------------
  Informasi                          Keterangan
  ---------------------------------- ----------------------------------
  Nama sistem                        Sistem Informasi Manajemen UKM
                                     Polytechnic Linux Community

  Dokumen                            Software Requirements
                                     Specification (SRS)

  Versi                              0.2

  Status                             Draft revisi --- diturunkan dari
                                     PRD v0.2, laporan proyek, dan
                                     keputusan desain yang dikunci

  Tanggal                            4 November 2026

  Dokumen induk                      `PRD.md`

  Dokumen sumber                     `LAPORAN PROYEK RPL.docx`
  ---------------------------------------------------------------------

## Riwayat Revisi

  -----------------------------------------------------------------------
  Versi                   Tanggal                 Perubahan
  ----------------------- ----------------------- -----------------------
  0.1                     4 November 2026         Baseline requirement
                                                  awal dari PRD v0.1 dan
                                                  laporan proyek.

  0.2                     4 November 2026         Menambahkan requirement
                                                  landing page publik,
                                                  use case pengunjung,
                                                  serta memperjelas
                                                  dependency TBD untuk
                                                  ranking, status, dan
                                                  catatan evaluasi.
  -----------------------------------------------------------------------

------------------------------------------------------------------------

> **Aturan pembacaan dokumen:** SRS ini harus dibaca bersama `PRD.md`.
> Status **LOCKED** berarti keputusan telah disetujui. Status
> **BASELINE** berarti informasi berasal dari laporan proyek. Status
> **PROPOSED** berarti rancangan yang belum dikonfirmasi. Status **TBD**
> berarti keputusan belum tersedia. AI coding agent tidak boleh mengubah
> PROPOSED atau TBD menjadi requirement final tanpa persetujuan pemilik
> proyek.

------------------------------------------------------------------------

# 1. Pendahuluan

## 1.1 Tujuan Dokumen

Dokumen SRS ini menjelaskan kebutuhan perangkat lunak Sistem Informasi
Manajemen UKM Polytechnic Linux Community secara lebih terstruktur dan
dapat diuji. SRS menjadi acuan bagi proses analisis, perancangan,
implementasi, pengujian, dan evaluasi sistem.

SRS menurunkan kebutuhan produk dari PRD dan laporan proyek. SRS tidak
menggantikan keputusan dalam PRD. Apabila terdapat perbedaan, keputusan
terbaru yang telah disetujui dan dicatat pada PRD menjadi acuan.

## 1.2 Ruang Lingkup Sistem

Sistem merupakan aplikasi web untuk mendukung: 1. Autentikasi pengguna.
2. Pengelolaan pengguna, role, permission, dan periode kepengurusan. 3.
Pengelolaan bidang dan keanggotaan. 4. Pengelolaan program kerja mulai
dari draft, pengajuan, evaluasi, revisi, keputusan, hingga penyelesaian.
5. Pengelolaan PIC program kerja. 6. Pengelolaan kriteria dan bobot SAW
per periode. 7. Pengisian penilaian program kerja. 8. Perhitungan dan
pemeringkatan prioritas menggunakan layanan Python. 9. Penyimpanan
riwayat perhitungan SAW dan keputusan program kerja.

## 1.3 Tujuan Sistem

Sistem diharapkan: - Membuat pengelolaan program kerja lebih
terstruktur. - Mendukung evaluasi dan penilaian program kerja
menggunakan kriteria yang ditentukan. - Menghasilkan rekomendasi ranking
prioritas program kerja melalui metode SAW. - Menyediakan jejak riwayat
keputusan dan perhitungan. - Membatasi akses sesuai role, permission,
keanggotaan, dan periode kepengurusan.

## 1.4 Definisi dan Istilah

  ---------------------------------------------------------------------
  Istilah                            Definisi
  ---------------------------------- ----------------------------------
  User                               Akun pengguna untuk autentikasi.

  Role                               Jabatan/peran yang menentukan
                                     permission default.

  Permission                         Hak untuk melakukan tindakan
                                     tertentu di sistem.

  Keanggotaan                        Hubungan User dengan role dan
                                     periode kepengurusan, serta bidang
                                     jika relevan.

  Periode Kepengurusan               Rentang waktu berlakunya struktur
                                     organisasi.

  Bidang                             Unit organisasi yang memiliki
                                     program kerja dan anggota.

  Program Kerja                      Kegiatan/program yang diusulkan
                                     oleh bidang.

  PIC                                Penanggung jawab program kerja
                                     yang direferensikan melalui
                                     Keanggotaan.

  SAW                                Simple Additive Weighting, metode
                                     untuk menghitung nilai preferensi
                                     dan ranking alternatif.

  Alternatif                         Program kerja yang dinilai dalam
                                     proses SAW.

  Benefit                            Kriteria yang nilai lebih besarnya
                                     dianggap lebih baik.

  Cost                               Kriteria yang nilai lebih kecilnya
                                     dianggap lebih baik.

  SPA                                Single Page Application.

  Same-origin                        Frontend dan backend aplikasi
                                     utama diakses melalui
                                     origin/domain yang sama.
  ---------------------------------------------------------------------

## 1.5 Referensi

1.  `PRD.md` --- Product Requirements Document versi 0.1.
2.  `LAPORAN PROYEK RPL.docx`, khususnya:
    -   BAB I: latar belakang, rumusan masalah, batasan, tujuan, dan
        manfaat.
    -   BAB III bagian 3.1: arsitektur sistem.
    -   Bagian 3.2: use case diagram.
    -   Bagian 3.3: activity diagram.
    -   Bagian 3.4: sequence diagram.
    -   Bagian 3.5: class diagram.
    -   Bagian 3.6: tabel relasional.

------------------------------------------------------------------------

# 2. Deskripsi Umum

## 2.1 Perspektif Produk

Sistem terdiri atas: - Frontend React + TypeScript yang berjalan di
browser. - Backend Laravel sebagai pusat business logic dan REST API. -
Database MySQL sebagai baseline penyimpanan data utama dari laporan
proyek. - Layanan Python yang menerima permintaan perhitungan SAW dari
Laravel.

Frontend dan backend utama berada dalam satu repository (monorepo) dan
menggunakan same-origin deployment. Browser tidak mengakses database
atau layanan Python secara langsung.

## 2.2 Fungsi Produk

Fungsi utama sistem meliputi: 1. Login dan logout. 2. Penyajian
informasi pengguna dan hak akses. 3. Administrasi pengguna dan periode.
4. Pengelolaan bidang dan keanggotaan. 5. Pengelolaan program kerja. 6.
Penugasan satu atau beberapa PIC. 7. Evaluasi, catatan, dan revisi
program kerja. 8. Pengaturan kriteria dan bobot SAW. 9. Penilaian
program kerja. 10. Perhitungan SAW dan penyajian ranking. 11. Pencatatan
keputusan program kerja. 12. Pencatatan penyelesaian program kerja.

## 2.3 Karakteristik Pengguna

  ---------------------------------------------------------------------
  Pengguna                           Karakteristik dan tanggung jawab
  ---------------------------------- ----------------------------------
  Super Admin                        Mengelola administrasi sistem
                                     secara global; tidak terikat
                                     periode kepengurusan.

  Pejabat Teras                      Melakukan evaluasi, penilaian,
                                     melihat hasil ranking, dan
                                     terlibat dalam keputusan program
                                     kerja sesuai permission.

  Ketua Umum                         Termasuk Pejabat Teras; memiliki
                                     permission tambahan
                                     `saw.weights.manage` pada
                                     keanggotaan terkait.

  Ketua Bidang                       Meninjau draft dan mengajukan
                                     program kerja bidang sesuai
                                     kewenangan.

  Pengurus Bidang                    Menyusun dan mengelola draft
                                     program kerja sesuai kewenangan.
  ---------------------------------------------------------------------

Ketua Umum bukan role terpisah. Ketua Umum menggunakan role Pejabat
Teras dan memperoleh permission tambahan yang relevan.

## 2.4 Lingkungan Operasi

  ---------------------------------------------------------------------
  Komponen                           Ketentuan
  ---------------------------------- ----------------------------------
  Client                             Browser web pada perangkat
                                     pengguna.

  Frontend                           React + TypeScript.

  Backend                            Laravel.

  Database                           MySQL sebagai baseline dokumen;
                                     versi dan konfigurasi harus
                                     diverifikasi dari repository.

  SAW service                        Python melalui REST API internal.

  Deployment                         Same-origin untuk frontend dan
                                     backend aplikasi utama.

  Repository                         Monorepo.
  ---------------------------------------------------------------------

Versi Laravel, PHP, Node.js, React, TypeScript, Vite, MySQL, dan Python
tidak ditentukan dalam SRS ini. Versi aktual harus mengikuti hasil audit
repository dan keputusan implementasi.

## 2.5 Batasan Desain dan Implementasi

1.  Laravel menjadi sumber kebenaran untuk validasi, business logic, dan
    authorization.
2.  React tidak boleh menjadi satu-satunya lapisan pembatasan hak akses.
3.  Browser tidak boleh mengakses MySQL secara langsung.
4.  Browser tidak boleh memanggil Python SAW Service secara langsung.
5.  Komunikasi frontend-backend menggunakan REST API berformat JSON.
6.  Autentikasi menggunakan Laravel Sanctum dengan cookie-based session,
    bukan JWT.
7.  Perhitungan SAW dilakukan secara synchronous melalui request Laravel
    ke Python.
8.  Permission catalog ditentukan developer dan tidak dibuat bebas
    melalui dashboard.
9.  Riwayat keputusan dan riwayat perhitungan SAW dipertahankan.

## 2.6 Asumsi dan Ketergantungan

-   Pengguna telah dibuat atau didaftarkan oleh pihak yang berwenang;
    self-registration publik belum menjadi kebutuhan.
-   Data periode, bidang, role, dan pengguna tersedia sebelum aktivitas
    organisasi terkait dilakukan.
-   Layanan Python tersedia ketika perhitungan SAW diminta.
-   Kontrak request/response Python harus disepakati sebelum integrasi
    diimplementasikan.
-   Kebutuhan rinci yang belum diputuskan dicatat pada Bagian 10.

------------------------------------------------------------------------

# 3. Kebutuhan Antarmuka Eksternal

## 3.1 Antarmuka Pengguna

Antarmuka web harus menyediakan tampilan yang sesuai dengan role dan
permission pengguna. Pengguna hanya ditampilkan menu dan tindakan yang
relevan, tetapi semua tindakan tetap harus diverifikasi oleh backend.

Daftar halaman dan spesifikasi visual rinci ditetapkan dalam
`DESIGN.md`, bukan ditentukan sepenuhnya dalam SRS ini.

Halaman fungsional yang diperlukan berdasarkan ruang lingkup: - Public
Landing Page (dapat diakses tanpa autentikasi). - Login. - Dashboard. -
Administrasi pengguna. - Pengelolaan periode kepengurusan. - Pengelolaan
bidang dan keanggotaan. - Daftar dan detail program kerja. - Form
draft/pengajuan program kerja. - Evaluasi dan catatan program kerja. -
Penilaian SAW. - Pengaturan kriteria dan bobot SAW. - Hasil ranking. -
Riwayat keputusan dan penyelesaian program kerja.

Keberadaan halaman tersebut tidak berarti setiap role otomatis memiliki
akses. Hak akses mengikuti matriks permission yang ditetapkan di
backend.

## 3.2 Antarmuka Perangkat Lunak --- Laravel API

  ---------------------------------------------------------------------
  Aspek                              Ketentuan
  ---------------------------------- ----------------------------------
  Gaya komunikasi                    REST API

  Prefix                             `/api/v1`

  Format data                        JSON

  Authentication                     Sanctum cookie-based session

  Validasi                           Backend Laravel

  Authorization                      Middleware, permission, dan policy

  Response                           Konsisten melalui API
                                     Resource/standar response yang
                                     disepakati

  Error                              Menggunakan HTTP status code yang
                                     sesuai dan format error konsisten
  ---------------------------------------------------------------------

Endpoint autentikasi yang direncanakan: - `GET /sanctum/csrf-cookie` -
`POST /login` - `POST /logout` - `GET /api/v1/me`

Endpoint di atas adalah rancangan konseptual. Implementasi harus
memeriksa konfigurasi dan route yang sudah tersedia pada repository
sebelum menambah atau mengubah endpoint.

Kelompok resource API yang direncanakan: - Users. - Periods. -
Departments/Bidang. - Memberships. - Program Works. - Program Work
Notes. - Program Work Decisions. - SAW Criteria. - SAW Assessments. -
SAW Results/Runs.

Nama endpoint, payload, pagination, filter, dan format error secara
rinci perlu ditetapkan dalam API contract.

## 3.3 Antarmuka Perangkat Lunak --- Python SAW Service

Laravel berkomunikasi dengan Python melalui HTTP REST API secara
synchronous.

Data yang secara konseptual dibutuhkan untuk perhitungan: - Identitas
proses atau referensi proses. - Alternatif/program kerja. - Identitas
dan jenis kriteria. - Bobot kriteria. - Nilai setiap alternatif untuk
setiap kriteria.

Python mengembalikan hasil perhitungan yang setidaknya mencakup nilai
preferensi dan peringkat setiap alternatif.

**TBD:** URL endpoint, metode HTTP final, autentikasi layanan, skema
JSON, presisi desimal, struktur error, timeout, dan versi kontrak.

## 3.4 Antarmuka Komunikasi

-   Komunikasi browser dengan aplikasi menggunakan HTTPS pada deployment
    production.
-   Komunikasi frontend-backend mengikuti same-origin.
-   Komunikasi Laravel-Python dilakukan melalui jalur layanan internal
    yang dikonfigurasi melalui environment.
-   Secret dan kredensial tidak boleh ditanam langsung dalam source
    code.

------------------------------------------------------------------------

# 4. Kebutuhan Fungsional

Setiap requirement memiliki ID unik. Prioritas `Must` menunjukkan
kebutuhan inti sesuai PRD. Kebutuhan yang berstatus TBD tidak boleh
diimplementasikan dengan asumsi yang tidak disetujui.

## Public Landing Page

### FR-PUB-01 --- Akses Landing Page Publik

**Prioritas:** Must

Sistem harus menyediakan landing page yang dapat diakses pengunjung
tanpa session autentikasi.

**Kriteria penerimaan:** - Pengunjung yang belum login dapat membuka
route landing page. - Route publik tidak mewajibkan session Sanctum. -
Halaman publik tidak memberikan akses ke resource privat.

### FR-PUB-02 --- Konten Landing Page

**Prioritas:** Must

Landing page menampilkan lima bagian: navigation, hero, feature
highlights, closing CTA, dan footer. Konten memperkenalkan sistem secara
singkat dan tidak menampilkan data privat.

### FR-PUB-03 --- Navigasi Section

**Prioritas:** Must

Item navigasi yang menunjuk section landing page mengarahkan pengguna
melalui anchor/scroll pada halaman yang sama.

### FR-PUB-04 --- CTA Login

**Prioritas:** Must

CTA `Masuk` mengarahkan pengunjung ke halaman login. Setelah autentikasi
berhasil, alur dilanjutkan sesuai FR-AUTH-01.

### FR-PUB-05 --- Batasan Akses Publik

**Prioritas:** Must

Landing page tidak menyediakan self-registration, pengajuan program
kerja, akses dashboard internal, atau akses data organisasi yang
memerlukan autentikasi dan authorization.

------------------------------------------------------------------------

## 4.1 Autentikasi

### FR-AUTH-01 --- Login

**Prioritas:** Must

Sistem harus memungkinkan pengguna yang terdaftar untuk masuk
menggunakan kredensial yang valid.

**Alur utama:** 1. Pengguna membuka halaman login. 2. Pengguna mengisi
kredensial. 3. Frontend mengirim permintaan login ke Laravel. 4. Laravel
memvalidasi kredensial. 5. Jika valid, Laravel membuat session
autentikasi. 6. Sistem mengembalikan hasil login dan informasi yang
diperlukan frontend. 7. Pengguna diarahkan ke area aplikasi sesuai hak
akses.

**Alur alternatif/kegagalan:** - Jika kredensial tidak valid, sistem
menolak login dan menampilkan pesan yang sesuai. - Jika input tidak
lengkap, Laravel mengembalikan kesalahan validasi. - Sistem tidak boleh
mengungkap apakah email tertentu terdaftar melalui pesan error yang
tidak aman.

**Kriteria penerimaan:** - Kredensial valid menghasilkan session
aktif. - Kredensial tidak valid tidak menghasilkan session
terautentikasi. - Password tidak pernah dikembalikan dalam response.

### FR-AUTH-02 --- Logout

**Prioritas:** Must

Sistem harus memungkinkan pengguna mengakhiri session.

**Alur utama:** 1. Pengguna memilih logout. 2. Frontend mengirim
permintaan logout. 3. Laravel mengakhiri session dan melakukan
invalidasi session sesuai mekanisme framework. 4. Pengguna kembali ke
halaman login.

**Kriteria penerimaan:** - Session tidak dapat digunakan kembali setelah
logout. - Request privat berikutnya ditolak jika tidak ada autentikasi
baru.

### FR-AUTH-03 --- Informasi Pengguna Aktif

**Prioritas:** Must

Sistem harus menyediakan endpoint untuk mengambil informasi pengguna
yang sedang terautentikasi beserta konteks role/permission yang
diperlukan frontend.

**Kriteria penerimaan:** - Pengguna terautentikasi memperoleh informasi
yang relevan. - Pengguna yang belum terautentikasi ditolak. - Response
tidak menyertakan password, secret, atau data sensitif yang tidak
diperlukan.

### FR-AUTH-04 --- Perlindungan Endpoint Privat

**Prioritas:** Must

Setiap endpoint yang membutuhkan login harus menolak request tanpa
session autentikasi yang valid.

### FR-AUTH-05 --- Perlindungan CSRF dan Session

**Prioritas:** Must

Implementasi Sanctum SPA harus mengikuti mekanisme CSRF, cookie, dan
session yang sesuai dengan same-origin deployment.

------------------------------------------------------------------------

## 4.2 Administrasi Sistem

### FR-ADM-01 --- Melihat Pengguna

**Prioritas:** Must

Super Admin dapat melihat daftar pengguna sesuai kebutuhan administrasi.

**Kriteria penerimaan:** - Endpoint hanya dapat diakses oleh pengguna
dengan permission administrasi. - Data pengguna ditampilkan tanpa
password atau secret.

### FR-ADM-02 --- Menambah Pengguna

**Prioritas:** Must

Super Admin dapat membuat akun pengguna.

**Kriteria penerimaan:** - Data yang diwajibkan oleh skema pengguna
divalidasi backend. - Email atau identitas login yang diwajibkan harus
mengikuti constraint unik. - Password disimpan dalam bentuk hash. -
Detail field wajib dan mekanisme pemberian password masih mengikuti
keputusan implementasi yang perlu difinalisasi.

### FR-ADM-03 --- Memperbarui Pengguna

**Prioritas:** Must

Super Admin dapat memperbarui data pengguna sesuai field yang diizinkan.

### FR-ADM-04 --- Menonaktifkan Pengguna

**Prioritas:** Must

Super Admin dapat menonaktifkan akun pengguna.

**Kriteria penerimaan:** - Akun nonaktif tidak dapat melakukan
autentikasi baru. - Perilaku session yang sudah aktif ketika akun
dinonaktifkan harus diverifikasi dan ditangani. - Mekanisme penyimpanan
status aktif/nonaktif harus ditetapkan dalam data design.

### FR-ADM-05 --- Mengelola Periode Kepengurusan

**Prioritas:** Must

Super Admin dapat mengelola data periode kepengurusan.

Data baseline periode: - Nama periode. - Tanggal mulai. - Tanggal
selesai. - Status aktif.

**Kriteria penerimaan:** - Tanggal mulai dan selesai divalidasi. -
Sistem menyimpan status periode. - Aturan jumlah periode aktif secara
bersamaan masih TBD.

### FR-ADM-06 --- Role dan Permission Catalog

**Prioritas:** Must

Sistem harus menggunakan role dan permission catalog yang ditentukan
developer.

**Kriteria penerimaan:** - Super Admin tidak dapat membuat permission
baru dari dashboard. - Role memiliki permission default. - Permission
diperiksa pada backend. - Perubahan katalog permission memerlukan
perubahan implementasi dan pengujian.

### FR-ADM-07 --- Permission Override Keanggotaan

**Prioritas:** Must

Super Admin dapat memberikan atau mencabut permission yang tersedia pada
keanggotaan tertentu melalui mekanisme override.

Keadaan override: - `INHERIT`: mengikuti default role. - `GRANT`:
memberikan permission. - `DENY`: menolak permission default role.

**Kriteria penerimaan:** - Override berlaku pada keanggotaan yang
ditentukan. - Override tidak otomatis diwariskan ke keanggotaan periode
baru. - Permission yang tidak tercantum dalam katalog ditolak. -
Keputusan override keanggotaan diprioritaskan terhadap default role. -
Hak global Super Admin tidak dicampur dengan permission keanggotaan.

### FR-ADM-08 --- Data Bidang

**Prioritas:** Must

Sistem harus menyediakan data bidang sebagai referensi bagi keanggotaan
dan program kerja.

**TBD:** aktor yang boleh membuat, mengubah, mengarsipkan, atau
menghapus bidang.

------------------------------------------------------------------------

## 4.3 Periode dan Keanggotaan

### FR-MEM-01 --- Data Keanggotaan

Sistem harus menyimpan hubungan User, Role, Periode Kepengurusan, dan
Bidang bila relevan.

### FR-MEM-02 --- Batas Satu Keanggotaan per Periode

**Keputusan LOCKED:** satu User hanya boleh memiliki satu Keanggotaan
dalam satu Periode Kepengurusan.

**Kriteria penerimaan:** - Kombinasi `user_id` dan `periode_id` harus
unik. - Pembuatan keanggotaan kedua untuk user dan periode yang sama
ditolak. - Aturan pergantian role di tengah periode masih TBD.

### FR-MEM-03 --- Bidang pada Keanggotaan

Keanggotaan Ketua Bidang dan Pengurus Bidang harus memiliki bidang.
Keanggotaan Pejabat Teras tidak wajib memiliki bidang.

### FR-MEM-04 --- Super Admin Global

Super Admin merupakan role global pada User dan tidak diwajibkan
memiliki Keanggotaan pada periode.

### FR-MEM-05 --- Batas Periode Permission

Permission khusus yang melekat pada Keanggotaan hanya berlaku dalam
konteks keanggotaan tersebut dan tidak otomatis berpindah ke periode
berikutnya.

------------------------------------------------------------------------

## 4.4 Pengelolaan Program Kerja

### FR-PRO-01 --- Membuat Draft Program Kerja

**Prioritas:** Must

Pengguna bidang yang memiliki permission dapat membuat draft program
kerja.

Atribut baseline: - Nama program kerja. - Tujuan. - Deskripsi. - Waktu
pelaksanaan. - Estimasi anggaran. - Target. - Periode. - Bidang pemilik.

**Kriteria penerimaan:** - Data disimpan dengan status `Draft`. -
Program kerja terhubung dengan periode dan bidang yang sesuai. - Backend
memeriksa permission pembuat. - Field wajib dan batas panjang/nilai
harus mengikuti data dictionary final.

### FR-PRO-02 --- Melihat Program Kerja

Pengguna dapat melihat daftar/detail program kerja sesuai hak akses dan
konteks organisasi.

**TBD:** cakupan visibilitas masing-masing role terhadap program kerja
lintas bidang.

### FR-PRO-03 --- Memperbarui Draft

Pengguna yang berwenang dapat memperbarui draft program kerja.

**Kriteria penerimaan:** - Backend memeriksa status program kerja. -
Backend memeriksa kewenangan pengguna terhadap program kerja. -
Perubahan tidak boleh melewati aturan status yang ditetapkan.

### FR-PRO-04 --- Meninjau Draft oleh Ketua Bidang

Ketua Bidang dapat meninjau draft program kerja yang berada dalam
tanggung jawab bidangnya.

### FR-PRO-05 --- Mengajukan Program Kerja

Ketua Bidang dapat mengajukan program kerja yang telah ditinjau.

**Alur utama:** 1. Ketua Bidang membuka draft. 2. Sistem menampilkan
detail draft. 3. Ketua Bidang mengirim permintaan pengajuan. 4. Laravel
memvalidasi kelengkapan dan kewenangan. 5. Status program kerja berubah
menjadi `Diajukan`. 6. Sistem menampilkan hasil pengajuan.

**Alur alternatif:** - Jika data tidak valid, sistem menolak pengajuan
dan menampilkan kesalahan. - Jika pengguna tidak berwenang, sistem
menolak request. - Jika status tidak memungkinkan pengajuan, sistem
menolak perubahan.

### FR-PRO-06 --- Multiple PIC

**Keputusan LOCKED:** satu program kerja dapat memiliki lebih dari satu
PIC.

**Kriteria penerimaan:** - PIC direferensikan melalui
`keanggotaan_id`. - Kombinasi program kerja dan keanggotaan PIC tidak
boleh duplikat. - PIC harus merupakan keanggotaan yang valid. - Aturan
lintas bidang dan validasi periode final masih perlu dikonfirmasi
sebelum dianggap lengkap.

### FR-PRO-07 --- Evaluasi Program Kerja

Pejabat Teras yang memiliki permission dapat mengevaluasi program kerja
berstatus `Diajukan`.

Sistem harus menampilkan informasi program kerja yang diperlukan untuk
evaluasi.

### FR-PRO-08 --- Memberikan Catatan Revisi

Pejabat Teras yang berwenang dapat memberikan catatan revisi pada
program kerja.

**Kriteria penerimaan:** - Catatan terhubung dengan program kerja. -
Catatan menyimpan referensi keanggotaan pembuat. - Waktu pembuatan
catatan disimpan. - Kebijakan edit/hapus catatan masih TBD-002. -
Sebelum TBD-002 diputuskan, UI dan API tidak boleh mengasumsikan operasi
edit atau hapus.

### FR-PRO-09 --- Memperbaiki Program Kerja

Bidang dapat memperbaiki program kerja yang berstatus `Revisi` sesuai
kewenangan.

Setelah diperbaiki, Ketua Bidang dapat mengajukan kembali program kerja.

### FR-PRO-10 --- Riwayat Keputusan

**Keputusan LOCKED:** sistem harus menyimpan seluruh riwayat keputusan
program kerja.

Data keputusan baseline: - Program kerja. - Jenis keputusan. -
Catatan. - Keanggotaan yang mencatat keputusan. - Waktu keputusan.

Keputusan sebelumnya tidak boleh ditimpa ketika keputusan baru dicatat.

### FR-PRO-11 --- Status Program Kerja

Status yang tercantum dalam dokumen proyek: - `Draft` - `Diajukan` -
`Revisi` - `Disetujui` - `Ditolak` - `Selesai`

**BASELINE:** enam status tersebut tercantum pada laporan proyek dan
menjadi daftar status baseline v0.2: `Draft`, `Diajukan`, `Revisi`,
`Disetujui`, `Ditolak`, dan `Selesai`.

**TBD-001:** apakah status tambahan `Dalam Evaluasi` diperlukan. Status
tersebut belum menjadi status operasional dan tidak boleh ditambahkan ke
enum, validasi, filter, atau transisi sebelum keputusan pemilik proyek.
Transisi final setiap status juga harus difinalisasi.

### FR-PRO-12 --- Menandai Selesai

Program kerja yang telah disetujui dan dilaksanakan dapat ditandai
`Selesai`.

**TBD:** aktor yang dapat menandai selesai, bukti pelaksanaan, dan field
penyelesaian yang wajib.

------------------------------------------------------------------------

## 4.5 Penilaian dan Perhitungan SAW

### FR-SAW-01 --- Kriteria SAW per Periode

**Keputusan LOCKED:** konfigurasi kriteria dan bobot dikelola per
periode kepengurusan.

Sistem menyediakan lima kriteria baseline: 1. Kesesuaian visi dan
kebutuhan anggota. 2. Kesiapan sumber daya manusia. 3. Estimasi anggaran
biaya. 4. Urgensi terhadap kalender kegiatan. 5. Kerumitan dan risiko
pelaksanaan.

### FR-SAW-02 --- Mengelola Bobot

Ketua Umum yang memiliki permission `saw.weights.manage` dapat mengelola
bobot kriteria pada konteks periode yang menjadi kewenangannya.

**Kriteria penerimaan:** - Backend memeriksa permission khusus. -
Pengguna Pejabat Teras tanpa permission tambahan tidak dapat mengubah
bobot. - Perubahan konfigurasi tidak mengubah hasil SAW historis. -
Aturan total bobot masih TBD.

### FR-SAW-03 --- Mengelola Jenis Kriteria

Setiap kriteria memiliki jenis `Benefit` atau `Cost`.

Pemetaan jenis per kriteria masih PROPOSED dan perlu dikonfirmasi.

### FR-SAW-04 --- Mengisi Penilaian

Pejabat Teras yang memiliki permission dapat memberikan nilai program
kerja untuk setiap kriteria.

**Kriteria penerimaan:** - Penilaian terhubung dengan program kerja dan
kriteria. - Nilai divalidasi sesuai tipe dan skala kriteria. - Penilaian
yang belum lengkap tidak dapat digunakan untuk perhitungan final. -
Riwayat perubahan penilaian harus mengikuti aturan audit yang akan
ditentukan.

### FR-SAW-05 --- Validasi Kelengkapan

Sebelum perhitungan SAW, Laravel harus memeriksa: - Alternatif yang akan
dihitung. - Kriteria aktif pada periode. - Bobot dan jenis kriteria. -
Kelengkapan nilai setiap alternatif. - Kesesuaian data dengan periode
perhitungan.

Jika ada nilai wajib yang belum tersedia, proses perhitungan harus
ditolak dan sistem memberi informasi yang dapat ditindaklanjuti.

### FR-SAW-06 --- Perhitungan SAW

Sistem menggunakan formula baseline:

Benefit: `r_ij = x_ij / max(x_ij)`

Cost: `r_ij = min(x_ij) / x_ij`

Nilai preferensi: `V_i = Σ(w_j × r_ij)`

Alternatif diurutkan dari nilai preferensi terbesar ke terkecil.

Penanganan nilai nol, nilai seri, presisi, dan pembulatan masih TBD.

### FR-SAW-07 --- Integrasi Synchronous Python

**Keputusan LOCKED:** Laravel memanggil Python SAW Service secara
synchronous.

**Alur utama:** 1. Pengguna meminta perhitungan. 2. Laravel memvalidasi
akses dan data. 3. Laravel membentuk payload perhitungan. 4. Laravel
mengirim request ke Python. 5. Python memvalidasi payload. 6. Python
menghitung SAW. 7. Python mengembalikan nilai preferensi dan ranking. 8.
Laravel memvalidasi hasil. 9. Laravel menyimpan proses dan hasil. 10.
Laravel mengembalikan hasil kepada frontend.

**Alur kegagalan:** - Jika Python tidak tersedia atau timeout, Laravel
tidak boleh menyimpan hasil parsial sebagai hasil sukses. - Penilaian
yang sudah tersimpan tidak boleh hilang akibat kegagalan perhitungan. -
Sistem menampilkan pesan kegagalan yang aman. - Detail teknis dicatat
pada log server tanpa membocorkan secret kepada pengguna.

### FR-SAW-08 --- Riwayat Perhitungan

**Keputusan LOCKED:** setiap proses perhitungan SAW disimpan sebagai
riwayat terpisah.

Satu proses perhitungan dapat menghasilkan banyak hasil alternatif.

**PROPOSED:** setiap proses menyimpan snapshot kriteria, bobot, nilai
penilaian, dan versi algoritma. Detail penyimpanan perlu ditentukan pada
desain database.

### FR-SAW-09 --- Melihat Ranking

Sistem menampilkan hasil nilai preferensi dan peringkat untuk alternatif
dalam proses SAW.

**Baseline akses yang diketahui:** Pejabat Teras dapat melihat ranking;
Ketua Umum termasuk Pejabat Teras dan mewarisi akses tersebut.

**TBD-015:** hak Super Admin, Ketua Bidang, dan Pengurus Bidang untuk
melihat ranking belum ditetapkan. Sistem wajib memeriksa permission di
backend dan tidak boleh memberikan akses kepada role yang belum
ditetapkan. Filter periode dan perilaku jika nilai preferensi sama tetap
mengikuti keputusan terkait.

------------------------------------------------------------------------

## 4.6 Keputusan dan Penyelesaian

### FR-DEC-01 --- Mencatat Keputusan

Pejabat Teras yang berwenang dapat mencatat keputusan program kerja
berdasarkan mekanisme organisasi/rapat pleno.

Jenis keputusan baseline: - Revisi. - Disetujui. - Ditolak.

Sistem menyimpan setiap keputusan sebagai riwayat.

### FR-DEC-02 --- Menampilkan Keputusan

Pengguna yang berwenang dapat melihat status dan riwayat keputusan
program kerja.

### FR-DEC-03 --- Menyelesaikan Program Kerja

Program kerja yang disetujui dan telah dilaksanakan dapat dicatat
sebagai selesai oleh aktor yang berwenang.

Aturan rinci penyelesaian masih TBD.

------------------------------------------------------------------------

# 5. Aturan Bisnis

Bagian ini memisahkan keputusan yang telah disetujui dari hal yang masih
terbuka.

## 5.1 Aturan LOCKED

  ---------------------------------------------------------------------
  ID                                 Aturan
  ---------------------------------- ----------------------------------
  BR-001                             Ketua Umum termasuk Pejabat Teras
                                     dan bukan role terpisah.

  BR-002                             Ketua Umum memperoleh permission
                                     tambahan `saw.weights.manage` pada
                                     keanggotaan terkait.

  BR-003                             Satu User hanya memiliki satu
                                     Keanggotaan dalam satu Periode
                                     Kepengurusan.

  BR-004                             Super Admin merupakan role global
                                     dan tidak terikat periode.

  BR-005                             Permission catalog bersifat tetap
                                     dan didefinisikan developer.

  BR-006                             Role mempunyai permission default.

  BR-007                             Permission override melekat pada
                                     Keanggotaan.

  BR-008                             Permission override mendukung
                                     keadaan Inherit, Grant, dan Deny.

  BR-009                             Keputusan override Keanggotaan
                                     diprioritaskan terhadap permission
                                     default role.

  BR-010                             Satu Program Kerja dapat memiliki
                                     lebih dari satu PIC.

  BR-011                             PIC direferensikan melalui
                                     Keanggotaan.

  BR-012                             Riwayat keputusan Program Kerja
                                     disimpan, bukan ditimpa.

  BR-013                             Kriteria dan bobot SAW dikelola
                                     per periode.

  BR-014                             Skala nilai mengikuti
                                     karakteristik kriteria; anggaran
                                     menggunakan nominal rupiah dalam
                                     rancangan.

  BR-015                             Riwayat proses SAW disimpan
                                     sebagai proses terpisah.

  BR-016                             Laravel memanggil Python SAW
                                     Service secara synchronous.

  BR-017                             Frontend dan backend utama
                                     menggunakan same-origin
                                     deployment.

  BR-018                             Autentikasi menggunakan Sanctum
                                     cookie-based session, tanpa JWT.
  ---------------------------------------------------------------------

**BR-019 --- Public landing page:** dapat diakses tanpa autentikasi,
bersifat informatif, dan tidak menyediakan self-registration atau akses
data internal.

## 5.2 Aturan BASELINE dari Laporan Proyek

  ---------------------------------------------------------------------
  ID                                 Aturan/Informasi
  ---------------------------------- ----------------------------------
  BR-BL-001                          Status program kerja yang
                                     dicantumkan: Draft, Diajukan,
                                     Revisi, Disetujui, Ditolak,
                                     Selesai.

  BR-BL-002                          Kriteria SAW berjumlah lima sesuai
                                     daftar pada Bagian 4.5.

  BR-BL-003                          Hasil SAW digunakan sebagai bahan
                                     pertimbangan rapat pleno.

  BR-BL-004                          Keputusan program kerja meliputi
                                     Revisi, Disetujui, dan Ditolak.

  BR-BL-005                          Formula SAW mengikuti normalisasi
                                     benefit/cost dan penjumlahan
                                     terbobot.
  ---------------------------------------------------------------------

## 5.3 Aturan yang Masih TBD

-   Transisi status lengkap.
-   Edit/hapus catatan.
-   PIC lintas bidang.
-   Benefit/cost final.
-   Validasi total bobot.
-   Anggaran nol dan pembagian nol.
-   Penanganan nilai preferensi sama.
-   Snapshot historis SAW.
-   Periode aktif.
-   Pergantian jabatan dalam periode yang sama.
-   Kebijakan penghapusan data.
-   Aktor yang berwenang mencatat keputusan dan penyelesaian.

------------------------------------------------------------------------

# 6. Model Data dan Kebutuhan Data

## 6.1 Entitas Konseptual

### Organisasi dan akses

-   User.
-   Role.
-   Permission.
-   RolePermission.
-   PeriodeKepengurusan.
-   Bidang.
-   Keanggotaan.
-   PermissionOverrideKeanggotaan.

### Program kerja

-   ProgramKerja.
-   ProgramKerjaPIC.
-   CatatanProgramKerja.
-   KeputusanProgramKerja.

### SAW

-   KriteriaSAW.
-   PenilaianSAW.
-   SAWRun.
-   SAWRunResult.

Nama tabel fisik dan struktur kolom final ditentukan dalam ERD dan data
dictionary.

## 6.2 Atribut Baseline

### User

Dari laporan proyek: - id - nama - email - password - created_at -
updated_at

Password harus disimpan dalam bentuk hash.

### Periode Kepengurusan

Dari laporan proyek: - id - nama_periode - tanggal_mulai -
tanggal_selesai - status_aktif - created_at - updated_at

### Keanggotaan

Dari class diagram: - id - user_id - role_id - periode_id - bidang_id
(relasi bidang disepakati) - created_at - updated_at

Constraint: kombinasi `user_id` dan `periode_id` unik.

### Bidang

Entitas Bidang terdapat pada class diagram dan relasi bidang telah
disepakati. Atribut final perlu ditetapkan pada data dictionary. Nama
bidang setidaknya diperlukan untuk identifikasi.

### Program Kerja

Dari laporan proyek: - id - periode_id - bidang_id - nama_proker -
tujuan - deskripsi - waktu - anggaran - target - status - created_at -
updated_at

### Program Kerja PIC

Dari laporan proyek: - id - program_kerja_id - keanggotaan_id -
created_at

Constraint: pasangan `program_kerja_id` dan `keanggotaan_id` unik.

### Catatan Program Kerja

Dari laporan proyek: - id - program_kerja_id - keanggotaan_id -
isi_catatan - created_at - updated_at

Kebijakan update/delete masih TBD.

### Kriteria SAW

Dari laporan proyek: - id - periode_id - nama_kriteria - jenis - bobot -
created_at - updated_at

### Penilaian SAW

Dari laporan proyek: - id - program_kerja_id - kriteria_id - nilai -
created_at - updated_at

Constraint dan kebutuhan riwayat perubahan penilaian masih perlu
ditentukan.

### Riwayat Keputusan Program Kerja

Dari laporan proyek: - id - program_kerja_id - keanggotaan_id -
keputusan - catatan - tanggal_keputusan - created_at

### SAW Run dan Result

Entitas ini ditambahkan untuk memenuhi keputusan penyimpanan riwayat
SAW. Atribut final belum ditetapkan. Satu SAW Run memiliki banyak hasil
alternatif. Snapshot input historis direkomendasikan tetapi bentuk
fisiknya masih TBD.

## 6.3 Integritas Data

Sistem harus menerapkan: - Primary key pada setiap entitas. - Foreign
key untuk relasi antartabel. - Unique constraint pada kombinasi yang
telah ditentukan. - Validasi referensi periode dan keanggotaan. -
Transaksi database untuk operasi yang mengubah beberapa entitas
terkait. - Kebijakan delete/update foreign key yang konsisten dengan
kebutuhan retensi data.

Jangan menetapkan `CASCADE`, `SET NULL`, atau `RESTRICT` untuk semua
relasi secara seragam tanpa memeriksa dampak bisnis.

------------------------------------------------------------------------

# 7. Kebutuhan Non-Fungsional

## 7.1 Keamanan

  ---------------------------------------------------------------------
  ID                                 Requirement
  ---------------------------------- ----------------------------------
  NFR-SEC-01                         Password disimpan menggunakan
                                     hashing yang aman.

  NFR-SEC-02                         Endpoint privat memerlukan session
                                     autentikasi yang valid.

  NFR-SEC-03                         Backend memeriksa authorization
                                     untuk setiap operasi yang
                                     dilindungi.

  NFR-SEC-04                         Implementasi Sanctum mengikuti
                                     perlindungan CSRF dan cookie yang
                                     sesuai.

  NFR-SEC-05                         Secret, password, dan kredensial
                                     layanan tidak boleh ditanam di
                                     source code.

  NFR-SEC-06                         Pesan error tidak membocorkan
                                     stack trace, secret, atau detail
                                     internal pada production.

  NFR-SEC-07                         Input divalidasi di backend.
  ---------------------------------------------------------------------

## 7.2 Integritas dan Konsistensi

  ---------------------------------------------------------------------
  ID                                 Requirement
  ---------------------------------- ----------------------------------
  NFR-DATA-01                        Relasi data penting dilindungi
                                     constraint database.

  NFR-DATA-02                        Operasi multi-data menggunakan
                                     transaksi jika diperlukan.

  NFR-DATA-03                        Riwayat keputusan tidak ditimpa
                                     oleh keputusan baru.

  NFR-DATA-04                        Riwayat proses SAW tidak ditimpa
                                     oleh proses baru.

  NFR-DATA-05                        Kegagalan Python tidak menghapus
                                     penilaian yang sudah tersimpan.
  ---------------------------------------------------------------------

## 7.3 Maintainability

  ---------------------------------------------------------------------
  ID                                 Requirement
  ---------------------------------- ----------------------------------
  NFR-MNT-01                         Pemisahan frontend dan backend
                                     dilakukan berdasarkan tanggung
                                     jawab.

  NFR-MNT-02                         Business logic tidak ditumpuk
                                     seluruhnya dalam Controller.

  NFR-MNT-03                         Permission catalog dan aturan
                                     bisnis memiliki sumber definisi
                                     yang konsisten.

  NFR-MNT-04                         Perubahan database dilakukan
                                     melalui migration yang dapat
                                     ditelusuri.

  NFR-MNT-05                         Implementasi mengikuti struktur
                                     dan konvensi repository aktual.
  ---------------------------------------------------------------------

## 7.4 Pengujian

  ---------------------------------------------------------------------
  ID                                 Requirement
  ---------------------------------- ----------------------------------
  NFR-TST-01                         Fitur inti memiliki pengujian
                                     fungsional.

  NFR-TST-02                         Authorization diuji untuk pengguna
                                     yang berhak dan tidak berhak.

  NFR-TST-03                         Perhitungan SAW diuji menggunakan
                                     contoh input dan hasil yang dapat
                                     diverifikasi.

  NFR-TST-04                         Integrasi Laravel-Python diuji
                                     untuk respons sukses dan
                                     kegagalan.

  NFR-TST-05                         Perubahan tidak boleh dianggap
                                     selesai sebelum pengujian yang
                                     relevan dijalankan atau
                                     keterbatasannya dilaporkan.
  ---------------------------------------------------------------------

## 7.5 Reliability dan Error Handling

-   Sistem harus memberikan pesan kesalahan yang dapat dipahami.
-   Kegagalan layanan Python harus ditangani tanpa menyimpan hasil
    perhitungan parsial sebagai hasil sukses.
-   Operasi yang gagal tidak boleh meninggalkan data relasional dalam
    keadaan tidak konsisten.
-   Detail error teknis dicatat pada log server sesuai kebijakan
    keamanan.

Target numerik response time, kapasitas concurrent user, availability,
backup, dan recovery belum ditetapkan.

------------------------------------------------------------------------

# 8. Use Case Specification

Use case berikut diturunkan dari aktor, alur activity diagram, dan
sequence diagram pada laporan proyek serta requirement PRD. Detail
permission tertentu yang belum diputuskan tetap ditandai TBD.

## UC-PUB-01 --- Mengakses Landing Page Publik

**Aktor:** Pengunjung (belum terautentikasi).

**Prasyarat:** Pengunjung dapat mengakses alamat aplikasi.

**Alur utama:** 1. Pengunjung membuka alamat aplikasi. 2. Sistem
menampilkan landing page publik. 3. Pengunjung menggunakan navigasi
anchor untuk membaca section. 4. Pengunjung memilih CTA `Masuk`. 5.
Sistem mengarahkan pengunjung ke halaman login.

**Alur alternatif:** Pengunjung dapat meninggalkan landing page tanpa
melakukan login.

**Pascakondisi:** Tidak ada session autentikasi yang dibuat hanya dengan
membuka landing page.

## UC-01 --- Login

**Aktor:** Seluruh pengguna terdaftar.

**Prasyarat:** - Pengguna memiliki akun. - Akun dapat digunakan untuk
autentikasi.

**Alur utama:** 1. Pengguna membuka halaman login. 2. Pengguna
memasukkan email dan password. 3. Sistem memvalidasi kredensial. 4.
Sistem membuat session. 5. Sistem menyediakan konteks pengguna. 6.
Pengguna masuk ke aplikasi.

**Alur alternatif:** - Kredensial salah: sistem menolak login. - Input
tidak valid: sistem menampilkan error validasi. - Akun nonaktif: sistem
menolak login.

**Pascakondisi:** Session aktif untuk pengguna yang valid.

## UC-02 --- Logout

**Aktor:** Pengguna terautentikasi.

**Prasyarat:** Pengguna memiliki session aktif.

**Alur utama:** 1. Pengguna memilih logout. 2. Sistem mengakhiri
session. 3. Sistem mengarahkan pengguna ke halaman login.

**Pascakondisi:** Session tidak lagi valid.

## UC-03 --- Administrasi Pengguna

**Aktor:** Super Admin.

**Prasyarat:** Super Admin terautentikasi dan memiliki kewenangan
administrasi.

**Alur utama:** 1. Super Admin membuka administrasi pengguna. 2. Sistem
menampilkan daftar pengguna. 3. Super Admin memilih tambah, ubah, atau
nonaktifkan pengguna. 4. Super Admin mengisi atau mengonfirmasi data. 5.
Sistem memvalidasi perubahan. 6. Sistem menyimpan perubahan dan
menampilkan hasil.

**Alur alternatif:** - Data tidak valid: perubahan ditolak. -
Email/identitas unik telah digunakan: sistem menolak data duplikat. -
Pengguna tidak memiliki kewenangan: request ditolak.

## UC-04 --- Pengelolaan Periode dan Keanggotaan

**Aktor:** Super Admin.

**Prasyarat:** Super Admin memiliki kewenangan administrasi.

**Alur utama:** 1. Super Admin membuka pengelolaan periode atau
keanggotaan. 2. Sistem menampilkan data terkait. 3. Super Admin menambah
atau memperbarui periode/keanggotaan. 4. Sistem memvalidasi tanggal,
user, role, periode, dan bidang. 5. Sistem memeriksa constraint satu
keanggotaan per user per periode. 6. Sistem menyimpan perubahan.

**Alur alternatif:** - Kombinasi user dan periode sudah digunakan:
sistem menolak keanggotaan duplikat. - Bidang tidak sesuai dengan aturan
role: sistem menolak atau meminta koreksi. - Periode tidak valid: sistem
menolak penyimpanan.

## UC-05 --- Mengelola Permission Keanggotaan

**Aktor:** Super Admin.

**Prasyarat:** Keanggotaan dan permission catalog tersedia.

**Alur utama:** 1. Super Admin memilih keanggotaan. 2. Sistem
menampilkan permission default role dan override yang ada. 3. Super
Admin memilih permission dari katalog tetap. 4. Super Admin menentukan
Inherit, Grant, atau Deny. 5. Sistem memvalidasi permission. 6. Sistem
menyimpan override.

**Pascakondisi:** Permission efektif keanggotaan mengikuti default role
dan override.

## UC-06 --- Membuat Draft Program Kerja

**Aktor:** Ketua Bidang atau Pengurus Bidang yang berwenang.

**Prasyarat:** - Pengguna terautentikasi. - Pengguna memiliki
keanggotaan yang relevan. - Periode dan bidang tersedia.

**Alur utama:** 1. Pengguna membuka form program kerja. 2. Sistem
menampilkan form. 3. Pengguna mengisi data program kerja. 4. Pengguna
menyimpan draft. 5. Laravel memvalidasi input dan permission. 6. Sistem
menyimpan program kerja dengan status Draft. 7. Sistem menampilkan
draft.

**Alur alternatif:** - Input tidak valid: sistem menampilkan error. -
Pengguna tidak berwenang: request ditolak.

## UC-07 --- Menetapkan PIC

**Aktor:** Pengguna yang memiliki kewenangan mengelola program kerja.

**Prasyarat:** Program kerja dan keanggotaan calon PIC tersedia.

**Alur utama:** 1. Pengguna membuka detail/edit program kerja. 2.
Pengguna memilih satu atau beberapa keanggotaan sebagai PIC. 3. Sistem
memvalidasi keanggotaan dan duplikasi. 4. Sistem menyimpan relasi PIC.
5. Sistem menampilkan daftar PIC.

**TBD:** aturan lintas bidang dan aturan periode PIC.

## UC-08 --- Mengajukan Program Kerja

**Aktor:** Ketua Bidang.

**Prasyarat:** - Program kerja berstatus Draft atau Revisi sesuai aturan
final. - Ketua Bidang berwenang atas program kerja. - Data pengajuan
lengkap.

**Alur utama:** 1. Ketua Bidang membuka draft. 2. Ketua Bidang meninjau
data. 3. Ketua Bidang memilih ajukan. 4. Sistem memvalidasi data dan
permission. 5. Sistem mengubah status menjadi Diajukan. 6. Sistem
menampilkan status terbaru.

**Alur alternatif:** - Data belum lengkap: sistem menolak pengajuan. -
Status tidak memungkinkan: sistem menolak pengajuan. - Pengguna tidak
berwenang: sistem menolak request.

## UC-09 --- Evaluasi dan Revisi Program Kerja

**Aktor:** Pejabat Teras dan Ketua/Pengurus Bidang.

**Prasyarat:** Program kerja berstatus Diajukan atau Revisi sesuai
langkah.

**Alur utama:** 1. Pejabat Teras membuka program kerja yang diajukan. 2.
Sistem menampilkan detail. 3. Pejabat Teras mengevaluasi. 4. Jika perlu
revisi, Pejabat Teras memberikan catatan. 5. Sistem menyimpan catatan
dan mengubah status menjadi Revisi. 6. Bidang melihat catatan. 7. Bidang
memperbaiki program kerja. 8. Ketua Bidang mengajukan kembali. 9. Sistem
mengubah status menjadi Diajukan.

**Alur alternatif:** - Jika tidak perlu revisi, program kerja dapat
melanjutkan proses penilaian dan keputusan sesuai alur organisasi. -
Pengguna tanpa permission tidak dapat mengevaluasi atau mengubah data.

## UC-10 --- Mengatur Kriteria dan Bobot SAW

**Aktor:** Ketua Umum dengan permission `saw.weights.manage`.

**Prasyarat:** - Ketua Umum terautentikasi. - Keanggotaan dan periode
terkait valid. - Permission efektif tersedia.

**Alur utama:** 1. Ketua Umum membuka konfigurasi SAW periode. 2. Sistem
menampilkan kriteria dan bobot. 3. Ketua Umum mengubah bobot yang
diizinkan. 4. Sistem memvalidasi konfigurasi. 5. Sistem menyimpan
konfigurasi periode.

**Alur alternatif:** - Permission tidak tersedia: sistem menolak
perubahan. - Bobot tidak valid: sistem menolak penyimpanan. - Aturan
total bobot masih TBD.

## UC-11 --- Memberikan Penilaian SAW

**Aktor:** Pejabat Teras yang memiliki permission penilaian.

**Prasyarat:** - Program kerja memenuhi syarat untuk dinilai. - Kriteria
dan bobot periode tersedia. - Pengguna memiliki permission.

**Alur utama:** 1. Pejabat Teras membuka daftar program kerja yang akan
dinilai. 2. Sistem menampilkan form penilaian. 3. Pejabat Teras mengisi
nilai setiap kriteria. 4. Laravel memvalidasi nilai. 5. Sistem menyimpan
penilaian. 6. Sistem menampilkan hasil penyimpanan.

**Alur alternatif:** - Nilai tidak valid: sistem menolak nilai dan
menampilkan kesalahan. - Nilai belum lengkap: sistem menandai penilaian
belum lengkap. - Pengguna tidak berwenang: sistem menolak request.

## UC-12 --- Menghitung SAW dan Melihat Ranking

**Aktor:** Pejabat Teras yang memiliki permission terkait.

**Prasyarat:** - Data alternatif tersedia. - Kriteria dan bobot
tersedia. - Penilaian lengkap. - Python SAW Service tersedia.

**Alur utama:** 1. Pengguna meminta perhitungan/ranking. 2. Laravel
memeriksa autentikasi dan permission. 3. Laravel memvalidasi kelengkapan
data. 4. Laravel mengirim data ke Python. 5. Python membentuk matriks
keputusan. 6. Python melakukan normalisasi benefit/cost. 7. Python
menghitung nilai preferensi. 8. Python mengurutkan alternatif. 9. Python
mengembalikan hasil ke Laravel. 10. Laravel memvalidasi hasil. 11.
Laravel menyimpan SAW Run dan hasil alternatif. 12. Laravel
mengembalikan hasil kepada frontend. 13. Frontend menampilkan ranking.

**Alur alternatif:** - Penilaian belum lengkap: perhitungan tidak
dijalankan. - Python gagal/timeout: Laravel menampilkan kegagalan dan
tidak menyimpan hasil sebagai perhitungan sukses. - Hasil Python tidak
valid: Laravel menolak penyimpanan hasil. - Penanganan nilai nol dan tie
mengikuti aturan yang harus difinalisasi.

## UC-13 --- Mencatat Keputusan Program Kerja

**Aktor:** Pejabat Teras yang berwenang.

**Prasyarat:** Program kerja telah melalui evaluasi dan proses
organisasi yang diperlukan.

**Alur utama:** 1. Pejabat Teras membuka detail program kerja dan hasil
ranking bila relevan. 2. Keputusan pleno dilaksanakan sebagai mekanisme
organisasi. 3. Pejabat Teras mencatat keputusan. 4. Sistem memvalidasi
kewenangan dan data keputusan. 5. Sistem menyimpan keputusan sebagai
riwayat. 6. Sistem memperbarui status terkini program kerja. 7. Sistem
menampilkan hasil keputusan.

**Alur alternatif:** - Keputusan Revisi: sistem menyimpan catatan dan
status Revisi. - Keputusan Disetujui: sistem menyimpan status
Disetujui. - Keputusan Ditolak: sistem menyimpan status Ditolak. - Data
tidak valid atau permission tidak tersedia: sistem menolak perubahan.

## UC-14 --- Menandai Program Kerja Selesai

**Aktor:** Aktor yang akan ditetapkan.

**Prasyarat:** Program kerja berstatus Disetujui dan pelaksanaan telah
dilakukan.

**Alur utama:** 1. Aktor membuka program kerja yang disetujui. 2. Aktor
menjalankan tindakan tandai selesai. 3. Sistem memeriksa status dan
kewenangan. 4. Sistem mengubah status menjadi Selesai. 5. Sistem
menampilkan status terbaru.

**TBD:** aktor, bukti pelaksanaan, dan data wajib penyelesaian.

------------------------------------------------------------------------

# 9. Kebutuhan Pengujian dan Acceptance Criteria

## 9.1 Pengujian Autentikasi

-   Login dengan kredensial valid.
-   Login dengan password salah.
-   Login dengan input tidak lengkap.
-   Logout dan akses ulang endpoint privat.
-   Akses endpoint privat tanpa session.
-   Session pengguna yang dinonaktifkan.

## 9.2 Pengujian Authorization

-   Super Admin dapat mengakses fungsi administrasi yang diizinkan.
-   Pengguna non-admin tidak dapat mengakses fungsi administrasi yang
    dilindungi.
-   Ketua Umum dapat mengelola bobot jika permission tersedia.
-   Pejabat Teras tanpa `saw.weights.manage` tidak dapat mengubah bobot.
-   Permission `DENY` menolak tindakan meskipun role default
    mengizinkan.
-   Permission `GRANT` memberikan tindakan yang tercantum pada katalog.
-   Permission `INHERIT` mengikuti role default.
-   Override periode lama tidak otomatis berlaku pada keanggotaan baru.

## 9.3 Pengujian Keanggotaan

-   User dapat mempunyai keanggotaan pada periode berbeda.
-   User tidak dapat mempunyai dua keanggotaan pada periode sama.
-   Keanggotaan Ketua Bidang/Pengurus Bidang memiliki bidang.
-   Super Admin tidak membutuhkan keanggotaan periode.

## 9.4 Pengujian Program Kerja

-   Membuat draft dengan data valid.
-   Menolak draft dengan data tidak valid.
-   Memperbarui draft sesuai permission.
-   Mengajukan draft.
-   Menolak pengajuan yang belum lengkap.
-   Menetapkan beberapa PIC.
-   Menolak PIC duplikat.
-   Menguji revisi dan pengajuan ulang.
-   Menyimpan lebih dari satu keputusan dan memastikan riwayat
    sebelumnya tetap tersedia.
-   Menandai selesai sesuai aturan final.

## 9.5 Pengujian SAW

-   Memastikan input lima kriteria tersedia.
-   Memastikan validasi kelengkapan penilaian.
-   Memastikan normalisasi benefit.
-   Memastikan normalisasi cost.
-   Memastikan pembobotan dan nilai preferensi.
-   Memastikan urutan ranking dari nilai terbesar ke terkecil.
-   Memastikan hasil proses sebelumnya tetap tersimpan.
-   Menguji kegagalan layanan Python.
-   Menguji nilai nol dan tie setelah aturan final ditetapkan.
-   Memastikan perubahan bobot periode tidak mengubah snapshot hasil
    historis.

## 9.6 Kriteria Selesai (Definition of Done)

Suatu requirement dianggap selesai apabila: 1. Implementasi sesuai
requirement yang sudah disetujui. 2. Authorization dan validasi backend
telah diterapkan. 3. Perubahan database menggunakan migration yang
sesuai. 4. Pengujian relevan dijalankan. 5. Hasil pengujian dilaporkan
secara jujur. 6. Tidak terdapat perubahan tidak terkait dalam patch. 7.
Dokumentasi atau API contract diperbarui bila diperlukan. 8. Tidak ada
keputusan TBD yang diam-diam diubah menjadi aturan final.

------------------------------------------------------------------------

# 10. Daftar Keputusan Terbuka (TBD)

Tabel berikut wajib dipertahankan dan diperbarui ketika pemilik proyek
memberikan keputusan.

  -----------------------------------------------------------------------
  ID                      Topik                   Status
  ----------------------- ----------------------- -----------------------
  TBD-001                 Apakah status           Open
                          `Dalam Evaluasi`        
                          diperlukan?             

  TBD-002                 Apakah catatan dapat    Open
                          diedit/dihapus atau     
                          append-only?            

  TBD-003                 Apakah PIC boleh lintas Open
                          bidang?                 

  TBD-004                 Pemetaan benefit/cost   Open
                          final untuk lima        
                          kriteria                

  TBD-005                 Total bobot wajib 100%  Open
                          atau dinormalisasi      
                          otomatis                

  TBD-006                 Apakah anggaran Rp0     Open
                          diperbolehkan?          

  TBD-007                 Penanganan nilai nol    Open
                          pada normalisasi cost   

  TBD-008                 Penanganan nilai        Open
                          preferensi yang         
                          sama/tie                

  TBD-009                 Detail snapshot SAW dan Open
                          versi algoritma         

  TBD-010                 Reset password dan      Open
                          aktivasi akun           

  TBD-011                 Kebijakan soft          Open
                          delete/hard             
                          delete/larangan hapus   

  TBD-012                 Apakah hanya boleh ada  Open
                          satu periode aktif?     

  TBD-013                 Aturan pergantian       Open
                          jabatan dalam periode   
                          yang sama               

  TBD-014                 Aktor dan bukti untuk   Open
                          menandai program kerja  
                          selesai                 

  TBD-015                 Visibilitas ranking     Open
                          untuk setiap role       

  TBD-016                 Detail kontrak API      Open
                          Python                  

  TBD-017                 Versi dan konfigurasi   Verify against
                          database final          repository

  TBD-018                 Topologi production,    Open
                          hosting, dan domain     

  TBD-019                 Field wajib dan batas   Open
                          validasi seluruh form   

  TBD-020                 Hak akses CRUD data     Open
                          Bidang                  

  TBD-021                 Hak akses melihat       Open
                          program kerja lintas    
                          bidang                  

  TBD-022                 Hak akses mencatat      Open
                          keputusan dan keputusan 
                          berulang                
  -----------------------------------------------------------------------

Klarifikasi `TBD-015`: Pejabat Teras, termasuk Ketua Umum, tercantum
memiliki akses ranking pada matriks kewenangan PRD. Hak Super Admin,
Ketua Bidang, dan Pengurus Bidang belum ditetapkan.

AI coding agent harus meminta klarifikasi jika TBD memengaruhi fitur
yang sedang dikerjakan dan tidak dapat ditangani tanpa menetapkan aturan
bisnis baru.

------------------------------------------------------------------------

# 11. Traceability Matrix

Pemetaan requirement ke bagian PRD dan sumber laporan proyek.

Catatan v0.2: Public Landing Page dipetakan ke FR-PUB-01 s.d. FR-PUB-05
dan UC-PUB-01. Sumbernya adalah keputusan ruang lingkup publik v0.2,
bukan use case pada laporan awal.

  -----------------------------------------------------------------------
  Kelompok SRS            Referensi PRD           Referensi laporan
                                                  proyek
  ----------------------- ----------------------- -----------------------
  Autentikasi             FR-AUTH-01 s.d.         Sequence diagram
                          FR-AUTH-05              administrasi dan use
                                                  case

  Administrasi            FR-ADM-01 s.d.          Use case dan Sequence
                          FR-ADM-08               Diagram Administrasi

  Keanggotaan             FR-MEM-01 s.d.          Class Diagram dan Tabel
                          FR-MEM-05               Relasional

  Program Kerja           FR-PRO-01 s.d.          Activity Diagram dan
                          FR-PRO-12               Sequence Diagram
                                                  Pengajuan

  Catatan dan Revisi      FR-PRO-08 s.d.          Class Diagram, Activity
                          FR-PRO-10               Diagram, Sequence
                                                  Diagram Keputusan

  Konfigurasi SAW         FR-SAW-01 s.d.          Class Diagram dan Tabel
                          FR-SAW-03               Relasional

  Penilaian SAW           FR-SAW-04 s.d.          Sequence Diagram
                          FR-SAW-05               Penilaian dan
                                                  Perhitungan

  Integrasi Python        FR-SAW-06 s.d.          Arsitektur Sistem dan
                          FR-SAW-08               Sequence Diagram SAW

  Ranking                 FR-SAW-09               Flowchart SAW dan
                                                  Sequence Diagram

  Keputusan               FR-DEC-01 s.d.          Activity Diagram dan
                          FR-DEC-03               Sequence Diagram
                                                  Keputusan
  -----------------------------------------------------------------------

------------------------------------------------------------------------

# 12. Instruksi untuk AI Coding Agent

SRS ini merupakan spesifikasi perilaku sistem dan harus digunakan
bersama `PRD.md`, `ARCHITECTURE.md`, dan `DESIGN.md` jika dokumen
tersebut telah tersedia.

Sebelum mengimplementasikan requirement:

1.  Audit repository aktual.
2.  Periksa versi dan dependency.
3.  Periksa route, controller, model, migration, service, React
    component, dan test yang sudah ada.
4.  Cocokkan implementasi dengan requirement ID dalam SRS.
5.  Laporkan requirement yang sudah ada, belum ada, tidak sesuai, atau
    tidak dapat diverifikasi.
6.  Jangan membuat ulang project yang sudah ada.
7.  Jangan menebak nama file, struktur folder, tabel, route, atau class.
8.  Jangan mengubah keputusan LOCKED tanpa persetujuan.
9.  Jangan mengimplementasikan keputusan TBD dengan asumsi diam-diam.
10. Buat patch terfokus dan sertakan file terdampak, cara menerapkan,
    command testing, dan hasil verifikasi.
11. Jangan mengklaim test berhasil jika belum dijalankan.
12. Jangan melakukan commit, push, reset, atau operasi destruktif
    kecuali diminta.
13. Jangan menampilkan atau menimpa secret dari environment.
14. Jika SRS berbeda dari implementasi aktual, laporkan gap sebelum
    mengubah bagian yang berisiko.

## Format Laporan Audit Requirement

  ---------------------------------------------------------------------
  Requirement   Kondisi       Gap           Rekomendasi   Status
  ID            repository                                
  ------------- ------------- ------------- ------------- -------------
  FR-AUTH-01    Belum diaudit Belum         Audit setelah Pending
                              diketahui     ZIP tersedia  

  ---------------------------------------------------------------------

Status yang boleh digunakan: - `Implemented` - `Partially Implemented` -
`Not Implemented` - `Failing` - `Needs Clarification` - `Not Audited`

------------------------------------------------------------------------

**Akhir Dokumen --- SRS v0.2**

Status: Draft revisi v0.2 yang diturunkan dari PRD v0.2, laporan proyek,
dan keputusan perencanaan. TBD-001, TBD-002, dan TBD-015 tetap terbuka.
