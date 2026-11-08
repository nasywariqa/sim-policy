# PHASES --- DEVELOPMENT ROADMAP

## Sistem Informasi Manajemen UKM Polytechnic Linux Community

  -----------------------------------------------------------------------
  Informasi                           Nilai
  ----------------------------------- -----------------------------------
  Dokumen                             Development Phases & Work Breakdown

  Versi                               1.2

  Tanggal                             8 November 2026

  Status                              **APPROVED BASELINE - MVP IMPLEMENTED; QA PENDING**

  Project                             `sim-ukm-plc`

  Repository                          Laravel monorepo

  Acuan                               `PRD.md`, `SRS.md`,
                                      `ARCHITECTURE.md`, `DESIGN.md`, dan
                                      audit ZIP terbaru
                                      `sim-ukm-plc-current.zip`
  -----------------------------------------------------------------------

> **Fungsi dokumen:** menjadi sumber konteks utama untuk urutan fase,
> ruang lingkup, hasil yang diharapkan, dependency, dan pemeriksaan
> minimum. Dokumen ini mencegah pekerjaan melompat fase, membuat fitur
> berdasarkan asumsi, atau menambahkan kode yang tidak diperlukan.

------------------------------------------------------------------------

# 1. Prinsip Pengerjaan yang Disepakati

Roadmap 12 fase telah disetujui pemilik proyek. Urutan fase menjadi
baseline kerja. Detail task boleh disesuaikan berdasarkan kondisi
repository dan hasil validasi, tetapi perubahan urutan atau scope yang
berdampak besar harus dibicarakan terlebih dahulu.

## 1.1 Prioritas Utama

Tujuan utama proyek adalah menghasilkan sistem yang **siap digunakan dan
fungsional sesuai kebutuhan inti**, bukan mengejar kesempurnaan teknis
atau membuat arsitektur yang berlebihan.

Urutan prioritas:

1.  Fitur yang diminta PRD/SRS benar-benar bekerja.
2.  Data tersimpan dan dibaca dengan benar.
3.  Hak akses dan validasi bisnis yang penting berjalan di backend.
4.  Alur utama antarfitur terhubung.
5.  Tampilan konsisten dan mudah digunakan sesuai DESIGN.
6.  Pengujian minimum membuktikan fungsi inti.
7.  Penyempurnaan tambahan dilakukan hanya jika dibutuhkan dan waktunya
    memungkinkan.

## 1.2 Prinsip Kode: Hanya yang Dibutuhkan

-   Jangan membuat kode, modul, class, route, tabel, komponen, service,
    atau konfigurasi yang belum dibutuhkan oleh task aktif.
-   Jangan membuat placeholder, stub, mock endpoint, halaman kosong,
    atau TODO scaffolding yang tidak dipakai oleh alur nyata.
-   Jangan menambah package hanya karena populer atau mungkin berguna.
    Tambahkan hanya jika ada kebutuhan nyata yang tidak praktis dipenuhi
    oleh dependency yang sudah tersedia.
-   Jangan membuat abstraksi berlapis untuk operasi sederhana. Gunakan
    pola yang cukup untuk kebutuhan aktual.
-   Jangan membuat data palsu seolah-olah data production.
-   Data contoh hanya boleh dipakai untuk local development/testing,
    harus jelas sebagai data contoh, dan tidak boleh menjadi sumber
    hasil bisnis.
-   Jangan menambahkan fitur di luar PRD/SRS tanpa persetujuan.
-   Jangan mengimplementasikan keputusan TBD dengan asumsi.
-   Jangan mengganti teknologi atau keputusan LOCKED tanpa persetujuan.
-   Hapus import, dependency, route, atau komponen yang benar-benar
    tidak digunakan jika perubahan tersebut aman dan terkait langsung
    dengan pekerjaan.
-   Jangan menghapus kode atau data yang belum dipahami. Audit dahulu
    sebelum menghapus.

## 1.3 Strategi Pengujian Bertahap

Pengujian dilakukan **secara berangsur setelah satu fase selesai**,
bukan membuat seluruh test suite lengkap sebelum fitur inti selesai.

Setiap fase cukup melewati pemeriksaan minimum yang relevan: -
Build/boot check untuk memastikan aplikasi dapat dijalankan. - Smoke
test pada alur utama fase tersebut. - Pemeriksaan validasi dan
permission jika fase menyentuh data privat atau aksi penting. - Test
otomatis terfokus jika aturan bisnis penting dapat diuji dengan mudah.

Tidak perlu mengejar 100% test coverage, performance benchmark, load
test, atau automation kompleks jika tidak menjadi kebutuhan proyek.
Pemeriksaan tersebut hanya ditambahkan jika ada risiko nyata atau
diminta.

**Aturan pelaporan:** jangan menyatakan test lulus jika belum
dijalankan. Jika pemeriksaan belum dilakukan, tulis `Belum dijalankan`.

## 1.4 Siklus Kerja Setiap Patch

1.  Periksa file dan implementasi aktual yang akan disentuh.
2.  Pastikan task sesuai fase aktif dan requirement.
3.  Jelaskan tujuan serta file yang akan dibuat/diubah.
4.  Berikan patch yang terfokus dan dapat diterapkan.
5.  Pemilik proyek menerapkan patch di repository lokal.
6.  Jalankan pemeriksaan minimum yang disebutkan.
7.  Perbaiki error sebelum melanjutkan.
8.  Review perubahan dan buat commit setelah disetujui.

Jangan mengasumsikan isi repository dari nama file atau struktur ideal.
Selalu periksa file aktual terlebih dahulu.

## 1.5 Definition of Done Satu Fase

Satu fase dapat dinyatakan selesai apabila:

-   Deliverable fase tersedia.
-   Alur utama fase dapat dijalankan.
-   Pemeriksaan minimum yang relevan telah dilakukan.
-   Tidak ada error blocker yang diketahui.
-   Tidak ada requirement TBD yang diam-diam diputuskan.
-   Perubahan tidak merusak fungsi yang sudah selesai dan relevan.
-   Pemilik proyek telah melihat hasil dan menyetujui kelanjutan.
-   Perubahan disimpan dalam commit Git.

------------------------------------------------------------------------

# 2. Keputusan Arsitektur yang Harus Dipertahankan

