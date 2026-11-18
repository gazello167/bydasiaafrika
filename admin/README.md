# BYD Asia Afrika - Admin CMS

CMS admin untuk mengelola konten website BYD Asia Afrika (bydasiaafrika.com).

## 🎯 Fitur

- ✅ Edit informasi site (kontak, alamat, jam operasional, social media)
- ✅ Manage banner hero/slides
- ✅ Manage model mobil (harga, specs, highlights, gambar)
- ✅ Upload gambar ke Cloudflare R2
- ✅ Auto rebuild Astro site setelah perubahan data
- ✅ Simple authentication dengan session PHP

## 📦 Setup

### 1. Database Setup

Jalankan SQL schema di `admin/db/schema.sql`:

```bash
mysql -u bydaaUser -p bydasiaafDb < admin/db/schema.sql
```

**Default Login:**
- Username: `admin`
- Password: `admin123`

**⚠️ PENTING:** Ganti password setelah login pertama kali dengan query:

```sql
UPDATE admin_users 
SET password = '$2y$10$YourNewHashedPassword' 
WHERE username = 'admin';
```

Generate hash dengan PHP:
```php
echo password_hash('password_baru_anda', PASSWORD_DEFAULT);
```

### 2. Cloudflare R2 Setup

Tambahkan credentials R2 ke `.env` atau environment variables:

```env
R2_ACCOUNT_ID=your_account_id
R2_ACCESS_KEY_ID=your_access_key
R2_SECRET_ACCESS_KEY=your_secret_key
R2_BUCKET=byd-media
PUBLIC_R2_URL=https://byd.gazello.my.id
```

**Cara dapat credentials:**
1. Login ke Cloudflare Dashboard
2. R2 → Overview → Manage R2 API Tokens
3. Create API Token dengan permissions **Object Read & Write**
4. Copy **Access Key ID** dan **Secret Access Key**

### 3. Install AWS SDK (untuk R2)

Di root project:

```bash
composer require aws/aws-sdk-php
```

Atau jika belum ada composer.json:

```bash
composer init
composer require aws/aws-sdk-php
```

### 4. Permissions

Pastikan PHP bisa menulis ke `src/data/`:

```bash
chmod 755 src/data/
chmod 664 src/data/*.ts
```

Dan bisa execute `npm`:

```bash
which npm  # Pastikan npm ada di PATH
```

### 5. Test Rebuild

Coba manual rebuild dulu:

```bash
cd /path/to/bydasiaafrika
npm run build
```

Jika berhasil, berarti PHP juga bisa jalankan via `exec()`.

## 🚀 Usage

1. **Login** ke `https://admin.bydasiaafrika.com`
2. **Edit data** di halaman Site Config, Slides, atau Models
3. **Simpan perubahan** (data tersimpan ke `src/data/*.ts`)
4. **Rebuild site** dengan klik tombol "Rebuild Site" di sidebar atau dashboard
5. **Tunggu 1-2 menit** sampai build selesai
6. **Refresh frontend** - perubahan sudah live!

## 📁 Struktur File

```
admin/
├── api/              # REST API endpoints
│   ├── auth.php      # Login/logout
│   ├── site.php      # GET/POST site config
│   ├── slides.php    # GET/POST slides
│   ├── models.php    # GET/POST models
│   ├── upload.php    # Upload to R2
│   └── rebuild.php   # Trigger npm build
├── config/           # Configuration
│   ├── database.php
│   ├── auth.php
│   └── r2.php
├── includes/         # Header & footer
├── assets/           # CSS & JS
├── db/               # SQL schema
├── index.php         # Login page
├── dashboard.php     # Dashboard
├── site.php          # Site config editor
├── slides.php        # Slides editor
└── models.php        # Models editor
```

## 🔐 Security

- ✅ Session-based authentication
- ✅ File write permissions ke `src/data/` only
- ✅ Upload validation (file type & size)
- ✅ SQL injection protection (PDO prepared statements)
- ✅ XSS protection (htmlspecialchars)
- ⚠️ Untuk production, tambahkan:
  - Rate limiting pada login
  - CSRF tokens
  - HTTPS only
  - IP whitelist (optional)

## 🐛 Troubleshooting

### "Database connection failed"
- Cek credentials di `.env` atau env vars
- Pastikan MySQL service running
- Test connection: `mysql -u bydaaUser -p bydasiaafDb`

### "Upload failed"
- Cek R2 credentials
- Pastikan bucket exists dan public
- Cek PHP upload limits: `php -i | grep upload_max_filesize`

### "Build failed"
- Cek `npm run build` manual dulu
- Pastikan `node_modules/` ter-install
- Cek error log: `tail -f /path/to/error.log`

### Session expired terus
- Cek PHP session config: `php -i | grep session`
- Pastikan `session_start()` di-call sebelum output
- CloudPanel: pastikan PHP-FPM service running

## 📝 Notes

- Data disimpan langsung ke file `.ts` di `src/data/`
- Tidak pakai database untuk content (hanya untuk auth & future logs)
- Rebuild site wajib dilakukan setelah edit data
- Astro build output ke `dist/` yang di-serve CloudPanel
- Gambar disimpan di Cloudflare R2, bukan lokal

## 🔄 Workflow

```
Edit Data (CMS) 
   ↓
Simpan ke src/data/*.ts
   ↓
Klik "Rebuild Site"
   ↓
npm run build (Astro)
   ↓
Output ke dist/
   ↓
CloudPanel serve dist/
   ↓
Frontend Updated ✅
```

## 📞 Support

Jika ada masalah, cek:
1. PHP error log: `/var/log/php-fpm/error.log` (CloudPanel)
2. Astro build log: console output saat rebuild
3. Browser console: untuk JS errors di admin UI

---

**Built with ❤️ for BYD Asia Afrika Bandung**
