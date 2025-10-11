<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWhyChoose extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'title'       => ['type' => 'VARCHAR', 'constraint' => 255],
            'description' => ['type' => 'TEXT', 'null' => true],
            'icon'        => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'order'       => ['type' => 'INT', 'constraint' => 3, 'default' => 0],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('why_choose');
    }

    public function down()
    {
        $this->forge->dropTable('why_choose');
    }
}