Keputusan berikut tercatat sebagai keputusan LOCKED pada dokumen proyek
dan menjadi batas implementasi:

  -----------------------------------------------------------------------
  Area                                Keputusan
  ----------------------------------- -----------------------------------
  Backend                             Laravel

  Frontend                            React + TypeScript

  Repository                          Satu repository (monorepo)

  Deployment                          Same-origin; Laravel menyajikan
                                      frontend dan API

  Authentication                      Laravel Sanctum, cookie-based
                                      session, tanpa JWT

  Authorization                       RBAC + permission + middleware +
                                      policy

  Permission catalog                  Tetap, ditentukan developer

  Default permission                  Diperoleh dari role

  Permission khusus                   Melekat pada keanggotaan per
                                      periode

  Permission override                 `INHERIT`, `GRANT`, `DENY`

  API                                 REST API dengan prefix `/api/v1`

  SAW integration                     Laravel memanggil Python secara
                                      synchronous

  SAW source of truth                 Laravel melakukan validasi dan
                                      menyimpan hasil/riwayat

  Frontend SAW                        React tidak menghitung SAW dan
                                      tidak memanggil Python langsung

  PIC                                 Satu program kerja dapat memiliki
                                      banyak PIC melalui relasi
                                      keanggotaan

  Riwayat keputusan                   Keputusan baru tidak menghapus
                                      riwayat sebelumnya

  UI                                  Dark-only, landing page singkat,
                                      hero memakai preview dashboard,
                                      animasi subtle, navigasi landing
                                      page menggunakan scroll/anchor
  -----------------------------------------------------------------------

Ketentuan tambahan: - Ketua Umum merupakan Pejabat Teras dengan
permission tambahan `saw.weights.manage` pada keanggotaannya. - Super
Admin bersifat global, bukan role yang harus melekat pada keanggotaan
periode. - Jangan membuat role Ketua Umum terpisah jika tidak ada
keputusan baru yang mengubah model tersebut. - Database MySQL masih
tercantum sebagai baseline dokumen, tetapi belum menjadi keputusan
final. Verifikasi dan kunci pada Phase 0.

------------------------------------------------------------------------

# 3. Kondisi Awal Repository Berdasarkan ZIP Audit

Audit dilakukan terhadap file `sim-ukm-plc-phase-0.zip` yang dikirim pada
4 November 2026. Kondisi ini adalah snapshot saat ZIP dibuat; jika
repository berubah setelah itu, periksa kondisi terbaru sebelum memberi
patch.

## 3.1 Kondisi Aktual

  -----------------------------------------------------------------------
  Komponen                            Kondisi saat audit
  ----------------------------------- -----------------------------------
  Laravel                             Laravel Framework `v13.40.0` pada
                                      `composer.lock`

  Composer requirement                PHP `^8.3`, Laravel `^13.0`

  Composer lock                       Ada

  Vite                                `^7.0.7` pada `package.json`

  Laravel Vite plugin                 `^2.0.0`

  Tailwind CSS                        `^4.0.0` dengan
                                      `@tailwindcss/vite` `^4.0.0`

  Axios                               `^1.11.0`

  React                               Belum ada pada `package.json`

  TypeScript                          Belum ada dependency atau
                                      konfigurasi TypeScript

  Sanctum                             Belum ada pada `composer.json`

  API routes                          `bootstrap/app.php` belum
                                      mendaftarkan `routes/api.php`;
                                      file tersebut belum tersedia

  Web route                           `routes/web.php` hanya memetakan
                                      `/` ke view `welcome`

  Model                               Baru model `User` bawaan

  Migration                           Tiga migration bawaan Laravel:
                                      users, cache, jobs

  Database config example             `.env.example` menetapkan
                                      `DB_CONNECTION=sqlite`

  Database fallback                   `config/database.php` menggunakan
                                      `sqlite` sebagai fallback

  Database file                       `database/database.sqlite`
                                      tersedia pada ZIP

  Database baseline dokumen           MySQL; final engine belum diputuskan

  Python SAW service                  Belum ada

  Testing                             PHPUnit/Laravel test skeleton
                                      bawaan

  Frontend entry                      `resources/js/app.js` dan
                                      `resources/css/app.css`

  Vite input                          `resources/css/app.css`,
                                      `resources/js/app.js`

  npm lockfile                        Tidak ada pada ZIP; verifikasi ulang
                                      sebelum instalasi dependency
  -----------------------------------------------------------------------

## 3.2 Konsekuensi terhadap Pengerjaan

-   Project tidak perlu dibuat ulang.
-   React dan TypeScript harus diintegrasikan ke Laravel/Vite yang sudah
    ada.
-   Jangan menganggap React, Sanctum, API, atau Python service sudah
    tersedia.
-   Jangan menghapus Blade atau mengganti routing sebelum strategi
    frontend Phase 1 diputuskan dan diuji.
-   Migration bisnis belum dibuat.
-   Database engine harus dipastikan sebelum migration domain dibuat.
-   Pertahankan dependency yang sudah tersedia jika memang digunakan.
-   Periksa `composer.json`, `package.json`, lockfile, dan konfigurasi
    aktual sebelum menambah package.

------------------------------------------------------------------------

# 4. Roadmap Utama --- 12 Fase

Urutan berikut merupakan roadmap yang disetujui:

  -----------------------------------------------------------------------
  Fase                    Nama                    Hasil utama
  ----------------------- ----------------------- -----------------------
  0                       Requirement Alignment & Requirement, TBD,
                          Planning                keputusan teknis, dan
                                                  dependency jelas

  1                       Project Foundation      React + TypeScript
                                                  terintegrasi dengan
                                                  Laravel/Vite; landing
                                                  page berjalan

  2                       Database Design &       Database dan tabel
                          Migration               domain siap

  3                       Authentication          Login, logout, session,
                                                  dan identitas pengguna
                                                  berjalan

  4                       Organization &          Pengguna, periode,
                          Authorization           bidang, keanggotaan,
                                                  role, permission
                                                  berjalan

  5                       Program Kerja           Draft, CRUD sesuai hak
                          Management              akses, PIC, dan
                                                  pengajuan berjalan

  6                       Evaluation & Revision   Catatan evaluasi,
                                                  revisi, dan pengajuan
                                                  ulang berjalan

  7                       SAW Configuration &     Kriteria, bobot, dan
                          Assessment              penilaian siap

  8                       Python SAW Integration  Perhitungan SAW,
                                                  ranking, dan riwayat
                                                  berjalan

  9                       Decision & Completion   Keputusan dan
                                                  penyelesaian program
                                                  kerja berjalan

  10                      Dashboard & Reporting   Dashboard menampilkan
                                                  data aktual sesuai
                                                  akses

  11                      Quality Assurance &     Alur inti diverifikasi
                          Deployment              dan aplikasi siap
                                                  dijalankan pada
                                                  environment target
  -----------------------------------------------------------------------

------------------------------------------------------------------------

# 5. Detail Work Breakdown per Fase

## PHASE 0 --- Requirement Alignment & Planning

**Status:** Complete for MVP foundation; business TBD items remain open.
**Tujuan:** menyelaraskan keputusan teknis fondasi dan memastikan
keputusan bisnis yang belum selesai tetap ditandai sebagai asumsi
sementara, bukan keputusan final.

