<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPeriodAndStatusToDuesTypes extends Migration
{
    public function up()
    {
        $this->forge->addColumn('dues_types', [
            'period' => [
                'type'       => 'ENUM',
                'constraint' => ['monthly', 'yearly', 'once'],
                'default'    => 'monthly',
                'after'      => 'amount',
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'after'      => 'period',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('dues_types', ['period', 'is_active']);
    }
}
