# 🚀 Setup Admin Subdomain - CloudPanel Varnish

Panduan lengkap setup `admin.bydasiaafrika.com` dengan vhost yang sudah pakai Varnish.

## 📋 Pre-requisites

- ✅ Folder admin sudah ada di `/home/bydasiaafrika/htdocs/bydasiaafrika.com/admin`
- ✅ DNS A record untuk `admin.bydasiaafrika.com` sudah ditambahkan
- ✅ SSL certificate main domain bisa dipakai untuk subdomain

## 🔧 Step 1: Update DNS

### Di Cloudflare (atau DNS provider lain)

Tambahkan A record baru:

```
Type: A
Name: admin
Value: 43.157.209.144
TTL: Auto
Proxy status: DNS only (abu-abu, bukan orange) - untuk testing dulu
```

Atau pakai CNAME:

```
Type: CNAME
Name: admin
Target: bydasiaafrika.com
TTL: Auto
```

**Test DNS:**

```bash
nslookup admin.bydasiaafrika.com
# Harus return: 43.157.209.144
```

## 🔧 Step 2: Update Vhost di CloudPanel

### Via CloudPanel UI

1. Login ke CloudPanel
2. **Sites** → `bydasiaafrika.com` → **Vhost** tab
3. Klik **Edit**
4. **Copy semua isi vhost-updated.conf** yang sudah saya buatkan
5. **Replace seluruh isi** dengan konfigurasi baru
6. **Save Changes**

### Via SSH (alternative)

```bash
# Backup vhost original
sudo cp /etc/nginx/sites-available/bydasiaafrika.com.conf /etc/nginx/sites-available/bydasiaafrika.com.conf.backup

# Edit vhost
sudo nano /etc/nginx/sites-available/bydasiaafrika.com.conf
```

Paste isi `vhost-updated.conf`, lalu:

```bash
# Test nginx config
sudo nginx -t

# Jika OK, reload
sudo systemctl reload nginx
```

## 🔧 Step 3: Update SSL Certificate

Karena CloudPanel pakai placeholder `{{ssl_certificate_key}}`, SSL sudah include subdomain secara otomatis jika certificate-nya wildcard atau multi-domain.

**Cek certificate saat ini:**

```bash
sudo certbot certificates
```

**Jika certificate belum include admin subdomain:**

```bash
# Expand certificate untuk include subdomain
sudo certbot --nginx -d bydasiaafrika.com -d www.bydasiaafrika.com -d admin.bydasiaafrika.com --expand

# Atau renew dengan subdomain baru
sudo certbot certonly --webroot -w /home/bydasiaafrika/htdocs/bydasiaafrika.com -d bydasiaafrika.com -d www.bydasiaafrika.com -d admin.bydasiaafrika.com --expand
```

**CloudPanel auto-manage SSL:**

Kalau CloudPanel manage SSL, tambahkan subdomain via UI:
1. Sites → bydasiaafrika.com → **SSL/TLS**
2. **Edit** certificate
3. Tambahkan `admin.bydasiaafrika.com` ke domain list
4. **Save & Renew**

## 🔧 Step 4: Test Configuration

### 1. Test Nginx Syntax

```bash
sudo nginx -t
```

Output harus:
```
nginx: configuration file /etc/nginx/nginx.conf test is successful
```

### 2. Reload Nginx

```bash
sudo systemctl reload nginx
# atau
sudo systemctl restart nginx
```

### 3. Test Access

```bash
# Test HTTP (harus redirect ke HTTPS)
curl -I http://admin.bydasiaafrika.com

# Test HTTPS
curl -I https://admin.bydasiaafrika.com
```

Expected response:
```
HTTP/2 200
server: nginx
content-type: text/html
```

### 4. Test di Browser

1. Buka `https://admin.bydasiaafrika.com`
2. Harus muncul login page (index.php)
3. Cek certificate (klik gembok) - harus valid

## 🔧 Step 5: Verify Admin Panel Works

### Test Login

1. Username: `admin`
2. Password: `admin123`
3. Seharusnya redirect ke dashboard

### Test API

```bash
# Test auth API (seharusnya return 401 atau error unauthorized)
curl https://admin.bydasiaafrika.com/api/site.php

# Test dengan session (after login)
curl -c cookies.txt -d '{"username":"admin","password":"admin123"}' \
  -H "Content-Type: application/json" \
  https://admin.bydasiaafrika.com/api/auth.php

curl -b cookies.txt https://admin.bydasiaafrika.com/api/site.php
```

## 🔧 Step 6: Fix Paths (jika perlu)

Jika admin folder di subdirectory, mungkin path ke `src/data/` perlu adjustment.

**Test path dengan debug:**

Buat file `test-path.php` di admin folder:

