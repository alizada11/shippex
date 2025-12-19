<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNotificationsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'auto_increment' => true],
            'title'       => ['type' => 'VARCHAR', 'constraint' => '255'],
            'action'      => ['type' => 'TEXT'],
            'model'       => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'record_id'   => ['type' => 'INT', 'null' => true],
            'user_name'   => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'user_email'  => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'link'        => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'is_read'     => ['type' => 'TINYINT', 'default' => 0], // 0 = unread, 1 = read
            'created_at'  => ['type' => 'DATETIME'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('admin_notifications');
    }

    public function down()
    {
        $this->forge->dropTable('admin_notifications');
    }
}
