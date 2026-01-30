<?php

class Model_User extends Model
{
    // ユーザーを登録するメソッド
    public static function add_user($name, $email, $password, $authority)
    {
        try
        {
            // ▼ DB::insert をやめて、Authの専用機能を使う！
            // Auth::create_user( ユーザー名, パスワード, メアド, 権限グループ )
            $result = Auth::create_user($name, $password, $email, $authority);
        
            // 成功すると、作成されたユーザーIDが返ってきます
            return $result;
        }
        catch (\SimpleUserUpdateException $e)
        {
            // 「メアドが既に使われている」などのエラー時は false を返す
            return false;
        }
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