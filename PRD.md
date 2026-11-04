# PRODUCT REQUIREMENTS DOCUMENT (PRD)

## Sistem Informasi Manajemen UKM Polytechnic Linux Community

  -----------------------------------------------------------------------
  Informasi                           Keterangan
  ----------------------------------- -----------------------------------
  Nama produk                         Sistem Informasi Manajemen UKM
                                      Polytechnic Linux Community

  Modul utama                         Manajemen Program Kerja dan Sistem
                                      Pendukung Keputusan (SPK) SAW

  Versi dokumen                       0.1

  Status                              Draft kerja --- menjadi acuan
                                      implementasi setelah diverifikasi
                                      terhadap repository

  Tanggal                             3 November 2026

  Dokumen sumber                      `LAPORAN PROYEK RPL.docx` dan
                                      keputusan perencanaan dalam
                                      percakapan proyek
  -----------------------------------------------------------------------

> **Penting:** Dokumen ini adalah baseline kebutuhan dan keputusan
> teknis. Bagian berstatus **LOCKED** merupakan keputusan yang sudah
> disetujui. Bagian **BASELINE** berasal dari dokumen proyek. Bagian
> **PROPOSED** adalah rekomendasi yang belum dikonfirmasi. Bagian
> **TBD** belum diputuskan. AI coding agent tidak boleh mengubah status
> tersebut berdasarkan asumsi sendiri.

------------------------------------------------------------------------

# 1. Ringkasan Produk

Sistem Informasi Manajemen UKM Polytechnic Linux Community adalah
aplikasi berbasis web untuk membantu pengelolaan administrasi organisasi
dan siklus program kerja, mulai dari penyusunan draft, pengajuan,
evaluasi, penilaian, pemeringkatan prioritas menggunakan metode Simple
Additive Weighting (SAW), pengambilan keputusan melalui pleno, hingga
pencatatan penyelesaian program kerja.

Sistem ditujukan untuk mendukung proses internal UKM Polytechnic Linux
Community. Aplikasi menyediakan hak akses berdasarkan peran organisasi
dan keanggotaan dalam periode kepengurusan. Perhitungan SAW dilakukan
oleh layanan Python yang diakses melalui backend Laravel.

## 1.1 Latar Belakang

Berdasarkan laporan proyek, proses pengusulan dan evaluasi program kerja
sebelumnya dilakukan melalui dokumen presentasi dan beberapa tahap
rapat. Evaluasi belum menggunakan parameter penilaian terstandarisasi
sehingga pertimbangan pengurus dapat berbeda-beda. Keterbatasan
anggaran, kesiapan sumber daya manusia, jadwal akademik, serta tingkat
kerumitan dan risiko pelaksanaan turut memengaruhi prioritas program
kerja.

Sistem ini dikembangkan untuk menyediakan pengelolaan program kerja yang
lebih terstruktur dan rekomendasi prioritas berbasis kriteria melalui
metode SAW.

## 1.2 Tujuan Produk

1.  Menyediakan satu sistem untuk mengelola data organisasi dan program
    kerja.
2.  Mendukung alur draft, pengajuan, evaluasi, revisi, keputusan, dan
    penyelesaian program kerja.
3.  Menerapkan penilaian program kerja berdasarkan kriteria dan bobot
    SAW.
4.  Menyediakan hasil pemeringkatan sebagai bahan pertimbangan rapat
    pleno.
5.  Menyimpan riwayat penilaian, perhitungan, dan keputusan agar proses
    dapat ditelusuri.
6.  Menerapkan pembatasan akses berdasarkan role, permission, dan
    periode kepengurusan.

## 1.3 Bukan Tujuan Produk

Hal-hal berikut belum ditetapkan sebagai kebutuhan versi pertama dan
tidak boleh diasumsikan sebagai fitur wajib: - Aplikasi mobile native. -
Integrasi dengan layanan eksternal selain layanan perhitungan SAW
Python. - Sistem notifikasi email, WhatsApp, atau push notification. -
Pendaftaran akun publik/self-registration. - Modul keuangan atau
pencairan dana. - Tanda tangan elektronik. - Fitur lain yang tidak
tercantum dalam kebutuhan atau belum disetujui pemilik proyek.

------------------------------------------------------------------------

# 2. Pengguna dan Hak Akses

## 2.1 Aktor Sistem

Terdapat empat role organisasi/sistem utama. Ketua Umum merupakan bagian
dari Pejabat Teras, bukan role terpisah.

  -----------------------------------------------------------------------
  Role                    Lingkup                 Keterangan
  ----------------------- ----------------------- -----------------------
  Super Admin             Global                  Mengelola administrasi
                                                  sistem dan tidak
                                                  terikat periode
                                                  kepengurusan

  Pejabat Teras           Keanggotaan per periode Mengevaluasi, menilai,
                                                  melihat ranking, dan
                                                  terlibat dalam
                                                  keputusan program kerja

  Ketua Bidang            Keanggotaan per periode Meninjau draft bidang
                          dan bidang              dan mengajukan program
                                                  kerja

  Pengurus Bidang         Keanggotaan per periode Menyusun dan mengelola
                          dan bidang              draft program kerja
                                                  sesuai kewenangan
  -----------------------------------------------------------------------

