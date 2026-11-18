<?php
$pageTitle = 'Site Config';
include __DIR__ . '/includes/header.php';
?>

<h1 style="margin-bottom: 24px;">⚙️ Site Configuration</h1>

<div id="alert" class="alert" style="display: none;"></div>

<form id="siteForm">
    <div class="card">
        <h2 class="card-title">Informasi Dasar</h2>
        
        <div class="form-group">
            <label for="description">Deskripsi Dealer</label>
            <textarea id="description" name="description" required placeholder="Dealer BYD di Jl. Asia Afrika, Bandung..."></textarea>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="phone">Nomor Telepon</label>
                <input type="text" id="phone" name="phone" required placeholder="+62-22-8080800">
            </div>
            
            <div class="form-group">
                <label for="whatsapp">WhatsApp (format: 628xxx)</label>
                <input type="text" id="whatsapp" name="whatsapp" required placeholder="6281234567890">
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required placeholder="info@bydasiaafrika.com">
            </div>
        </div>
    </div>
    
    <div class="card">
        <h2 class="card-title">Alamat</h2>
        
        <div class="form-group">
            <label for="street">Jalan</label>
            <input type="text" id="street" name="street" required placeholder="Jl. Asia Afrika No. XX">
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="city">Kota</label>
                <input type="text" id="city" name="city" required placeholder="Bandung">
            </div>
            
            <div class="form-group">
                <label for="region">Provinsi</label>
                <input type="text" id="region" name="region" required placeholder="Jawa Barat">
            </div>
            
            <div class="form-group">
                <label for="postalCode">Kode Pos</label>
                <input type="text" id="postalCode" name="postalCode" required placeholder="40111">
            </div>
            
            <div class="form-group">
                <label for="country">Kode Negara</label>
                <input type="text" id="country" name="country" required placeholder="ID" maxlength="2">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="lat">Latitude (Google Maps)</label>
                <input type="number" step="any" id="lat" name="lat" required placeholder="-6.9215">
            </div>
            
            <div class="form-group">
                <label for="lng">Longitude (Google Maps)</label>
                <input type="number" step="any" id="lng" name="lng" required placeholder="107.6071">
            </div>
        </div>
        
        <div class="form-group">
            <label for="mapsEmbedUrl">Google Maps Embed URL</label>
            <input type="url" id="mapsEmbedUrl" name="mapsEmbedUrl" placeholder="https://maps.google.com/...">
            <small class="text-muted">Opsional. Paste iframe URL dari Google Maps.</small>
        </div>
    </div>
    
    <div class="card">
        <h2 class="card-title">Jam Operasional</h2>
        
        <div class="form-row">
            <div class="form-group">
                <label for="weekdayOpen">Senin-Sabtu Buka</label>
                <input type="time" id="weekdayOpen" name="weekdayOpen" required value="09:00">
            </div>
            
            <div class="form-group">
                <label for="weekdayClose">Senin-Sabtu Tutup</label>
                <input type="time" id="weekdayClose" name="weekdayClose" required value="18:00">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="sundayOpen">Minggu Buka</label>
                <input type="time" id="sundayOpen" name="sundayOpen" required value="10:00">
            </div>
            
            <div class="form-group">
                <label for="sundayClose">Minggu Tutup</label>
                <input type="time" id="sundayClose" name="sundayClose" required value="16:00">
            </div>
        </div>
    </div>
    
    <div class="card">
        <h2 class="card-title">Social Media</h2>
        
        <div class="form-group">
            <label for="instagram">Instagram URL</label>
            <input type="url" id="instagram" name="instagram" placeholder="https://instagram.com/...">
        </div>
        
        <div class="form-group">
            <label for="tiktok">TikTok URL</label>
            <input type="url" id="tiktok" name="tiktok" placeholder="https://tiktok.com/@...">
        </div>
        
        <div class="form-group">
            <label for="youtube">YouTube URL</label>
            <input type="url" id="youtube" name="youtube" placeholder="https://youtube.com/@...">
        </div>
    </div>
    
    <div class="card">
        <h2 class="card-title">Disclaimer</h2>
        
        <div class="form-group">
            <label for="disclaimer">Teks Disclaimer (Footer)</label>
            <textarea id="disclaimer" name="disclaimer" required placeholder="Situs ini dikelola oleh dealer/mitra pemasaran..."></textarea>
        </div>
    </div>
    
    <div class="card">
        <button type="submit" class="btn btn-primary" id="saveBtn">💾 Simpan Perubahan</button>
    </div>
