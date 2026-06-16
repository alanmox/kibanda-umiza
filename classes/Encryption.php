<?php
class Encryption
{
    private static $method = 'AES-256-CBC';
    private static $key;

    public static function init()
    {
        if (empty(self::$key)) {
            self::$key = hash('sha256', ENCRYPTION_KEY, true);
        }
    }

    public static function encrypt($data)
    {
        self::init();
        $ivLength = openssl_cipher_iv_length(self::$method);
        $iv = openssl_random_pseudo_bytes($ivLength);
        $encrypted = openssl_encrypt($data, self::$method, self::$key, 0, $iv);
        return base64_encode($iv . '::' . $encrypted);
    }

    public static function decrypt($data)
    {
        self::init();
        $decoded = base64_decode($data);
        $parts = explode('::', $decoded, 2);
        if (count($parts) !== 2) {
            return '';
        }
        list($iv, $encrypted) = $parts;
        return openssl_decrypt($encrypted, self::$method, self::$key, 0, $iv);
    }
}
