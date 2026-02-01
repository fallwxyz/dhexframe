<?php
require_once __DIR__ . '/dhextools/core/core.php';

$page = page();
$view = __DIR__ . "/dhextools/page/";
$host = dhex('app_url');

switch ($page) {
    case "dhex":
        echo tanggal('d');
        break;

    default:
        http_response_code(404);
        echo parse() . "<br>";
        echo "Error: Failed to open stream. No such file or directory $page";
        break;
}