<?php
$pageTitle = 'Model Mobil';
include __DIR__ . '/includes/header.php';
?>

<h1 style="margin-bottom: 24px;">🚘 Model Mobil</h1>

<div id="alert" class="alert" style="display: none;"></div>

<div class="card">
    <div class="flex justify-between items-center mb-4">
        <h2 class="card-title mb-0">Manage Models</h2>
        <button onclick="addModel()" class="btn btn-success">➕ Tambah Model</button>
    </div>
    
    <div id="modelsContainer"></div>
    
    <div class="mt-4">
        <button onclick="saveModels()" class="btn btn-primary" id="saveBtn">💾 Simpan Semua Perubahan</button>
    </div>
</div>

<script>
    let models = [];
    const categories = ['MPV', 'SUV', 'Sedan', 'Citycar'];
    const powertrains = ['BEV', 'PHEV'];
    
    // Load models
    async function loadModels() {
        try {
            const res = await fetch('/admin/api/models.php');
            models = await res.json();
            renderModels();
        } catch (err) {
            showAlert('Gagal memuat models: ' + err.message, 'error');
        }
    }
    
    // Render models
    function renderModels() {
        const container = document.getElementById('modelsContainer');
        
        if (models.length === 0) {
            container.innerHTML = '<p class="text-muted">Belum ada model. Klik "Tambah Model" untuk membuat yang pertama.</p>';
            return;
        }
        
        container.innerHTML = models.map((model, index) => `
            <div class="card" style="background: #f9fafb; margin-bottom: 24px; border: 2px solid #e5e7eb;">
                <div class="flex justify-between items-center mb-4">
                    <h3 style="font-size: 18px; font-weight: 700; color: #1e293b;">${model.name || 'Model Baru'}</h3>
                    <div class="flex gap-2">
                        <button onclick="moveModel(${index}, -1)" class="btn btn-secondary" style="padding: 6px 12px; font-size: 12px;" ${index === 0 ? 'disabled' : ''}>⬆️</button>
                        <button onclick="moveModel(${index}, 1)" class="btn btn-secondary" style="padding: 6px 12px; font-size: 12px;" ${index === models.length - 1 ? 'disabled' : ''}>⬇️</button>
                        <button onclick="removeModel(${index})" class="btn btn-danger" style="padding: 6px 12px; font-size: 12px;">🗑️ Hapus</button>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Slug (URL-friendly)</label>
                        <input type="text" data-index="${index}" data-field="slug" value="${model.slug || ''}" placeholder="atto-3" required onchange="updateModel(this)">
                        <small class="text-muted">Contoh: atto-3, m6, sealion-7</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Nama Model</label>
                        <input type="text" data-index="${index}" data-field="name" value="${model.name || ''}" placeholder="BYD Atto 3" required onchange="updateModel(this)">
                    </div>
                    
                    <div class="form-group">
                        <label>Kategori</label>
                        <select data-index="${index}" data-field="category" onchange="updateModel(this)">
                            ${categories.map(cat => `<option value="${cat}" ${model.category === cat ? 'selected' : ''}>${cat}</option>`).join('')}
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Powertrain</label>
                        <select data-index="${index}" data-field="powertrain" onchange="updateModel(this)">
                            ${powertrains.map(pt => `<option value="${pt}" ${model.powertrain === pt ? 'selected' : ''}>${pt === 'BEV' ? 'Listrik (BEV)' : 'Plug-in Hybrid (PHEV)'}</option>`).join('')}
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Tagline</label>
                        <input type="text" data-index="${index}" data-field="tagline" value="${model.tagline || ''}" placeholder="SUV listrik kompak untuk keluarga" onchange="updateModel(this)">
                    </div>
                    
                    <div class="form-group">
                        <label>Harga OTR (Rupiah)</label>
                        <input type="number" data-index="${index}" data-field="hargaOTR" value="${model.hargaOTR || ''}" placeholder="450000000" onchange="updateModel(this)">
                        <small class="text-muted">Kosongkan jika harga belum tersedia (akan tampil "Hubungi kami")</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Model Baru?</label>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <input type="checkbox" data-index="${index}" data-field="isNew" ${model.isNew ? 'checked' : ''} onchange="updateModelCheckbox(this)" style="width: auto;">
                            <span class="text-muted">Tampilkan badge "New"</span>
                        </div>
                    </div>
                </div>
                
                <!-- Specs Section -->
                <div style="margin: 20px 0; padding: 16px; background: white; border-radius: 6px;">
                    <div class="flex justify-between items-center mb-3">
                        <strong>Spesifikasi</strong>
                        <button onclick="addSpec(${index})" class="btn btn-success" style="padding: 4px 12px; font-size: 12px;">➕ Tambah Spec</button>
                    </div>
                    <div id="specs-${index}">
                        ${(model.specs || []).map((spec, si) => `
                            <div class="form-row" style="margin-bottom: 12px; align-items: center;">
                                <div class="form-group mb-0">
                                    <input type="text" placeholder="Label (e.g., Kapasitas baterai)" value="${spec.label}" onchange="updateSpec(${index}, ${si}, 'label', this.value)">
                                </div>
                                <div class="form-group mb-0">
                                    <input type="text" placeholder="Value (e.g., 60.48 kWh)" value="${spec.value}" onchange="updateSpec(${index}, ${si}, 'value', this.value)">
                                </div>
                                <button onclick="removeSpec(${index}, ${si})" class="btn btn-danger" style="padding: 6px 10px; font-size: 12px; white-space: nowrap;">🗑️</button>
                            </div>
                        `).join('')}
                    </div>
                </div>
                
                <!-- Highlights Section -->
                <div style="margin: 20px 0; padding: 16px; background: white; border-radius: 6px;">
                    <div class="flex justify-between items-center mb-3">
                        <strong>Highlights (Keunggulan)</strong>
                        <button onclick="addHighlight(${index})" class="btn btn-success" style="padding: 4px 12px; font-size: 12px;">➕ Tambah</button>
                    </div>
                    <div id="highlights-${index}">
                        ${(model.highlights || []).map((hl, hi) => `
                            <div class="flex gap-2 items-center" style="margin-bottom: 8px;">
                                <input type="text" placeholder="Keunggulan..." value="${hl}" onchange="updateHighlight(${index}, ${hi}, this.value)" style="flex: 1;">
                                <button onclick="removeHighlight(${index}, ${hi})" class="btn btn-danger" style="padding: 6px 10px; font-size: 12px;">🗑️</button>
                            </div>
                        `).join('')}
                    </div>
                </div>
                
                <!-- Images Section -->
                <div class="form-row">
                    <div class="form-group">
                        <label>Gambar Model (preview card)</label>
                        <input type="file" accept="image/*" onchange="uploadModelImage(this, ${index}, 'image')">
                        <small class="text-muted">Rekomendasi: 800x600px, format WebP/PNG</small>
                        ${model.image ? `
                            <div class="image-preview">
                                <img src="${model.image.startsWith('http') ? model.image : '/src/assets/' + model.image}" alt="Preview" style="max-width: 300px;">
                                <button onclick="removeModelImage(${index}, 'image')" class="btn btn-danger btn-sm mt-2">Hapus Gambar</button>
                            </div>
                        ` : ''}
                    </div>
                    
                    <div class="form-group">
                        <label>Brosur PDF (opsional)</label>
                        <input type="file" accept="application/pdf" onchange="uploadModelImage(this, ${index}, 'brosur')">
                        ${model.brosur ? `
                            <div style="margin-top: 8px;">
                                <a href="${model.brosur}" target="_blank" class="btn btn-secondary btn-sm">📄 Lihat Brosur</a>
                                <button onclick="removeModelImage(${index}, 'brosur')" class="btn btn-danger btn-sm">Hapus</button>
                            </div>
                        ` : ''}
                    </div>
                </div>
            </div>
        `).join('');
    }
    
    // Update model field
    function updateModel(input) {
        const index = parseInt(input.dataset.index);
        const field = input.dataset.field;
        let value = input.value;
        
        if (field === 'hargaOTR') {
            value = value ? parseFloat(value) : null;
        }
        
        models[index][field] = value;
        
        // Re-render if name changed (for header)
        if (field === 'name' || field === 'slug') {
            renderModels();
        }
    }
    
    function updateModelCheckbox(input) {
        const index = parseInt(input.dataset.index);
        models[index].isNew = input.checked;
    }
    
    // Specs management
    function addSpec(modelIndex) {
        if (!models[modelIndex].specs) models[modelIndex].specs = [];
        models[modelIndex].specs.push({ label: '', value: '' });
        renderModels();
    }
    
    function updateSpec(modelIndex, specIndex, field, value) {
        models[modelIndex].specs[specIndex][field] = value;
    }
    
    function removeSpec(modelIndex, specIndex) {
        models[modelIndex].specs.splice(specIndex, 1);
        renderModels();
    }
    
    // Highlights management
    function addHighlight(modelIndex) {
        if (!models[modelIndex].highlights) models[modelIndex].highlights = [];
        models[modelIndex].highlights.push('');
        renderModels();
    }
    
    function updateHighlight(modelIndex, hlIndex, value) {
        models[modelIndex].highlights[hlIndex] = value;
    }
    
    function removeHighlight(modelIndex, hlIndex) {
        models[modelIndex].highlights.splice(hlIndex, 1);
        renderModels();
    }
    
    // Move model up/down
    function moveModel(index, direction) {
        const newIndex = index + direction;
        if (newIndex < 0 || newIndex >= models.length) return;
        
        [models[index], models[newIndex]] = [models[newIndex], models[index]];
        renderModels();
    }
    
    // Add new model
    function addModel() {
        models.push({
            slug: 'model-baru',
            name: 'Model Baru',
            category: 'SUV',
            powertrain: 'BEV',
            isNew: false,
            tagline: 'Tagline model...',
            hargaOTR: null,
            specs: [
                { label: 'Tipe', value: 'TODO' },
                { label: 'Kapasitas baterai', value: 'TODO' },
            ],
            highlights: ['Keunggulan 1', 'Keunggulan 2'],
            image: '',
            brosur: '',
        });
        renderModels();
        
        // Scroll to bottom
        setTimeout(() => {
            window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
        }, 100);
    }
    
    // Remove model
    function removeModel(index) {
        if (!confirm(`Hapus model "${models[index].name}"?`)) return;
        models.splice(index, 1);
        renderModels();
    }
    
    // Upload model image/brosur
    async function uploadModelImage(input, index, type) {
        if (!input.files || !input.files[0]) return;
        
        const file = input.files[0];
        
        const formData = new FormData();
        formData.append('file', file);
        formData.append('type', type === 'brosur' ? 'brosur' : 'model');
        
        showAlert('⏳ Mengupload...', 'success');
        
        try {
            const res = await fetch('/admin/api/upload.php', {
                method: 'POST',
                body: formData,
            });
            
            const data = await res.json();
            
            if (data.success) {
                models[index][type] = data.url;
                renderModels();
                showAlert(`✅ ${type === 'brosur' ? 'Brosur' : 'Gambar'} berhasil diupload!`, 'success');
            } else {
                alert('Upload gagal: ' + data.error);
            }
        } catch (err) {
            alert('Error upload: ' + err.message);
        }
    }
    
    function removeModelImage(index, type) {
        if (!confirm(`Hapus ${type === 'brosur' ? 'brosur' : 'gambar'} ini?`)) return;
        models[index][type] = '';
        renderModels();
    }
    
    // Save all models
    async function saveModels() {
        const saveBtn = document.getElementById('saveBtn');
        saveBtn.disabled = true;
        saveBtn.textContent = '⏳ Menyimpan...';
        
        try {
            const res = await fetch('/admin/api/models.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(models),
            });
            
            const result = await res.json();
            
            if (result.success) {
                showAlert('✅ Models berhasil disimpan! Jangan lupa rebuild site.', 'success');
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
    
    // Load models on page load
    loadModels();
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