### Task

  -----------------------------------------------------------------------
  ID                      Pekerjaan               Output
  ----------------------- ----------------------- -----------------------
  P0-01                   Baca dan audit PRD,     Daftar gap dokumen
                          SRS, ARCHITECTURE,      
                          DESIGN                  

  P0-02                   Buat crosswalk ID TBD   TBD cross-reference
                          PRD dan SRS berdasarkan 
                          topik                   

  P0-03                   Perbaiki status/riwayat Dokumen sinkron
                          revisi yang tidak       
                          konsisten               

  P0-04                   Konfirmasi database     Keputusan database
                          engine untuk            
                          development dan target  
                          deployment              

  P0-05                   Tentukan strategi       Frontend decision
                          routing React dan       
                          struktur frontend       
                          minimal                 

  P0-06                   Tentukan API            API contract baseline
                          response/error          
                          convention yang         
                          sederhana dan konsisten 

  P0-07                   Tentukan kebutuhan      Dependency decision
                          package frontend yang   
                          benar-benar diperlukan  

  P0-08                   Konfirmasi keputusan    Decision register
                          TBD yang diperlukan     
                          untuk fase terdekat     

  P0-09                   Tetapkan milestone dan  Working agreement
                          branch/commit workflow  
  -----------------------------------------------------------------------

### Temuan dokumen yang wajib diperhatikan

Jangan memetakan TBD hanya berdasarkan nomor ID karena beberapa topik
pada PRD dan SRS tidak sejajar.

Contoh perbedaan yang ditemukan: - PRD `TBD-013` membahas keputusan
pleno dan keputusan berulang, sedangkan SRS `TBD-013` membahas
pergantian jabatan dalam periode yang sama. - PRD `TBD-014` membahas
aktor dan data untuk menandai selesai, sedangkan SRS `TBD-014` membahas
aktor dan bukti penyelesaian. - PRD `TBD-016` membahas database final,
sedangkan SRS `TBD-016` membahas kontrak Python. - PRD `TBD-017`
membahas deployment, sedangkan SRS `TBD-017` membahas database. - PRD
`TBD-018` membahas UI/UX, sedangkan SRS `TBD-018` membahas topologi
production. - SRS memiliki `TBD-019` sampai `TBD-022` yang perlu
dicross-reference ke PRD jika relevan. - Ringkasan status TBD di bagian
akhir PRD perlu diperiksa karena tidak mencerminkan seluruh daftar TBD
yang masih ada di tabelnya.

### Cross-reference TBD antar dokumen

Nomor TBD bersifat lokal per dokumen. Nomor yang sama pada PRD, SRS,
ARCHITECTURE, atau DESIGN tidak otomatis merujuk pada topik yang sama.
Pemetaan berikut berdasarkan topik, bukan penyamaan nomor.

| Topik | PRD | SRS | Referensi terkait | Catatan |
|---|---|---|---|---|
| Status `Dalam Evaluasi` | TBD-001 | TBD-001 | DESIGN UI-TBD-13 | Topik sama |
| Edit/hapus catatan evaluasi | TBD-002 | TBD-002 | DESIGN UI-TBD-15 | Topik sama |
| PIC lintas bidang | TBD-003 | TBD-003 | â€” | Topik sama |
| Pemetaan benefit/cost | TBD-004 | TBD-004 | â€” | Topik sama |
| Total bobot | TBD-005 | TBD-005 | â€” | Topik sama |
| Anggaran Rp0 | TBD-006 | TBD-006 | â€” | Topik sama |
| Normalisasi SAW nilai nol | TBD-007 | TBD-007 | â€” | Topik sama |
| Tie/ranking sama | TBD-008 | TBD-008 | â€” | Topik sama |
| Snapshot SAW | TBD-009 | TBD-009 | ARCH ARC-TBD-08 | Topik sama |
| Reset/aktivasi akun | TBD-010 | TBD-010 | â€” | Topik sama |
| Kebijakan penghapusan data | TBD-011 | TBD-011 | â€” | Topik sama |
| Periode aktif | TBD-012 | TBD-012 | â€” | Topik sama |
| Keputusan pleno: aktor dan pengulangan | TBD-013 | TBD-022 | â€” | Nomor berbeda, topik sama |
| Pergantian jabatan dalam periode | â€” | TBD-013 | â€” | Tidak memiliki pasangan TBD langsung pada PRD; perlu ditelusuri terhadap aturan keanggotaan |
| Aktor/bukti penyelesaian | TBD-014 | TBD-014 | â€” | Topik sama |
| Visibilitas ranking | TBD-015 | TBD-015 | DESIGN UI-TBD-14 | Topik sama |
| Database engine final | TBD-016 | TBD-017 | ARCH ARC-TBD-01 | Nomor berbeda, topik sama |
| Deployment/hosting | TBD-017 | TBD-018 | ARCH ARC-TBD-12 | Nomor berbeda, topik sama |
| Detail UI/UX | TBD-018 | â€” | DESIGN UI-TBD-01 s.d. UI-TBD-15 | Tidak ada satu TBD SRS yang menjadi pasangan langsung |
| Kontrak API Python | â€” | TBD-016 | ARCH ARC-TBD-06 | Tidak memiliki pasangan TBD PRD langsung |
| Field wajib dan batas validasi form | â€” | TBD-019 | Requirement fields pada modul terkait | Tidak ada TBD PRD langsung; perlu ditelusuri per form |
| Hak CRUD data Bidang | â€” | TBD-020 | PRD bagian 2.4 dan 3.3 | Gap kewenangan yang perlu diputuskan |
| Visibilitas program kerja lintas bidang | â€” | TBD-021 | PRD bagian 2.4 dan 3.5 | Gap kewenangan/visibilitas yang perlu diputuskan |

Cross-reference ini tidak menghapus atau mengganti nomor ID pada dokumen
sumber. `SRS TBD-013` membahas pergantian jabatan dalam periode yang sama,
sedangkan `PRD TBD-013` membahas aktor dan pengulangan keputusan pleno.
`SRS TBD-016` membahas kontrak API Python, sedangkan `PRD TBD-016`
membahas database final.

### Status pekerjaan Phase 0 setelah audit awal

| Task | Status | Hasil |
|---|---|---|
| P0-01 Audit dokumen dan repository | Selesai | Gap dokumen dan struktur repository terbaru telah diperiksa |
| P0-02 Cross-reference ID TBD | Selesai | Cross-reference berdasarkan topik tersedia |
| P0-03 Sinkronisasi dokumen | Selesai pada patch ini | PRD/SRS/ARCHITECTURE/PHASES diselaraskan dengan keputusan dan audit MVP |
| P0-04 Konfirmasi database engine | LOCKED | MySQL untuk development dan production; migration serta seeder berhasil dijalankan pemilik proyek |
| P0-05 Routing React dan struktur frontend | Selected | React Router DOM v7 BrowserRouter pada React SPA same-origin |
| P0-06 API response/error convention | Selected | JSON `data` untuk resource, `message` untuk aksi, error validasi default Laravel |
| P0-07 Dependency frontend | Selected | React, React DOM, React Router DOM, TypeScript, Vite React plugin, Tailwind, Axios; tanpa state manager/form library tambahan |
| P0-08 Keputusan TBD untuk fase terdekat | Berjalan dengan asumsi MVP | TBD bisnis yang belum final tetap terbuka; asumsi demo dicatat di `RUN_DEMO.md` |
| P0-09 Workflow Git dan milestone | Baseline disetujui | Patch terfokus, pemeriksaan minimum, review, lalu commit |

