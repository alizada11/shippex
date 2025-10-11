<?php

namespace Modules\Blog\Database\Migrations;


use CodeIgniter\Database\Migration;


class CreateBlogPosts extends Migration
{
 public function up()
 {
  $this->forge->addField([
   'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
   'title' => ['type' => 'VARCHAR', 'constraint' => 255],
   'thumbnail' => ['type' => 'VARCHAR', 'constraint' => 255],
   'slug' => ['type' => 'VARCHAR', 'constraint' => 255, 'unique' => true],
   'excerpt' => ['type' => 'TEXT', 'null' => true],
   'content' => ['type' => 'LONGTEXT'],
   'status' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'draft'], // draft|published
   'author_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
   'published_at' => ['type' => 'DATETIME', 'null' => true],
   'created_at' => ['type' => 'DATETIME', 'null' => true],
   'updated_at' => ['type' => 'DATETIME', 'null' => true],
   'deleted_at' => ['type' => 'DATETIME', 'null' => true],
  ]);
  $this->forge->addKey('id', true);
  $this->forge->createTable('posts');
 }


 public function down()
 {
  $this->forge->dropTable('posts', true);
 }
}
