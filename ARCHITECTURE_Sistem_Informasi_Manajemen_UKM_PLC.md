# SYSTEM ARCHITECTURE DOCUMENT

## Sistem Informasi Manajemen UKM Polytechnic Linux Community

  -----------------------------------------------------------------------
  Informasi                           Keterangan
  ----------------------------------- -----------------------------------
  Nama sistem                         Sistem Informasi Manajemen UKM
                                      Polytechnic Linux Community

  Dokumen                             System Architecture Document

  Versi                               0.1

  Status                              Draft arsitektur --- mengacu pada
                                      PRD v0.1 dan SRS v0.1

  Tanggal                             4 November 2026

  Dokumen induk kebutuhan             `PRD.md`

  Spesifikasi kebutuhan               `SRS.md`
  -----------------------------------------------------------------------

> **Aturan status keputusan:** `LOCKED` berarti sudah disetujui dan
> tidak boleh diubah tanpa persetujuan pemilik proyek. `BASELINE`
> berarti berasal dari dokumen proyek. `PROPOSED` berarti rekomendasi
> arsitektur yang perlu diverifikasi ketika repository diaudit. `TBD`
> berarti belum diputuskan. Dokumen ini tidak boleh mengubah keputusan
> PROPOSED/TBD menjadi keputusan final secara diam-diam.

------------------------------------------------------------------------

# 1. Tujuan dan Ruang Lingkup

## 1.1 Tujuan Dokumen

Dokumen ini menjelaskan rancangan arsitektur teknis untuk Sistem
Informasi Manajemen UKM Polytechnic Linux Community. Dokumen menjadi
acuan untuk: - Menentukan batas tanggung jawab frontend, backend,
database, dan layanan Python. - Menjelaskan komunikasi antarkomponen. -
Menentukan prinsip struktur aplikasi dan pengelolaan data. - Menjelaskan
autentikasi, authorization, dan integrasi SAW. - Menjadi pedoman audit
repository dan implementasi patch.

Dokumen ini harus dibaca bersama: - `PRD.md` untuk tujuan, ruang
lingkup, aktor, dan keputusan produk. - `SRS.md` untuk kebutuhan
fungsional, nonfungsional, use case, dan acceptance criteria. -
`DESIGN.md` untuk spesifikasi tampilan, jika sudah dibuat.

## 1.2 Ruang Lingkup Arsitektur

Arsitektur mencakup: 1. Aplikasi web React + TypeScript. 2. Backend
Laravel dan REST API. 3. Authentication menggunakan Laravel Sanctum
cookie-based session. 4. Authorization RBAC, permission, dan permission
override per keanggotaan. 5. Database relasional untuk data organisasi,
program kerja, penilaian, dan riwayat. 6. Python SAW Service untuk
perhitungan synchronous. 7. Konfigurasi, keamanan, logging, testing, dan
deployment.

Arsitektur tidak menetapkan desain visual rinci, seluruh detail field
database, atau kontrak payload final Python. Detail tersebut diturunkan
pada dokumen desain dan implementasi setelah audit repository.

------------------------------------------------------------------------

# 2. Ringkasan Keputusan Arsitektur

## 2.1 Keputusan yang Sudah LOCKED

  -----------------------------------------------------------------------
  Area                    Keputusan               Konsekuensi arsitektur
  ----------------------- ----------------------- -----------------------
  Repository              Satu repository         Kode aplikasi utama dan
                          (monorepo)              frontend dikelola dalam
                                                  satu repository.

  Backend                 Laravel                 Menjadi pusat business
                                                  logic, autentikasi,
                                                  authorization,
                                                  validasi, dan akses
                                                  data.

  Frontend                React + TypeScript      Menjadi antarmuka web
                                                  dan berkomunikasi
                                                  dengan Laravel melalui
                                                  API.

  Deployment              Same-origin; frontend   Browser mengakses
                          disajikan melalui       frontend dan API dari
                          Laravel                 origin yang sama.

  Authentication          Laravel Sanctum,        Menggunakan
                          cookie-based session,   session/cookie dan
                          tanpa JWT               perlindungan CSRF;
                                                  tidak membuat alur
                                                  token JWT.

  Authorization           RBAC + permission +     Akses diverifikasi pada
                          middleware + policy     backend berdasarkan
                                                  role, permission,
                                                  konteks keanggotaan,
                                                  dan resource.

  Permission catalog      Tetap, ditentukan       Super Admin tidak
                          developer               membuat permission baru
                                                  dari dashboard.

  Default permission      Ditentukan oleh role    Role menyediakan hak
                                                  akses default.

  Permission scope        Permission khusus       Hak akses khusus
                          melekat pada            dibatasi pada
                          keanggotaan             keanggotaan/periode
                                                  terkait.

  Permission override     `INHERIT`, `GRANT`,     Override keanggotaan
                          `DENY`                  dapat mengikuti,
                                                  menambah, atau menolak
                                                  permission default
                                                  role.

  API                     REST API dengan         Endpoint aplikasi
                          versioning              menggunakan prefix
                                                  `/api/v1` dan JSON.

  Integrasi SAW           Laravel memanggil       Request perhitungan
                          Python secara           menunggu respons Python
                          synchronous             sebelum hasil
                                                  dikembalikan.

  Riwayat SAW             Setiap proses           Hasil proses baru tidak
                          perhitungan disimpan    menimpa seluruh riwayat
                          terpisah                proses sebelumnya.

  PIC                     Satu program kerja      Relasi PIC menggunakan
                          dapat memiliki beberapa tabel penghubung dan
                          PIC                     referensi keanggotaan.

  Riwayat keputusan       Seluruh keputusan       Keputusan baru tidak
                          disimpan                menghapus keputusan
                                                  sebelumnya.

  Kriteria SAW            Dikelola per periode    Konfigurasi satu
                                                  periode tidak otomatis
                                                  menggantikan
                                                  konfigurasi periode
                                                  lain.
  -----------------------------------------------------------------------

