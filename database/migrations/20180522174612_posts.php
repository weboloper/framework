<?php

use Clarity\Support\Phinx\Migration\AbstractMigration;

class Posts extends AbstractMigration
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
        $table = $this->table('posts');

        $table->addColumn('title', 'string')
            ->addColumn('slug', 'string', ['default' => '' ])
            ->addColumn('type', 'string' , ['default' => 'post' ])
            ->addColumn('body', 'text' , ['limit' => 'TEXT_LONG', 'null' => true] )
            ->addColumn('excerpt', 'text' ,  ['null' => true] )
            ->addColumn('user_id', 'integer')
            ->addColumn('status', 'string', ['default' => 'publish' ])
            ->addColumn('comment_status', 'char', ['limit' => 1 , 'default' => 'Y'] )
            ->addColumn('comment_count', 'integer' , ['default' => 0 ] )
            ->addColumn('parent_id', 'integer' , ['default' => 0 ] )
            ->addColumn('mime_type', 'string', ['limit' => 100, 'default' => ''] )
            # indexes
            ->addIndex(['slug'])
            ->addIndex(['user_id'])
            # created_at and updated_at
            ->addTimestamps()
            # deleted_at
            ->addSoftDeletes()
            ->create();

        $table->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE'])
            ->save();
    }
    
    /**
     * Migrate Up.
     */
    public function up()
    {
       
    }
    /**
     * Migrate Down.
     */
    public function down()
    {

        $table = $this->table('posts');
        $table->dropForeignKey('user_id');

        $this->dropTable('posts');
    }


}
