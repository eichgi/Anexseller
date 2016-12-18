<?php
namespace Core;

use Illuminate\Database\Capsule\Manager as Capsule;

class DbContext {
    public static function initialize() {
        try {
            $config = ServicesContainer::getConfig();
            $capsule = new Capsule;
            $capsule->addConnection($config['database']);
            $capsule->setAsGlobal();
            $capsule->bootEloquent();
        } catch(\Exception $e) {
            Log::error(
                DbContext::class,
                $e->getMessage()
            );
        }
    }
}