## 2.2 Baseline dari Dokumen Proyek

  -----------------------------------------------------------------------
  Area                                Baseline
  ----------------------------------- -----------------------------------
  Database                            MySQL tercantum sebagai baseline
                                      teknologi pada dokumen proyek.

  Metode pemeringkatan                Simple Additive Weighting (SAW).

  Kriteria                            Lima kriteria: kesesuaian
                                      visi/kebutuhan, kesiapan SDM,
                                      anggaran, urgensi kalender,
                                      kerumitan/risiko.

  Aktor organisasi                    Super Admin, Pejabat Teras, Ketua
                                      Bidang, Pengurus Bidang.

  Ketua Umum                          Termasuk Pejabat Teras dengan
                                      permission tambahan
                                      `saw.weights.manage`.
  -----------------------------------------------------------------------

**Catatan:** MySQL belum diperlakukan sebagai keputusan teknologi final
yang tidak dapat diubah. PRD dan SRS meminta verifikasi terhadap
repository dan lingkungan deployment. Jangan membuat migration sebelum
database aktual dan konfigurasi proyek diperiksa.

## 2.3 Rekomendasi Arsitektur

Rekomendasi berikut merupakan rancangan teknis, bukan keputusan bisnis
baru: - Menggunakan Laravel sebagai **modular monolith** untuk backend
utama. - Memisahkan Python sebagai layanan komputasi SAW yang hanya
diakses backend Laravel. - Menjaga Controller tetap tipis dan
memindahkan proses aplikasi ke Service/Action sesuai pola repository. -
Menggunakan database transaction untuk operasi yang mengubah beberapa
record terkait. - Menyimpan hasil perhitungan SAW secara atomik setelah
respons Python lolos validasi. - Menghindari penambahan microservice,
queue, cache, atau package arsitektural sebelum ada kebutuhan terukur.

Rekomendasi ini harus disesuaikan dengan pola dan kondisi repository
aktual.

------------------------------------------------------------------------

# 3. Architectural Drivers

Faktor utama yang memengaruhi rancangan:

1.  **Kesesuaian dengan keputusan proyek:** Laravel, React + TypeScript,
    Sanctum session, REST API, same-origin, dan Python SAW synchronous.
2.  **Keamanan akses:** role organisasi melekat pada keanggotaan per
    periode; Super Admin berada pada lingkup global.
3.  **Keterlacakan:** riwayat keputusan dan proses SAW tidak boleh
    hilang saat ada perubahan baru.
4.  **Konsistensi data:** program kerja, PIC, penilaian, dan hasil
    perhitungan memiliki relasi yang harus dijaga.
5.  **Kemudahan implementasi:** arsitektur harus realistis untuk
    dikerjakan dan diuji dalam satu repository.
6.  **Kemudahan pemeliharaan:** perubahan satu modul tidak seharusnya
    memaksa perubahan tidak terkait pada seluruh aplikasi.
7.  **Kesesuaian dengan repository:** implementasi mengikuti versi,
    dependency, dan struktur aktual setelah ZIP diaudit.

------------------------------------------------------------------------

# 4. System Context

## 4.1 Aktor dan Sistem Eksternal

Aktor utama: - Super Admin. - Pejabat Teras. - Ketua Umum (anggota
Pejabat Teras dengan permission tambahan). - Ketua Bidang. - Pengurus
Bidang.

Komponen eksternal utama: - Browser pengguna. - Python SAW Service. -
Database relasional aplikasi.

Tidak ada integrasi eksternal lain yang menjadi kebutuhan wajib pada
versi pertama.

## 4.2 Context Diagram

``` mermaid
flowchart LR
    SA[Super Admin]
    PT[Pejabat Teras]
    KU[Ketua Umum]
    KB[Ketua Bidang]
    PB[Pengurus Bidang]

    WEB[Web Application<br/>React + TypeScript<br/>Laravel]
    DB[(Relational Database)]
    PY[Python SAW Service]

    SA -->|Administrasi sistem| WEB
    PT -->|Evaluasi, penilaian, keputusan| WEB
    KU -->|Fungsi Pejabat Teras + kelola bobot| WEB
    KB -->|Tinjau dan ajukan program kerja| WEB
    PB -->|Susun draft program kerja| WEB

    WEB -->|Read/write data| DB
    WEB -->|Synchronous HTTP request| PY
    PY -->|Hasil perhitungan| WEB
```

Diagram menunjukkan batas logis sistem. Database dan Python tidak
diakses langsung oleh browser pengguna.

------------------------------------------------------------------------

# 5. Container Architecture

## 5.1 Gambaran Komponen

Sistem utama terdiri atas tiga container logis: 1. **Web Client:**
React + TypeScript yang berjalan di browser. 2. **Laravel Application:**
menyajikan frontend hasil build, REST API, session authentication,
authorization, business logic, dan integrasi data. 3. **Python SAW
Service:** menerima input terstruktur dan mengembalikan hasil komputasi
SAW.

Database relasional digunakan oleh Laravel sebagai penyimpanan utama.
Python tidak menjadi pemilik data utama dan tidak melakukan query
langsung ke database aplikasi.

## 5.2 Container Diagram

``` mermaid
flowchart TB
    B[User Browser]
    subgraph APP[Same-Origin Web Application]
        SPA[React + TypeScript SPA]
        LAR[Laravel Application]
        API[REST API /api/v1]
        AUTH[Sanctum Session Authentication]
        AUTHZ[RBAC + Permission + Policy]
        BIZ[Application / Business Logic]
    end

    DB[(Relational Database)]
    PY[Python SAW Service]

    B -->|HTTPS, same origin| SPA
    SPA -->|JSON + session cookie + CSRF| API
    API --> AUTH
    API --> AUTHZ
    API --> BIZ
    BIZ --> DB
    BIZ -->|Synchronous internal HTTP| PY
    PY -->|JSON result| BIZ
```

## 5.3 Batas Tanggung Jawab

### React + TypeScript

Bertanggung jawab untuk: - Menampilkan halaman, form, tabel, navigasi,
dan komponen UI. - Mengelola interaksi pengguna dan state tampilan. -
Mengirim request ke endpoint Laravel. - Menampilkan data, status, dan
pesan validasi. - Menyajikan menu berdasarkan konteks role/permission
yang diberikan backend.

Tidak bertanggung jawab untuk: - Menentukan hak akses final. -
Menghitung SAW sebagai sumber hasil resmi. - Mengakses database secara
langsung. - Menyimpan secret backend atau kredensial Python.

### Laravel

