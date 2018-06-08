<?php

use Clarity\Support\Phinx\Migration\AbstractMigration;

class Audit extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('audit');

        $table->addColumn('user_id', 'integer')
            ->addColumn('ipaddress', 'integer', ['limit' => 10 ])
            ->addColumn('type', 'char', ['limit' => 1 ])
            ->addColumn('created_at', 'timestamp', array(
                'default' => 'CURRENT_TIMESTAMP',
                'update' => ''
            ))
            ->addColumn('model_name', 'string')
            ->create();
    }
}
