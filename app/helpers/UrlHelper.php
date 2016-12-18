<?php
namespace App\Helpers;

class UrlHelper {
    public static function base(string $route = '') : string {
        return _BASE_HTTP_ . $route;
    }

    public static function public(string $route = '') : string {
        return _BASE_HTTP_ . 'public/' . $route;
    }

    public static function toFriendly(string $text) : string {
        if (empty($text)) {
            throw new Exception('You entered an empty string');
        }

        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        return $text;
    }

    public static function redirect(string $url = '') {
        header(sprintf("Location: %s%s", _BASE_HTTP_, $url));
    }
}