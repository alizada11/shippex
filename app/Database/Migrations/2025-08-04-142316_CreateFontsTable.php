<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFontsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'auto_increment' => true],
            'font_name'  => ['type' => 'VARCHAR', 'constraint' => 100],
            'is_default' => ['type' => 'BOOLEAN', 'default' => false],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('fonts');
    }


    public function down()
    {
        //
    }
}