**Ketua Umum:** menggunakan role Pejabat Teras dan memperoleh permission
tambahan `saw.weights.manage` pada keanggotaan yang relevan.

## 2.2 Aturan Role dan Keanggotaan

1.  Akun pengguna direpresentasikan oleh entitas User.
2.  Role organisasi diberikan melalui Keanggotaan.
3.  Satu User hanya boleh memiliki satu Keanggotaan dalam satu Periode
    Kepengurusan.
4.  Keanggotaan menyimpan role, periode, dan bidang bila relevan.
5.  Keanggotaan Ketua Bidang dan Pengurus Bidang terhubung dengan satu
    bidang.
6.  Keanggotaan Pejabat Teras tidak wajib terhubung dengan bidang.
7.  Super Admin adalah role global pada akun User dan tidak membutuhkan
    Keanggotaan periode.
8.  Pergantian periode tidak boleh otomatis mewariskan permission khusus
    dari keanggotaan periode sebelumnya.

## 2.3 Model Authorization

**Keputusan LOCKED:** - Menggunakan Role-Based Access Control (RBAC)
dengan permission. - Permission catalog bersifat tetap dan didefinisikan
developer. - Role memiliki permission default. - Super Admin dapat
memberikan atau mencabut permission yang tersedia, tetapi tidak membuat
permission baru dari dashboard. - Permission override melekat pada
Keanggotaan, bukan permanen pada User. - Permission override memiliki
tiga keadaan konseptual: - `INHERIT`: mengikuti permission default
role. - `GRANT`: memberikan permission pada keanggotaan. - `DENY`:
menolak permission default role pada keanggotaan. - Jika terdapat
override, keputusan override keanggotaan menjadi prioritas terhadap
default role. - Super Admin memiliki kewenangan global sesuai permission
administrasi sistem.

**Catatan implementasi:** Model penyimpanan role global Super Admin dan
permission organisasi perlu diverifikasi saat database design. Jangan
menyamakan role global Super Admin dengan role yang diberikan melalui
Keanggotaan.

## 2.4 Matriks Kewenangan Awal

Matriks berikut merupakan baseline fungsional. Daftar permission
granular dan seluruh pengecualiannya masih perlu difinalisasi sebelum
authorization dianggap lengkap.

  ------------------------------------------------------------------------------------
  Aktivitas            Super Admin      Pejabat   Ketua Umum Ketua Bidang     Pengurus
                                          Teras                                 Bidang
  ------------------- ------------ ------------ ------------ ------------ ------------
  Mengelola pengguna            Ya        Tidak        Tidak        Tidak        Tidak

  Mengelola role dan            Ya        Tidak        Tidak        Tidak        Tidak
  permission                                                              

  Mengelola periode             Ya        Tidak        Tidak        Tidak        Tidak

  Membuat/mengelola         Sesuai        Tidak        Tidak           Ya           Ya
  draft bidang           kebijakan   ditetapkan   ditetapkan              
                             admin                                        

  Meninjau dan               Tidak        Tidak        Tidak           Ya        Tidak
  mengajukan program    ditetapkan                                          ditetapkan
  kerja                                                                   

  Mengevaluasi               Tidak           Ya           Ya        Tidak        Tidak
  program kerja         ditetapkan                                        

  Memberikan                 Tidak           Ya           Ya        Tidak        Tidak
  penilaian SAW         ditetapkan                                        

  Mengelola bobot SAW        Tidak        Tidak   Ya, dengan        Tidak        Tidak
                          otomatis     otomatis   permission              

  Melihat ranking            Tidak           Ya           Ya        Belum        Belum
                        ditetapkan                             ditetapkan   ditetapkan

  Mencatat keputusan         Tidak           Ya           Ya        Tidak        Tidak
  pleno                 ditetapkan                                        
  ------------------------------------------------------------------------------------

`Ya` pada Ketua Umum untuk aktivitas Pejabat Teras berarti Ketua Umum
mewarisi kewenangan role Pejabat Teras. Permission `saw.weights.manage`
adalah tambahan khusus.

------------------------------------------------------------------------

# 3. Ruang Lingkup Fungsional

## 3.1 Modul Autentikasi

  -----------------------------------------------------------------------
  ID                      Requirement             Prioritas
  ----------------------- ----------------------- -----------------------
  FR-AUTH-01              Pengguna dapat login    Must
                          menggunakan kredensial  
                          yang valid.             

  FR-AUTH-02              Pengguna dapat logout   Must
                          dan session diakhiri.   

  FR-AUTH-03              Sistem menyediakan      Must
                          informasi pengguna dan  
                          hak akses aktif untuk   
                          frontend.               

  FR-AUTH-04              Endpoint yang           Must
                          membutuhkan autentikasi 
                          menolak pengguna yang   
                          belum login.            

  FR-AUTH-05              Sistem melindungi       Must
                          proses autentikasi      
                          dengan mekanisme CSRF   
                          dan session yang aman.  
  -----------------------------------------------------------------------

