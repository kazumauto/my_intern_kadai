<?php

class Model_User extends Model
{
    // ユーザーを登録するメソッド
    public static function add_user($name, $email, $password, $authority)
    {
        return DB::insert('users')->set(array(
            'name'       => $name,
            'email'      => $email,
            'password'   => $password,
            'authority'  => $authority,
            'created_at' => time(),
            'updated_at' => time(),
        ))->execute();
    }

    // メールとパスワードでユーザーを探すメソッド
    public static function get_user($email, $password)
    {
        $query = DB::select()->from('users')
            ->where('email', $email)
            ->where('password', $password)
            ->execute();
        
        return $query->current();
    }
}