<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateHowItWorksSteps extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'section_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'title'       => ['type' => 'VARCHAR', 'constraint' => 255],
            'description' => ['type' => 'TEXT', 'null' => true],
            'image'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'bg_image'    => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'order'       => ['type' => 'INT', 'constraint' => 3, 'default' => 0],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('section_id', 'how_it_works_sections', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('how_it_works_steps');
    }

    public function down()
    {
        $this->forge->dropTable('how_it_works_steps');
    }
}