Pembuatan akun dilakukan melalui administrasi pengguna oleh Super Admin
sebagai baseline dokumen. Mekanisme undangan, reset password, dan
aktivasi akun belum dirinci dan berstatus TBD.

## 3.2 Modul Administrasi Sistem

  -----------------------------------------------------------------------
  ID                      Requirement             Prioritas
  ----------------------- ----------------------- -----------------------
  FR-ADM-01               Super Admin dapat       Must
                          melihat daftar          
                          pengguna.               

  FR-ADM-02               Super Admin dapat       Must
                          menambah pengguna.      

  FR-ADM-03               Super Admin dapat       Must
                          memperbarui data        
                          pengguna.               

  FR-ADM-04               Super Admin dapat       Must
                          menonaktifkan pengguna. 

  FR-ADM-05               Super Admin dapat       Must
                          mengelola periode       
                          kepengurusan.           

  FR-ADM-06               Sistem mengelola role   Must
                          dan permission          
                          berdasarkan katalog     
                          tetap.                  

  FR-ADM-07               Super Admin dapat       Must
                          mengatur permission     
                          override pada           
                          keanggotaan.            

  FR-ADM-08               Sistem menyediakan data Must
                          bidang untuk            
                          pengelolaan keanggotaan 
                          dan program kerja.      
  -----------------------------------------------------------------------

Aturan penghapusan permanen pengguna/periode/bidang belum diputuskan.
Hindari hard delete data yang telah direferensikan sebelum aturan
retensi dan foreign key ditetapkan.

## 3.3 Modul Periode dan Keanggotaan

  -----------------------------------------------------------------------
  ID                      Requirement             Prioritas
  ----------------------- ----------------------- -----------------------
  FR-MEM-01               Sistem menyimpan        Must
                          periode kepengurusan    
                          beserta tanggal mulai   
                          dan selesai.            

  FR-MEM-02               Sistem dapat menandai   Must
                          periode aktif.          

  FR-MEM-03               User dapat memiliki     Must
                          paling banyak satu      
                          keanggotaan dalam satu  
                          periode.                

  FR-MEM-04               Keanggotaan menyimpan   Must
                          role dan periode.       

  FR-MEM-05               Keanggotaan Ketua       Must
                          Bidang/Pengurus Bidang  
                          terhubung dengan        
                          bidang.                 

  FR-MEM-06               Permission khusus       Must
                          mengikuti keanggotaan   
                          dan tidak otomatis      
                          diwariskan ke periode   
                          baru.                   
  -----------------------------------------------------------------------

## 3.4 Modul Program Kerja

  -----------------------------------------------------------------------
  ID                      Requirement             Prioritas
  ----------------------- ----------------------- -----------------------
  FR-PRO-01               Pengguna yang berwenang Must
                          dapat membuat draft     
                          program kerja.          

  FR-PRO-02               Draft dapat dilihat dan Must
                          diperbarui sesuai       
                          kewenangan.             

  FR-PRO-03               Ketua Bidang dapat      Must
                          meninjau draft bidang   
                          dan mengajukan program  
                          kerja.                  

  FR-PRO-04               Sistem memvalidasi data Must
                          sebelum program kerja   
                          diajukan.               

  FR-PRO-05               Pejabat Teras dapat     Must
                          mengevaluasi program    
                          kerja yang diajukan.    

  FR-PRO-06               Pejabat Teras dapat     Must
                          memberikan catatan      
                          revisi.                 

  FR-PRO-07               Bidang dapat            Must
                          memperbaiki program     
                          kerja yang berstatus    
                          revisi.                 

  FR-PRO-08               Program kerja dapat     Must
                          memiliki lebih dari     
                          satu PIC.               

  FR-PRO-09               PIC mengacu pada        Must
                          Keanggotaan, bukan      
                          hanya User.             

  FR-PRO-10               Sistem menyimpan status Must
                          terkini program kerja.  

  FR-PRO-11               Sistem menyimpan        Must
                          riwayat keputusan       
                          program kerja.          

  FR-PRO-12               Program kerja yang      Must
                          disetujui dapat         
                          ditandai selesai        
                          setelah pelaksanaan.    
  -----------------------------------------------------------------------

### 3.4.1 Data Program Kerja

Atribut baseline dari dokumen: - `id` - `periode_id` - `bidang_id` -
`nama_proker` - `tujuan` - `deskripsi` - `waktu` - `anggaran` -
`target` - `status` - `created_at` - `updated_at`

