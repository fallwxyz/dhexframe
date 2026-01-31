<?php
declare(strict_types=1);

define('DHEX_CORE_PATH', __DIR__);
define('DHEX_ROOT_PATH', dirname(__DIR__, 2));

require_once DHEX_CORE_PATH . '/config.php';
require_once DHEX_CORE_PATH . '/function.php';

DhexConfig::load(DHEX_ROOT_PATH . '/.dhex');
installRouteSystem();

foreach (['hostname', 'username'] as $key) {
    if (!DhexConfig::get($key)) {
        throw new Exception("Config '{$key}' belum di-set di .dhex");
    }
}
