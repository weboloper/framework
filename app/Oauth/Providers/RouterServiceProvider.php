<?php

namespace App\Oauth\Providers;

use Phalcon\Di\FactoryDefault;
use Components\Clarity\Providers\ServiceProvider;
use Components\Clarity\Contracts\Providers\ModuleInterface;

class RouterServiceProvider extends ServiceProvider implements ModuleInterface
{
    protected $alias = 'oauth';
    protected $shared = false;

    /**
     * {@inherit}
     */
    public function register()
    {
        return $this;
    }

    /**
     * {@inherit}
     */
    public function module(FactoryDefault $di)
    {
        $di
            ->get('dispatcher')
            ->setDefaultNamespace('App\Oauth\Controllers');
    }

    /**
     * {@inherit}
     */
    public function afterModuleRun()
    {
        require_once realpath(__DIR__.'/../').'/Routes.php';
    }
}