Atribut dan aturan wajib/opsional setiap field harus difinalisasi dalam
data dictionary. Program kerja dimiliki oleh satu bidang dan satu
periode. Program kerja dapat memiliki beberapa PIC melalui tabel
penghubung.

### 3.4.2 PIC Program Kerja

**Keputusan LOCKED:** satu program kerja dapat memiliki lebih dari satu
PIC. PIC direferensikan melalui `keanggotaan_id`.

**TBD:** apakah PIC harus berasal dari bidang pemilik program kerja atau
boleh lintas bidang. Keanggotaan PIC setidaknya harus berasal dari
periode yang sesuai dengan program kerja; aturan final perlu divalidasi.

## 3.5 Catatan dan Evaluasi

Sistem mencatat catatan evaluasi yang berkaitan dengan program kerja dan
keanggotaan pembuat catatan.

**Baseline dokumen:** CatatanProgramKerja memiliki relasi ke
ProgramKerja dan Keanggotaan.

**TBD:** apakah catatan bersifat append-only atau dapat diedit/dihapus.
Jangan mengunci perilaku ini sebelum dikonfirmasi.

## 3.6 Modul Penilaian dan SAW

  -----------------------------------------------------------------------
  ID                      Requirement             Prioritas
  ----------------------- ----------------------- -----------------------
  FR-SAW-01               Sistem menyediakan lima Must
                          kriteria SAW sesuai     
                          kebutuhan proyek.       

  FR-SAW-02               Konfigurasi kriteria    Must
                          dan bobot dikelola per  
                          periode kepengurusan.   

  FR-SAW-03               Ketua Umum dapat        Must
                          mengelola bobot jika    
                          memiliki                
                          `saw.weights.manage`.   

  FR-SAW-04               Pejabat Teras yang      Must
                          berwenang dapat         
                          memberikan nilai untuk  
                          setiap kriteria.        

  FR-SAW-05               Sistem memeriksa        Must
                          kelengkapan penilaian   
                          sebelum perhitungan.    

  FR-SAW-06               Laravel mengirim data   Must
                          perhitungan ke Python   
                          melalui REST API        
                          internal.               

  FR-SAW-07               Python mengembalikan    Must
                          hasil perhitungan SAW   
                          kepada Laravel.         

  FR-SAW-08               Laravel memvalidasi dan Must
                          menyimpan hasil         
                          perhitungan.            

  FR-SAW-09               Sistem menyimpan        Must
                          riwayat proses          
                          perhitungan SAW.        

  FR-SAW-10               Pengguna berwenang      Must
                          dapat melihat ranking   
                          prioritas.              
  -----------------------------------------------------------------------

### 3.6.1 Kriteria SAW

Kriteria dari dokumen proyek:

1.  Kesesuaian visi dan kebutuhan anggota.
2.  Kesiapan sumber daya manusia.
3.  Estimasi anggaran biaya.
4.  Tingkat urgensi terhadap kalender kegiatan.
5.  Tingkat kerumitan dan risiko pelaksanaan.

**Keputusan LOCKED:** skala penilaian mengikuti karakteristik kriteria.
Rancangan awal adalah skor 1--5 untuk kriteria kualitatif dan nominal
rupiah untuk anggaran.

**Pemetaan benefit/cost berikut adalah PROPOSED, belum dikunci:**

  Kriteria                                Jenis yang diusulkan
  --------------------------------------- ----------------------
  Kesesuaian visi dan kebutuhan anggota   Benefit
  Kesiapan sumber daya manusia            Benefit
  Estimasi anggaran biaya                 Cost
  Urgensi terhadap kalender kegiatan      Benefit
  Kerumitan dan risiko pelaksanaan        Cost

### 3.6.2 Formula SAW Baseline

Untuk kriteria benefit:

`r_ij = x_ij / max(x_ij)`

Untuk kriteria cost:

`r_ij = min(x_ij) / x_ij`

Nilai preferensi:

`V_i = Σ(w_j × r_ij)`

Alternatif diurutkan berdasarkan nilai preferensi dari terbesar ke
terkecil.

Formula di atas mengikuti flowchart perhitungan SAW pada laporan proyek.
Penanganan nilai nol, data kosong, nilai seri/tie, pembulatan, serta
validasi bobot harus ditentukan sebelum layanan SAW diimplementasikan.

### 3.6.3 Riwayat Perhitungan

**Keputusan LOCKED:** sistem menyimpan lebih dari satu proses
perhitungan, bukan menimpa seluruh hasil sebelumnya.

Struktur konseptual: - `saw_runs`: satu record untuk setiap proses
perhitungan. - `saw_run_results`: hasil setiap alternatif pada satu
proses perhitungan.

**PROPOSED:** simpan snapshot kriteria, bobot, nilai penilaian, dan
versi algoritma pada setiap proses agar hasil historis dapat ditelusuri
secara konsisten. Detail bentuk penyimpanan akan ditentukan pada
database design.

## 3.7 Keputusan dan Penyelesaian Program Kerja

