<?php
function dhex($key = "all")
{
    if ($key != "all") {
        return DhexConfig::get($key);
    }
    return DhexConfig::all();
}

function updateDhexValue(string $key, string $value): void
{
    $path = realpath(__DIR__ . '/../../.dhex');
    if (!$path || !is_writable($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $found = false;

    foreach ($lines as &$line) {
        if (str_starts_with(trim($line), $key . '=')) {
            $line  = $key . '=' . $value;
            $found = true;
        }
    }

    if (!$found) {
        $lines[] = $key . '=' . $value;
    }

    file_put_contents($path, implode(PHP_EOL, $lines) . PHP_EOL);
}

function installRouteSystem(): void
{
    if (dhex('system') !== 'route') {
        return;
    }

    $root     = realpath(__DIR__ . '/../../');
    $template = $root . '/dhextools/template';

    $files = [
        'dhex.php',
        '.htaccess'
    ];

    foreach ($files as $file) {
        $source = $template . '/' . $file;
        $target = $root . '/' . $file;

        if (!file_exists($source)) {
            continue;
        }

        copy($source, $target);
    }

    $index = $root . '/index.php';
    if (file_exists($index)) {
        unlink($index);
    }

    updateDhexValue('system', 'classic');
}


function conf($type = 'singleton', $connection = 'mysql')
{
    if ($type == 'singleton' || $type == 's') {
        return SingletonDatabase::getInstance();
    }
    $db = new Database;
    return $db->getConnection();
}

function parse(?int $index = null): string|null
{
    $uri = $_SERVER['REQUEST_URI'] ?? null;
    if (!$uri) {
        return "null";
    }

    $path = parse_url($uri, PHP_URL_PATH);
    $path = trim($path, '/');

    if ($path === '') {
        return "null";
    }

    $segments = explode('/', $path);

    $segments = array_combine(
        range(1, count($segments)),
        $segments
    );

    if ($index !== null) {
        return $segments[$index] ?? "null";
    }

    return implode('/', $segments);
}

function page(){
    return $_GET['page'] ?? 'dhex';
}