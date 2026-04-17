<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserIdToDuesTables extends Migration
{
    public function up()
    {
        // Add user_id to members
        $this->forge->addColumn('members', [
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'after'      => 'id',
            ],
        ]);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        // Note: For existing data, we might need a default user or handle nulls, 
        // but since this is locally developed, keeping it simple.

        // Add user_id to dues_types
        $this->forge->addColumn('dues_types', [
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'after'      => 'id',
            ],
        ]);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->forge->dropForeignKey('members', 'members_user_id_foreign');
        $this->forge->dropColumn('members', 'user_id');

        $this->forge->dropForeignKey('dues_types', 'dues_types_user_id_foreign');
        $this->forge->dropColumn('dues_types', 'user_id');
    }
}