Baseline dokumen menyediakan keputusan Revisi, Disetujui, dan Ditolak,
serta status program kerja yang mencakup Draft, Diajukan, Revisi,
Disetujui, Ditolak, dan Selesai.

Sistem harus menyimpan: - Program kerja yang diputuskan. - Jenis
keputusan. - Catatan keputusan. - Keanggotaan yang mencatat keputusan. -
Tanggal dan waktu keputusan.

**TBD:** transisi status secara rinci, apakah status `Dalam Evaluasi`
diperlukan, aturan keputusan berulang, dan siapa yang berwenang menandai
program kerja selesai.

------------------------------------------------------------------------

# 4. Arsitektur dan Keputusan Teknologi

## 4.1 Keputusan yang Sudah LOCKED

  Area                          Keputusan
  ----------------------------- -------------------------------------------------------
  Repository                    Satu repository (monorepo)
  Backend                       Laravel
  Frontend                      React + TypeScript
  Deployment                    Same-origin; frontend disajikan melalui Laravel
  Komunikasi frontend-backend   REST API
  Authentication                Laravel Sanctum, cookie-based session, tanpa JWT
  Authorization                 RBAC + permission + middleware + policy
  Permission catalog            Tetap, didefinisikan developer
  Default permission            Ditentukan oleh role
  Permission override           Inherit / Grant / Deny pada Keanggotaan
  API prefix                    `/api/v1`
  API format                    JSON dengan standar response dan error yang konsisten
  Integrasi Python SAW          Synchronous HTTP request dari Laravel

## 4.2 Baseline Teknologi dari Dokumen

  Komponen        Teknologi
  --------------- --------------------
  Backend         Laravel / PHP
  Frontend        React + TypeScript
  Database        MySQL
  Komputasi SAW   Python
  Integrasi       REST API

MySQL adalah baseline dokumen proyek, tetapi status pilihan database
final harus diverifikasi terhadap repository dan kebutuhan deployment
sebelum migration dibuat.

Versi Laravel, PHP, Node.js, React, TypeScript, Vite, serta dependency
aktual harus diperiksa dari file konfigurasi repository. Jangan
mengasumsikan versi.

## 4.3 Batas Tanggung Jawab

### React + TypeScript

-   Menampilkan halaman dan komponen antarmuka.
-   Mengelola interaksi form dan navigasi.
-   Mengirim request ke REST API Laravel.
-   Menampilkan hasil dan pesan validasi.
-   Tidak mengakses database secara langsung.
-   Tidak menjadi satu-satunya lapisan authorization.

### Laravel

-   Authentication dan session.
-   Authorization dan permission.
-   Validasi request.
-   Business logic.
-   Pengelolaan data dan transaksi database.
-   Mengirim permintaan perhitungan kepada Python.
-   Memvalidasi dan menyimpan hasil SAW.
-   Menyajikan API JSON dan frontend build.

### Python SAW Service

-   Menerima data perhitungan dari Laravel.
-   Memvalidasi input komputasi.
-   Menjalankan normalisasi, pembobotan, nilai preferensi, dan ranking.
-   Mengembalikan hasil ke Laravel.
-   Tidak menjadi pemilik data utama organisasi dan tidak diakses
    langsung oleh browser.

## 4.4 Konvensi API

-   Prefix `/api/v1`.
-   JSON sebagai format request/response.
-   Gunakan HTTP method sesuai operasi.
-   Validasi dilakukan di backend.
-   Gunakan HTTP status code yang tepat.
-   Daftar data menggunakan pagination bila relevan.
-   Format response dan error konsisten.
-   Endpoint yang dilindungi wajib memeriksa autentikasi dan
    authorization.

Contoh bentuk response konseptual:

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

Contoh ini hanya konvensi awal. Bentuk final harus mengikuti pola yang
sudah ada di repository jika implementasi telah dimulai, kecuali ada
alasan dan persetujuan untuk mengubahnya.

## 4.5 Alur Integrasi SAW

1.  Pejabat Teras mengisi penilaian melalui frontend.
2.  React mengirim data ke Laravel.
3.  Laravel memeriksa autentikasi, permission, dan kelengkapan nilai.
4.  Laravel mengambil alternatif, kriteria, bobot, jenis kriteria, dan
    nilai penilaian.
5.  Laravel mengirim payload ke Python SAW Service.
6.  Python melakukan perhitungan dan mengembalikan hasil.
7.  Laravel memvalidasi hasil dan menyimpan riwayat perhitungan.
8.  React menampilkan hasil ranking.

Karena integrasi dipilih synchronous, Laravel menunggu respons Python
pada request perhitungan. Timeout, error handling, dan batas ukuran
input perlu ditentukan saat implementasi.

------------------------------------------------------------------------

