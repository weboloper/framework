<?php

/**
 * PhalconSlayer\Framework.
 *
 * @copyright 2015-2016 Daison Carino <daison12006013@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://docs.phalconslayer.com
 */

namespace Components\Providers;

use Exception;
use Monolog\Logger;
use Phalcon\Events\Manager as EventsManager;
use Monolog\Handler\StreamHandler;

/**
 * This provider handles the relational database adapters, that lives inside the
 * configuration file.
 *
 * @see \Phalcon\Db and its related classes provide a simple SQL database interface for Phalcon Framework.
 * The @see \Phalcon\Db is the basic class you use to connect your PHP application to an RDBMS.
 * There is a different adapter class for each brand of RDBMS.
 * This component is intended to lower level database operations.
 * If you want to interact with databases using higher level of abstraction use Phalcon\Mvc\Model.
 * @see \Phalcon\Db is an abstract class.
 * You only can use it with a database adapter like @see \Phalcon\Db\Adapter\Pdo
 */
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;

class DB extends ServiceProvider
{
    /**
     * {@inheridoc}.
     */
    protected $alias = 'db';

    /**
     * {@inheridoc}.
     */
    protected $shared = true;

     public function register()  {
        return new DbAdapter(
        [
            "host" => env('DB_HOST', 'localhost'),
            "port" => env('DB_PORT', 3306), 
            "username" => env('DB_USERNAME'),
            "password" => env('DB_PASSWORD'),
            "dbname" => env('DB_DATABASE', 'slayer'),
            'charset'  => env('DB_CHARSET', 'utf8'),
            "options"  => [
     
                    \PDO::ATTR_AUTOCOMMIT => 1
                ]
        ]
    );

     }
}