<?php

class Model_User extends Model
{
  // ユーザーを登録するメソッド
  public static function add_user($name, $email, $password, $authority)
  {
    // try ~ catch はここでは書かない！
    // エラーが起きたら、そのまま呼び出し元（コントローラー）にエラーを投げつける
    return Auth::create_user($name, $password, $email, $authority);
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
