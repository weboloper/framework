<?php

use Clarity\Support\Phinx\Migration\AbstractMigration;


class PostMeta extends AbstractMigration
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
        $table = $this->table('post_meta' , ['id' => 'meta_id']);

        $table->addColumn('post_id', 'integer')
            ->addColumn('meta_key', 'string')
            ->addColumn('meta_value', 'text', ['limit' => 'TEXT_LONG'])
            ->create();
   
    }
}
