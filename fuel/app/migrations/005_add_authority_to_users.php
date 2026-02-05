<?php

namespace Fuel\Migrations;

class Add_authority_to_users
{
  public function up()
  {
    \DBUtil::add_fields('users', array(
      'authority' => array('constraint' => 1, 'type' => 'int'),

    ));
  }

  public function down()
  {
    \DBUtil::drop_fields('users', array(
      'authority'

    ));
  }
}
