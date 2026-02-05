<?php

namespace Fuel\Migrations;

class Create_rankings
{
  public function up()
  {
    \DBUtil::create_table('rankings', array(
      'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
      'user_id' => array('constraint' => 11, 'type' => 'int'),
      'name' => array('constraint' => 50, 'type' => 'varchar'),
      'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
      'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

    ), array('id'));
  }

  public function down()
  {
    \DBUtil::drop_table('rankings');
  }
}
