<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class VirtualAddresses extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'auto_increment' => true],
            'user_id'       => ['type' => 'INT', 'unsigned' => true],
            'country'       => ['type' => 'VARCHAR', 'constraint' => 100],
            'address_line'  => ['type' => 'TEXT'],
            'postal_code'   => ['type' => 'VARCHAR', 'constraint' => 20],
            'phone'         => ['type' => 'VARCHAR', 'constraint' => 20],
            'is_default'    => ['type' => 'BOOLEAN', 'default' => true],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('virtual_addresses');
    }

    public function down()
    {
        $this->forge->dropTable('virtual_addresses');
    }
}
