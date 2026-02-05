<?php

namespace Fuel\Migrations;

class Rename_authority_to_group
{
  public function up()
  {
    // 'authority' を 'group' に変更
    \DBUtil::modify_fields('users', array(
      'authority' => array('name' => 'group', 'type' => 'int', 'constraint' => 11),
    ));
  }

  public function down()
  {
    // 元に戻す処理
    \DBUtil::modify_fields('users', array(
      'group' => array('name' => 'authority', 'type' => 'int', 'constraint' => 11),
    ));
  }
}
