<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDeliveredToday extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'courier_logo'   => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'retailer_logo'  => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'icon'           => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true], // e.g., "fas fa-laptop"
            'from_country'   => ['type' => 'VARCHAR', 'constraint' => 100],
            'from_flag'      => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'to_country'     => ['type' => 'VARCHAR', 'constraint' => 100],
            'to_flag'        => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'cost'           => ['type' => 'VARCHAR', 'constraint' => 50],
            'weight'         => ['type' => 'VARCHAR', 'constraint' => 50],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
            'updated_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('delivered_today');
    }

    public function down()
    {
        $this->forge->dropTable('delivered_today');
    }
}