</form>

<script>
    const form = document.getElementById('siteForm');
    const alert = document.getElementById('alert');
    const saveBtn = document.getElementById('saveBtn');
    
    // Load data on page load
    async function loadData() {
        try {
            const res = await fetch('/api/site.php');
            const data = await res.json();
            
            document.getElementById('description').value = data.description || '';
            document.getElementById('phone').value = data.phone || '';
            document.getElementById('whatsapp').value = data.whatsapp || '';
            document.getElementById('email').value = data.email || '';
            
            if (data.address) {
                document.getElementById('street').value = data.address.street || '';
                document.getElementById('city').value = data.address.city || '';
                document.getElementById('region').value = data.address.region || '';
                document.getElementById('postalCode').value = data.address.postalCode || '';
                document.getElementById('country').value = data.address.country || '';
            }
            
            document.getElementById('lat').value = data.lat || '';
            document.getElementById('lng').value = data.lng || '';
            document.getElementById('mapsEmbedUrl').value = data.mapsEmbedUrl || '';
            
            document.getElementById('weekdayOpen').value = data.weekdayOpen || '09:00';
            document.getElementById('weekdayClose').value = data.weekdayClose || '18:00';
            document.getElementById('sundayOpen').value = data.sundayOpen || '10:00';
            document.getElementById('sundayClose').value = data.sundayClose || '16:00';
            
            document.getElementById('instagram').value = data.instagram || '';
            document.getElementById('tiktok').value = data.tiktok || '';
            document.getElementById('youtube').value = data.youtube || '';
            
            document.getElementById('disclaimer').value = data.disclaimer || '';
        } catch (err) {
            showAlert('Gagal memuat data: ' + err.message, 'error');
        }
    }
    
    // Save data
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const formData = new FormData(form);
        const data = {
            description: formData.get('description'),
            phone: formData.get('phone'),
            whatsapp: formData.get('whatsapp'),
            email: formData.get('email'),
            address: {
                street: formData.get('street'),
                city: formData.get('city'),
                region: formData.get('region'),
                postalCode: formData.get('postalCode'),
                country: formData.get('country'),
            },
            lat: parseFloat(formData.get('lat')),
            lng: parseFloat(formData.get('lng')),
            mapsEmbedUrl: formData.get('mapsEmbedUrl'),
            weekdayOpen: formData.get('weekdayOpen'),
            weekdayClose: formData.get('weekdayClose'),
            sundayOpen: formData.get('sundayOpen'),
            sundayClose: formData.get('sundayClose'),
            instagram: formData.get('instagram'),
            tiktok: formData.get('tiktok'),
            youtube: formData.get('youtube'),
            disclaimer: formData.get('disclaimer'),
        };
        
        saveBtn.disabled = true;
        saveBtn.textContent = '⏳ Menyimpan...';
        
        try {
            const res = await fetch('/api/site.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data),
            });
            
            const result = await res.json();
            
            if (result.success) {
                showAlert('✅ Data berhasil disimpan! Jangan lupa rebuild site.', 'success');
            } else {
                showAlert('❌ Gagal menyimpan: ' + result.error, 'error');
            }
        } catch (err) {
            showAlert('❌ Error: ' + err.message, 'error');
        } finally {
            saveBtn.disabled = false;
            saveBtn.textContent = '💾 Simpan Perubahan';
        }
    });
    
    function showAlert(message, type) {
        alert.textContent = message;
        alert.className = 'alert alert-' + type;
        alert.style.display = 'block';
        
        setTimeout(() => {
            alert.style.display = 'none';
        }, 5000);
    }
    
    // Load data when page loads
    loadData();
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
