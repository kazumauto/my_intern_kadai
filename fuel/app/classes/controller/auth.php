<?php

class Controller_Auth extends Controller
{
    // 新規登録ページ
    public function action_register()
    {
        // 1. もし「登録ボタン」が押されたら（POST送信されたら）
        if (Input::method() == 'POST')
        {
            // フォームの入力値を受け取る
            $name = Input::post('name');
            $email = Input::post('email');
            $password = Input::post('password');

            // 2. データベースに保存する準備
            // (oil generate model で作った Model_User を使います)
            $user = Model_User::forge();
            
            $user->name = $name;
            $user->email = $email;
            // ※本来はハッシュ化（暗号化）すべきですが、まずはそのまま保存します
            $user->password = $password; 

            // 3. 保存実行
            try {
                $user->save();
                // 成功したらログインページへ飛ばす
                Response::redirect('auth/login');
            }
            catch (Exception $e) {
                // エラーなら何もしない（画面にそのまま留まる）
                // 実践ではここでエラーメッセージを出します
            }
        }

        // 4. 登録画面（HTML）を表示する
        return View::forge('auth/register');
    }

    // ログインページ
    public function action_login()
    {
        // 1. ログインボタンが押されたら
        if (Input::method() == 'POST')
        {
            $email = Input::post('email');
            $password = Input::post('password');

            // 2. データベースから「メール」と「パスワード」が一致する人を探す
            $user = Model_User::find('first', array(
                'where' => array(
                    array('email', $email),
                    array('password', $password) // ※練習用：本来は暗号化が必要です
                )
            ));

            // 3. ユーザーが見つかったら（ログイン成功）
            if ($user)
            {
                // 「この人がログイン中ですよ」という証拠（ID）を保存する
                Session::set('user_id', $user->id);

                // 成功したら、ランキング画面やトップページへ飛ばす
                // （とりあえず今回は、トップページへ飛ばします）
                Response::redirect('ranking/home');
            }
            else
            {
                // 4. 見つからなかったら（失敗）
                // エラーメッセージを一時的に保存する
                Session::set_flash('error', 'メールアドレスかパスワードが間違っています。');
            }
        }

        // ログイン画面を表示
        return View::forge('auth/login');
    }
}