Bertanggung jawab untuk: - Menyajikan aplikasi web dan API. - Mengelola
login, logout, session, dan CSRF melalui Sanctum. - Memeriksa role,
permission, override, dan policy resource. - Memvalidasi seluruh
input. - Menjalankan business logic. - Mengelola transaksi dan akses
database. - Menyiapkan payload perhitungan SAW. - Memanggil Python
secara synchronous. - Memvalidasi dan menyimpan hasil SAW. - Menyediakan
data bagi frontend.

Laravel merupakan sumber kebenaran untuk aturan bisnis dan
authorization.

### Python SAW Service

Bertanggung jawab untuk: - Menerima input perhitungan dari Laravel. -
Memvalidasi struktur input komputasi. - Melakukan normalisasi
benefit/cost sesuai konfigurasi yang dikirim. - Menghitung nilai
preferensi. - Mengurutkan alternatif. - Mengembalikan hasil terstruktur
kepada Laravel.

Python tidak bertanggung jawab untuk: - Login atau session pengguna. -
Menentukan permission pengguna. - Mengakses database aplikasi secara
langsung. - Menjadi sumber utama data program kerja, kriteria, bobot,
atau penilaian. - Menyimpan keputusan organisasi.

------------------------------------------------------------------------

# 6. Component Architecture

## 6.1 Backend Laravel

**Rancangan logis yang direkomendasikan:**

``` mermaid
flowchart TB
    ROUTE[API Routes /api/v1]
    MW[Authentication Middleware]
    AUTHZ[Permission Middleware / Policies]
    REQ[Form Request Validation]
    CTRL[Controllers]
    APP[Application Services / Actions]
    MODEL[Eloquent Models]
    DB[(Database)]
    PYCLIENT[Python SAW HTTP Client]
    RESOURCE[API Resources / Response Mapping]

    ROUTE --> MW
    MW --> AUTHZ
    AUTHZ --> REQ
    REQ --> CTRL
    CTRL --> APP
    APP --> MODEL
    MODEL --> DB
    APP --> PYCLIENT
    PYCLIENT --> APP
    APP --> RESOURCE
```

Urutan di atas bersifat konseptual. Laravel dapat menerapkan validasi
dan authorization melalui kombinasi middleware, Form Request, Policy,
dan service sesuai pola repository.

### Lapisan dan tanggung jawab

  -----------------------------------------------------------------------
  Lapisan                             Tanggung jawab
  ----------------------------------- -----------------------------------
  Routes                              Mendefinisikan endpoint dan
                                      middleware.

  Middleware                          Memeriksa session dan pembatasan
                                      request tingkat route.

  Form Requests                       Memvalidasi input dan, bila sesuai,
                                      otorisasi request.

  Controllers                         Mengorkestrasi request/response;
                                      tidak menampung seluruh business
                                      logic.

  Services/Actions                    Menjalankan alur aplikasi dan
                                      business rules.

  Models                              Merepresentasikan entitas dan
                                      relasi data.

  Policies                            Memeriksa akses terhadap resource
                                      tertentu.

  API Resources                       Mengubah model/data menjadi
                                      response API yang konsisten.

  Integration Client                  Mengisolasi komunikasi HTTP ke
                                      Python SAW Service.
  -----------------------------------------------------------------------

**Prinsip:** jangan menambahkan Repository Pattern, Event Bus, CQRS,
atau abstraksi lain secara otomatis. Gunakan hanya jika kebutuhan dan
pola repository mendukungnya.

## 6.2 Modul Backend

Modul logis backend:

  -----------------------------------------------------------------------
  Modul                               Tanggung jawab utama
  ----------------------------------- -----------------------------------
  Identity & Authentication           User, login, logout, session,
                                      profil pengguna.

  Authorization                       Role, permission catalog, role
                                      default permission, membership
                                      override, policy.

  Organization                        Periode kepengurusan, bidang,
                                      keanggotaan.

  Program Work                        Draft, detail, update, pengajuan,
                                      status program kerja.

  PIC Management                      Relasi satu atau beberapa PIC ke
                                      program kerja.

  Evaluation                          Catatan evaluasi dan revisi.

  Decision                            Pencatatan keputusan dan riwayat
                                      keputusan.

  SAW Configuration                   Kriteria, jenis kriteria, dan bobot
                                      per periode.

  SAW Assessment                      Input, validasi, dan penyimpanan
                                      penilaian.

  SAW Calculation                     Persiapan input, pemanggilan
                                      Python, validasi output, riwayat
                                      proses dan hasil.
  -----------------------------------------------------------------------

Modul-modul tersebut merupakan batas tanggung jawab logis, bukan
perintah untuk membuat satu folder atau satu service untuk setiap baris.
Struktur fisik mengikuti repository aktual.

## 6.3 Frontend React + TypeScript

Struktur tanggung jawab frontend yang direkomendasikan:

``` mermaid
flowchart TB
    APP[Application Entry]
    ROUTER[Application Router]
    LAYOUT[Authenticated Layout]
    PAGES[Feature Pages]
    COMPONENTS[Reusable UI Components]
    HOOKS[Feature Hooks]
    API[Typed API Client]
    TYPES[Shared Type Definitions]
    BACKEND[Laravel REST API]

    APP --> ROUTER
    ROUTER --> LAYOUT
    LAYOUT --> PAGES
    PAGES --> COMPONENTS
    PAGES --> HOOKS
    HOOKS --> API
    API --> TYPES
    API --> BACKEND
```

Pembagian logis: - `app`: bootstrap aplikasi dan konfigurasi frontend. -
`pages` atau feature entry: halaman per modul. - `features`: alur fitur,
hook, dan komponen spesifik. - `components`: komponen UI yang dapat
digunakan ulang. - `layouts`: kerangka halaman sesuai konteks
autentikasi. - `lib` atau `services`: API client dan helper. - `types`:
tipe data request/response yang dibutuhkan frontend.

Nama folder di atas adalah contoh rancangan. Jangan memindahkan atau
membuat ulang struktur sebelum memeriksa struktur frontend yang sudah
ada.

### Aturan frontend

1.  Gunakan TypeScript untuk kontrak data.
2.  Pisahkan komponen umum dari komponen khusus fitur.
3.  Panggil backend melalui satu pola API client yang konsisten.
4.  Tangani loading, empty, success, validation error, dan server error.
5.  Menu yang disembunyikan di frontend bukan pengganti authorization
    backend.
