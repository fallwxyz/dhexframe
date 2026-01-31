<?php
require_once __DIR__ . '/dhextools/core/core.php';

$page = $_GET['page'] ?? 'dhex';
$view = __DIR__ . "/dhextools/page/";
$host = dhex('app_url');

switch ($page) {
    case "dhex":
        echo "Welcome to DHEX Frame";
        break;

    default:
        http_response_code(404);
        echo parse() . "<br>";
        echo "Error: Failed to open stream. No such file or directory $page";
        break;
}