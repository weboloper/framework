<?php

use Clarity\Support\Phinx\Seed\AbstractSeed;

class Resources extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $data = [
            [
                'name'        => 'AdminArea',
                'description' =>  'Access admin area'
            ]
        ];

        // $user = $this->table('resources');
        // $user->insert($data)
        //       ->save();
    }
}