6.  Jangan menanam bobot SAW, role, atau permission sebagai aturan
    otoritatif hanya di frontend.
7.  Library routing, state management, dan form validation mengikuti
    dependency yang sudah ada atau dipilih setelah audit.

------------------------------------------------------------------------

# 7. Authentication and Authorization Architecture

## 7.1 Authentication

**Keputusan LOCKED:** Laravel Sanctum dengan cookie-based session
authentication, tanpa JWT.

Alur konseptual:

``` mermaid
sequenceDiagram
    actor U as User
    participant B as Browser / React
    participant L as Laravel
    participant S as Session Store

    U->>B: Membuka halaman login
    B->>L: Meminta CSRF cookie
    L-->>B: CSRF cookie
    U->>B: Mengisi kredensial
    B->>L: POST login + CSRF + cookie
    L->>L: Validasi kredensial
    L->>S: Membuat session
    L-->>B: Login berhasil + session cookie
    B->>L: GET /api/v1/me
    L->>S: Memvalidasi session
    L-->>B: Profil dan konteks akses
```

Aturan: - Browser menggunakan cookie session untuk request
terautentikasi. - Request yang mengubah data harus mengikuti
perlindungan CSRF Sanctum. - Logout mengakhiri session. - Endpoint
privat menolak request tanpa session valid. - Cookie production harus
dikonfigurasi aman sesuai HTTPS dan kebijakan deployment. - Jangan
membuat token JWT untuk menggantikan mekanisme yang telah dipilih.

## 7.2 Authorization Model

Terdapat dua lingkup akses:

1.  **Global system access:** Super Admin diberikan pada akun User dan
    tidak terikat periode.
2.  **Organization access:** role organisasi dan permission default
    diperoleh melalui Keanggotaan pada periode tertentu.

Ketua Umum menggunakan role Pejabat Teras dan memperoleh permission
tambahan `saw.weights.manage` pada keanggotaan terkait.

## 7.3 Effective Permission Resolution

Urutan evaluasi konseptual:

1.  Laravel memastikan pengguna telah terautentikasi.
2.  Sistem menentukan konteks tindakan: global atau organisasi/periode.
3.  Untuk tindakan organisasi, sistem memilih Keanggotaan yang relevan.
4.  Sistem mengambil permission default dari role Keanggotaan.
5.  Sistem memeriksa permission override pada Keanggotaan:
    -   `GRANT`: permission diberikan.
    -   `DENY`: permission ditolak.
    -   `INHERIT` atau tidak ada override: mengikuti default role.
6.  Policy memeriksa aturan resource, misalnya kepemilikan bidang,
    periode, status program kerja, atau relasi PIC.
7.  Request diizinkan hanya jika seluruh pemeriksaan yang relevan
    terpenuhi.

``` mermaid
flowchart TD
    A[Request] --> B{Authenticated?}
    B -->|No| X[401 Unauthorized]
    B -->|Yes| C{Global or organization action?}
    C -->|Global| D[Check global role and permission]
    C -->|Organization| E[Resolve membership and period]
    E --> F[Load role default permission]
    F --> G{Membership override exists?}
    G -->|GRANT| H[Allow permission candidate]
    G -->|DENY| I[Deny]
    G -->|INHERIT / none| J[Use role default]
    D --> K{Permission and policy valid?}
    H --> K
    J --> K
    K -->|No| Y[403 Forbidden]
    K -->|Yes| Z[Execute authorized action]
```

Diagram tersebut menggambarkan aturan konseptual. Super Admin tidak
boleh diasumsikan otomatis melewati semua aturan resource atau business
rule; kewenangan global harus tetap sesuai permission administrasi yang
ditetapkan.

## 7.4 Scope Keanggotaan

-   Satu User hanya memiliki satu Keanggotaan dalam satu periode.
-   Keanggotaan menyimpan role, periode, dan bidang bila relevan.
-   Ketua Bidang dan Pengurus Bidang harus terhubung dengan bidang.
-   Pejabat Teras tidak wajib memiliki bidang.
-   Permission override melekat pada keanggotaan.
-   Permission khusus periode lama tidak otomatis berlaku pada periode
    baru.

**TBD:** cara memilih konteks periode aktif ketika lebih dari satu
periode dapat aktif, karena aturan jumlah periode aktif belum dikunci.

------------------------------------------------------------------------

# 8. API Architecture

## 8.1 Prinsip API

**Keputusan LOCKED:** - REST API. - Prefix `/api/v1`. - JSON. - HTTP
method sesuai operasi. - Laravel API Resources atau mekanisme response
terstandarisasi. - HTTP status code yang tepat. - Pagination untuk
daftar data yang relevan. - Validasi dan error handling di backend.

## 8.2 Kelompok Resource API

Kelompok endpoint konseptual:

  Resource                          Operasi konseptual
  --------------------------------- --------------------------------------------------
  Authentication                    Login, logout, current user.
  Users                             List, create, detail, update, deactivate.
  Periods                           List, create, detail, update.
  Departments/Bidang                List dan administrasi sesuai permission.
  Memberships                       List, create, update, detail.
  Membership Permission Overrides   Read dan update override.
  Program Works                     List, create draft, detail, update, submit.
  Program Work PICs                 Tambah, lihat, dan ubah PIC sesuai kewenangan.
  Program Work Notes                Membuat dan melihat catatan.
  Program Work Decisions            Mencatat dan melihat riwayat keputusan.
  SAW Criteria                      Melihat dan mengelola konfigurasi kriteria.
  SAW Assessments                   Membuat dan memperbarui penilaian sesuai aturan.
  SAW Runs                          Meminta perhitungan dan melihat riwayat proses.
  SAW Results                       Melihat hasil dan ranking.

Daftar ini bukan kontrak endpoint final. Nama route, payload, query
parameters, pagination, dan response schema ditetapkan setelah audit
kode dan API design.

## 8.3 HTTP Method

  -----------------------------------------------------------------------
  Method                              Penggunaan umum
  ----------------------------------- -----------------------------------
  GET                                 Membaca resource.

  POST                                Membuat resource atau menjalankan
                                      aksi seperti submit/calculate.

  PUT                                 Mengganti representasi resource
                                      jika memang diperlukan.

  PATCH                               Memperbarui sebagian field.

  DELETE                              Menghapus resource jika kebijakan
                                      penghapusan mengizinkan.
  -----------------------------------------------------------------------

