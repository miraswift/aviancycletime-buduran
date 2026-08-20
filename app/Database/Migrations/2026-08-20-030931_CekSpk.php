<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CekSpk extends Migration
{
    public function up()
    {
        //
        $this->forge->addField([
            'id_cek_spk' => [
                'type' => 'int',
                'auto_increment' => true,
            ],
            'no_spk' => [
                'type' => 'text',
                'null' => false,
            ],
            'mixer' => [
                'type' => 'varchar',
                'constraint' => 5,
                'null' => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addPrimaryKey('id_cek_spk');
        $this->forge->createTable('tb_cek_spk');
    }

    public function down()
    {
        //
        $this->forge->dropTable('tb_cek_spk');
    }
}
