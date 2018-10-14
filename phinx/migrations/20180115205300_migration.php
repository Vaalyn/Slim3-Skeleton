<?php

use Phinx\Migration\AbstractMigration;

class Migration extends AbstractMigration {
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
    public function change() {
		$this->table('authentication_token', ['id' => false, 'primary_key' => ['authentication_token_id']])
			->addColumn('authentication_token_id', 'uuid',     [])
			->addColumn('user_id',                 'integer',  ['null' => false])
			->addColumn('token',                   'string',   ['limit' => 255, 'null' => false])
			->addColumn('browser',                 'text',     ['null' => false])
			->addColumn('created_at',              'datetime', ['null' => false])
			->addColumn('updated_at',              'datetime', ['null' => false])
			->addColumn('deleted_at',              'datetime', ['default' => null, 'null' => true])
			->save();

		$this->table('user', ['id' => false, 'primary_key' => ['user_id']])
			->addColumn('user_id',     'integer',  ['identity' => true])
			->addColumn('username',    'string',   ['limit' => 255, 'null' => false])
			->addColumn('password',    'string',   ['limit' => 255, 'null' => false])
			->addColumn('email',       'string',   ['limit' => 255, 'null' => false])
			->addColumn('is_admin',    'boolean',  ['default' => false, 'null' => false])
			->addColumn('created_at',  'datetime', ['null' => false])
			->addColumn('updated_at',  'datetime', ['null' => false])
			->addColumn('deleted_at',  'datetime', ['default' => null, 'null' => true])
			->save();
    }
}