Jangan menyediakan DELETE untuk data historis atau data yang harus
dipertahankan tanpa keputusan retensi yang jelas.

## 8.4 HTTP Status Code

  -----------------------------------------------------------------------
  Status                              Makna umum
  ----------------------------------- -----------------------------------
  200                                 Request berhasil.

  201                                 Resource berhasil dibuat.

  204                                 Berhasil tanpa response body.

  401                                 Belum terautentikasi atau session
                                      tidak valid.

  403                                 Terautentikasi tetapi tidak
                                      memiliki akses.

  404                                 Resource tidak ditemukan atau tidak
                                      dapat diakses sesuai kebijakan.

  409                                 Konflik state/constraint bisnis.

  422                                 Validasi input gagal.

  500                                 Kesalahan internal aplikasi.

  502/503/504                         Kegagalan atau timeout pada layanan
                                      upstream, sesuai kondisi.
  -----------------------------------------------------------------------

## 8.5 Bentuk Response

Contoh response sukses konseptual:

``` json
{
  "success": true,
  "message": "Program kerja berhasil disimpan",
  "data": {
    "id": 1,
    "nama_proker": "Workshop Linux"
  }
}
```

Contoh response validasi konseptual:

``` json
{
  "success": false,
  "message": "Data yang diberikan tidak valid",
  "errors": {
    "nama_proker": [
      "Nama program kerja wajib diisi."
    ]
  }
}
```

Bentuk final harus konsisten dengan pola repository aktual. Jangan
membuat response wrapper baru jika project sudah memiliki standar yang
berfungsi tanpa alasan migrasi yang kuat.

## 8.6 Pagination dan Filtering

Endpoint daftar data harus mendukung pagination ketika jumlah record
berpotensi bertambah. Filter yang relevan dapat meliputi periode,
bidang, status, dan pencarian. Nama parameter dan default pagination
perlu ditentukan dalam API contract.

------------------------------------------------------------------------

# 9. SAW Integration Architecture

## 9.1 Prinsip Integrasi

-   Laravel adalah pemilik data dan sumber kebenaran proses bisnis.
-   Python adalah layanan komputasi.
-   Komunikasi dilakukan melalui HTTP internal secara synchronous.
-   Browser tidak memanggil Python secara langsung.
-   Python tidak membaca atau menulis database aplikasi.
-   Laravel memvalidasi input sebelum request dan memvalidasi output
    sebelum penyimpanan.

## 9.2 Data yang Dikirim ke Python

Payload secara konseptual memuat: - Identitas atau correlation ID
proses. - Daftar alternatif/program kerja. - Daftar kriteria. - Jenis
kriteria (`benefit`/`cost`). - Bobot kriteria. - Nilai setiap alternatif
untuk setiap kriteria. - Informasi presisi/versi algoritma bila
disepakati.

Payload final dan nama field belum dikunci.

## 9.3 Sequence Diagram

``` mermaid
sequenceDiagram
    actor U as Pejabat Teras
    participant FE as React Frontend
    participant API as Laravel API
    participant AUTH as Authorization
    participant DB as Database
    participant PY as Python SAW Service

    U->>FE: Meminta perhitungan SAW
    FE->>API: POST /api/v1/saw-runs
    API->>AUTH: Periksa session dan permission
    AUTH-->>API: Diizinkan
    API->>DB: Ambil alternatif, kriteria, bobot, penilaian
    DB-->>API: Data perhitungan
    API->>API: Validasi kelengkapan dan bentuk input
    API->>PY: HTTP request synchronous
    PY->>PY: Validasi dan hitung SAW
    PY-->>API: Nilai preferensi dan ranking
    API->>API: Validasi hasil
    API->>DB: Simpan run dan results dalam transaksi
    DB-->>API: Commit berhasil
    API-->>FE: Response hasil ranking
    FE-->>U: Tampilkan ranking
```

## 9.4 Formula Perhitungan

Formula baseline:

Benefit:

`r_ij = x_ij / max(x_ij)`

Cost:

`r_ij = min(x_ij) / x_ij`

Nilai preferensi:

`V_i = Σ(w_j × r_ij)`

Alternatif diurutkan berdasarkan nilai preferensi terbesar ke terkecil.

Pemetaan final benefit/cost, bobot, nilai nol, tie, pembulatan, dan
presisi belum seluruhnya dikunci. Python tidak boleh menambahkan asumsi
bisnis yang tidak ada pada kontrak.

## 9.5 Alur Konsistensi dan Penyimpanan

Rekomendasi alur: 1. Laravel memeriksa autentikasi, permission, periode,
dan kelengkapan input. 2. Laravel membentuk input perhitungan dari data
yang tersimpan. 3. Laravel memanggil Python tanpa menahan transaksi
database yang panjang. 4. Laravel memeriksa struktur dan kelengkapan
respons Python. 5. Jika respons valid, Laravel menyimpan record proses
dan seluruh hasil alternatif dalam satu transaksi database. 6. Jika
proses gagal, Laravel tidak menyimpan hasil parsial sebagai hasil
sukses. 7. Penilaian yang telah tersimpan tidak boleh hilang akibat
kegagalan Python.

**PROPOSED:** simpan snapshot input yang dipakai dalam setiap SAW Run,
meliputi kriteria, bobot, dan nilai penilaian. Bentuk fisik snapshot
(tabel detail atau struktur JSON terkontrol) belum diputuskan.

**TBD:** apakah proses gagal juga disimpan sebagai record historis
berstatus gagal atau hanya dicatat pada log aplikasi.

## 9.6 Timeout dan Error Handling

-   Timeout request ke Python harus dikonfigurasi, bukan dibiarkan tanpa
    batas.
-   Error upstream ditangani dan dipetakan menjadi response API yang
    aman.
-   Detail exception internal tidak dikirim ke browser.
-   Log harus memuat correlation ID atau identitas proses untuk
    memudahkan penelusuran.
-   Laravel tidak boleh menganggap respons kosong/tidak valid sebagai
    perhitungan sukses.
-   Kebijakan retry belum ditetapkan. Jangan melakukan retry otomatis
    tanpa memastikan operasi aman dan tidak menggandakan proses.

## 9.7 Keamanan Layanan

-   Endpoint Python hanya boleh dapat dijangkau dari backend/jaringan
    layanan yang diizinkan.
