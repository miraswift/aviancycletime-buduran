<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CekSpkUpdateStatus extends Migration
{
    public function up()
    {
        //
        $this->forge->addColumn('tb_cek_spk', [
            'status_cek_spk' => [
                'type' => 'varchar',
                'constraint' => '20',
                'null' => false,
                'after' => 'mixer',
            ]
        ]);
    }

    public function down()
    {
        //
        $this->forge->dropColumn('tb_cek_spk', ['status_cek_spk']);
    }
}
