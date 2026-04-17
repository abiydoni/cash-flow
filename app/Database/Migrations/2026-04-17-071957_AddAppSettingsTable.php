<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAppSettingsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'key' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'unique'     => true,
            ],
            'value' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('app_settings');

        // Seed default values
        $db = \Config\Database::connect();
        $db->table('app_settings')->insertBatch([
            ['key' => 'wa_gateway_url', 'value' => ''],
            ['key' => 'wa_api_key', 'value' => ''],
            ['key' => 'notif_enabled', 'value' => '0'],
            ['key' => 'app_name', 'value' => 'CashFlow'],
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('app_settings');
    }
}
