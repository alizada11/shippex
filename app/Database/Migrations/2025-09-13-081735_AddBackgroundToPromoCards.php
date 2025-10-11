<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBackgroundToPromoCards extends Migration
{
    public function up()
    {
        $this->forge->addColumn('promo_cards', [
            'background' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'image' // optional: place after image column
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('promo_cards', 'background');
    }
}
