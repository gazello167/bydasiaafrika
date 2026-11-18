<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/auth.php';

requireLogin();

$projectRoot = dirname(__DIR__, 2);
$slidesFile = $projectRoot . '/src/data/slides.ts';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!file_exists($slidesFile)) {
        http_response_code(404);
        echo json_encode(['error' => 'slides.ts not found']);
        exit;
    }
    
    $content = file_get_contents($slidesFile);
    $slides = [];
    
    // Parse slides array (simplified regex parser)
    if (preg_match('/export const slides.*?\[(.*?)\];/s', $content, $matches)) {
        $arrayContent = $matches[1];
        
        // Split by objects
        preg_match_all('/\{(.*?)\}/s', $arrayContent, $objects);
        
        foreach ($objects[1] as $obj) {
            $slide = [];
            if (preg_match("/eyebrow:\s*'([^']+)'/", $obj, $m)) $slide['eyebrow'] = $m[1];
            if (preg_match("/title:\s*'([^']+)'/", $obj, $m)) $slide['title'] = $m[1];
            if (preg_match("/text:\s*'([^']+)'/", $obj, $m)) $slide['text'] = $m[1];
            if (preg_match("/label:\s*'([^']+)'/", $obj, $m)) $slide['ctaLabel'] = $m[1];
            if (preg_match("/href:\s*'([^']+)'/", $obj, $m)) $slide['ctaHref'] = $m[1];
            if (preg_match("/image:\s*'([^']+)'/", $obj, $m)) $slide['image'] = $m[1];
            
            $slides[] = $slide;
        }
    }
    
    echo json_encode($slides);
    
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $slides = json_decode(file_get_contents('php://input'), true);
    
    // Generate TypeScript content
    $tsSlides = [];
    foreach ($slides as $slide) {
        $image = isset($slide['image']) ? "image: '{$slide['image']}'," : '';
        $tsSlides[] = "  {
    eyebrow: '{$slide['eyebrow']}',
    title: '{$slide['title']}',
    text: '{$slide['text']}',
    cta: { label: '{$slide['ctaLabel']}', href: '{$slide['ctaHref']}' },
    {$image}
  }";
    }
    
    $template = <<<TS
// Slide hero. Gambar opsional: taruh di src/assets/hero/ lalu isi image: "hero/nama.webp"
// (disarankan lebar 1920px, di bawah 200 KB). Tanpa gambar, slide memakai latar gradien.
export interface Slide {
  eyebrow: string;
  title: string;
  text: string;
  cta: { label: string; href: string };
  image?: string;
}

export const slides: Slide[] = [
{implode(",\n", $tsSlides)}
];

TS;
    
    if (file_put_contents($slidesFile, $template)) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to write file']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
