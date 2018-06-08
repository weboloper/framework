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
namespace Components\Clarity\Support\Phalcon\Mvc;

use Phalcon\Config;
use League\Tactician\CommandBus;
use Components\Clarity\Support\Phalcon\Http\Middleware;
use Phalcon\Mvc\Controller as BaseController;

class Controller extends BaseController
{   
    public function onConstruct()
    {
        $this->view->setVars([
            'auth'          => auth()->user() ,
            'app'           => $this->config->app->app,
            'action'        => di()->get('router')->getActionName(),
            'controller'    => di()->get('router')->getControllerName(),
            // 'googleAnalytic'=> $this->config->googleAnalytic
        ]);
    }

    public function middleware($alias, $options = [])
    {
        $middlewares = [];

        # get previously assigned aliases
        if (di()->has('middleware_aliases')) {
            $middlewares = di()->get('middleware_aliases')->toArray();
        }

        $append_alias = true;
        $action_name = dispatcher()->getActionName();

        if (isset($options['only'])) {
            if (in_array($action_name, $options['only']) === false) {
                $append_alias = false;
            }
        }

        if (isset($options['except'])) {
            if (in_array($action_name, $options['except'])) {
                $append_alias = false;
            }
        }

        if ($append_alias === true) {
            $middlewares[] = $alias;
        }

        di()->set('middleware_aliases', function () use ($middlewares) {
            return new Config($middlewares);
        });
    }

    public function beforeExecuteRoute()
    {
        # call the initialize to work with the middleware()
        if (method_exists($this, 'initialize')) {
            $this->initialize();
        }

        $this->middlewareHandler();
    }

    private function middlewareHandler()
    {
        if (di()->has('middleware_aliases') === false) {
            return;
        }

        # get all the middlewares in the config/app.php
        $middleware = new Middleware(config()->app->middlewares);

        $instances = [];
        $aliases = di()->get('middleware_aliases')->toArray();

        foreach ($aliases as $alias) {
            $class = $middleware->get($alias);
            $instances[] = new $class;
        }

        # register all the middlewares
        $command_bus = new CommandBus($instances);
        $command_bus->handle($this->request);
    }
}