-   URL dan kredensial layanan berasal dari environment/configuration.
-   Secret tidak disimpan dalam repository.
-   Mekanisme autentikasi antarservice masih TBD dan harus diputuskan
    sebelum production.
-   Input Python tetap divalidasi meskipun Laravel telah melakukan
    validasi.

------------------------------------------------------------------------

# 10. Data Architecture

## 10.1 Prinsip Data

1.  Database aplikasi menjadi penyimpanan utama data organisasi.
2.  Laravel menjadi satu-satunya komponen aplikasi utama yang mengakses
    database.
3.  Relasi penting dijaga dengan foreign key dan constraint.
4.  Riwayat keputusan tidak ditimpa.
5.  Setiap proses SAW memiliki identitas dan hasil sendiri.
6.  Data historis harus tetap dapat ditelusuri ketika konfigurasi
    periode berubah.
7.  Kebijakan penghapusan mengikuti keputusan retensi data, bukan asumsi
    teknis.

## 10.2 Kelompok Entitas

### Identity dan Organization

-   `users`
-   `roles`
-   `permissions`
-   `role_permissions`
-   `periode_kepengurusan`
-   `bidang`
-   `keanggotaan`
-   entitas permission override keanggotaan

### Program Kerja

-   `program_kerja`
-   `program_kerja_pic`
-   `catatan_program_kerja`
-   `keputusan_program_kerja`

### SAW

-   `kriteria_saw`
-   `penilaian_saw`
-   `saw_runs`
-   `saw_run_results`

Daftar ini adalah model konseptual, bukan migration final. Nama tabel,
kolom, tipe data, index, dan aturan delete ditetapkan dalam ERD/data
dictionary setelah audit repository.

## 10.3 Relasi Konseptual

``` mermaid
erDiagram
    USERS ||--o{ KEANGGOTAAN : memiliki
    PERIODE_KEPENGURUSAN ||--o{ KEANGGOTAAN : mencakup
    ROLES ||--o{ KEANGGOTAAN : digunakan
    BIDANG o|--o{ KEANGGOTAAN : menaungi

    PERIODE_KEPENGURUSAN ||--o{ PROGRAM_KERJA : memiliki
    BIDANG ||--o{ PROGRAM_KERJA : pemilik
    PROGRAM_KERJA ||--o{ PROGRAM_KERJA_PIC : memiliki
    KEANGGOTAAN ||--o{ PROGRAM_KERJA_PIC : menjadi_pic

    PROGRAM_KERJA ||--o{ CATATAN_PROGRAM_KERJA : memiliki
    KEANGGOTAAN ||--o{ CATATAN_PROGRAM_KERJA : membuat
    PROGRAM_KERJA ||--o{ KEPUTUSAN_PROGRAM_KERJA : memiliki_riwayat
    KEANGGOTAAN ||--o{ KEPUTUSAN_PROGRAM_KERJA : mencatat

    PERIODE_KEPENGURUSAN ||--o{ KRITERIA_SAW : mengatur
    PROGRAM_KERJA ||--o{ PENILAIAN_SAW : dinilai
    KRITERIA_SAW ||--o{ PENILAIAN_SAW : digunakan

    SAW_RUN ||--o{ SAW_RUN_RESULT : menghasilkan
    PROGRAM_KERJA ||--o{ SAW_RUN_RESULT : menjadi_alternatif
```

Diagram bersifat konseptual. Entitas `SAW_RUN`, `SAW_RUN_RESULT`,
permission override, dan relasi detail lainnya harus diselaraskan dengan
ERD final.

## 10.4 Constraint Utama

Constraint yang sudah diketahui: - Kombinasi `user_id` dan `periode_id`
pada keanggotaan harus unik. - Satu program kerja memiliki satu bidang
pemilik. - Satu program kerja dapat mempunyai beberapa PIC. - Kombinasi
program kerja dan keanggotaan PIC tidak boleh duplikat. - Kriteria SAW
terkait dengan periode. - Satu SAW Run dapat memiliki banyak SAW Run
Result. - Riwayat keputusan tidak dihapus ketika keputusan baru dicatat.

## 10.5 Snapshot Historis SAW

Untuk menjaga keterlacakan, disarankan setiap proses menyimpan data
input yang benar-benar digunakan. Dengan demikian, hasil lama tidak
bergantung pada nilai bobot atau penilaian yang telah berubah.

Rekomendasi isi snapshot: - Periode dan waktu proses. - Identitas/versi
kriteria. - Jenis dan bobot kriteria saat perhitungan. - Nilai input
setiap alternatif. - Hasil normalisasi. - Nilai preferensi. - Ranking. -
Versi algoritma.

**Status:** PROPOSED. Kebutuhan riwayat proses sudah LOCKED, tetapi
bentuk snapshot dan seluruh detail di atas perlu disetujui sebelum
migration final.

## 10.6 Database Engine

MySQL tercantum sebagai baseline dokumen proyek. Sebelum
implementasi: 1. Audit konfigurasi database pada `.env.example` dan file
konfigurasi. 2. Periksa dependency driver database. 3. Periksa migration
yang sudah ada. 4. Periksa lingkungan development/deployment. 5.
Konfirmasi engine yang digunakan.

Jangan mengganti database atau membuat migration baru berdasarkan
asumsi.

------------------------------------------------------------------------

# 11. Deployment Architecture

## 11.1 Target Konseptual

Same-origin deployment berarti pengguna mengakses frontend dan API
melalui origin yang sama. Target utama: - Browser mengakses domain
aplikasi. - Laravel menyajikan React build dan menangani API. - Laravel
mengakses database. - Laravel memanggil Python SAW Service melalui
jaringan layanan. - Database dan Python tidak diekspos langsung ke
browser.

## 11.2 Deployment Diagram

``` mermaid
flowchart TB
    USER[User Browser]
    HTTPS[HTTPS / Same Origin]
    WEB[Laravel Web Application<br/>React build + REST API]
    DB[(MySQL / Database<br/>engine to verify)]
    PY[Python SAW Service]

    USER --> HTTPS
    HTTPS --> WEB
    WEB --> DB
    WEB -->|Internal HTTP request| PY
    PY -->|JSON response| WEB
```

Diagram deployment ini bersifat logical. Pilihan hosting, web server,
process manager, domain, jaringan internal, dan cara menjalankan Python
belum ditentukan.

