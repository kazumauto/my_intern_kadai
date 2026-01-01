<?php

class Controller_Post extends Controller
{
    // 1. アップロード画面を表示する
    public function action_create()
    {
    // 1. ログインチェック
        if (Session::get('user_id') == null) {
            Response::redirect('auth/login');
        }

    // 2. 権限チェック（追加部分）
    // もし権限が「100（管理者）」じゃなかったら、トップへ追い返す
        if (Session::get('authority') != 100) {
        // メッセージを出しても親切です
            Session::set_flash('error', '管理者権限がありません。');
            Response::redirect('home');
        }

        return View::forge('post/create');
    }

    // 2. 画像を保存する処理
    public function action_save()
    {
        // アップロードの設定
        $config = array(
            'path' => DOCROOT.'assets/img/uploads', // 保存先
            'randomize' => true,                    // ファイル名をランダムにする（重複防止）
            'ext_whitelist' => array('img', 'jpg', 'jpeg', 'gif', 'png'), // 許可する拡張子
        );

        // Uploadクラス（FuelPHPの便利機能）を使って処理
        Upload::process($config);

        // アップロードが正常に行われたかチェック
        if (Upload::is_valid())
        {
            // 実際にファイルを保存
            Upload::save();

            // 保存されたファイルの情報を取得
            $file = Upload::get_files(0);

            // データベースに保存
            $image = Model_Image::forge();
            $image->url = $file['saved_as']; // 保存されたファイル名を入れる
            $image->save();

            // 成功したらトップページへ
            Response::redirect('home');
        }
        else
        {
            // 失敗したらエラーを表示（今は簡易的に）
            return 'アップロードに失敗しました。画像ファイルを選択していますか？';
        }
    }
}