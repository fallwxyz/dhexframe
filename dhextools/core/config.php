<?php 
final class DhexConfig
{
    private static array $config = [];
    private static bool $loaded = false;

    public static function load(string $path = '.dhex'): void
    {
        if (self::$loaded) {
            return;
        }

        $default = <<<DHEX
# DO NOT EDIT system MANUALLY AFTER INSTALL
app_url=dhexframe
system=route

hostname=localhost
username=root
password=
database=
DHEX;

        if (!file_exists($path)) {
            file_put_contents($path, $default);
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            if (str_starts_with(trim($line), '#')) continue;

            [$key, $value] = array_pad(explode('=', $line, 2), 2, '');
            self::$config[trim($key)] = trim($value);
        }

        self::$loaded = true;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return self::$config[$key] ?? $default;
    }

    public static function all(): array
    {
        return self::$config;
    }
}

class Database
{
    protected mysqli $conn;

    public function __construct()
    {
        DhexConfig::load(__DIR__ . '/../../.dhex');

        $this->conn = new mysqli(
            DhexConfig::get('hostname'),
            DhexConfig::get('username'),
            DhexConfig::get('password'),
            DhexConfig::get('database')
        );

        if ($this->conn->connect_error) {
            throw new Exception('DB Connection failed: ' . $this->conn->connect_error);
        }

        $this->conn->set_charset('utf8mb4');
    }

    public function getConnection(): mysqli
    {
        return $this->conn;
    }
}



final class SingletonDatabase
{
    private static ?mysqli $instance = null;

    private function __construct() {}
    private function __clone() {}
    public function __wakeup() {}

    public static function getInstance(): mysqli
    {
        if (self::$instance === null) {

            DhexConfig::load(__DIR__ . '/../../.dhex');

            self::$instance = new mysqli(
                DhexConfig::get('hostname'),
                DhexConfig::get('username'),
                DhexConfig::get('password'),
                DhexConfig::get('database')
            );

            if (self::$instance->connect_error) {
                throw new Exception(
                    'DB Connection failed: ' . self::$instance->connect_error
                );
            }

            self::$instance->set_charset('utf8mb4');
        }

        return self::$instance;
    }
}