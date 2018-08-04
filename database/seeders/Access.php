<?php

use Clarity\Support\Phinx\Seed\AbstractSeed;

class Access extends AbstractSeed
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
                'object'    =>  'AdminArea',
                'action'    =>  'access',
                'role_id'   =>  1,
                'value'     =>  'allow'
            ],
            [
                'object'    =>  'ModArea',
                'action'    =>  'access',
                'role_id'   =>  1,
                'value'     =>  'allow'
            ],
             [
                'object'    =>  'ModArea',
                'action'    =>  'access',
                'role_id'   =>  2,
                'value'     =>  'allow'
            ]
        ];

        $user = $this->table('access');
        $user->insert($data)
              ->save();
    }
}
