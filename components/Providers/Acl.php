<?php

/**
 * PhalconSlayer\Framework.
 * Clarity\Providers\Auth copied
 */

namespace Components\Providers;

use Components\Library\Acl\Manager as AclManager;

/**
 * This provider handles the general authentication.
 */
class Acl extends ServiceProvider
{
    /**
     * {@inheridoc}.
     */
    protected $alias = 'acl';

    protected $config;

    /**
     * {@inheridoc}.
     */
    protected $shared = true;


    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function __construct()
    {
        /** @noinspection PhpIncludeInspection */
        $config = require config_path('acl.php');

        $driver = $config['default'];

        $this->config['config']   = $config['drivers'][$driver];
        $this->config['lifetime'] = $config['lifetime'];
        $this->config['cacheKey'] = $config['cacheKey'];
    }

    /**
     * {@inheridoc}.
     */
    public function register()
    {   
        $config = $this->config;
         
        $config = [
                    'adapter'  => '\Phalcon\Acl\Adapter\\' . $config['config']['adapter'],
                    'lifetime' => $config['lifetime'],
                    'cacheKey' => $config['cacheKey'],
                ];

        return new AclManager($config);
    }
}
