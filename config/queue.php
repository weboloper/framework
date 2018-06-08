<?php

return [

    'beanstalk' => [
        'class' => Components\Clarity\Support\Queue\Beanstalkd\Beanstalkd::class,
        'config' => [
            'host' => 'localhost',
            'port' => '11300',
        ],
    ],

];
