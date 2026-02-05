<?php

class Controller_Post extends Controller
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

  public function action_create()
  {
    // 2. 管理者権限チェック
    // 手順: AuthからIDを聞く → DBの最新情報を見る → 判断する

    // まず、今ログインしている人のIDを取得（配列で返ってくるので[1]を使う）
    $auth_info = Auth::get_user_id();
    $current_user_id = $auth_info[1];

    // 2. Model_User::find() は使わずに、DBクラスで直接データを取る
    // （これならモデルの設定に関係なく確実に動きます）
    $current_user = DB::select()->from('users')->where('id', $current_user_id)->execute()->current();

    // 取得したデータの 'authority' カラムをチェック
    // ※ $current_user が見つからない場合の保険も入れておくと完璧です
    if (! $current_user || $current_user['group'] != 100) {
      Session::set_flash('error', '管理者権限がありません。');
      Response::redirect('home');
    }

    // ★追加: 画像一覧データをモデルから取得
    $images = Model_Image::get_all_images();

    // ★修正: Viewにデータを渡す（第2引数に配列で渡します）
    return View::forge('post/create', array('images' => $images));
  }

  public function action_save()
  {
    if (Input::method() == 'POST') {
      if (! Security::check_token()) {
        Session::set_flash('error', 'ページ遷移が正しくありません。');
        Response::redirect('post/create');
      }

      // 保存先ディレクトリ
      $upload_dir = DOCROOT . 'assets/img/uploads/';

      // ★削除1: is_writable のデバッグチェックを削除しました

      // FuelPHPの設定。ファイルのアップロードを受け入れるための『ルール設定』
      $config = array(
        // 1. 場所の指定
        'path' => $upload_dir,

        // 2. ファイル名の変更ルール
        // true にすると、FuelPHPが勝手に「a8f3...jpg」のようなランダムな名前に変えます。
        // 今回はあとで自分でハッシュ化（名前変更）をするので、false（何もしない）にしています。
        'randomize' => false,

        // 3. 許可証（ホワイトリスト）
        // ここに書かれていない拡張子のファイルは「門前払い」にします。
        // .exe や .php などの危険なプログラムファイルを拒否するための重要なセキュリティ設定です。
        'ext_whitelist' => array('img', 'jpg', 'jpeg', 'gif', 'png'),
      );

      //検査の実行
      //この一行で、FuelPHPは以下の仕事を一気に行います。
      //1.荷物の確認: フォームから送られてきたデータ（$_FILES）を読み込みます。
      //2.ルールの適用: 先ほどの $config のルールと照らし合わせます。
      //3.仕分け:
      //合格したファイル → 「保存待ちリスト」に入れる（Upload::get_files() で取れるようになる）。
      //不合格のファイル → 「エラーリスト」に入れる（Upload::get_errors() で取れるようになる）。
      Upload::process($config);

      //合格したファイルが一つでもあれば。
      if (Upload::is_valid()) {
        $files = Upload::get_files();
        $saved_count = 0;
        $skipped_count = 0;

        foreach ($files as $file) {
          // $file の中身のイメージ
          //  array(
          //      'name'      => '旅行の写真.jpg',       // 元のファイル名
          //      'type'      => 'image/jpeg',           // ファイルの種類
          //      'size'      => 102400,                 // サイズ(バイト)
          //      'file'      => '/tmp/phpXyZ123',       // ★重要: 現在サーバーのどこにあるか（一時パス）
          //      'extension' => 'jpg',                  // ★重要: 拡張子
          //      'error'     => 0,                      // エラーがあるかどうか
          //      // ...その他いろいろ
          //  )

          // 1. ハッシュ化
          $hash = md5_file($file['file']);
          $extension = $file['extension'];
          $new_filename = $hash . '.' . $extension;
          $target_path = $upload_dir . $new_filename;

          // 2. 重複チェック
          if (Model_Image::check_duplicate($new_filename)) {
            $skipped_count++;
            continue;
          }

          // 3. 移動・保存
          try {
            //$file['file']という場所に置いてあるファイルを、$target_pathに移動させる。
            $success = move_uploaded_file($file['file'], $target_path);

            if (!$success) {
              $success = rename($file['file'], $target_path);
            }

            if ($success) {
              //ファイルの権限を、全体公開にする。
              chmod($target_path, 0644);
              //データベースに記録
              Model_Image::add_image($new_filename);
              $saved_count++;
            } else {
              // 失敗時は例外を投げて catch ブロックへ
              throw new Exception("移動失敗");
            }
          } catch (Exception $e) {
            // ★修正2: エラー内容を画面に出す(echo)のをやめ、ログに残すだけにする
            // Log::error('画像保存エラー: ' . $e->getMessage()); // ログに残したい場合はコメントアウトを外す

            // エラーが出ても強制終了(exit)せず、次のファイルの処理へ進む
            continue;
          }
        }

        // 完了メッセージ
        if ($saved_count > 0) {
          Session::set_flash('success', $saved_count . '件の画像をアップロードしました。');
        } else if ($skipped_count > 0) {
          Session::set_flash('error', 'アップロードされた画像は既に登録済みでした。');
        } else {
          Session::set_flash('error', '画像の保存に失敗しました。');
        }

        Response::redirect('post/create');
      } else {
        // バリデーションエラー
        foreach (Upload::get_errors() as $file) {
          //$file（不合格になった1つのファイルデータ）の中身
          //array(
          //  'name' => 'bad_image.exe',
          //  'errors' => array(          // ← ['errors']
          //      0 => array(             // ← [0] （1つ目のエラー）
          //          'message' => '許可されていない拡張子です', // ← ['message']
          //          'error'   => 201
          //      ),
          //      // (ごく稀に2つ以上のエラーがある場合、ここに1, 2...と続く)
          //  )
          //)
          Session::set_flash('error', $file['errors'][0]['message']);
        }
        Response::redirect('post/create');
      }
    }
  }

  // ★追加: 削除機能
  public function action_delete($id = null)
  {
    // 2. CSRFチェック（必須！）
    // これがないと、外部サイトから勝手に削除URLを叩かれてしまいます
    if (! Security::check_token()) {
      Session::set_flash('error', 'ページ遷移が正しくありません。');
      Response::redirect('post/create');
    }

    // 3. 権限チェック（DBの最新情報を見る）
    // 毎回DBを確認する「最強の構成」にします
    $auth_info = Auth::get_user_id();
    // find() の代わりに DBクラスで直接取得する
    $user = DB::select()->from('users')->where('id', $auth_info[1])->execute()->current();

    if (! $user || $user['group'] != 100) {
      Session::set_flash('error', '削除権限がありません。');
      Response::redirect('home');
    }

    // --- ここから下は変更なし ---

    // 削除対象の画像情報を取得
    $image = Model_Image::get_image($id);

    if ($image) {
      // 1. データベースから削除
      Model_Image::delete_image($id);

      // 2. 実際のファイルも削除（ゴミを残さないため）
      $file_path = DOCROOT . 'assets/img/uploads/' . $image['url'];
      if (file_exists($file_path)) {
        unlink($file_path);
      }

      Session::set_flash('success', '削除しました。');
    }

    // 元の画面に戻る
    Response::redirect('post/create');
  }
}
