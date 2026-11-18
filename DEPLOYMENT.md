# 🚀 Deployment Guide - BYD Asia Afrika CMS

Panduan lengkap deploy CMS admin ke CloudPanel.

## 📋 Prerequisites

- CloudPanel sudah running dengan domain `admin.bydasiaafrika.com`
- MySQL database sudah dibuat
- Source code Astro sudah di-deploy ke server
- Node.js & npm ter-install di server

## 🔧 Step-by-Step Deployment

### 1. Upload Source Code

Via SSH/SFTP, upload seluruh folder `admin/` ke:

```
/home/bydasiaafrika/htdocs/admin.bydasiaafrika.com/
```

Atau jika menggunakan File Manager CloudPanel:
- Masuk ke File Manager
- Navigate ke `htdocs/admin.bydasiaafrika.com/`
- Upload & extract `admin.zip`

### 2. Setup Database

```bash
# Login to MySQL
mysql -u bydaaUser -p

# Run schema
use bydasiaafDb;
source /home/bydasiaafrika/htdocs/admin.bydasiaafrika.com/admin/db/schema.sql;
```

### 3. Install PHP Dependencies (AWS SDK for R2)

```bash
cd /home/bydasiaafrika/htdocs/admin.bydasiaafrika.com
composer install
```

Jika composer belum ter-install:

```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### 4. Configure Environment

Buat file `.env` di root project:

```bash
cd /home/bydasiaafrika/htdocs/bydasiaafrika.com
nano .env
```

Paste ini (sesuaikan):

```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=bydasiaafDb
DB_USER=bydaaUser
DB_PASSWORD=your_password_here

R2_ACCOUNT_ID=abc123def456
R2_ACCESS_KEY_ID=key123
R2_SECRET_ACCESS_KEY=secret456
R2_BUCKET=byd-media
PUBLIC_R2_URL=https://byd.gazello.my.id
```

Lalu buat symlink atau load env ke PHP. Tambahkan di `admin/config/database.php`:

```php
<?php
// Load .env
function loadEnv($path) {
    if (!file_exists($path)) return;
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        putenv(trim($name) . '=' . trim($value));
    }
}
loadEnv(dirname(__DIR__, 2) . '/.env');

// Database connection
function getDB() {
    // ... existing code
}
```

### 5. Setup Permissions

```bash
# Data directory harus writable
chmod -R 755 /home/bydasiaafrika/htdocs/bydasiaafrika.com/src/data/
chmod 664 /home/bydasiaafrika/htdocs/bydasiaafrika.com/src/data/*.ts

# Npm harus executable
which npm
# Output: /usr/bin/npm (atau path lain)
```

### 6. Test Database Connection

Buat file test:

```php
<?php
// test-db.php
require_once 'admin/config/database.php';
try {
    $db = getDB();
    echo "✅ Database connected!\n";
    $stmt = $db->query("SHOW TABLES");
    print_r($stmt->fetchAll());
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
```

Jalankan:

```bash
php test-db.php
```

### 7. Setup Virtual Host (jika belum)

CloudPanel → Sites → `admin.bydasiaafrika.com` → Vhost

Pastikan document root: `/home/bydasiaafrika/htdocs/admin.bydasiaafrika.com/admin`

Atau jika admin sebagai subdomain dari main site, tambahkan location block:

```nginx
location /admin {
    alias /home/bydasiaafrika/htdocs/bydasiaafrika.com/admin;
    index index.php;
    
    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $request_filename;
    }
}
```

### 8. Test Rebuild Process

```bash
cd /home/bydasiaafrika/htdocs/bydasiaafrika.com
npm run build
```

Jika berhasil, output harus ada di `dist/` dan CloudPanel serve dari sana.

### 9. Change Default Password

```bash
# Login to MySQL
mysql -u bydaaUser -p bydasiaafDb

# Generate new password hash (ganti 'newpassword' dengan password baru)
php -r "echo password_hash('newpassword', PASSWORD_DEFAULT);"

# Update in database (copy hash dari output atas)
UPDATE admin_users SET password = '$2y$10$...' WHERE username = 'admin';
```

### 10. Test CMS

1. Akses `https://admin.bydasiaafrika.com`
2. Login dengan username `admin` dan password baru
3. Edit site config → Save → Rebuild
4. Cek frontend `https://bydasiaafrika.com` apakah sudah terupdate

## 🔍 Troubleshooting

### Error: "Database connection failed"

```bash
# Cek MySQL running
sudo systemctl status mysql

# Cek credentials
mysql -u bydaaUser -p bydasiaafDb

# Cek .env loaded
php -r "echo getenv('DB_HOST');"
```

### Error: "Call to undefined function putenv()"

Install PHP extension:

```bash
sudo apt-get install php-cli php-dev
sudo phpenmod pcntl
sudo systemctl restart php8.2-fpm
```

### Error: "AWS SDK not found"

```bash
cd /home/bydasiaafrika/htdocs/admin.bydasiaafrika.com
composer require aws/aws-sdk-php
```

### Error: "npm: command not found" saat rebuild

```bash
# Cek npm installed
which npm

# Jika tidak ada, install Node.js
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt-get install -y nodejs

# Atau load dari NVM
export NVM_DIR="$HOME/.nvm"
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"
```

### Error: "Permission denied" saat write file

```bash
# Fix ownership
sudo chown -R bydasiaafrika:bydasiaafrika /home/bydasiaafrika/htdocs/bydasiaafrika.com/src/data/

# Fix permissions
chmod 755 /home/bydasiaafrika/htdocs/bydasiaafrika.com/src/data/
chmod 664 /home/bydasiaafrika/htdocs/bydasiaafrika.com/src/data/*.ts
```

## 🔐 Security Checklist

- [ ] Password default sudah diganti
- [ ] `.env` tidak accessible via web (block di .htaccess atau nginx)
- [ ] Admin folder protected dengan Basic Auth (optional)
- [ ] HTTPS enabled (SSL certificate)
- [ ] Firewall restrict port 3306 (MySQL) only localhost
- [ ] PHP version up-to-date
- [ ] MySQL user dengan privileges minimal

## 📊 Monitoring

### Check PHP Error Log

```bash
tail -f /var/log/php8.2-fpm/error.log
```

### Check Astro Build Log

```bash
# Output ada di response API rebuild.php
# Atau redirect ke file
npm run build > build.log 2>&1
tail -f build.log
```

### Check MySQL Slow Query

```sql
-- Enable slow query log
SET GLOBAL slow_query_log = 'ON';
SET GLOBAL long_query_time = 2;

-- Check log
tail -f /var/log/mysql/mysql-slow.log
```

## 🔄 Backup

### Database Backup

```bash
# Manual backup
mysqldump -u bydaaUser -p bydasiaafDb > backup-$(date +%Y%m%d).sql

# Automated backup (crontab)
0 2 * * * mysqldump -u bydaaUser -p bydasiaafDb > /backups/bydasiaafDb-$(date +\%Y\%m\%d).sql
```

### File Backup

```bash
# Backup src/data/
tar -czf backup-data-$(date +%Y%m%d).tar.gz /home/bydasiaafrika/htdocs/bydasiaafrika.com/src/data/
```

## 🎯 Post-Deployment

1. ✅ Test login ke admin
2. ✅ Edit site config dan save
3. ✅ Upload gambar test ke R2
4. ✅ Trigger rebuild dan verify frontend updated
5. ✅ Change default password
6. ✅ Setup backup schedule
7. ✅ Monitor error logs untuk 24 jam pertama

---

**Questions?** Cek README.md di folder admin atau contact developer.
