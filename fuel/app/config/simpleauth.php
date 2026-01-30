<?php
return array(
    'db_connection' => null,
    'table_name' => 'users',
    'table_columns' => array(
        'user_id'   => 'id',
        'username'  => 'username',
        'email'     => 'email',
        'password'  => 'password',
        'group'     => 'group',      // ★ここを 'group' に変更！
        'last_login'=> 'last_login',
        'login_hash'=> 'login_hash',
        'profile_fields' => 'profile_fields',
    ),
    'guest_login' => false,
    'groups' => array(),
    'roles' => array(),
    'login_hash_salt' => 'put_some_salt_in_here',
    'username_post_key' => 'username',
    'password_post_key' => 'password',
);