## 11.3 Environment

Konfigurasi minimal perlu dipisahkan berdasarkan environment: -
Local/development. - Testing. - Production.

Contoh konfigurasi yang perlu dikelola melalui environment/config: -
Database connection. - Session/cookie settings. - Application URL. -
Python SAW service URL. - Python service credentials jika diperlukan. -
Timeout integrasi. - Logging level.

Nama variabel aktual harus mengikuti konvensi project dan tidak boleh
mengganti secret yang sudah ada.

## 11.4 Production Security

-   Gunakan HTTPS.
-   Aktifkan cookie Secure pada HTTPS production.
-   Jangan expose database ke jaringan publik tanpa kebutuhan dan
    kontrol akses.
-   Batasi akses Python Service ke backend.
-   Simpan secret pada environment/secret manager deployment.
-   Nonaktifkan debug mode production.
-   Pastikan log tidak menyimpan password, session cookie, token, atau
    secret.

------------------------------------------------------------------------

# 12. Cross-Cutting Concerns

## 12.1 Logging dan Auditability

Catat kejadian penting: - Login/logout dan kegagalan autentikasi sesuai
kebijakan keamanan. - Perubahan role dan permission override. -
Perubahan periode dan keanggotaan. - Pengajuan dan revisi program
kerja. - Catatan dan keputusan. - Perubahan bobot/kriteria SAW. -
Permintaan perhitungan dan kegagalan integrasi.

Jangan mencatat password, cookie, atau secret. Bentuk audit log permanen
dan retensinya masih perlu diputuskan.

## 12.2 Error Handling

-   Validasi input dilakukan di Laravel.
-   Error validasi disajikan dalam format konsisten.
-   Error 401 dan 403 dibedakan.
-   Error Python dipetakan menjadi error upstream yang aman.
-   Stack trace hanya tersedia di log lingkungan non-production atau log
    server terbatas.
-   Operasi multi-tabel yang harus konsisten menggunakan transaksi.

## 12.3 Concurrency

Potensi kondisi bersamaan: - Dua pengguna memperbarui program kerja yang
sama. - Dua pengguna mengubah konfigurasi bobot periode yang sama. - Dua
permintaan menjalankan SAW hampir bersamaan. - Dua admin membuat
keanggotaan untuk user/periode yang sama.

Perlindungan minimal: - Unique constraint database untuk aturan unik. -
Validasi state dan permission pada backend. - Transaksi pada perubahan
data terkait. - Pertimbangkan optimistic locking/version field jika
konflik update terbukti menjadi masalah.

Strategi locking, idempotency key, dan penanganan double-submit belum
dikunci.

## 12.4 Performance

-   Gunakan pagination untuk daftar data.
-   Ambil relasi yang dibutuhkan secara efisien.
-   Hindari N+1 query.
-   Batasi payload dan jumlah alternatif perhitungan sesuai kebutuhan.
-   Timeout Python dikonfigurasi.
-   Jangan menambah cache atau asynchronous queue untuk SAW karena
    keputusan integrasi yang dipilih adalah synchronous, kecuali ada
    perubahan keputusan.

## 12.5 Testing Architecture

  -----------------------------------------------------------------------
  Area                                Jenis pengujian
  ----------------------------------- -----------------------------------
  Authentication                      Feature test login, logout,
                                      session, CSRF sesuai test
                                      environment.

  Authorization                       Test permission role default dan
                                      override INHERIT/GRANT/DENY.

  Organization                        Test keanggotaan, periode, bidang,
                                      dan constraint unik.

  Program Kerja                       Feature test CRUD, submit, revisi,
                                      PIC, dan keputusan.

  SAW calculation                     Unit test formula benefit/cost dan
                                      edge cases yang telah diputuskan.

  Python integration                  Contract test/request test untuk
                                      sukses, invalid response, error,
                                      dan timeout.

  Frontend                            Component/integration test sesuai
                                      test stack repository.

  End-to-end                          Uji alur utama dari login sampai
                                      ranking/keputusan bila test
                                      environment mendukung.
  -----------------------------------------------------------------------

Pengujian harus mengikuti tools yang sudah digunakan repository setelah
audit. Jangan menambah framework test baru tanpa alasan.

------------------------------------------------------------------------

# 13. Struktur Repository yang Direkomendasikan

Struktur aktual belum diketahui. Setelah ZIP diterima, struktur yang ada
harus diaudit sebelum perubahan dilakukan.

Contoh pembagian tanggung jawab dalam satu repository:

``` text
project-root/
├── app/                    # Laravel backend
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Middleware/
│   │   ├── Requests/
│   │   └── Resources/
│   ├── Models/
│   ├── Policies/
│   └── Services/           # Jika sesuai pola project
│
├── bootstrap/
├── config/
├── database/
│   ├── migrations/
│   ├── factories/
│   └── seeders/
│
├── resources/
│   ├── js/                 # React + TypeScript
│   ├── css/
│   └── views/              # Entry/host view jika digunakan
│
├── routes/
│   ├── web.php
│   └── api.php
│
├── tests/
│
├── python-saw/             # Lokasi service Python (usulan; TBD)
│   ├── app/
│   ├── tests/
│   └── requirements.txt
│
├── docs/
│   ├── diagrams/
│   ├── api/
│   └── database/
│
├── PRD.md
├── SRS.md
├── ARCHITECTURE.md
├── DESIGN.md
├── composer.json
├── package.json
└── vite.config.*
```

Struktur ini hanya contoh logical grouping. Lokasi Python Service dan
struktur frontend/backend final harus mengikuti kondisi repository,
deployment target, dan tooling yang tersedia. Jangan memindahkan folder
existing hanya untuk menyamai contoh ini.

------------------------------------------------------------------------

