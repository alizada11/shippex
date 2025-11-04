<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWarehouseRequestsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                  => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'user_id'             => ['type' => 'INT', 'unsigned' => true],
            'warehouse_id'        => ['type' => 'INT', 'unsigned' => true],
            'status'              => ['type' => 'ENUM', 'constraint' => ['pending', 'accepted', 'rejected'], 'default' => 'pending'],
            'is_default'          => ['type' => 'TINYINT', 'default' => 0],
            'rejectation_reason'  => ['type' => 'TEXT', 'null' => true],
            'created_at'          => ['type' => 'DATETIME', 'null' => true],
            'updated_at'          => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('warehouse_id', 'warehouses', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('warehouse_requests');
    }

    public function down()
    {
        $this->forge->dropTable('warehouse_requests');
    }
}
