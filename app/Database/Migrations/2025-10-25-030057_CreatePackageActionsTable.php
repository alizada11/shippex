<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePackageActionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'package_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'action' => ['type' => 'VARCHAR', 'constraint' => 100],
            'notes' => ['type' => 'TEXT', 'null' => true],
            'performed_by' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => false],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('package_id', 'packages', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('package_actions');
    }

    public function down()
    {
        $this->forge->dropTable('package_actions');
    }
}
