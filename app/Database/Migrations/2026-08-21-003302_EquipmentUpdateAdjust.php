<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EquipmentUpdateAdjust extends Migration
{
    public function up()
    {
        //
        $this->forge->addColumn('tb_equipment', [
            'is_adjusted' => [
                'type' => 'int',
                'null' => false,
                'default' => 0,
                'after' => 'actual_equipment',
            ],
        ]);
    }

    public function down()
    {
        //
        $this->forge->dropColumn('tb_equipment', ['is_adjusted']);
    }
}