Phase 0 selesai untuk fondasi MVP: database, routing, convention API,
dan dependency minimal telah dipilih serta tercermin pada repository.
TBD bisnis yang tidak menghalangi demo tetap terbuka. Perilaku sementara
yang bergantung pada TBD tidak boleh dianggap sebagai keputusan produk
final; lihat `RUN_DEMO.md`.

### Keputusan TBD menurut fase kebutuhan

  -----------------------------------------------------------------------
  Topik                               Status / target
  ----------------------------------- -----------------------------------
  Database final                      Resolved — MySQL locked before Phase 2

  Status `Dalam Evaluasi` (`TBD-001`) Phase 5

  PIC lintas bidang (`TBD-003`)       Phase 5

  Penghapusan data (`TBD-011`)        Sebelum aksi hapus dibuat

  Siklus hidup akun (`TBD-010`)       Phase 4 jika memengaruhi
                                      aktivasi/nonaktif

  Catatan append-only/edit/delete     Phase 6
  (`TBD-002`)                         

  Benefit/cost, total bobot, nilai    Phase 7--8
  nol, tie, snapshot (`TBD-004` s.d.  
  `TBD-009`)                          

  Visibilitas ranking (`TBD-015`)     Sebelum ranking ditampilkan pada
                                      Phase 8/10

  Hak keputusan pleno (`TBD-013` PRD  Phase 9
  / topik terkait SRS)                

  Aturan penyelesaian (`TBD-014` PRD  Phase 9
  / topik terkait SRS)                

  Kontrak Python                      Implemented for local MVP; production hardening remains open

  Deployment                          Phase 11
  -----------------------------------------------------------------------

TBD yang tidak memengaruhi fase aktif boleh tetap terbuka. Jangan
memaksa semua keputusan bisnis selesai sebelum landing page dapat
dibuat.

### Pemeriksaan minimum Phase 0

-   Dokumen yang menjadi sumber keputusan sudah ditentukan.
-   Daftar perbedaan dan TBD dicatat berdasarkan topik.
-   Keputusan yang dibutuhkan untuk Phase 1 dan 2 sudah jelas.
-   Tidak ada kode yang perlu dibuat pada fase ini.

### Exit criteria

Phase 0 selesai untuk fondasi MVP setelah:
- Cross-reference dan sinkronisasi dokumen diperbarui.
- MySQL untuk development dan production dikonfirmasi.
- React Router DOM dan struktur frontend minimal dipilih.
- Convention response/error API sederhana dicatat.
- Dependency minimal yang benar-benar digunakan dipilih.
- TBD bisnis yang belum disetujui tetap terbuka dan asumsi demo diberi label sementara.

Keputusan TBD yang tidak diperlukan untuk alur MVP tidak dipaksakan menjadi final.

------------------------------------------------------------------------

## PHASE 1 --- Project Foundation

**Status:** Menunggu Phase 0\
**Tujuan:** mengintegrasikan React + TypeScript pada Laravel/Vite yang
sudah ada dan membuat fondasi UI yang langsung digunakan.

### Task

  -----------------------------------------------------------------------
  ID                      Pekerjaan               Output
  ----------------------- ----------------------- -----------------------
  P1-01                   Audit ulang package dan Dependency map
                          file frontend aktual    

  P1-02                   Integrasikan React      React berjalan

  P1-03                   Konfigurasi TypeScript  Type checking berjalan

  P1-04                   Sesuaikan Vite untuk    Vite build berjalan
                          React + TypeScript      

  P1-05                   Pertahankan dan gunakan Styling aktif
                          Tailwind CSS yang sudah 
                          tersedia                

  P1-06                   Tentukan routing        Route foundation
                          frontend minimal sesuai 
                          kebutuhan               
                          public/private page     

  P1-07                   Siapkan API client      API client dasar
                          hanya jika diperlukan   
                          untuk integrasi         
                          berikutnya              

  P1-08                   Terapkan design tokens  UI foundation
                          dark-only yang          
                          benar-benar digunakan   

  P1-09                   Buat public landing     Landing page berfungsi
                          page lima section       

  P1-10                   Pastikan CTA Masuk      Navigasi login
                          menuju halaman login    
                          yang akan dibangun pada 
                          Phase 3                 
  -----------------------------------------------------------------------

### Batasan

-   Jangan menginstal Inertia; arsitektur menggunakan React SPA dan
    Laravel REST API.
-   Jangan membuat halaman internal yang belum memiliki data/fitur.
-   Jangan membuat komponen UI generik yang belum dipakai.
-   Jangan menambah state management library sebelum kebutuhan nyata
    muncul.
-   Jangan menambah routing/form library sebelum dipastikan diperlukan.
-   Landing page menggunakan konten sesuai DESIGN dan tidak membutuhkan
    data privat.
-   Login page dapat dibuat pada Phase 3; CTA boleh diarahkan ke route
    login yang sudah disepakati, tetapi jangan membuat login palsu.

### Pemeriksaan minimum Phase 1

-   `npm run build` berhasil.
-   Laravel dapat dijalankan.
-   Landing page dapat dibuka pada same origin.
-   Navigasi anchor dan CTA bekerja.
-   Tidak ada error runtime yang menghalangi halaman.

### Exit criteria

React + TypeScript berjalan di dalam project Laravel, landing page
berfungsi, dan build berhasil.

------------------------------------------------------------------------

## PHASE 2 --- Database Design & Migration

**Status:** Menunggu Phase 0 dan fondasi keputusan database\
**Tujuan:** membangun schema yang benar-benar diperlukan untuk modul
aplikasi.

### Task

  -----------------------------------------------------------------------
  ID                      Pekerjaan               Output
  ----------------------- ----------------------- -----------------------
  P2-01                   Audit migration bawaan  Baseline migration
                          dan database aktual     

  P2-02                   Finalisasi ERD dan data Data model
                          dictionary              

  P2-03                   Finalisasi tipe data,   Constraint plan
                          nullability, FK,        
                          unique, index           

  P2-04                   Buat migration identity Tabel user/organisasi
                          dan organization        

  P2-05                   Buat migration role dan Tabel authorization
                          permission              

  P2-06                   Buat migration program  Tabel program kerja
                          kerja dan PIC           

  P2-07                   Buat migration evaluasi Tabel catatan/keputusan
                          dan keputusan           

  P2-08                   Buat migration SAW dan  Tabel SAW
                          riwayat                 

  P2-09                   Buat seeder minimum     Data master minimum
                          yang dibutuhkan untuk   
                          role/permission awal    
  -----------------------------------------------------------------------

