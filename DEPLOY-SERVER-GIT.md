# 🔄 Panduan Deploy/Pull ke Server CloudPanel

Server kamu sudah punya folder project (`src/`, `node_modules/`, `dist/`, dll) tapi
**belum terhubung ke Git**. Panduan ini menyambungkan folder existing ke repo GitHub
`gazello167/bydasiaafrika`, lalu cara update selanjutnya.

---

## 🅰️ SETUP AWAL (sekali saja) — Sambungkan folder existing ke Git

### 1. SSH ke server

```bash
ssh bydasiaafrika@43.157.209.144
# atau pakai user root lalu su ke site user
```

### 2. Masuk ke folder project

```bash
cd /home/bydasiaafrika/htdocs/bydasiaafrika.com
```

### 3. Cek apakah sudah ada .git

```bash
ls -la | grep .git
```

Kalau belum ada, lanjut ke step 4. Kalau sudah ada folder `.git`, skip ke bagian **B**.

### 4. Backup dulu (jaga-jaga)

```bash
cd /home/bydasiaafrika/htdocs
cp -r bydasiaafrika.com bydasiaafrika.com.backup-$(date +%Y%m%d)
```

### 5. Init git & sambungkan ke remote

```bash
cd /home/bydasiaafrika/htdocs/bydasiaafrika.com

git init
git remote add origin https://github.com/gazello167/bydasiaafrika.git
git fetch origin main
```

### 6. "Adopsi" file existing supaya cocok dengan repo

Karena folder di server sudah berisi file (hasil upload manual), kita perlu
reset index git supaya match dengan remote **tanpa menghapus file physical**
yang beda kecil (misalnya `src/data/*.ts` yang sudah kamu edit manual di server,
kalau ada).

**Opsi aman (recommended) — cek dulu bedanya:**

```bash
git diff origin/main -- src/data/
```

Kalau tidak ada output / sama persis, lanjut:

```bash
git checkout -b main origin/main
```

Kalau ada perbedaan (misalnya kamu sempat edit `site.ts` langsung di server),
**backup dulu file itu**, baru checkout, baru copy manual isi editan ke file baru.

```bash
# backup file yang beda
cp src/data/site.ts /tmp/site.ts.server-backup

# baru checkout
git checkout -b main origin/main

# bandingkan manual, merge isi yang perlu
diff /tmp/site.ts.server-backup src/data/site.ts
```

### 7. Buat file `.env` di server (TIDAK ikut di-commit)

```bash
cp .env.example .env
nano .env
```

Isi dengan credentials asli:

```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=bydasiaafDb
DB_USER=bydaaUser
DB_PASSWORD=password_asli_kamu

R2_ACCOUNT_ID=xxxxx
R2_ACCESS_KEY_ID=xxxxx
R2_SECRET_ACCESS_KEY=xxxxx
R2_BUCKET=byd-media
PUBLIC_R2_URL=https://byd.gazello.my.id
```

### 8. Install dependencies

```bash
# Node dependencies (sudah ada node_modules, tapi sync-kan versi)
npm install

# PHP dependencies untuk admin (AWS SDK buat R2)
composer install
```

### 9. Import database schema (kalau belum)

```bash
mysql -u bydaaUser -p bydasiaafDb < admin/db/schema.sql
```

### 10. Test build

```bash
npm run build
```

Kalau sukses, `dist/` ter-update dengan hasil terbaru.

### 11. Set up loadEnv di PHP (supaya baca .env)

Edit `admin/config/database.php`, tambahkan di paling atas sebelum `function getDB()`:

```php
<?php
// Load .env file
function loadEnv($path) {
    if (!file_exists($path)) return;
    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        list($name, $value) = explode('=', $line, 2);
        putenv(trim($name) . '=' . trim($value));
    }
}
loadEnv(dirname(__DIR__, 2) . '/.env');

// Database connection
function getDB() {
    // ... kode existing tetap
}
```

Lakukan hal sama di `admin/config/r2.php` (tambahkan `loadEnv(...)` di atas).

### 12. Set permissions

```bash
chown -R bydasiaafrika:bydasiaafrika /home/bydasiaafrika/htdocs/bydasiaafrika.com
chmod -R 755 /home/bydasiaafrika/htdocs/bydasiaafrika.com/src/data
chmod 600 /home/bydasiaafrika/htdocs/bydasiaafrika.com/.env
```

### 13. Verifikasi git status

```bash
git status
git log --oneline -5
```

Harus menunjukkan branch `main`, up to date dengan `origin/main`.

---

## 🅱️ UPDATE RUTIN — Setelah setup awal selesai

Setiap kali ada perubahan baru di GitHub (push dari lokal), cukup:

```bash
ssh bydasiaafrika@43.157.209.144
cd /home/bydasiaafrika/htdocs/bydasiaafrika.com

git pull origin main
npm install          # kalau ada dependency baru
composer install     # kalau ada dependency PHP baru
npm run build
```

Atau pakai script otomatis — lihat bagian **C** di bawah.

---

## 🅲️ Script Otomatis `deploy.sh`

Biar gak ngetik manual tiap kali, buat script ini di server:

```bash
nano /home/bydasiaafrika/htdocs/bydasiaafrika.com/deploy.sh
```

Isi:

```bash
#!/bin/bash
set -e

cd /home/bydasiaafrika/htdocs/bydasiaafrika.com

echo "📥 Pulling latest changes..."
git pull origin main

echo "📦 Installing Node dependencies..."
npm install

echo "📦 Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader

echo "🏗️  Building site..."
npm run build

echo "✅ Deploy selesai! $(date)"
```

Buat executable:

