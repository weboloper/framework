<?php
/**
 * PhalconSlayer\Framework.
 *
 * @copyright 2015-2016 Daison Carino <daison12006013@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://docs.phalconslayer.com
 */

/**
 */
namespace Components\Clarity\Providers;

use Components\Clarity\Support\Auth\Auth as BaseAuth;

/**
 * This provider handles the general authentication.
 */
class Auth extends ServiceProvider
{
    /**
     * {@inheridoc}.
     */
    protected $alias = 'auth';

    /**
     * {@inheridoc}.
     */
    protected $shared = false;

    /**
     * {@inheridoc}.
     */
    public function register()
    {
        return new BaseAuth;
    }
}
