<?php

class Controller_Auth extends Controller
{
  public function action_register()
  {
    if (Input::method() == 'POST') {
      $name = Input::post('name');
      $email = Input::post('email');
      $password = Input::post('password');
      $authority = 1; // 一般ユーザー

      try {
        // 自作メソッドを使用
        Model_User::add_user($name, $email, $password, $authority);
        // ▼▼▼ 追加：ここで前のユーザーをログアウトさせる！ ▼▼▼
        Auth::logout();
        // ▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲▲

        Session::set_flash('success', '登録に成功しました。ログインしてください。');

        Response::redirect('auth/login');
      }
      //$eは、エラーが発生した瞬間にPHPが自動で作ってくれるエラー情報オブジェクト
      catch (Exception $e) {
        Session::set_flash('error', '登録失敗: ' . $e->getMessage());
      }
    }

    return View::forge('auth/register');
  }

  public function action_login()
  {

    // 1. 既にAuthでログイン済みならホームへ
    if (Auth::check()) {
      Response::redirect('home');
    }

    if (Input::method() == 'POST') {
      // CSRF対策
      if (! Security::check_token()) {
        Session::set_flash('error', 'ページ遷移が正しくありません。');
        Response::redirect('auth/login');
      }

      // ▼ 変更点: 自作のチェックをやめて、Auth::login を使う！
      // Auth::login が「DB照合」「パスワード確認」「セッション(A)への保存」を全部やります
      if (Auth::login(Input::post('email'), Input::post('password'))) {
        // ログイン成功！
        // (Auth::loginの中でセッションIDのローテーションも自動で行われます)

        // ★便利のために「自分用の引き出し(B)」にも入れておくならここで
        // Auth::get_user_id() で、今ログインした人のIDが取れます（配列で返る点に注意）
        $id_info = Auth::get_user_id();
        Session::set('user_id', $id_info[1]); // SimpleAuthの場合、[1]がID

        Session::set_flash('success', 'ログインしました。');
        Response::redirect('home');
      } else {
        // ログイン失敗
        Session::set_flash('error', 'メールアドレスかパスワードが間違っています。');
      }
    }

    return View::forge('auth/login');
  }

  public function action_logout()
  {
    // 確実にログアウト処理を実行
    Auth::logout();

    // ログイン画面（またはトップページ）に戻す
    Response::redirect('auth/login');
  }
}
