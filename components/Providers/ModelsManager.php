<?php

/**
 * PhalconSlayer\Framework.
 * Clarity\Providers\Auth copied
 */

namespace Components\Providers;

use Phalcon\Mvc\Model\Manager;

/**
 * This provider handles the general authentication.
 */
class ModelsManager extends ServiceProvider
{
    /**
     * {@inheridoc}.
     */
    protected $alias = 'modelsmanager';

    /**
     * {@inheridoc}.
     */
    protected $shared = true;

    /**
     * {@inheridoc}.
     */
    public function register()
    {
        return new Manager;
    }
}
