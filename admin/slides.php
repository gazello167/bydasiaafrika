<?php
$pageTitle = 'Banner Hero';
include __DIR__ . '/includes/header.php';
?>

<h1 style="margin-bottom: 24px;">🖼️ Banner Hero / Slides</h1>

<div id="alert" class="alert" style="display: none;"></div>

<div class="card">
    <div class="flex justify-between items-center mb-4">
        <h2 class="card-title mb-0">Manage Slides</h2>
        <button onclick="addSlide()" class="btn btn-success">➕ Tambah Slide</button>
    </div>
    
    <div id="slidesContainer"></div>
    
    <div class="mt-4">
        <button onclick="saveSlides()" class="btn btn-primary" id="saveBtn">💾 Simpan Semua Perubahan</button>
    </div>
</div>

<script>
    let slides = [];
    
    // Load slides
    async function loadSlides() {
        try {
            const res = await fetch('/api/slides.php');
            slides = await res.json();
            renderSlides();
        } catch (err) {
            showAlert('Gagal memuat slides: ' + err.message, 'error');
        }
    }
    
    // Render slides
    function renderSlides() {
        const container = document.getElementById('slidesContainer');
        
        if (slides.length === 0) {
            container.innerHTML = '<p class="text-muted">Belum ada slide. Klik "Tambah Slide" untuk membuat yang pertama.</p>';
            return;
        }
        
        container.innerHTML = slides.map((slide, index) => `
            <div class="card" style="background: #f9fafb; margin-bottom: 16px;">
                <div class="flex justify-between items-center mb-4">
                    <h3 style="font-size: 16px; font-weight: 600;">Slide #${index + 1}</h3>
                    <button onclick="removeSlide(${index})" class="btn btn-danger btn-sm" style="padding: 6px 12px; font-size: 12px;">🗑️ Hapus</button>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Eyebrow (Teks kecil atas)</label>
                        <input type="text" data-index="${index}" data-field="eyebrow" value="${slide.eyebrow || ''}" placeholder="Test Drive" onchange="updateSlide(this)">
                    </div>
                    
                    <div class="form-group">
                        <label>Title (Judul utama)</label>
                        <input type="text" data-index="${index}" data-field="title" value="${slide.title || ''}" placeholder="Rasakan mobil listrik BYD" required onchange="updateSlide(this)">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Text (Deskripsi)</label>
                    <textarea data-index="${index}" data-field="text" onchange="updateSlide(this)" placeholder="Datang ke showroom kami...">${slide.text || ''}</textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>CTA Label (Tombol)</label>
                        <input type="text" data-index="${index}" data-field="ctaLabel" value="${slide.ctaLabel || ''}" placeholder="Jadwalkan Test Drive" onchange="updateSlide(this)">
                    </div>
                    
                    <div class="form-group">
                        <label>CTA Link (URL)</label>
                        <input type="text" data-index="${index}" data-field="ctaHref" value="${slide.ctaHref || ''}" placeholder="/test-drive" onchange="updateSlide(this)">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Gambar Background (opsional)</label>
                    <input type="file" accept="image/*" onchange="uploadImage(this, ${index})" style="margin-bottom: 8px;">
                    <small class="text-muted">Ukuran rekomendasi: 1920x1080px, max 500KB. Kosongkan untuk pakai gradien default.</small>
                    ${slide.image ? `
                        <div class="image-preview">
                            <img src="${slide.image.startsWith('http') ? slide.image : '/src/assets/' + slide.image}" alt="Preview">
                            <button onclick="removeImage(${index})" class="btn btn-danger btn-sm mt-2" style="padding: 4px 8px; font-size: 12px;">Hapus Gambar</button>
                        </div>
                    ` : ''}
                    <input type="hidden" data-index="${index}" data-field="image" value="${slide.image || ''}">
                </div>
            </div>
        `).join('');
    }
    
    // Update slide field
    function updateSlide(input) {
        const index = parseInt(input.dataset.index);
        const field = input.dataset.field;
        slides[index][field] = input.value;
    }
    
    // Add new slide
    function addSlide() {
        slides.push({
            eyebrow: 'Eyebrow',
            title: 'Judul Slide Baru',
            text: 'Deskripsi slide...',
            ctaLabel: 'Label Tombol',
            ctaHref: '/link',
            image: '',
        });
        renderSlides();
    }
    
    // Remove slide
    function removeSlide(index) {
        if (!confirm('Hapus slide ini?')) return;
        slides.splice(index, 1);
        renderSlides();
    }
    
    // Remove image
    function removeImage(index) {
        if (!confirm('Hapus gambar ini?')) return;
        slides[index].image = '';
        renderSlides();
    }
    
    // Upload image
    async function uploadImage(input, index) {
        if (!input.files || !input.files[0]) return;
        
        const file = input.files[0];
        
        // Validate size
        if (file.size > 1024 * 1024) { // 1MB
            alert('Ukuran file terlalu besar! Max 1MB.');
            input.value = '';
            return;
        }
        
        const formData = new FormData();
        formData.append('file', file);
        formData.append('type', 'slide');
        
        showAlert('⏳ Mengupload gambar...', 'success');
        
        try {
            const res = await fetch('/api/upload.php', {
                method: 'POST',
                body: formData,
            });
            
            const data = await res.json();
            
            if (data.success) {
                slides[index].image = data.url;
                renderSlides();
                showAlert('✅ Gambar berhasil diupload!', 'success');
            } else {
                alert('Upload gagal: ' + data.error);
            }
        } catch (err) {
            alert('Error upload: ' + err.message);
        }
    }
    
    // Save all slides
    async function saveSlides() {
        const saveBtn = document.getElementById('saveBtn');
        saveBtn.disabled = true;
        saveBtn.textContent = '⏳ Menyimpan...';
        
        try {
            const res = await fetch('/api/slides.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(slides),
            });
            
            const result = await res.json();
            
            if (result.success) {
                showAlert('✅ Slides berhasil disimpan! Jangan lupa rebuild site.', 'success');
            } else {
                showAlert('❌ Gagal menyimpan: ' + result.error, 'error');
            }
        } catch (err) {
            showAlert('❌ Error: ' + err.message, 'error');
        } finally {
            saveBtn.disabled = false;
            saveBtn.textContent = '💾 Simpan Semua Perubahan';
        }
    }
    
    function showAlert(message, type) {
        const alert = document.getElementById('alert');
        alert.textContent = message;
        alert.className = 'alert alert-' + type;
        alert.style.display = 'block';
        
        setTimeout(() => {
            alert.style.display = 'none';
        }, 5000);
    }
    
    // Load slides on page load
    loadSlides();
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