```php
<?php
$projectRoot = dirname(__DIR__);
echo "Admin folder: " . __DIR__ . "<br>";
echo "Project root: " . $projectRoot . "<br>";
echo "Data dir: " . $projectRoot . '/src/data' . "<br>";
echo "Site.ts exists: " . (file_exists($projectRoot . '/src/data/site.ts') ? 'YES' : 'NO') . "<br>";
```

Akses: `https://admin.bydasiaafrika.com/test-path.php`

**Expected output:**
```
Admin folder: /home/bydasiaafrika/htdocs/bydasiaafrika.com/admin
Project root: /home/bydasiaafrika/htdocs/bydasiaafrika.com
Data dir: /home/bydasiaafrika/htdocs/bydasiaafrika.com/src/data
Site.ts exists: YES
```

Jika path salah, update di semua API files:

```php
// Di admin/api/*.php, ganti:
$projectRoot = dirname(__DIR__, 2);

// Jadi:
$projectRoot = realpath(dirname(__DIR__) . '/..');
// atau hardcode:
$projectRoot = '/home/bydasiaafrika/htdocs/bydasiaafrika.com';
```

## 🐛 Troubleshooting

### 1. 502 Bad Gateway

**Cause:** PHP-FPM not running atau port salah

**Fix:**
```bash
# Cek PHP-FPM status
sudo systemctl status php8.2-fpm

# Restart jika perlu
sudo systemctl restart php8.2-fpm

# Cek port yang dipakai
ps aux | grep php-fpm
```

### 2. 404 Not Found

**Cause:** Document root salah atau file tidak ada

**Fix:**
```bash
# Cek folder exists
ls -la /home/bydasiaafrika/htdocs/bydasiaafrika.com/admin/

# Cek file index.php ada
ls -la /home/bydasiaafrika/htdocs/bydasiaafrika.com/admin/index.php

# Cek permissions
sudo chown -R bydasiaafrika:bydasiaafrika /home/bydasiaafrika/htdocs/bydasiaafrika.com/admin
sudo chmod -R 755 /home/bydasiaafrika/htdocs/bydasiaafrika.com/admin
```

### 3. SSL Certificate Invalid

**Cause:** Certificate belum include subdomain

**Fix:**
```bash
# Expand certificate
sudo certbot --nginx -d bydasiaafrika.com -d admin.bydasiaafrika.com --expand

# Atau generate new
sudo certbot certonly --webroot -w /home/bydasiaafrika/htdocs/bydasiaafrika.com \
  -d bydasiaafrika.com -d www.bydasiaafrika.com -d admin.bydasiaafrika.com
```

### 4. PHP not executing (download file instead)

**Cause:** Fastcgi config salah atau PHP-FPM port salah

**Fix:**
```bash
# Cek PHP-FPM port di CloudPanel
# Sites → bydasiaafrika.com → PHP-FPM

# Update {{php_fpm_port}} di vhost
# Biasanya: 9000, 9001, 9002, dll

# Test manual
curl -I https://admin.bydasiaafrika.com/index.php
# Header harus: Content-Type: text/html; charset=UTF-8
```

### 5. API returns "Unauthorized" setelah login

**Cause:** Session tidak tersimpan

**Fix:**
```bash
# Cek session directory writable
ls -la /var/lib/php/sessions/
sudo chmod 1733 /var/lib/php/sessions/

# Atau set custom session path di php.ini
session.save_path = "/home/bydasiaafrika/sessions"
```

## 📊 Verify Everything Works

Checklist final:

- [ ] DNS `admin.bydasiaafrika.com` resolve ke IP server
- [ ] HTTPS works (certificate valid)
- [ ] HTTP redirect ke HTTPS
- [ ] Login page tampil
- [ ] Bisa login dengan admin/admin123
- [ ] Dashboard tampil setelah login
- [ ] API endpoint `/api/site.php` return data (after login)
- [ ] Upload test image works
- [ ] Rebuild button works (check logs)

## 🔐 Security Post-Setup

Setelah semua jalan:

1. **Ganti password default:**
   ```sql
   mysql -u bydaaUser -p bydasiaafDb
   
   -- Generate hash dulu
   -- php -r "echo password_hash('password_baru_kamu', PASSWORD_DEFAULT);"
   
   UPDATE admin_users 
   SET password = '$2y$10$YourNewHashHere' 
   WHERE username = 'admin';
   ```

2. **Enable Cloudflare proxy** (orange cloud) untuk hide IP

3. **Optional: Basic Auth tambahan**
   ```nginx
   # Tambahkan di admin server block
   location / {
       auth_basic "Restricted Access";
       auth_basic_user_file /etc/nginx/.htpasswd;
       # ... existing config
   }
   ```
   
   Create htpasswd:
   ```bash
   sudo htpasswd -c /etc/nginx/.htpasswd adminuser
   ```

4. **Monitor logs:**
   ```bash
   tail -f /home/bydasiaafrika/logs/admin-bydasiaafrika-error.log
   ```

---

**Done! Admin panel siap digunakan.** 🚀

Kalau ada error, PM screenshot atau log error-nya ya brader!