# 5. Kebutuhan Non-Fungsional

  -----------------------------------------------------------------------
  ID                      Kategori                Requirement
  ----------------------- ----------------------- -----------------------
  NFR-SEC-01              Keamanan                Password harus disimpan
                                                  menggunakan hashing
                                                  yang sesuai, bukan
                                                  plaintext.

  NFR-SEC-02              Keamanan                Endpoint privat wajib
                                                  memeriksa autentikasi.

  NFR-SEC-03              Keamanan                Authorization diperiksa
                                                  di backend, bukan hanya
                                                  melalui tampilan
                                                  frontend.

  NFR-SEC-04              Keamanan                Implementasi Sanctum
                                                  SPA mengikuti
                                                  perlindungan CSRF dan
                                                  cookie yang sesuai.

  NFR-DATA-01             Integritas              Relasi penting
                                                  dilindungi foreign key
                                                  dan constraint
                                                  database.

  NFR-DATA-02             Integritas              Operasi yang mengubah
                                                  beberapa data terkait
                                                  menggunakan transaksi
                                                  database bila
                                                  diperlukan.

  NFR-HIST-01             Auditabilitas           Riwayat keputusan dan
                                                  proses SAW tidak hilang
                                                  ketika data terbaru
                                                  dibuat.

  NFR-MAINT-01            Maintainability         Business logic tidak
                                                  ditumpuk seluruhnya di
                                                  Controller.

  NFR-MAINT-02            Maintainability         Frontend dan backend
                                                  dipisahkan berdasarkan
                                                  tanggung jawab meskipun
                                                  berada dalam satu
                                                  repository.

  NFR-TEST-01             Testing                 Fitur utama memiliki
                                                  pengujian sesuai
                                                  perilaku dan aturan
                                                  aksesnya.

  NFR-ERROR-01            Reliability             Kegagalan layanan
                                                  Python tidak boleh
                                                  menyebabkan penilaian
                                                  yang telah tersimpan
                                                  ikut hilang.

  NFR-ERROR-02            Reliability             Sistem menampilkan
                                                  pesan kegagalan yang
                                                  dapat dipahami dan
                                                  tidak membocorkan
                                                  detail rahasia.
  -----------------------------------------------------------------------

Target angka performa, kapasitas pengguna, availability, backup, dan
recovery belum ditentukan.

------------------------------------------------------------------------

# 6. Data Model Konseptual

Daftar berikut adalah entitas konseptual, bukan migration final.

## 6.1 Entitas Organisasi dan Akses

-   `users`
-   `roles`
-   `permissions`
-   `role_permissions`
-   `periode_kepengurusan`
-   `bidang`
-   `keanggotaan`
-   tabel permission override keanggotaan

## 6.2 Entitas Program Kerja

-   `program_kerja`
-   `program_kerja_pic`
-   `catatan_program_kerja`
-   `keputusan_program_kerja`

## 6.3 Entitas SAW

-   `kriteria_saw`
-   `penilaian_saw`
-   `saw_runs`
-   `saw_run_results`

Relasi dan atribut final, termasuk apakah snapshot disimpan dalam kolom
terstruktur atau tabel detail tersendiri, harus ditentukan dalam ERD
final.

## 6.4 Constraint yang Sudah Diketahui

1.  Kombinasi `user_id` dan `periode_id` pada Keanggotaan harus unik.
2.  Satu program kerja mempunyai satu bidang pemilik.
3.  Satu program kerja dapat mempunyai banyak PIC.
4.  Kombinasi `program_kerja_id` dan `keanggotaan_id` pada tabel PIC
    harus unik.
5.  Satu periode dapat mempunyai banyak kriteria SAW.
6.  Satu proses SAW dapat mempunyai banyak hasil alternatif.
7.  Riwayat keputusan dan riwayat SAW tidak boleh tertimpa oleh proses
    baru.

Aturan penghapusan dan nullable constraint masih perlu difinalisasi.

------------------------------------------------------------------------

# 7. Kriteria Penerimaan Produk

Produk versi pertama dapat dinyatakan memenuhi kebutuhan inti apabila:

1.  Pengguna yang valid dapat login dan logout.
2.  Pengguna hanya dapat mengakses fungsi yang diizinkan.
3.  Super Admin dapat mengelola pengguna dan periode.
4.  Keanggotaan dapat dikaitkan dengan user, role, periode, dan bidang
    sesuai aturan.
5.  Pengurus/Ketua Bidang dapat menjalankan alur program kerja sesuai
    kewenangan.
6.  Program kerja dapat mempunyai beberapa PIC.
7.  Proses revisi dan keputusan tersimpan dan dapat ditelusuri.
8.  Pejabat Teras dapat melakukan penilaian yang lengkap.
9.  Ketua Umum yang memiliki permission dapat mengelola bobot SAW.
10. Laravel dapat mengirim permintaan ke Python dan menerima hasil
    perhitungan.
11. Hasil SAW dapat ditampilkan dan riwayat perhitungan tetap tersedia.
12. Program kerja yang disetujui dapat dicatat penyelesaiannya.
13. Pengujian membuktikan alur utama dan pembatasan hak akses berjalan.

