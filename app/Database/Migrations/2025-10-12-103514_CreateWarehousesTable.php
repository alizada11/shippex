<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWarehousesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'country_code'      => ['type' => 'VARCHAR', 'constraint' => 10, 'unique' => true],
            'country_name'      => ['type' => 'VARCHAR', 'constraint' => 100],

            // Hero section
            'banner_image'      => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'hero_bg'      => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'hero_title'        => ['type' => 'VARCHAR', 'constraint' => 255],
            'hero_description_1' => ['type' => 'TEXT', 'null' => true],
            'hero_description_2' => ['type' => 'TEXT', 'null' => true],
            'hero_cta_text'     => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => 'START SAVING'],
            'hero_cta_link'     => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],

            // Brands section
            'brands_title'      => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'brands_text'       => ['type' => 'TEXT', 'null' => true],
            'brands_image'      => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],

            // Shipping & Payment
            'shipping_text'     => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'payment_text'      => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],

            // Bottom Section
            'bottom_title'      => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'bottom_paragraph_1' => ['type' => 'TEXT', 'null' => true],
            'bottom_paragraph_2' => ['type' => 'TEXT', 'null' => true],
            'bottom_cta_text'   => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'bottom_cta_link'   => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],

            'is_active'         => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'        => ['type' => 'DATETIME', 'null' => true],
            'updated_at'        => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('warehouses');
    }

    public function down()
    {
        $this->forge->dropTable('warehouses');
    }
}
