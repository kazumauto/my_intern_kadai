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
            $user = Model_User::forge();
            
            $user->name = $name;
            $user->email = $email;
            $user->password = $password;
            
            // ★【重要】ここで権限（1=一般）をセットしないとエラーになります！
            $user->authority = 1;

            // 3. 保存実行
            try {
                $user->save();
                // 成功したらログインページへ飛ばす
                Response::redirect('auth/login');
            }
            catch (Exception $e) {
                // エラー内容を表示するように改良
                Session::set_flash('error', '登録失敗: '.$e->getMessage());
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
                    array('password', $password)
                )
            ));

            // 3. ユーザーが見つかったら（ログイン成功）
            if ($user)
            {
                // 「この人がログイン中ですよ」という証拠（ID）を保存する
                Session::set('user_id', $user->id);
                // 権限も保存
                Session::set('authority', $user->authority);

                // ★修正：Viewのファイル名ではなく、URL（コントローラー名）を指定します
                // ranking/home だと 404エラーになる可能性が高いです
                Response::redirect('home');
            }
            else
            {
                // 4. 見つからなかったら（失敗）
                Session::set_flash('error', 'メールアドレスかパスワードが間違っています。');
            }
        }

        // ログイン画面を表示
        return View::forge('auth/login');
    }
}