Kriteria penerimaan detail per endpoint dan per halaman akan diturunkan
setelah audit repository dan UI/UX planning.

------------------------------------------------------------------------

# 8. Keputusan yang Masih Terbuka (TBD)

Bagian ini sengaja dipertahankan agar AI coding agent tidak menganggap
rekomendasi sebagai keputusan final.

  -----------------------------------------------------------------------
  ID                      Topik                   Pilihan yang masih
                                                  perlu diputuskan
  ----------------------- ----------------------- -----------------------
  TBD-001                 Status program kerja    Enam status dari
                                                  dokumen atau menambah
                                                  `Dalam Evaluasi`

  TBD-002                 Catatan                 Append-only atau dapat
                                                  diedit/dihapus

  TBD-003                 PIC lintas bidang       PIC wajib satu bidang
                                                  dengan program kerja
                                                  atau boleh lintas
                                                  bidang

  TBD-004                 Pemetaan SAW            Konfirmasi jenis
                                                  benefit/cost untuk
                                                  setiap kriteria

  TBD-005                 Total bobot             Wajib 100% atau bobot
                                                  dinormalisasi otomatis

  TBD-006                 Anggaran nol            Rp0 diperbolehkan atau
                                                  wajib positif

  TBD-007                 SAW nilai nol           Aturan normalisasi cost
                                                  ketika nilai
                                                  minimum/nilai
                                                  alternatif nol

  TBD-008                 SAW tie                 Aturan ranking jika
                                                  nilai preferensi sama

  TBD-009                 Snapshot SAW            Bentuk penyimpanan
                                                  snapshot kriteria,
                                                  bobot, dan penilaian

  TBD-010                 Pengelolaan akun        Reset password,
                                                  aktivasi, dan
                                                  penonaktifan akun

  TBD-011                 Penghapusan data        Soft delete, hard
                                                  delete, atau larangan
                                                  penghapusan untuk
                                                  setiap entitas

  TBD-012                 Periode aktif           Apakah sistem hanya
                                                  mengizinkan satu
                                                  periode aktif pada satu
                                                  waktu

  TBD-013                 Keputusan pleno         Siapa yang boleh
                                                  mencatat keputusan dan
                                                  bagaimana keputusan
                                                  berulang ditangani

  TBD-014                 Penyelesaian            Siapa yang boleh
                                                  mengubah program kerja
                                                  menjadi Selesai dan
                                                  data apa yang wajib
                                                  diisi

  TBD-015                 Ranking                 Siapa saja yang boleh
                                                  melihat hasil ranking

  TBD-016                 Database final          Verifikasi MySQL
                                                  terhadap repository dan
                                                  lingkungan deployment

  TBD-017                 Deployment              Hosting, web server,
                                                  domain, dan konfigurasi
                                                  production

  TBD-018                 UI/UX                   Detail halaman,
                                                  navigasi, komponen, dan
                                                  design system
  -----------------------------------------------------------------------

Jika suatu TBD memengaruhi fitur yang sedang dikerjakan, AI coding agent
harus berhenti pada bagian tersebut dan meminta konfirmasi atau
mengajukan pilihan. Jangan membuat aturan bisnis baru secara diam-diam.

------------------------------------------------------------------------

# 9. Petunjuk Kerja untuk AI Coding Agent

Dokumen ini juga berfungsi sebagai instruksi kerja untuk AI coding agent
(misalnya Codex atau ChatGPT yang bekerja berdasarkan ZIP repository).

## 9.1 Sebelum Mengubah Kode

1.  Baca PRD ini secara menyeluruh.
2.  Audit struktur repository yang diberikan.
3.  Periksa `composer.json`, `package.json`, konfigurasi Vite, file
    environment example, routes, migrations, models, controllers,
    services, React entry point, dan tests yang tersedia.
4.  Identifikasi versi teknologi berdasarkan file project, bukan asumsi.
5.  Identifikasi fitur yang sudah ada, yang belum ada, dan yang rusak.
6.  Cocokkan implementasi aktual dengan requirement PRD.
7.  Laporkan temuan dan rencana patch sebelum melakukan perubahan besar.

## 9.2 Aturan Implementasi

-   Perlakukan keputusan berstatus LOCKED sebagai batasan.
-   Jangan mengubah requirement LOCKED tanpa meminta persetujuan.
-   Jangan menganggap BASELINE, PROPOSED, dan TBD sebagai keputusan
    final.
-   Jangan membuat ulang project jika repository Laravel sudah tersedia.
-   Jangan mengganti struktur atau dependency yang sudah berfungsi tanpa
    alasan teknis.
-   Jangan menambahkan package tanpa menjelaskan manfaat dan dampaknya.
-   Jangan mengarang nama file, class, route, tabel, atau fungsi yang
    belum diperiksa.
-   Ikuti pola penamaan dan struktur kode yang benar-benar digunakan
    repository, selama tidak bertentangan dengan PRD.
