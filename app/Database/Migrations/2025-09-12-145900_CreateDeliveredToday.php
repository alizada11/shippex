<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePromoCardsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true,
                'unsigned' => true
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'description' => [
                'type' => 'TEXT'
            ],
            'button_text' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
            ],
            'button_url' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'image' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
        ]);


        $this->forge->addKey('id', true);
        $this->forge->createTable('promo_cards');
    }

    public function down()
    {
        $this->forge->dropTable('promo_cards');
    }
}
