<?php

class Controller_Home extends Controller
{
  // ▼▼▼ 追加：すべての処理の前に必ず実行される ▼▼▼
  public function before()
  {
    // 親クラス（Controller）の準備処理も必ず実行させる（必須！）
    parent::before();

    // ログインチェックを一括で行う
    // ログインしていなければ、問答無用でログイン画面へ
    if (! Auth::check()) {
      Response::redirect('auth/login');
    }
  }

  // ----------------------------------------------------
  // 1. ホーム画面（ランキング一覧を表示）
  // ----------------------------------------------------
  public function action_index()
  {
    // ▼ランキング作成処理
    if (Input::method() == 'POST') {
      // ★追加: ここも「作成」処理なので、CSRFチェックが必須です！
      if (! Security::check_token()) {
        Session::set_flash('error', 'ページ遷移が正しくありません。');
        Response::redirect('home');
      }

      $name = Input::post('name');
      if ($name) {
        Model_Ranking::create_ranking(Session::get('user_id'), $name);
        Response::redirect('home');
      }
    }

    // (Auth::get_user_id()[1] の方が確実ですが、Sessionで動いているならこれでもOK)
    $my_user_id = Session::get('user_id');

    // 1. データを取得。?:は、左側がnullだったら、右側を使う、というルール。
    $rankings = Model_Ranking::get_by_user($my_user_id) ?: array();

    // ★追加: NULL退治（一覧データ用）
    // 配列の深い階層までNULLがないかチェックして掃除します
    foreach ($rankings as $key => $row) {
      foreach ($row as $col_name => $val) {
        if (is_null($val)) {
          $rankings[$key][$col_name] = '';
          //$valはコピーなので、本体を直接変えている。
        }
      }
    }

    // 2. Viewを作る
    $view = View::forge('home/index');

    // ★修正: false を削除！（これで安全にXSS対策が効きます）
    $view->set('rankings', $rankings);
    return $view;
  }

  // ----------------------------------------------------
  // 2. 詳細画面：特定のランキングの順位表を表示
  public function action_view($ranking_id = null)
  //=nullは、入力がなかった時のデフォルト値。エラー防止用。
  {
    // ランキング情報の取得
    $ranking = Model_Ranking::get_by_id($ranking_id);

    if (!$ranking) {
      Response::redirect('home');
    }

    // ★追加: NULL退治（単体データ用）
    foreach ($ranking as $key => $value) {
      if (is_null($value)) {
        $ranking[$key] = '';
      }
    }

    // 順位表を取得
    $list = Model_Rate::get_ranking_list($ranking_id);

    // ★追加: NULL退治（リストデータ用）
    foreach ($list as $key => $row) {
      foreach ($row as $col_name => $val) {
        if (is_null($val)) {
          $list[$key][$col_name] = '';
        }
      }
    }

    $view = View::forge('home/view');

    // ★修正: false を削除！（これで安全）
    $view->set('ranking', $ranking);
    $view->set('list',    $list);

    return $view;
  }

  public function action_delete($ranking_id = null)
  {
    // ★0. CSRF対策（最優先でチェック！）
    // フォームから送られた「合言葉」が正しいかチェックします。
    // もし合言葉がない（直接URLを入力した等）場合も、ここで弾かれます。
    if (! Security::check_token()) {
      // エラーを表示して元のページに戻す、または処理を中断する
      Session::set_flash('error', 'ページ遷移が正しくありません。もう一度お試しください。');
      Response::redirect('home');
    }

    // 2. ランキングが存在するか確認
    $ranking = Model_Ranking::get_by_id($ranking_id);
    if (!$ranking) {
      Response::redirect('home');
    }

    // 3. 「自分のランキング」以外は消せないようにする！

    // 【修正前】 手書きメモを見る（危険、または空っぽかも）
    // if ($ranking['user_id'] != Session::get('user_id')) {

    // 【修正後】 Auth公式のID情報を取得する
    // Auth::get_user_id() は array('simpleauth', '1') のように
    // [0]=>ドライバ名, [1]=>ユーザーID という配列で返ってきます。
    $auth_info = Auth::get_user_id();
    $current_user_id = $auth_info[1]; // 2番目の要素がIDです

    if ($ranking['user_id'] != $current_user_id) {
      Session::set_flash('error', '削除権限がありません。');
      Response::redirect('home');
    }

    // 4. 削除実行
    Model_Ranking::delete_ranking($ranking_id);

    // 5. メッセージを出してホームに戻る
    Session::set_flash('success', 'ランキングを削除しました。');
    Response::redirect('home');
  }
}