-   Buat perubahan sekecil mungkin dan terfokus pada satu tujuan.
-   Jangan menimpa konfigurasi environment atau mengungkap secret.
-   Jangan menjalankan operasi destruktif, reset database, atau
    menghapus data tanpa persetujuan eksplisit.
-   Migration baru harus mempertimbangkan migration yang sudah pernah
    dijalankan.
-   Backend wajib menjadi sumber kebenaran untuk authorization dan
    validasi bisnis.

## 9.3 Format Hasil Setiap Pekerjaan

Setiap tugas implementasi harus disertai:

1.  **Tujuan:** requirement/fitur yang dikerjakan.
2.  **Hasil audit:** kondisi kode sebelum perubahan.
3.  **Rencana:** pendekatan implementasi.
4.  **File terdampak:** file yang dibuat, diubah, atau dihapus.
5.  **Patch:** Git diff atau kode lengkap sesuai kebutuhan.
6.  **Command:** perintah untuk menerapkan perubahan.
7.  **Testing:** perintah dan langkah pengujian.
8.  **Hasil verifikasi:** pengujian yang benar-benar dijalankan dan
    hasilnya.
9.  **Risiko/TBD:** hal yang belum dapat dipastikan.

Jangan mengklaim test lulus jika test belum benar-benar dijalankan.

## 9.4 Alur Kerja Patch

Gunakan branch terpisah untuk setiap fitur atau kelompok perubahan yang
saling terkait.

Contoh alur:

``` bash
git status
git switch -c feature/nama-fitur

# Terapkan patch yang diberikan

git diff --check

# Jalankan test yang relevan
# Review hasil perubahan sebelum commit
```

Jika patch diberikan sebagai file:

``` bash
git apply nama-patch.patch
git diff --check
git diff
```

Jangan melakukan commit atau push kecuali diminta.

## 9.5 Prinsip Kolaborasi

AI coding agent berperan sebagai coding partner yang: - Mengutamakan
pemeriksaan kode aktual sebelum memberi solusi. - Menjelaskan alasan
teknis dengan bahasa yang dapat dipahami pemilik proyek. - Memisahkan
fakta repository dari rekomendasi. - Menyampaikan konflik requirement
sebelum mengubah kode. - Tidak memperluas scope tanpa persetujuan. -
Memprioritaskan fitur inti yang diperlukan untuk demonstrasi dan
pengumpulan proyek.

------------------------------------------------------------------------

# 10. Urutan Implementasi yang Direkomendasikan

Urutan berikut adalah roadmap awal dan dapat disesuaikan setelah audit
ZIP repository.

  -----------------------------------------------------------------------
  Tahap                   Modul                   Hasil yang diharapkan
  ----------------------- ----------------------- -----------------------
  0                       Repository audit        Peta struktur, versi,
                                                  fitur yang sudah ada,
                                                  dan gap

  1                       Project foundation      Laravel dan React +
                                                  TypeScript terintegrasi
                                                  dalam monorepo

  2                       Authentication          Login, logout, session,
                                                  dan identitas pengguna

  3                       Authorization           Role, permission,
                                                  membership scope,
                                                  middleware, policy

  4                       Master data             Periode, bidang,
                                                  pengguna, keanggotaan

  5                       Program kerja           CRUD, draft, pengajuan,
                                                  PIC

  6                       Evaluasi                Catatan, revisi,
                                                  keputusan dan riwayat

  7                       SAW configuration       Kriteria dan bobot per
                                                  periode

  8                       SAW assessment          Penilaian dan validasi
                                                  kelengkapan

  9                       Python integration      Perhitungan dan
                                                  penyimpanan riwayat

  10                      Dashboard and reporting Tampilan informasi
                                                  sesuai role

  11                      Testing and             Pengujian alur
                          stabilization           end-to-end dan
                                                  perbaikan
  -----------------------------------------------------------------------

Tahap yang menjadi dependency harus diselesaikan dan diverifikasi
sebelum modul yang bergantung padanya dikembangkan.

------------------------------------------------------------------------

# 11. Referensi Dokumen

PRD ini disusun berdasarkan: - `LAPORAN PROYEK RPL.docx`, khususnya
latar belakang, batasan masalah, arsitektur sistem, flowchart, use case,
activity diagram, sequence diagram, class diagram, dan tabel
relasional. - Keputusan arsitektur dan database yang telah disetujui
selama sesi perencanaan proyek.

PRD ini bukan pengganti dokumen akademik atau diagram asli. Jika
terdapat perbedaan antara kode repository, dokumen sumber, dan keputusan
terbaru, AI coding agent harus melaporkan perbedaannya dan meminta
klarifikasi jika dampaknya menyangkut requirement atau keputusan yang
belum terkunci.

------------------------------------------------------------------------

**Akhir Dokumen --- PRD v0.1**

Status dokumen: Draft kerja untuk ditanamkan ke repository dan
diverifikasi kembali setelah audit ZIP.