Urutan migration harus mengikuti dependency foreign key. Daftar entitas
final mengikuti PRD/SRS dan hasil keputusan Phase 0; jangan membuat
tabel hanya karena ada pada rancangan konseptual jika belum dibutuhkan
oleh fitur versi pertama.

### Constraint penting

-   Kombinasi `user_id + periode_id` unik untuk keanggotaan.
-   Relasi PIC menggunakan `keanggotaan_id`.
-   Satu program kerja dapat mempunyai beberapa PIC.
-   Konfigurasi kriteria SAW terikat pada periode.
-   Riwayat SAW disimpan per proses perhitungan.
-   Riwayat keputusan tidak ditimpa.
-   Constraint tambahan hanya dibuat setelah aturan bisnisnya jelas.

### Pemeriksaan minimum Phase 2

-   Migration dapat dijalankan pada database development kosong.
-   Migration status menunjukkan seluruh migration yang diharapkan telah
    berjalan.
-   Relasi dan constraint utama dapat diverifikasi.
-   Jika rollback diuji, lakukan hanya pada database lokal yang
    aman/dapat dibangun ulang.
-   Jangan menjalankan operasi reset pada database yang berisi data
    penting.

### Exit criteria

Database dapat dibangun dari migration dan siap dipakai oleh
authentication serta modul organisasi.

------------------------------------------------------------------------

## PHASE 3 --- Authentication

**Status:** Menunggu Phase 2\
**Tujuan:** membuat autentikasi session yang benar-benar berfungsi.

### Task

  -----------------------------------------------------------------------
  ID                      Pekerjaan               Output
  ----------------------- ----------------------- -----------------------
  P3-01                   Instalasi dan           Sanctum aktif
                          konfigurasi Laravel     
                          Sanctum                 

  P3-02                   Konfigurasi same-origin Session aman
                          cookie/session dan CSRF 

  P3-03                   Siapkan API route group API entry
                          `/api/v1`               

  P3-04                   Implementasi login      Login API

  P3-05                   Implementasi logout     Logout API

  P3-06                   Implementasi            Identitas pengguna
                          current-user endpoint   

  P3-07                   Integrasikan login      Login UI
                          React                   

  P3-08                   Tangani session expired Feedback UI
                          dan error login         
  -----------------------------------------------------------------------

### Batasan

-   Tidak menggunakan JWT.
-   Tidak membuat self-registration publik.
-   Jangan menambah forgot password, OTP, atau social login jika belum
    menjadi requirement.
-   Jangan menyimpan password atau session secret di frontend.
-   Backend menjadi sumber kebenaran autentikasi.

### Pemeriksaan minimum Phase 3

-   Login dengan akun valid berhasil.
-   Login invalid ditolak.
-   Endpoint privat menolak request tanpa session.
-   Logout mengakhiri session.
-   CSRF/session berjalan pada alur same-origin.
-   Current-user endpoint mengembalikan user aktif.

### Exit criteria

Pengguna dapat login, mengakses halaman privat, melihat identitas
sendiri, dan logout.

------------------------------------------------------------------------

## PHASE 4 --- Organization & Authorization

**Status:** Menunggu Phase 3\
**Tujuan:** membuat struktur organisasi dan sistem hak akses yang
menjadi dasar semua modul privat.

### Task A --- Organization

-   Administrasi pengguna sesuai requirement.
-   Pengelolaan periode kepengurusan.
-   Pengelolaan bidang.
-   Pengelolaan keanggotaan.
-   Penetapan role pada keanggotaan.
-   Super Admin global.

### Task B --- Authorization

-   Permission catalog tetap.
-   Permission default berdasarkan role.
-   Permission override per keanggotaan.
-   Resolver `INHERIT`, `GRANT`, `DENY`.
-   Middleware dan policy Laravel.
-   Pemeriksaan akses berdasarkan periode dan resource.

### Batasan

-   Ketua Umum bukan role terpisah; Ketua Umum adalah Pejabat Teras
    dengan permission tambahan `saw.weights.manage`.
-   Jangan memberi permission permanen pada user jika keputusan
    menyatakan permission melekat pada keanggotaan.
-   Jangan hanya menyembunyikan tombol/menu di React; backend wajib
    memvalidasi.
-   Super Admin tidak dipaksa memiliki keanggotaan periode.
-   Hak CRUD bidang, pergantian jabatan, aktivasi akun, dan periode
    aktif mengikuti keputusan TBD terkait.

### Pemeriksaan minimum Phase 4

-   Super Admin dapat mengakses fungsi administrasi yang disetujui.
-   Role Pejabat Teras mendapatkan permission default yang disepakati.
-   Override `GRANT` menambah permission khusus.
-   Override `DENY` menolak permission yang diwarisi.
-   Override satu keanggotaan tidak memengaruhi periode lain.
-   Request tanpa permission ditolak oleh backend.

### Exit criteria

Pengguna, periode, bidang, keanggotaan, role, dan permission dapat
dikelola; authorization backend bekerja untuk kombinasi akses inti.

------------------------------------------------------------------------

## PHASE 5 --- Program Kerja Management

**Status:** Menunggu Phase 4\
**Tujuan:** membuat alur program kerja dari draft hingga pengajuan.

### Task

  -----------------------------------------------------------------------
  ID                      Pekerjaan               Output
  ----------------------- ----------------------- -----------------------
  P5-01                   Membuat draft program   Create draft
                          kerja                   

  P5-02                   Menampilkan daftar      List
                          program kerja sesuai    
                          akses                   

  P5-03                   Menampilkan detail      Detail
                          program kerja           

  P5-04                   Memperbarui draft       Update
                          sesuai                  
                          status/permission       

  P5-05                   Menetapkan satu atau    Multiple PIC
                          beberapa PIC            

  P5-06                   Review Ketua Bidang     Review
                          sesuai alur requirement 

  P5-07                   Mengajukan program      Submit
                          kerja                   

  P5-08                   Menampilkan status dan  Status/history
                          riwayat yang dibutuhkan 
  -----------------------------------------------------------------------

### Data baseline

Gunakan atribut program kerja pada PRD/SRS, termasuk nama, tujuan,
deskripsi, waktu pelaksanaan, estimasi anggaran, target, bidang,
periode, dan PIC. Field wajib dan batas validasi mengikuti
keputusan/requirement yang sudah dikunci.

### Batasan

-   `TBD-001`: jangan menambahkan status `Dalam Evaluasi` sebelum
    diputuskan.
-   `TBD-003`: jangan mengasumsikan PIC lintas bidang boleh atau tidak
    boleh.
