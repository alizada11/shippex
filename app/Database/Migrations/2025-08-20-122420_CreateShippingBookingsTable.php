<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateShippingBookingsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'               => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],

            // Origin
            'origin_line_1'    => ['type' => 'VARCHAR', 'constraint' => 255],
            'origin_city'      => ['type' => 'VARCHAR', 'constraint' => 100],
            'origin_state'     => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'origin_postal'    => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'origin_country'   => ['type' => 'VARCHAR', 'constraint' => 5],

            // Destination
            'dest_line_1'      => ['type' => 'VARCHAR', 'constraint' => 255],
            'dest_city'        => ['type' => 'VARCHAR', 'constraint' => 100],
            'dest_state'       => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'dest_postal'      => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'dest_country'     => ['type' => 'VARCHAR', 'constraint' => 5],

            // Parcel info
            'weight'           => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'length'           => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'width'            => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'height'           => ['type' => 'DECIMAL', 'constraint' => '10,2'],

            // Service from Easyship
            'courier_name'     => ['type' => 'VARCHAR', 'constraint' => 255],
            'service_name'     => ['type' => 'VARCHAR', 'constraint' => 255],
            'delivery_time'    => ['type' => 'VARCHAR', 'constraint' => 100],
            'currency'         => ['type' => 'VARCHAR', 'constraint' => 10],
            'total_charge'     => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'description'      => ['type' => 'TEXT', 'null' => true],

            'created_at'       => ['type' => 'DATETIME', 'null' => true],
            'updated_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('shipping_bookings');
    }

    public function down()
    {
        $this->forge->dropTable('shipping_bookings');
    }
}
