<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateShippingServicesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'provider_name' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'service_name' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'provider_logo' => [
                'type' => 'VARCHAR',
                'constraint' => '512',
                'null' => true,
            ],
            'price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'currency' => [
                'type' => 'VARCHAR',
                'constraint' => '10',
                'null' => true,
            ],
            'rating' => [
                'type' => 'FLOAT',
                'null' => true,
            ],
            'transit_text' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'transit_days' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'features' => [
                'type' => 'TEXT', // JSON stored as text
                'null' => true,
            ],
            'quote_key' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('shipping_services', true);
    }

    public function down()
    {
        $this->forge->dropTable('shipping_services', true);
    }
}