# 14. Keputusan Arsitektur Terbuka

  -----------------------------------------------------------------------------------------
  ID                Topik                               Status            Catatan
  ----------------- ----------------------------------- ----------------- -----------------
  ARC-TBD-01        Database engine final               Open / verify     MySQL adalah
                                                                          baseline dokumen;
                                                                          audit repository
                                                                          dan deployment.

  ARC-TBD-02        Versi                               Verify            Ambil dari
                    Laravel/PHP/Node/React/TypeScript                     manifest dan
                                                                          lockfile
                                                                          repository.

  ARC-TBD-03        Struktur fisik frontend             Verify            Ikuti struktur
                                                                          aktual dan
                                                                          dependency.

  ARC-TBD-04        Library routing/state               Open / verify     Jangan menambah
                    management/form                                       package sebelum
                                                                          audit.

  ARC-TBD-05        Struktur fisik Python Service       Open              Tentukan lokasi
                                                                          dan runtime
                                                                          deployment.

  ARC-TBD-06        Kontrak HTTP Python                 Open              Endpoint, schema,
                                                                          error, auth
                                                                          service, timeout.

  ARC-TBD-07        Authentication antarservice         Open              Tentukan sebelum
                                                                          production.

  ARC-TBD-08        Snapshot SAW                        Proposed          Riwayat run
                                                                          locked; bentuk
                                                                          snapshot belum
                                                                          final.

  ARC-TBD-09        Riwayat proses gagal                Open              Log saja atau
                                                                          record status
                                                                          failed.

  ARC-TBD-10        API response envelope final         Open / verify     Pertahankan pola
                                                                          existing bila
                                                                          tersedia.

  ARC-TBD-11        Audit log permanen                  Open              Event, retensi,
                                                                          dan akses.

  ARC-TBD-12        Deployment/hosting                  Open              Domain, web
                                                                          server, process
                                                                          manager, network.

  ARC-TBD-13        Session store                       Verify            Ikuti konfigurasi
                                                                          Laravel dan
                                                                          kebutuhan
                                                                          deployment.

  ARC-TBD-14        File storage/backup                 Open              Kebutuhan backup
                                                                          dan recovery
                                                                          belum ditentukan.

  ARC-TBD-15        Concurrency dan idempotency         Open              Tentukan
                                                                          berdasarkan alur
                                                                          dan risiko
                                                                          double-submit.
  -----------------------------------------------------------------------------------------

------------------------------------------------------------------------

# 15. Panduan Implementasi untuk AI Coding Agent

Dokumen ini merupakan acuan arsitektur, bukan izin untuk langsung
merombak repository.

## 15.1 Audit Wajib Sebelum Patch

Saat ZIP repository diberikan: 1. Baca `PRD.md`, `SRS.md`, dan
`ARCHITECTURE.md`. 2. Periksa struktur folder aktual. 3. Periksa
`composer.json` dan `composer.lock`. 4. Periksa `package.json` dan
lockfile JavaScript. 5. Periksa konfigurasi Vite dan entry point React.
6. Periksa `routes/web.php`, `routes/api.php`, dan route lain yang
digunakan. 7. Periksa model, migration, controller, middleware, policy,
request, service, dan test. 8. Periksa `.env.example`, tanpa menampilkan
atau meminta secret `.env`. 9. Identifikasi apakah Python service sudah
tersedia. 10. Cocokkan implementasi dengan requirement dan keputusan
LOCKED.

## 15.2 Aturan Patch

-   Jangan mengasumsikan nama file atau class sebelum memeriksa
    repository.
-   Jangan membuat ulang project Laravel jika project sudah ada.
-   Jangan mengganti versi dependency tanpa alasan dan persetujuan.
-   Jangan mengganti database berdasarkan asumsi.
-   Jangan mengubah autentikasi menjadi JWT.
-   Jangan mengubah SAW menjadi asynchronous.
-   Jangan membiarkan frontend menjadi satu-satunya penjaga akses.
-   Jangan membuat permission baru melalui dashboard.
-   Jangan menimpa riwayat keputusan atau SAW.
-   Jangan mengimplementasikan TBD sebagai keputusan final tanpa
    konfirmasi.
-   Jangan menjalankan reset database, operasi destruktif, commit, atau
    push tanpa instruksi.
-   Setiap patch harus terfokus, dapat direview, dan disertai testing.

## 15.3 Format Hasil Pekerjaan

Setiap tugas harus melaporkan: 1. Tujuan dan requirement ID terkait. 2.
Temuan audit kode sebelum perubahan. 3. Rencana perubahan. 4. Daftar
file yang dibuat/diubah. 5. Patch Git atau kode lengkap. 6. Perintah
untuk menerapkan patch. 7. Perintah testing. 8. Hasil test yang
benar-benar dijalankan. 9. Risiko, keterbatasan, dan TBD yang terkait.

## 15.4 Prinsip Perubahan Arsitektur

Jika implementasi aktual membutuhkan perubahan terhadap dokumen: 1.
Jelaskan masalah teknis atau requirement yang memicu perubahan. 2.
Tunjukkan dampaknya terhadap komponen lain. 3. Berikan opsi dan
rekomendasi. 4. Minta persetujuan jika menyentuh keputusan LOCKED atau
business rule. 5. Setelah disetujui, perbarui dokumen terkait
sebelum/bersamaan dengan patch.

------------------------------------------------------------------------

# 16. Traceability Arsitektur

  -----------------------------------------------------------------------
  Area arsitektur         Acuan PRD               Acuan SRS
  ----------------------- ----------------------- -----------------------
  Frontend/backend        Bagian 4                Bagian 2 dan 3

  Same-origin             Bagian 4.1 dan 4.3      Bagian 2.5 dan 3.4

  Authentication          Bagian 4.1              FR-AUTH-01 s.d.
                                                  FR-AUTH-05

  Authorization           Bagian 2 dan 4          FR-ADM-06, FR-ADM-07,
                                                  FR-MEM-01 s.d.
                                                  FR-MEM-05

  Program kerja           Bagian 3.4              FR-PRO-01 s.d.
                                                  FR-PRO-12

  SAW                     Bagian 3.6 dan 4.5      FR-SAW-01 s.d.
                                                  FR-SAW-09

  Python integration      Bagian 4.3 dan 4.5      Bagian 3.3 dan
                                                  FR-SAW-07

  Data model              Bagian 6                Bagian 6

  Security                Bagian 5                Bagian 7.1

  Testing                 Bagian 7 dan 9          Bagian 9
  -----------------------------------------------------------------------

------------------------------------------------------------------------

**Akhir Dokumen --- ARCHITECTURE v0.1**

Status: Draft arsitektur yang diturunkan dari `PRD.md` dan `SRS.md`.
Struktur fisik, versi dependency, database final, dan kontrak Python
harus diverifikasi setelah repository diaudit.
