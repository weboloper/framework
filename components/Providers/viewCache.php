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
namespace Components\Providers;

use Phalcon\Cache\Frontend\Output as OutputFrontend;
use Phalcon\Cache\Backend\File as MemcacheBackend;
/**
 * This provider manages the cache drivers.
 */
class viewCache extends ServiceProvider
{
    /**
     * {@inheridoc}.
     */
    protected $alias = 'viewCache';

    /**
     * {@inheridoc}.
     */
    protected $shared = true;

    /**
     * Get the selected cache adapter.
     *
     * @return string
     */
    private function getSelectedAdapter()
    {
        return config()->app->cache_adapter;
    }

    /**
     * {@inheridoc}.
     */
    public function register()
    {
        // Cache data for one day by default
        $frontCache = new OutputFrontend(
            [
                "lifetime" => 86400,
            ]
        );

        // Memcached connection settings
        $cache = new MemcacheBackend(
            $frontCache,
            [
  
                'options'  => [
                    'prefix'   => '_slayer_',
                ],
                'cacheDir' => config('path.root').'/storage/views/',
            ]
        );

        return $cache;

        $adapter = config()->cache->adapters->{$this->getSelectedAdapter()};

        $backend = $adapter->backend;
        $frontend = Phalcon\Cache\Frontend\OutputFrontend::class;

        $front_cache = new $frontend([
            'lifetime' => $adapter->lifetime,
            'cacheDir' => config('path.root').'/storage/cache/',
        ]);

        return new $backend($front_cache, $adapter->options->toArray());
    }
}
