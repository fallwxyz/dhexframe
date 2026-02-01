<?php
declare(strict_types=1);

define('DHEX_CORE_PATH', __DIR__);
define('DHEX_ROOT_PATH', dirname(__DIR__, 2));

require_once DHEX_CORE_PATH . '/config.php';
require_once DHEX_CORE_PATH . '/system.php';
require_once DHEX_CORE_PATH . '/function.php';

DhexConfig::load(DHEX_ROOT_PATH . '/.dhex');
installRouteSystem();

foreach ([] as $key) {
    if (!DhexConfig::get($key)) {
        throw new Exception("The '$key' configuration has not been set in .dhex");
    }
}