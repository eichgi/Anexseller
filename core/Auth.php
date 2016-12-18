<?php
namespace Core;

class Auth {
    public static function signIn(array $data) {
        $time = time() + ( 3600 * ServicesContainer::getConfig()['session-time'] );
        setcookie(
            ServicesContainer::getConfig()['session-name'],
            Auth::encryptCookie( serialize($data) ),
            $time,
            '/'
        );
    }

    public static function destroy() {
        if(empty($_COOKIE[ServicesContainer::getConfig()['session-name']])) return;

        unset( $_COOKIE[ServicesContainer::getConfig()['session-name']] );
        setcookie(ServicesContainer::getConfig()['session-name'], null, -1, '/');
    }

    public static function getCurrentUser() : \stdClass {
        if(empty($_COOKIE[ServicesContainer::getConfig()['session-name']])) {
            throw new \Exception("Auth cookie is not defined");
        }

        $current = self::decryptCookie($_COOKIE[ServicesContainer::getConfig()['session-name']]);
        return (object)unserialize($current);
    }

    public static function isLoggedIn() : bool {
        if(empty($_COOKIE[ServicesContainer::getConfig()['session-name']])) return false;

        $current = self::decryptCookie($_COOKIE[ServicesContainer::getConfig()['session-name']]);
        return @unserialize($current) === false ? false : true;
    }

    private static function encryptCookie(string $value) : string {
        $key = self::aud();
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        return mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $value, MCRYPT_MODE_ECB, $iv);
    }

    private static function decryptCookie(string $value) : string {
        $key = self::aud();
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        return mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $value, MCRYPT_MODE_ECB, $iv);
    }

    private static function aud() : string {
        $aud = '';

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $aud = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $aud = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $aud = $_SERVER['REMOTE_ADDR'];
        }

        $aud .= @$_SERVER['HTTP_USER_AGENT'];
        $aud .= gethostname();

        return md5(ServicesContainer::getConfig()['secret-key'] . $aud);
    }
}