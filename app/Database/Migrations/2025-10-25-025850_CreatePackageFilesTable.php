<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePackageFilesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'package_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'file_type' => ['type' => 'ENUM', 'constraint' => ['invoice', 'photo', 'label', 'other'], 'default' => 'other'],
            'file_path' => ['type' => 'VARCHAR', 'constraint' => 255],
            'uploaded_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('package_id', 'packages', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('package_files');
    }

    public function down()
    {
        $this->forge->dropTable('package_files');
    }
}
