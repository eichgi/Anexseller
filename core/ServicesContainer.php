<?php
namespace Core;

class ServicesContainer {
    private static $config;
    private static $dbContext = false;

    public static function setConfig(array $value) {
        self::$config = $value;
    }

    /* Configuration */
    public static function getConfig() : array{
        return self::$config;
    }

    public static function initializeDbContext() {
        if(!(self::$dbContext)) {
            DbContext::initialize();
            self::$dbContext = true;
        }
    }
}