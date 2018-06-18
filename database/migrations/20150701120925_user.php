<?php

use Clarity\Support\Phinx\Migration\AbstractMigration;

class User extends AbstractMigration
{
    public function up()
    {
        $users = $this->table('users');
        $users
            # columns
        
            ->addColumn('email', 'string')
            ->addColumn('password', 'string')
            ->addColumn('name', 'string', ['null' => true])
            ->addColumn('token', 'string')
            ->addColumn('activated', 'boolean', ['default' => false])
            ->addColumn('forgetpass', 'boolean', ['default' => false])
            ->addColumn('status', 'integer' , ['limit' => 1 , 'default' => 1 ] )

            # indexes
            ->addIndex(['email'], ['unique' => true])
            ->addIndex(['name'])
            ->addIndex(['password'])
            ->addIndex(['token'])

            # created_at and updated_at
            ->addTimestamps()

            ->addColumn('lastPasswdReset', 'integer', ['null' => true ])

            # deleted_at
            ->addSoftDeletes()

            # build the entire table
            ->create();
    }

    public function down()
    {
        $this->dropTable('users');
    }
}
