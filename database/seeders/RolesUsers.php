<?php

use Clarity\Support\Phinx\Seed\AbstractSeed;

class RolesUsers extends AbstractSeed
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
                'user_id' => '1',
                'role_id' =>  '1'
            ],
            [
                'user_id'        => '2',
                'role_id' =>  '2'
            ] 
        ];

        $user = $this->table('roles_users');
        $user->insert($data)
              ->save();
    }
}
