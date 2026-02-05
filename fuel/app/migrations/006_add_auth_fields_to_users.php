<?php

namespace Fuel\Migrations;

class Add_auth_fields_to_users
{
  public function up()
  {
    \DBUtil::add_fields('users', array(
      'last_login' => array('constraint' => 11, 'type' => 'int'),
      'login_hash' => array('constraint' => 255, 'type' => 'varchar'),
      'profile_fields' => array('type' => 'text'),

    ));
  }

  public function down()
  {
    \DBUtil::drop_fields('users', array(
      'last_login',
      'login_hash',
      'profile_fields'

    ));
  }
}