-   `TBD-011`: jangan menambahkan aksi hapus tanpa kebijakan
    penghapusan.
-   `TBD-019` SRS: field wajib dan batas validasi perlu difinalisasi
    untuk field yang belum jelas.
-   `TBD-021` SRS: visibilitas lintas bidang harus mengikuti keputusan.
-   Jangan membuat workflow tambahan yang tidak ada pada requirement.

### Pemeriksaan minimum Phase 5

Alur minimum yang harus berjalan: 1. Pengguna yang berwenang membuat
draft. 2. Draft tersimpan dan muncul pada daftar yang tepat. 3. Detail
dapat dibuka. 4. Draft dapat diperbarui sesuai aturan. 5. PIC dapat
ditetapkan. 6. Pengajuan ditolak jika syarat yang telah disepakati belum
terpenuhi. 7. Pengajuan berhasil jika valid.

### Exit criteria

Alur draft, pengelolaan PIC, review yang diwajibkan, dan pengajuan
program kerja berfungsi dengan akses yang benar.

------------------------------------------------------------------------

## PHASE 6 --- Evaluation & Revision

**Status:** Menunggu Phase 5\
**Tujuan:** membuat alur evaluasi, catatan, perbaikan, dan pengajuan
ulang.

### Task

-   Menampilkan program kerja yang perlu dievaluasi.
-   Menampilkan detail yang diperlukan evaluator.
-   Membuat catatan evaluasi/revisi.
-   Menampilkan catatan kepada pihak yang berhak.
-   Memperbarui program kerja sesuai catatan.
-   Mengajukan ulang program kerja.
-   Menjaga keterkaitan catatan dengan pembuat dan waktu pencatatan.

### Batasan

-   Selesaikan `TBD-002` sebelum menentukan apakah catatan dapat
    diedit/dihapus atau append-only.
-   Jangan membuat tombol edit/hapus atau endpoint update/delete sebelum
    keputusan.
-   Jangan menganggap tidak adanya tombol edit/hapus otomatis berarti
    append-only.
-   Gunakan status yang sudah disetujui; jangan membuat state baru
    berdasarkan asumsi.

### Pemeriksaan minimum Phase 6

-   Evaluator yang berwenang dapat memberi catatan.
-   Catatan tersimpan dengan pembuat dan waktu.
-   Pihak yang dituju dapat membaca catatan sesuai akses.
-   Program kerja dapat diperbaiki dan diajukan ulang.
-   Riwayat yang diwajibkan tidak hilang.

### Exit criteria

Satu siklus evaluasi â†’ catatan â†’ revisi â†’ pengajuan ulang berjalan
sesuai keputusan.

------------------------------------------------------------------------

## PHASE 7 --- SAW Configuration & Assessment

**Status:** Menunggu Phase 4 dan data program kerja yang dibutuhkan\
**Tujuan:** mengelola kriteria/bobot dan mengumpulkan penilaian yang
valid sebelum kalkulasi.

### Task A --- Configuration

-   Lima kriteria SAW per periode.
-   Jenis benefit/cost.
-   Pengelolaan bobot.
-   Hak akses `saw.weights.manage`.
-   Penyimpanan konfigurasi sesuai periode.

### Task B --- Assessment

-   Menampilkan program kerja yang perlu dinilai.
-   Input nilai per kriteria.
-   Validasi nilai sesuai skala yang disepakati.
-   Validasi kelengkapan penilaian.
-   Menyimpan penilaian.

### Keputusan yang harus tersedia sebelum implementasi terkait

-   `TBD-004`: pemetaan benefit/cost.
-   `TBD-005`: bobot wajib 100% atau dinormalisasi.
-   `TBD-006`: anggaran Rp0.
-   `TBD-009`: bentuk snapshot konfigurasi/penilaian.
-   Skala penilaian sesuai keputusan database dan laporan proyek.

### Batasan

-   Jangan menebak jenis benefit/cost.
-   Jangan menetapkan aturan bobot tanpa persetujuan.
-   Jangan membuat batas nilai yang tidak didukung dokumen.
-   Jangan mengizinkan perubahan bobot oleh pengguna tanpa
    `saw.weights.manage`.
-   Jangan menghitung ranking di frontend.

### Pemeriksaan minimum Phase 7

-   Pengguna berwenang dapat mengelola konfigurasi.
-   Pengguna tanpa permission tidak dapat mengubah bobot.
-   Penilaian dapat disimpan.
-   Penilaian tidak lengkap ditolak saat akan digunakan untuk
    perhitungan.
-   Nilai di luar skala ditolak.

### Exit criteria

Konfigurasi dan penilaian SAW tersimpan serta siap dikirim untuk
perhitungan.

------------------------------------------------------------------------

## PHASE 8 --- Python SAW Integration

**Status:** Menunggu Phase 7\
**Tujuan:** menjalankan kalkulasi SAW melalui Python service dan
menyimpan hasil valid di Laravel.

### Task

  -----------------------------------------------------------------------
  ID                      Pekerjaan               Output
  ----------------------- ----------------------- -----------------------
  P8-01                   Finalisasi kontrak HTTP Python API contract
                          request/response        

  P8-02                   Implementasi fungsi SAW Calculation function
                          Python                  

  P8-03                   Validasi input/output   Contract validation
                          service                 

  P8-04                   Implementasi Laravel    Backend integration
                          HTTP client             

  P8-05                   Panggilan synchronous   Calculation flow
                          dari Laravel            

  P8-06                   Validasi hasil sebelum  Validated result
                          disimpan                

  P8-07                   Simpan `SawRun` dan     Calculation history
                          `SawRunResult`          

  P8-08                   Tampilkan hasil ranking Ranking UI

  P8-09                   Tangani timeout dan     Error handling
                          kegagalan service       
  -----------------------------------------------------------------------

### Keputusan yang harus tersedia

-   `TBD-007`: penanganan nilai nol pada normalisasi cost.
-   `TBD-008`: tie/ranking dengan nilai preferensi sama.
-   `TBD-009`: snapshot SAW.
-   Kontrak Python: endpoint, schema, error response, timeout, dan
    autentikasi antarservice jika diperlukan.
-   `TBD-015`: visibilitas ranking per role sebelum menu/hasil
    ditampilkan kepada role terkait.

### Batasan

-   Laravel adalah satu-satunya pemanggil Python.
-   Python hanya menghitung dan tidak mengakses database aplikasi.
-   Laravel melakukan validasi dan menyimpan hasil.
-   React tidak menghitung SAW.
-   Hasil baru tidak menimpa riwayat perhitungan sebelumnya.
-   Jangan menampilkan hasil ranking jika service gagal atau hasil tidak
    lolos validasi.

### Pemeriksaan minimum Phase 8

