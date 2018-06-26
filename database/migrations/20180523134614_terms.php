<?php

use Clarity\Support\Phinx\Migration\AbstractMigration;

class Terms extends AbstractMigration
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
        $table = $this->table('terms', ['id' => 'term_id']);

        $table->addColumn('name', 'string')
            ->addColumn('slug', 'string')
            ->addColumn('taxonomy', 'string')
            ->addColumn('description', 'text', ['null' => true ])
            ->addColumn('count', 'integer', ['default' => 0 ] )
            ->addColumn('parent', 'integer' , ['default' => 0 ])
            ->create();

   
    }
}
