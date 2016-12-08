<?php
use Migrations\AbstractMigration;

class CreateBlogs extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('blogs');
        $table->addColumn('title', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('mail_address', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('password', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('last_login', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->addIndex([
            'title',
        ], [
            'name' => 'UNIQUE_TITLE',
            'unique' => true,
        ]);
        $table->addIndex([
            'mail_address',
        ], [
            'name' => 'UNIQUE_MAIL_ADDRESS',
            'unique' => true,
        ]);
        $table->create();
    }
}
