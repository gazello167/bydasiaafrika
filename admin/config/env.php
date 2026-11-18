<?php
// Load variables from .env file in project root into PHP's environment.
// Runs once per request (static guard prevents duplicate loads).
function loadEnvFile() {
    static $loaded = false;
    if ($loaded) return;
    $loaded = true;

    $path = dirname(__DIR__, 2) . '/.env';
    if (!file_exists($path)) return;

    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0) continue;
        if (strpos($line, '=') === false) continue;

        [$name, $value] = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        // Strip surrounding quotes if present
        $value = trim($value, "\"'");

        putenv("$name=$value");
        $_ENV[$name] = $value;
    }
}

loadEnvFile();
