<?php

class Controller_Auth extends Controller
{
    public function action_register()
    {
        if (Input::method() == 'POST')
        {
            $name = Input::post('name');
            $email = Input::post('email');
            $password = Input::post('password');
            $authority = 1; // 一般ユーザー

            try {
                // 自作メソッドを使用
                Model_User::add_user($name, $email, $password, $authority);
                Response::redirect('auth/login');
            }
            catch (Exception $e) {
                Session::set_flash('error', '登録失敗: '.$e->getMessage());
            }
        }

        return View::forge('auth/register');
    }

    public function action_login()
    {
        if (Input::method() == 'POST')
        {
            $email = Input::post('email');
            $password = Input::post('password');

            // 自作メソッドを使用
            $user = Model_User::get_user($email, $password);

            if ($user)
            {
                // 配列としてアクセス
                Session::set('user_id', $user['id']);
                Session::set('authority', $user['authority']);

                Response::redirect('home');
            }
            else
            {
                Session::set_flash('error', 'メールアドレスかパスワードが間違っています。');
            }
        }

        return View::forge('auth/login');
    }
}