<?php
$pageTitle = 'Dashboard';
include __DIR__ . '/includes/header.php';

$projectRoot = dirname(__DIR__);
$dataDir = $projectRoot . '/src/data';

// Count stats
$slideCount = 0;
$modelCount = 0;

if (file_exists($dataDir . '/slides.ts')) {
    $content = file_get_contents($dataDir . '/slides.ts');
    preg_match_all('/\{[^}]+\}/s', $content, $matches);
    $slideCount = count($matches[0]);
}

if (file_exists($dataDir . '/models.ts')) {
    $content = file_get_contents($dataDir . '/models.ts');
    preg_match_all('/slug:\s*\'[^\']+\'/s', $content, $matches);
    $modelCount = count($matches[0]);
}
?>

<h1 style="margin-bottom: 32px;">Dashboard</h1>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Banner Hero</div>
        <div class="stat-value"><?= $slideCount ?></div>
    </div>
    
    <div class="stat-card">
        <div class="stat-label">Total Model Mobil</div>
        <div class="stat-value"><?= $modelCount ?></div>
    </div>
    
    <div class="stat-card">
        <div class="stat-label">Status</div>
        <div class="stat-value" style="font-size: 20px; color: #10b981;">✓ Ready</div>
    </div>
</div>

<div class="card">
    <h2 class="card-title">Quick Actions</h2>
    <div class="flex gap-2">
        <a href="/admin/slides.php" class="btn btn-primary">Edit Banner</a>
        <a href="/admin/models.php" class="btn btn-primary">Edit Models</a>
        <a href="/admin/site.php" class="btn btn-secondary">Site Config</a>
    </div>
</div>

<div class="card">
    <h2 class="card-title">⚡ Rebuild Site</h2>
    <p class="text-muted mb-4">
        Setelah mengubah data (banner, model, atau site config), klik tombol di bawah untuk rebuild Astro site.
        Proses ini akan memakan waktu 1-2 menit.
    </p>
    <button onclick="rebuild()" class="btn btn-success">🔄 Rebuild Sekarang</button>
</div>

<div class="card">
    <h2 class="card-title">📝 Instruksi</h2>
    <ol style="line-height: 1.8; padding-left: 20px;">
        <li>Edit data di halaman <strong>Site Config</strong>, <strong>Banner Hero</strong>, atau <strong>Model Mobil</strong></li>
        <li>Data tersimpan otomatis ke file <code>.ts</code> di <code>src/data/</code></li>
        <li>Klik <strong>Rebuild Site</strong> untuk generate static pages baru</li>
        <li>Site akan otomatis terupdate (jika Astro build output-nya ke <code>dist/</code> yang di-serve CloudPanel)</li>
    </ol>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
