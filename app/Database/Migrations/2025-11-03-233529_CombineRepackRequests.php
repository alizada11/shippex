<?php

use CodeIgniter\Database\Migration;

class CreateCombineRepackRequests extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'auto_increment' => true],
            'user_id'        => ['type' => 'INT', 'null' => false],
            'package_ids'    => ['type' => 'TEXT', 'null' => false], // JSON array
            'warehouse_id'   => ['type' => 'INT', 'null' => false],
            'total_weight'   => ['type' => 'FLOAT', 'default' => 0],
            'total_length'   => ['type' => 'FLOAT', 'default' => 0],
            'total_width'    => ['type' => 'FLOAT', 'default' => 0],
            'total_height'   => ['type' => 'FLOAT', 'default' => 0],
            'status'         => ['type' => "ENUM('pending','processing','completed')", 'default' => 'pending'],
            'admin_id'       => ['type' => 'INT', 'null' => true],
            'created_at'   => ['type' => 'DATETIME', 'null' => false],
            'processed_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('combine_repack_requests');
    }

    public function down()
    {
        $this->forge->dropTable('combine_repack_requests');
    }
}
