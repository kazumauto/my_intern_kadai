<?php

namespace Fuel\Migrations;

class Rename_name_to_username
{
    public function up()
    {
        // 'name' というカラムを 'username' に変更する（ここをコピペ！）
        \DBUtil::modify_fields('users', array(
            'name' => array('name' => 'username', 'type' => 'varchar', 'constraint' => 255),
        ));
    }

    public function down()
    {
        // 元に戻す処理
        \DBUtil::modify_fields('users', array(
            'username' => array('name' => 'name', 'type' => 'varchar', 'constraint' => 255),
        ));
    }
}