<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/auth.php';

requireLogin();

$projectRoot = dirname(__DIR__, 2);
$modelsFile = $projectRoot . '/src/data/models.ts';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!file_exists($modelsFile)) {
        http_response_code(404);
        echo json_encode(['error' => 'models.ts not found']);
        exit;
    }
    
    $content = file_get_contents($modelsFile);
    $models = [];
    
    // Parse models array
    if (preg_match('/export const models.*?\[(.*?)\];/s', $content, $matches)) {
        $arrayContent = $matches[1];
        
        // Split by objects
        preg_match_all('/\{(.*?)\n  \}/s', $arrayContent, $objects);
        
        foreach ($objects[1] as $obj) {
            $model = [];
            if (preg_match("/slug:\s*'([^']+)'/", $obj, $m)) $model['slug'] = $m[1];
            if (preg_match("/name:\s*'([^']+)'/", $obj, $m)) $model['name'] = $m[1];
            if (preg_match("/category:\s*'([^']+)'/", $obj, $m)) $model['category'] = $m[1];
            if (preg_match("/powertrain:\s*'([^']+)'/", $obj, $m)) $model['powertrain'] = $m[1];
            if (preg_match("/isNew:\s*(true|false)/", $obj, $m)) $model['isNew'] = $m[1] === 'true';
            if (preg_match("/tagline:\s*'([^']+)'/", $obj, $m)) $model['tagline'] = $m[1];
            if (preg_match("/hargaOTR:\s*(null|\d+)/", $obj, $m)) {
                $model['hargaOTR'] = $m[1] === 'null' ? null : intval($m[1]);
            }
            if (preg_match("/image:\s*'([^']+)'/", $obj, $m)) $model['image'] = $m[1];
            if (preg_match("/brosur:\s*'([^']+)'/", $obj, $m)) $model['brosur'] = $m[1];
            
            // Parse specs array
            if (preg_match("/specs:\s*\[(.*?)\]/s", $obj, $m)) {
                $specsStr = $m[1];
                $specs = [];
                preg_match_all("/\{\s*label:\s*'([^']+)',\s*value:\s*'([^']+)'/", $specsStr, $specMatches, PREG_SET_ORDER);
                foreach ($specMatches as $sm) {
                    $specs[] = ['label' => $sm[1], 'value' => $sm[2]];
                }
                $model['specs'] = $specs;
            }
            
            // Parse highlights array
            if (preg_match("/highlights:\s*\[(.*?)\]/s", $obj, $m)) {
                $highlightsStr = $m[1];
                $highlights = [];
                preg_match_all("/'([^']+)'/", $highlightsStr, $hlMatches);
                $model['highlights'] = $hlMatches[1];
            }
            
            $models[] = $model;
        }
    }
    
    echo json_encode($models);
    
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $models = json_decode(file_get_contents('php://input'), true);
    
    // Generate TypeScript content
    $tsModels = [];
    foreach ($models as $model) {
        $isNew = isset($model['isNew']) && $model['isNew'] ? 'isNew: true,' : '';
        $harga = $model['hargaOTR'] ? $model['hargaOTR'] : 'null';
        $image = isset($model['image']) ? "image: '{$model['image']}'," : '';
        $brosur = isset($model['brosur']) ? "brosur: '{$model['brosur']}'," : '';
        
        // Specs
        $specs = [];
        foreach ($model['specs'] as $spec) {
            $specs[] = "{ label: '{$spec['label']}', value: '{$spec['value']}' }";
        }
        $specsStr = implode(', ', $specs);
        
        // Highlights
        $highlights = array_map(fn($h) => "'$h'", $model['highlights']);
        $highlightsStr = implode(', ', $highlights);
        
        $tsModels[] = "  {
    slug: '{$model['slug']}',
    name: '{$model['name']}',
    category: '{$model['category']}',
    powertrain: '{$model['powertrain']}',
    {$isNew}
    tagline: '{$model['tagline']}',
    hargaOTR: {$harga},
    specs: [{$specsStr}],
    highlights: [{$highlightsStr}],
    {$image}
    {$brosur}
  }";
    }
    
    $template = <<<'TS'
// "Database" model mobil. Tab kategori dan filter penggerak di halaman dibuat
// otomatis dari field `category` dan `powertrain` di sini.
// SEMUA harga/spesifikasi di bawah PLACEHOLDER: samakan dengan pricelist resmi.

export type Category = 'MPV' | 'SUV' | 'Sedan' | 'Citycar';
export type Powertrain = 'BEV' | 'PHEV';

// Urutan tab kategori. Kategori tanpa model tidak ditampilkan.
export const categoryOrder: Category[] = ['MPV', 'SUV', 'Sedan', 'Citycar'];

export const powertrainLabels: Record<Powertrain, string> = {
  BEV: 'Listrik (BEV)',
  PHEV: 'Plug-in Hybrid',
};

export interface CarModel {
  slug: string;
  name: string;
  category: Category;
  powertrain: Powertrain;
  isNew?: boolean;
  tagline: string;
  hargaOTR: number | null; // Rupiah; null = tampilkan "Hubungi kami"
  specs: { label: string; value: string }[];
  highlights: string[];
  image?: string; // path relatif ke src/assets, mis. "models/atto-3.webp"
  brosur?: string; // path PDF di public/, mis. "/brosur/atto-3.pdf"
}

export const models: CarModel[] = [

TS;
    $template .= implode(",\n", $tsModels);
    $template .= <<<'TS'

];

// Kategori & penggerak yang benar-benar dipakai data (untuk tab/filter)
export const activeCategories = categoryOrder.filter((c) => models.some((m) => m.category === c));
export const activePowertrains = (Object.keys(powertrainLabels) as Powertrain[]).filter((p) =>
  models.some((m) => m.powertrain === p),
);

export const formatRupiah = (n: number) =>
  new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(n);

TS;
    
    if (file_put_contents($modelsFile, $template)) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to write file']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
