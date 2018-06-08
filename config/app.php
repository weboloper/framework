<?php

return [

    'app' => [
        'name' => env('APP_NAME' , 'slayer') ,
        'url' => env('APP_URL' , 'http://slayer.dev') 
    ],
    /*
    +----------------------------------------------------------------+
    |\ Application Debugging                                        /|
    +----------------------------------------------------------------+
    |
    | To easily track your bugs, by defining it to true, you
    | can get a full error response
    |
    */

    'debug' => env('APP_DEBUG', false),

    /*
    +----------------------------------------------------------------+
    |\ Language Settings                                            /|
    +----------------------------------------------------------------+
    |
    | The place where you should supposed to assign which
    | language folder will be used
    |
    */

    'lang' => 'en',

    /*
    +----------------------------------------------------------------+
    |\ Default Timezone                                             /|
    +----------------------------------------------------------------+
    |
    | The system time to be, useful for CRUD records that will be
    | based on the timezone for created, updated, and deleted
    | timestamps
    |
    */

    'timezone' => 'UTC',

    /*
    +----------------------------------------------------------------+
    |\ SSL Support                                                  /|
    +----------------------------------------------------------------+
    |
    | Mark true if your domain supports ssl, and to enforce
    | re-write for every module's url into https
    |
    */

    'ssl' => [
        'main' => false,
    ],

    /*
    +----------------------------------------------------------------+
    |\ Base URI                                                     /|
    +----------------------------------------------------------------+
    |
    | It is not an accurate way to use server catched base uri,
    | this options helps our command line interface (cli) to use
    | the defined module's base uri.
    |
    | This is also helpful when using the request()->module(...), it
    | builds the guzzle/guzzle package
    |
    */

    'base_uri' => [
        'main' => 'slayer.app',
    ],

    /*
    +----------------------------------------------------------------+
    |\ Session Name                                                 /|
    +----------------------------------------------------------------+
    |
    | It will be the name of your session located in the browser's
    | console, rename it to change your session name
    |
    | Note: Provide an alphanumeric character without any special
    | character
    |
    */

    'session' => 'slayer',

    /*
    +----------------------------------------------------------------+
    |\ Database Adapter                                             /|
    +----------------------------------------------------------------+
    |
    | Define your database adapter, base it on database.php config
    | file
    |
    */

    'db_adapter' => env('DB_ADAPTER'),

    /*
    +----------------------------------------------------------------+
    |\ NoSQL Adapter                                                /|
    +----------------------------------------------------------------+
    |
    | Define your nosql adapter, base it on database.php config
    | file, the default adapter is "mongo1"
    |
    */

    'nosql_adapter' => 'mongo1',

    /*
    +----------------------------------------------------------------+
    |\ Cache Adapter                                                /|
    +----------------------------------------------------------------+
    |
    | Define your cache adapter, base it on cache.php config file,
    | the default adapter is "file"
    |
    */

    'cache_adapter' => env('CACHE_ADAPTER', 'file'),

    /*
    +----------------------------------------------------------------+
    |\ Queue Adapter                                                /|
    +----------------------------------------------------------------+
    |
    | Define your queue adapter, base it on queue.php config file,
    | the default adapter is "beanstalk"
    |
    */

    'queue_adapter' => env('QUEUE_ADAPTER', 'beanstalk'),

    /*
    +----------------------------------------------------------------+
    |\ Session Adapter                                              /|
    +----------------------------------------------------------------+
    |
    | Define your session adapter, base it on session.php config file,
    | the default adapter is "file"
    |
    */

    'session_adapter' => env('SESSION_ADAPTER', 'file'),

    /*
    +----------------------------------------------------------------+
    | Flysystem                                                     /|
    +----------------------------------------------------------------+
    |
    | A file system handler that manages your files to an organizable
    | or manageable storage area such as (AWS S3)
    |
    */

    'flysystem' => 'local',

    /*
    +----------------------------------------------------------------+
    |\ Error Handler                                                /|
    +----------------------------------------------------------------+
    |
    | An error handler that dispatches all errors for specific
    | instance
    |
    */

    'error_handler' => Components\Exceptions\Handler::class,

    /*
    +----------------------------------------------------------------+
    |\ Mailer Settings                                              /|
    +----------------------------------------------------------------+
    |
    | To be able to send an email, provide your email
    | settings, such as adapters and the like
    |
    */

    'mail_adapter' => env('MAIL_ADAPTER', 'swift'),

    /*
    +----------------------------------------------------------------+
    |\ Logging Time                                                 /|
    +----------------------------------------------------------------+
    |
    | Try to choose "monthly", "daily", "hourly" to log separate files
    | else provide a boolean false to disable
    |
    */

    'logging_time' => false,

    /*
    +----------------------------------------------------------------+
    |\ Authentication Settings                                      /|
    +----------------------------------------------------------------+
    |
    | This auth settings will help you to easily authenticate your
    | users from your form inputs
    |
    */

    'auth' => [
        'model'          => Components\Model\Users::class,
        'password_field' => 'password',
        'redirect_key'   => 'ref',
    ],

    /*
    +----------------------------------------------------------------+
    |\ Application Encryption                                       /|
    +----------------------------------------------------------------+
    |
    | This handles the Phalcon\Crypt, in which you could encrypt
    | or decrypt data.
    |
    */

    'encryption' => [
        'key'    => env('APP_KEY'),
        'cipher' => 'aes-256-cbc',

        # you don't need to change this when you're
        # under 3.0.x Phalcon which uses openssl
        # this only applies under 2.0.x Phalcon
        // 'cipher' => 'rijndael-256',
        'mode'   => 'cbc',
    ],

    /*
    +----------------------------------------------------------------+
    |\ Service Providers                                            /|
    +----------------------------------------------------------------+
    |
    | A service will be stored and available to call using the
    | di()->get(<alias>) function
    |
    */

    'services' => [
        # This must be on top to log things to
        # all supporting providers
        Components\Clarity\Providers\Log::class,
        Components\Providers\Acl::class,
        Components\Clarity\Providers\Aliaser::class,
        Components\Providers\Application::class,
        Components\Clarity\Providers\Auth::class,
        Components\Clarity\Providers\Cache::class,
        Components\Clarity\Providers\CollectionManager::class,
        Components\Clarity\Providers\Console::class,
        Components\Clarity\Providers\Crypt::class,
        // Components\Clarity\Providers\DB::class,
        Components\Providers\DB::class,
        Components\Providers\Dispatcher::class,
        Components\Clarity\Providers\ErrorHandler::class,
        Components\Clarity\Providers\Filter::class,
        Components\Clarity\Providers\Flash::class,
        Components\Clarity\Providers\Flysystem::class,
        Components\Clarity\Lang\LangServiceProvider::class,
        Components\Clarity\Mail\MailServiceProvider::class,
        Components\Clarity\Providers\MetadataAdapter::class,
        Components\Clarity\Providers\Module::class,
        Components\Clarity\Providers\Mongo::class,
        Components\Clarity\Providers\Queue::class,
        Components\Clarity\Providers\Redirect::class,
        Components\Clarity\Providers\Request::class,
        Components\Clarity\Providers\Response::class,
        Components\Clarity\Providers\Router::class,
        Components\Clarity\Providers\RouterAnnotations::class,
        Components\Clarity\Providers\Session::class,
        Components\Clarity\Providers\URL::class,
        Components\Clarity\View\ViewServiceProvider::class,

        # register your providers below.
        App\Oauth\Providers\RouterServiceProvider::class,
        App\Blog\Providers\RouterServiceProvider::class,

        # register your sandbox providers below.
        // Acme\Acme\AcmeServiceProvider::class,
        Components\Providers\ModelsManager::class,
        
    ],

    /*
    +----------------------------------------------------------------+
    |\ Class Aliases                                                /|
    +----------------------------------------------------------------+
    |
    | Instead of using a full class namespace, you could defined
    | below an alias of your class
    |
    */

    'aliases'  => [
        'Auth'        => Components\Clarity\Facades\Auth::class,
        'Cache'       => Components\Clarity\Facades\Cache::class,
        'CLI'         => Components\Clarity\Console\CLI::class,
        'Config'      => Components\Clarity\Facades\Config::class,
        'DB'          => Components\Clarity\Facades\DB::class,
        'File'        => Components\Clarity\Facades\Flysystem::class,
        'FileManager' => Components\Clarity\Facades\FlysystemManager::class,
        'Filter'      => Components\Clarity\Facades\Filter::class,
        'Flash'       => Components\Clarity\Facades\Flash::class,
        'Lang'        => Components\Clarity\Lang\LangFacade::class,
        'Log'         => Components\Clarity\Facades\Log::class,
        'Mail'        => Components\Clarity\Mail\MailFacade::class,
        'Queue'       => Components\Clarity\Facades\Queue::class,
        'Redirect'    => Components\Clarity\Facades\Redirect::class,
        'Request'     => Components\Clarity\Facades\Request::class,
        'Response'    => Components\Clarity\Facades\Response::class,
        'Route'       => Components\Clarity\Facades\Route::class,
        'Security'    => Components\Clarity\Facades\Security::class,
        'Session'     => Components\Clarity\Facades\Session::class,
        'Tag'         => Components\Clarity\Facades\Tag::class,
        'URL'         => Components\Clarity\Facades\URL::class,
        'View'        => Components\Clarity\Facades\View::class,

        # register your sandbox providers below.
        'Acl'         => Components\Library\Acl\Manager::class,
    ],

    /*
    +----------------------------------------------------------------+
    |\ Middlewares                                                  /|
    +----------------------------------------------------------------+
    |
    | Callable key classes on before route is called, this helps
    | to filter every request on a nicer way
    |
    */

    'middlewares' => [
        'auth' => Components\Middleware\Auth::class,
        'csrf' => Components\Middleware\CSRF::class,
        'permission'  => Components\Middleware\Permission::class,
    ],

    /**
     * Your client ID and client secret keys come from
     *
     * @link https://developers.facebook.com/
     */

    'facebook' => [
        'clientId'     => env('FACEBOOK_CLIENT_ID'),
        'clientSecret' => env('FACEBOOK_SECRET'),
        'redirectUri'  => env('FACEBOOK_REDIRECT_URI')
    ],
    

]; # end of return