```bash
chmod +x deploy.sh
```

Jalankan kapan saja setelah push dari lokal:

```bash
./deploy.sh
```

---

## 🅳️ (Opsional) Auto-deploy via GitHub Webhook

Kalau mau tiap `git push` otomatis trigger deploy di server tanpa SSH manual:

### 1. Buat webhook listener PHP

```bash
nano /home/bydasiaafrika/htdocs/bydasiaafrika.com/webhook.php
```

```php
<?php
// Simple GitHub webhook listener
$secret = getenv('WEBHOOK_SECRET') ?: 'ganti-dengan-random-string-rahasia';

$payload = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';

$expected = 'sha256=' . hash_hmac('sha256', $payload, $secret);

if (!hash_equals($expected, $signature)) {
    http_response_code(403);
    die('Invalid signature');
}

$data = json_decode($payload, true);
if (($data['ref'] ?? '') !== 'refs/heads/main') {
    die('Not main branch, skip');
}

// Trigger deploy script in background
exec('cd ' . __DIR__ . ' && bash deploy.sh > deploy.log 2>&1 &');

echo 'Deploy triggered';
```

### 2. Setup GitHub Webhook

1. GitHub repo → **Settings** → **Webhooks** → **Add webhook**
2. Payload URL: `https://bydasiaafrika.com/webhook.php` (atau subdomain terpisah)
3. Content type: `application/json`
4. Secret: isi string random yang sama dengan `$secret` di atas
5. Events: pilih **Just the push event**
6. **Add webhook**

**⚠️ Catatan keamanan:** Taruh `webhook.php` di luar document root publik kalau bisa,
atau pastikan secret kuat dan random. Untuk production serius, pertimbangkan
GitHub Actions + SSH deploy key (lebih aman, lihat bagian **E**).

---

## 🅴 (Alternatif Lebih Aman) GitHub Actions Auto-Deploy

Build di CI, baru upload hasil build via SSH — server tidak perlu install Node/npm sama sekali.

### 1. Generate SSH deploy key

Di lokal/server:

```bash
ssh-keygen -t ed25519 -f deploy_key -C "github-actions-deploy" -N ""
```

- `deploy_key` (private) → masukkan ke GitHub Secrets
- `deploy_key.pub` (public) → tambahkan ke `~/.ssh/authorized_keys` di server

```bash
cat deploy_key.pub >> /home/bydasiaafrika/.ssh/authorized_keys
```

### 2. Tambahkan GitHub Secrets

Repo → **Settings** → **Secrets and variables** → **Actions**:

- `SSH_PRIVATE_KEY` → isi `deploy_key` (private key)
- `SSH_HOST` → `43.157.209.144`
- `SSH_USER` → `bydasiaafrika`

### 3. Buat workflow

`.github/workflows/deploy.yml`:

```yaml
name: Deploy to CloudPanel

on:
  push:
    branches: [main]

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4

      - uses: actions/setup-node@v4
        with:
          node-version: 20

      - name: Install & Build
        run: |
          npm ci
          npm run build

      - name: Deploy dist/ via rsync
        uses: burnett01/rsync-deployments@7.0.1
        with:
          switches: -avzr --delete
          path: dist/
          remote_path: /home/bydasiaafrika/htdocs/bydasiaafrika.com/dist/
          remote_host: ${{ secrets.SSH_HOST }}
          remote_user: ${{ secrets.SSH_USER }}
          remote_key: ${{ secrets.SSH_PRIVATE_KEY }}

      - name: Sync admin/ PHP files (optional, jarang berubah)
        uses: burnett01/rsync-deployments@7.0.1
        with:
          switches: -avzr --exclude='config/' --exclude='.env'
          path: admin/
          remote_path: /home/bydasiaafrika/htdocs/bydasiaafrika.com/admin/
          remote_host: ${{ secrets.SSH_HOST }}
          remote_user: ${{ secrets.SSH_USER }}
          remote_key: ${{ secrets.SSH_PRIVATE_KEY }}
```

Dengan ini, tiap `git push origin main` dari lokal → GitHub Actions build →
upload `dist/` langsung ke server. Server tidak pernah jalankan `npm install`
sendiri, lebih ringan & aman.

**Catatan:** kalau pakai cara ini, tombol "Rebuild" di admin CMS brader
(`admin/api/rebuild.php`) jadi tidak relevan lagi — karena build dilakukan di CI,
bukan di server. Pilih salah satu pendekatan:
- **Cara C (manual/script di server)** → tombol rebuild di CMS tetap jalan.
- **Cara E (GitHub Actions)** → build sepenuhnya di CI, CMS edit data lalu commit+push
  balik ke repo (perlu endpoint tambahan untuk auto git-commit dari CMS — lebih kompleks).

---

## ✅ Rekomendasi untuk Kasus Kamu

Karena CMS admin kamu **menulis langsung ke `src/data/*.ts` di server** lalu
klik tombol **Rebuild** (yang jalankan `npm run build` di tempat), pakai:

👉 **Cara A (setup awal)** + **Cara C (`deploy.sh` manual)** untuk update kode dari GitHub.

Alurnya jadi:

```
Kamu edit kode di lokal → git push → SSH ke server → ./deploy.sh
                                                         ↓
Dealer edit konten via CMS admin → klik "Rebuild" → npm run build otomatis (tanpa git pull)
```

Dua jalur ini tidak konflik: `deploy.sh` untuk update **kode**, tombol CMS untuk
update **konten** — asal kamu jangan edit `src/data/*.ts` dua-duanya (dari CMS dan
dari git push) di waktu yang sama tanpa sync dulu.
