<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateShopperRequests extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id'           => ['type' => 'INT', 'null' => true],
            'status'            => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'requested'],
            'use_another_retailer' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'delivery_description' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'delivery_notes'    => ['type' => 'TEXT', 'null' => true],
            'is_saved'          => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0], // saved for later flag
            'created_at'        => ['type' => 'DATETIME', 'null' => true],
            'updated_at'        => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('shopper_requests');
    }

    public function down()
    {
        $this->forge->dropTable('shopper_requests');
    }
}
