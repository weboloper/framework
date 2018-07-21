<?php

use Clarity\Support\Phinx\Seed\AbstractSeed;

class Users extends AbstractSeed
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
                'email'       => 'admin@slayer.com',
                'password'    => security()->hash("123123123"),
                'username'    => 'admin',
                'name'        => 'mr. admin',
                'token'       =>  bin2hex(random_bytes(100)),
                'activated'   => (int) true,
                'created_at'  => date('Y-m-d H:i:s'),
            ], 
            [
                'email'       => 'mod@slayer.com',
                'password'    => security()->hash("123123123"),
                'username'    => 'mod',
                'name'        => 'mr. mod',
                'token'       =>  bin2hex(random_bytes(100)),
                'activated'   => (int) true,
                'created_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'email'       => 'user@slayer.com',
                'password'    => security()->hash("123123123"),
                'username'    => 'user',
                'name'        => 'mr. user',
                'token'       =>  bin2hex(random_bytes(100)),
                'activated'   => (int) true,
                'created_at'  => date('Y-m-d H:i:s'),
            ] 
        ];

        $user = $this->table('users');
        $user->insert($data)
              ->save();
    }
}
