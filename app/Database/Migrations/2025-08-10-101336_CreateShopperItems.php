<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateShopperItems extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'request_id'    => ['type' => 'INT', 'unsigned' => true],
            'name'          => ['type' => 'VARCHAR', 'constraint' => 255],
            'url'           => ['type' => 'VARCHAR', 'constraint' => 1024, 'null' => true],
            'size'          => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'color'         => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'instructions'  => ['type' => 'TEXT', 'null' => true],
            'quantity'      => ['type' => 'INT', 'constraint' => 11, 'default' => 1],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('request_id', 'shopper_requests', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('shopper_items');
    }

    public function down()
    {
        $this->forge->dropTable('shopper_items');
    }
}
