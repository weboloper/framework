<?php

return [

    /*
    +----------------------------------------------------------------+
    |\ PhalconSlayer Registered Commands                            /|
    +----------------------------------------------------------------+
    |
    | Register your slayer commands to lists up upon running
    | `php brood` to your terminal
    |
    */

    Components\Clarity\Console\Queue\Listen::class,
    Components\Clarity\Console\App\ControllerCommand::class,
    Components\Clarity\Console\App\ModuleCommand::class,
    Components\Clarity\Console\App\RouteCommand::class,
    Components\Clarity\Console\Clear\AllCommand::class,
    Components\Clarity\Console\Clear\CacheCommand::class,
    Components\Clarity\Console\Clear\CompiledCommand::class,
    Components\Clarity\Console\Clear\LogsCommand::class,
    Components\Clarity\Console\Clear\SessionCommand::class,
    Components\Clarity\Console\Clear\ViewsCommand::class,
    Components\Clarity\Console\DB\Create::class,
    Components\Clarity\Console\DB\Migrate::class,
    Components\Clarity\Console\DB\Rollback::class,
    Components\Clarity\Console\DB\SeedCreate::class,
    Components\Clarity\Console\DB\SeedFactory::class,
    Components\Clarity\Console\DB\SeedRun::class,
    Components\Clarity\Console\DB\Status::class,
    Components\Clarity\Console\Mail\InlinerCommand::class,
    Components\Clarity\Console\Make\CollectionCommand::class,
    Components\Clarity\Console\Make\ConsoleCommand::class,
    Components\Clarity\Console\Make\ModelCommand::class,
    Components\Clarity\Console\Script\RunCommand::class,
    Components\Clarity\Console\Server\OptimizeCommand::class,
    Components\Clarity\Console\Server\ServeCommand::class,
    Components\Clarity\Console\Server\EnvCommand::class,
    Components\Clarity\Console\Server\ClutchCommand::class,
    Components\Clarity\Console\Server\RoutesCommand::class,
    Components\Clarity\Console\Vendor\NewCommand::class,
    Components\Clarity\Console\Vendor\PublishCommand::class,

    # add your console commands below ...

]; # end of return
