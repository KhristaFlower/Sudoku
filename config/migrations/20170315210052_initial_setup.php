<?php

use Phinx\Migration\AbstractMigration;

class InitialSetup extends AbstractMigration
{
    public function up()
    {
        $this->table('users')
            ->addColumn('username', 'string', ['length' => 30])
            ->addColumn('email', 'string', ['length' => 255])
            ->addColumn('password', 'string', ['length' => 60])
            ->addTimestamps()
            ->addIndex('username', ['unique' => true])
            ->addIndex('email', ['unique' => true])
            ->save();

        $this->table('puzzles')
            ->addColumn('seed', 'string', ['length' => 255])
            ->addColumn('difficulty', 'integer')
            ->addColumn('modifier', 'integer')
            ->addColumn('data', 'text')
            ->addTimestamps()
            ->save();

        $this->table('user_puzzles')
            ->addColumn('user_id', 'integer')
            ->addColumn('puzzle_id', 'integer')
            ->addColumn('completed_at', 'timestamp')
            ->addColumn('state', 'text')
            ->addTimestamps()
            ->addForeignKey('user_id', 'users')
            ->addForeignKey('puzzle_id', 'puzzles')
            ->save();
    }

    public function down()
    {
        $this->dropTable('user_puzzles');
        $this->dropTable('puzzles');
        $this->dropTable('users');
    }
}