-   Uji satu contoh data yang hasilnya dapat diperiksa secara manual.
-   Laravel berhasil mengirim request dan menerima response Python.
-   Hasil yang valid tersimpan.
-   Riwayat perhitungan dapat dibuka.
-   Timeout/error Python ditangani tanpa menampilkan ranking palsu.
-   Akses ranking mengikuti keputusan role.

### Exit criteria

Perhitungan SAW dapat dijalankan dari aplikasi, hasil tervalidasi
tersimpan, dan ranking dapat ditampilkan kepada role yang berhak.

------------------------------------------------------------------------

## PHASE 9 --- Decision & Completion

**Status:** Menunggu Phase 5, Phase 6, dan Phase 8\
**Tujuan:** menutup siklus program kerja melalui keputusan dan
penyelesaian.

### Task

-   Menampilkan konteks program kerja dan informasi hasil SAW yang
    relevan.
-   Mencatat keputusan pleno sesuai aktor berwenang.
-   Mendukung keputusan Revisi, Disetujui, atau Ditolak sesuai baseline.
-   Menyimpan riwayat keputusan.
-   Menampilkan keputusan kepada pihak yang berhak.
-   Menandai program kerja selesai sesuai syarat.

### Keputusan yang harus tersedia

-   Topik PRD `TBD-013`: aktor pencatat keputusan dan aturan keputusan
    berulang.
-   Topik SRS terkait keputusan pleno dan pergantian jabatan.
-   PRD `TBD-014` dan SRS `TBD-014`: aktor, bukti, serta syarat menandai
    selesai.
-   SRS `TBD-022`: hak akses mencatat keputusan dan keputusan berulang.

Jangan menyimpulkan bahwa nomor TBD yang sama pada PRD dan SRS selalu
membahas topik identik.

### Pemeriksaan minimum Phase 9

-   Hanya aktor yang berwenang dapat mencatat keputusan.
-   Keputusan tersimpan dengan pembuat dan waktu.
-   Keputusan baru tidak menghapus riwayat sebelumnya.
-   Status berubah sesuai keputusan yang sah.
-   Program kerja hanya dapat ditandai selesai jika syarat yang
    disepakati terpenuhi.

### Exit criteria

Keputusan dan penyelesaian program kerja berjalan sesuai aturan
organisasi.

------------------------------------------------------------------------

## PHASE 10 --- Dashboard & Reporting

**Status:** Menunggu modul yang menyediakan data dashboard\
**Tujuan:** menampilkan ringkasan operasional berdasarkan data nyata dan
hak akses.

### Task

-   Membuat dashboard internal.
-   Menampilkan KPI yang memiliki sumber data aktual.
-   Menampilkan ringkasan status program kerja.
-   Menampilkan daftar aktivitas/tindakan yang relevan.
-   Menampilkan statistik/grafik yang didukung data.
-   Filter periode jika dibutuhkan.
-   Menyesuaikan widget dan menu berdasarkan role/permission.

### Batasan

-   Jangan membuat angka dummy/hardcoded seolah-olah data aktual.
-   Jangan membuat widget jika belum ada data/API yang mendukung.
-   Ranking hanya ditampilkan kepada role yang sudah mendapat izin.
-   Jangan menambah AI Assistant, notifikasi, export, atau laporan
    lanjutan tanpa requirement.
-   Dashboard tidak boleh menjadi sumber perhitungan SAW.

### Pemeriksaan minimum Phase 10

-   Dashboard menampilkan data dari backend.
-   Angka ringkasan sesuai data sumber.
-   Pengguna tidak melihat informasi yang tidak berhak diakses.
-   Empty state tampil jika data belum ada.
-   Filter periode bekerja jika disediakan.

### Exit criteria

Dashboard menampilkan informasi inti yang akurat, relevan, dan sesuai
akses setiap role.

------------------------------------------------------------------------

## PHASE 11 --- Quality Assurance & Deployment

**Status:** Fase terakhir\
**Tujuan:** memastikan alur inti stabil dan aplikasi dapat dijalankan
pada environment target.

### Task

-   Jalankan build production frontend.
-   Jalankan pemeriksaan Laravel yang relevan.
-   Jalankan regression smoke test alur utama.
-   Periksa login, session, CSRF, dan authorization.
-   Periksa migration pada environment target.
-   Periksa integrasi Python dan konfigurasi timeout.
-   Periksa error/logging dan secret.
-   Siapkan konfigurasi deployment.
-   Dokumentasikan cara menjalankan aplikasi.
-   Periksa backup/recovery yang memang menjadi kebutuhan deployment.

### Pengujian akhir yang diprioritaskan

1.  Pengunjung membuka landing page.
2.  User login dan logout.
3.  User mendapat akses sesuai role/permission.
4.  Program kerja dibuat, diperbarui, dan diajukan.
5.  Program kerja dievaluasi dan direvisi.
6.  Penilaian SAW dilengkapi.
7.  Perhitungan SAW berjalan dan riwayat tersimpan.
8.  Keputusan dicatat dan program kerja diselesaikan.
9.  Dashboard menampilkan data sesuai akses.

### Batasan

-   Fokus pada alur utama dan blocker.
-   Tidak wajib mengejar test coverage 100%.
-   Performance/load testing hanya dilakukan jika dibutuhkan atau ada
    risiko nyata.
-   Jangan menambahkan monitoring/infrastruktur kompleks tanpa
    kebutuhan.
-   Jangan mengklaim deployment berhasil sebelum benar-benar diuji pada
    environment target.

### Exit criteria

Aplikasi dapat dijalankan pada environment target dan alur inti yang
disepakati telah diverifikasi.

------------------------------------------------------------------------

# 6. Dependency Antar Fase

Urutan utama:

``` text
Phase 0 â€” Requirement Alignment & Planning
    |
    v
Phase 1 â€” Project Foundation
    |
    v
Phase 2 â€” Database Design & Migration
    |
    v
Phase 3 â€” Authentication
    |
    v
Phase 4 â€” Organization & Authorization
    |
    v
Phase 5 â€” Program Kerja Management
    |
    v
Phase 6 â€” Evaluation & Revision
    |
    v
Phase 7 â€” SAW Configuration & Assessment
    |
    v
Phase 8 â€” Python SAW Integration
    |
    v
Phase 9 â€” Decision & Completion
    |
    v
Phase 10 â€” Dashboard & Reporting
    |
    v
Phase 11 â€” Quality Assurance & Deployment
```

Catatan dependency: - Phase 2 dapat mulai setelah keputusan database
Phase 0 selesai. - Phase 3 bergantung pada database dan tabel
user/session. - Phase 4 bergantung pada authentication. - Phase 5
bergantung pada struktur organisasi dan authorization. - Phase 6
bergantung pada program kerja. - Phase 7 bergantung pada data program
kerja dan authorization. - Phase 8 bergantung pada konfigurasi dan
penilaian SAW. - Phase 9 memerlukan alur program kerja, keputusan, dan
hasil SAW yang relevan. - Phase 10 diletakkan setelah data fitur utama
tersedia agar tidak membuat dashboard berbasis asumsi. - Phase 11
memverifikasi integrasi seluruh alur.

