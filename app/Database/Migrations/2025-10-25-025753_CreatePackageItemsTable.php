<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePackageItemsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'package_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'description' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'hs_code' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'quantity' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 1,
            ],
            'value' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('package_id', 'packages', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('package_items');
    }

    public function down()
    {
        $this->forge->dropTable('package_items');
    }
}
