<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/database.php';

requireLogin();

$db = getDB();
$projectRoot = dirname(__DIR__, 2);
$siteFile = $projectRoot . '/src/data/site.ts';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Read current site.ts and parse to JSON
    if (!file_exists($siteFile)) {
        http_response_code(404);
        echo json_encode(['error' => 'site.ts not found']);
        exit;
    }
    
    $content = file_get_contents($siteFile);
    
    // Extract data using regex (simple parser)
    $data = [];
    
    // Description
    if (preg_match("/description:\s*'([^']+)'/", $content, $m)) {
        $data['description'] = $m[1];
    }
    
    // Phone
    if (preg_match("/phone:\s*'([^']+)'/", $content, $m)) {
        $data['phone'] = $m[1];
    }
    
    // WhatsApp
    if (preg_match("/whatsapp:\s*'([^']+)'/", $content, $m)) {
        $data['whatsapp'] = $m[1];
    }
    
    // Email
    if (preg_match("/email:\s*'([^']+)'/", $content, $m)) {
        $data['email'] = $m[1];
    }
    
    // Address
    if (preg_match("/address:\s*\{([^}]+)\}/s", $content, $m)) {
        $addr = $m[1];
        $address = [];
        if (preg_match("/street:\s*'([^']+)'/", $addr, $sm)) $address['street'] = $sm[1];
        if (preg_match("/city:\s*'([^']+)'/", $addr, $sm)) $address['city'] = $sm[1];
        if (preg_match("/region:\s*'([^']+)'/", $addr, $sm)) $address['region'] = $sm[1];
        if (preg_match("/postalCode:\s*'([^']+)'/", $addr, $sm)) $address['postalCode'] = $sm[1];
        if (preg_match("/country:\s*'([^']+)'/", $addr, $sm)) $address['country'] = $sm[1];
        $data['address'] = $address;
    }
    
    // Geo
    if (preg_match("/lat:\s*([-\d.]+)/", $content, $m)) {
        $data['lat'] = floatval($m[1]);
    }
    if (preg_match("/lng:\s*([-\d.]+)/", $content, $m)) {
        $data['lng'] = floatval($m[1]);
    }
    
    // Maps embed URL
    if (preg_match("/mapsEmbedUrl:\s*'([^']*)'/", $content, $m)) {
        $data['mapsEmbedUrl'] = $m[1];
    }
    
    // Social media
    if (preg_match("/instagram:\s*'([^']*)'/", $content, $m)) {
        $data['instagram'] = $m[1];
    }
    if (preg_match("/tiktok:\s*'([^']*)'/", $content, $m)) {
        $data['tiktok'] = $m[1];
    }
    if (preg_match("/youtube:\s*'([^']*)'/", $content, $m)) {
        $data['youtube'] = $m[1];
    }
    
    // Opening hours (simplified)
    $data['weekdayOpen'] = '09:00';
    $data['weekdayClose'] = '18:00';
    $data['sundayOpen'] = '10:00';
    $data['sundayClose'] = '16:00';
    
    // Disclaimer
    if (preg_match("/disclaimer:\s*'(.+?)'/s", $content, $m)) {
        $data['disclaimer'] = $m[1];
    }
    
    echo json_encode($data);
    
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Generate new site.ts content
    $template = <<<TS
export const site = {
  description: '{$data['description']}',
  phone: '{$data['phone']}',
  whatsapp: '{$data['whatsapp']}',
  email: '{$data['email']}',
  address: {
    street: '{$data['address']['street']}',
    city: '{$data['address']['city']}',
    region: '{$data['address']['region']}',
    postalCode: '{$data['address']['postalCode']}',
    country: '{$data['address']['country']}',
  },
  lat: {$data['lat']},
  lng: {$data['lng']},
  openingHours: [
    {
      days: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
      opens: '{$data['weekdayOpen']}',
      closes: '{$data['weekdayClose']}',
    },
    {
      days: ['Sunday'],
      opens: '{$data['sundayOpen']}',
      closes: '{$data['sundayClose']}',
    },
  ],
  social: {
    instagram: '{$data['instagram']}',
    tiktok: '{$data['tiktok']}',
    youtube: '{$data['youtube']}',
  },
  mapsEmbedUrl: '{$data['mapsEmbedUrl']}',
  disclaimer: '{$data['disclaimer']}',
};

export const waLink = (message: string) =>
  `https://wa.me/\${site.whatsapp}?text=\${encodeURIComponent(message)}`;

TS;
    
    // Write to file
    if (file_put_contents($siteFile, $template)) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to write file']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