Pekerjaan paralel hanya boleh dilakukan jika tidak mengubah file/kontrak
yang sama dan dependency-nya jelas. Default pengerjaan adalah
sequential.

------------------------------------------------------------------------

# 7. Pola Pengujian Minimum per Fase

Pengujian dilakukan setelah satu fase selesai. Pemeriksaan disesuaikan
dengan risiko fase, bukan memaksakan test suite besar di setiap langkah.

  Fase       Pemeriksaan minimum
  ---------- ------------------------------------------------------------
  Phase 0    Review keputusan dan konsistensi dokumen
  Phase 1    Laravel boot, Vite build, landing page dan navigasi
  Phase 2    Migration fresh pada database lokal aman, constraint utama
  Phase 3    Login valid/invalid, endpoint privat, logout, CSRF
  Phase 4    Akses role, `GRANT`, `DENY`, batas keanggotaan
  Phase 5    Create, read, update draft, PIC, submit
  Phase 6    Catatan, revisi, pengajuan ulang
  Phase 7    Konfigurasi, input nilai, validasi kelengkapan
  Phase 8    Contoh hitung SAW, simpan hasil, riwayat, error Python
  Phase 9    Catat keputusan, riwayat, penyelesaian
  Phase 10   Data dashboard, filter, visibilitas role
  Phase 11   Regression alur utama dan production build

Pemeriksaan yang sudah dilakukan pada fase sebelumnya tidak perlu
diulang seluruhnya setiap kali ada patch kecil. Ulangi pemeriksaan
terdampak dan jalankan regression utama pada Phase 11.

------------------------------------------------------------------------

# 8. Aturan Git dan Patch

## 8.1 Prinsip

-   Kerjakan satu fase aktif pada satu waktu.
-   Gunakan branch kerja jika perubahan cukup besar atau menyentuh
    banyak file; untuk perubahan kecil, ikuti workflow branch yang
    disetujui.
-   Jangan commit file `.env`, secret, credential, database lokal berisi
    data privat, atau file dependency besar.
-   Jangan melakukan `git reset --hard`, penghapusan database, atau
    operasi destruktif tanpa persetujuan.
-   Jangan commit sebelum pemeriksaan minimum fase selesai dan hasilnya
    direview.

## 8.2 Format Laporan Setiap Patch

Setiap patch harus menjelaskan:

1.  Tujuan dan requirement yang dikerjakan.
2.  Kondisi repository sebelum perubahan.
3.  File yang dibuat/diubah/dihapus.
4.  Patch atau kode yang dapat diterapkan.
5.  Command untuk menjalankan/validasi.
6.  Hasil yang diharapkan.
7.  Hasil aktual setelah pemilik proyek menjalankan command.
8.  Catatan TBD atau risiko jika ada.

Jangan mengarang output terminal atau mengklaim sudah menjalankan test
pada mesin pemilik proyek.

------------------------------------------------------------------------

# 9. Status Pengerjaan

Status implementasi berdasarkan ZIP terbaru 8 November 2026 dan hasil
uji manual yang dilaporkan pemilik proyek:

  Fase                                           Status
  ---------------------------------------------- -----------------------------------------
  Phase 0 --- Requirement Alignment & Planning   Selesai untuk fondasi MVP; business TBD terbuka
  Phase 1 --- Project Foundation                 Implemented; landing dan SPA berhasil dibuka
  Phase 2 --- Database Design & Migration        Migration dan DemoSeeder berhasil dijalankan
  Phase 3 --- Authentication                     Login berhasil digunakan; regression test pending
  Phase 4 --- Organization & Authorization       Implemented; smoke test role/override pending
  Phase 5 --- Program Kerja Management           Implemented; smoke test CRUD/submit/PIC pending
  Phase 6 --- Evaluation & Revision              Implemented; smoke test revisi/keputusan pending
  Phase 7 --- SAW Configuration & Assessment     Implemented; halaman SAW terbuka, multi-alternatif pending
  Phase 8 --- Python SAW Integration             Perhitungan SAW berhasil dan hasil tersimpan pada demo
  Phase 9 --- Decision & Completion              Implemented; smoke test end-to-end pending
  Phase 10 --- Dashboard & Reporting             Implemented; pemeriksaan angka/akses pending
  Phase 11 --- Quality Assurance & Deployment    Pending; fokus regression dan persiapan demo

Status 'Implemented' berarti kode tersedia, bukan seluruh acceptance criteria
sudah lulus. Jangan mengklaim fitur terverifikasi sebelum smoke test dilakukan.

------------------------------------------------------------------------

# 10. Instruksi Konteks untuk AI Coding Agent

Saat melanjutkan percakapan atau menerima ZIP repository terbaru:

1.  Baca `PHASES.md` terlebih dahulu untuk mengetahui fase aktif dan
    urutan kerja yang disetujui.
2.  Baca `PRD.md`, `SRS.md`, `ARCHITECTURE.md`, dan `DESIGN.md` untuk
    requirement rinci.
3.  Periksa repository terbaru; jangan menganggap ZIP lama selalu
    mencerminkan kode terkini.
4.  Identifikasi fase aktif dan task yang belum selesai.
5.  Jangan melompati dependency.
6.  Jangan mengulang pekerjaan yang sudah selesai tanpa alasan.
7.  Jangan membuat kode spekulatif, placeholder, atau fitur di luar
    scope.
8.  Jika requirement TBD memengaruhi task aktif, berhenti dan minta
    keputusan pemilik proyek.
9.  Berikan patch kecil dan spesifik yang sesuai kondisi kode aktual.
10. Lakukan pemeriksaan minimum setelah fase selesai; fokus pada
    kesiapan dan fungsi inti.
11. Laporkan hasil nyata dan jangan mengarang keberhasilan.
12. Jangan melakukan commit, push, reset, atau operasi destruktif tanpa
    instruksi/persetujuan pemilik proyek.

**Aturan utama:** implementasikan hanya kebutuhan yang sudah jelas,
gunakan dependency yang benar-benar diperlukan, dan pastikan setiap
bagian kode dipakai oleh alur aplikasi yang nyata.

------------------------------------------------------------------------

**Akhir Dokumen --- PHASES v1.2**

Roadmap 12 fase tetap menjadi baseline. Fondasi MVP dan alur SAW telah
diimplementasikan; migration/seeder serta perhitungan SAW telah
dikonfirmasi berhasil oleh pemilik proyek. Fokus berikutnya adalah
smoke test role, Program Kerja, evaluasi, keputusan, dashboard, lalu
regression minimum. Jangan menambahkan fitur di luar kebutuhan demo
sebelum alur wajib stabil.
