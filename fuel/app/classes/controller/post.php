<?php

class Controller_Post extends Controller
{
    public function action_create()
    {
        // ログイン＆権限チェック
        if (Session::get('user_id') == null) {
            Response::redirect('auth/login');
        }
        if (Session::get('authority') != 100) {
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
        $config = array(
            'path' => DOCROOT.'assets/img/uploads',
            'randomize' => false,
            'ext_whitelist' => array('img', 'jpg', 'jpeg', 'gif', 'png'),
        );

        Upload::process($config);

        if (Upload::is_valid())
        {
            Upload::save();
            $files = Upload::get_files();
            
            $success_count = 0;
            $skip_count = 0;

            foreach ($files as $file)
            {
                // ★修正1: チェックには「元のファイル名」を使う
                $original_name = $file['name'];
                
                // ★修正2: 実際に保存されたファイル名（リネームされている可能性がある）
                $saved_filename = $file['saved_as'];

                // 元のファイル名でDBを探す
                if (Model_Image::check_duplicate($original_name))
                {
                    // 重複している場合
                    $skip_count++;
                    
                    // サーバーにはリネームされて保存（例: image_1.jpg）されてしまっているので、それを消す
                    $file_path = DOCROOT.'assets/img/uploads/'.$saved_filename;
                    if (file_exists($file_path)) {
                        unlink($file_path); 
                    }
                }
                else
                {
                    // 重複していない場合 -> 保存された名前で登録
                    Model_Image::add_image($saved_filename);
                    $success_count++;
                }
            }

            $msg = "{$success_count} 件アップロードしました。";
            if ($skip_count > 0) {
                $msg .= "（重複のため {$skip_count} 件スキップしました）";
            }

            Session::set_flash('success', $msg);
            Response::redirect('post/create');
        }
        else
        {
            Session::set_flash('error', 'アップロードに失敗しました。');
            Response::redirect('post/create');
        }
    }

    // ★追加: 削除機能
    public function action_delete($id = null)
    {
        // 権限チェック
        if (Session::get('authority') != 100) {
            Response::redirect('home');
        }

        // 削除対象の画像情報を取得
        $image = Model_Image::get_image($id);

        if ($image)
        {
            // 1. データベースから削除
            Model_Image::delete_image($id);

            // 2. 実際のファイルも削除（ゴミを残さないため）
            $file_path = DOCROOT.'assets/img/uploads/'.$image['url'];
            if (file_exists($file_path))
            {
                unlink($file_path);
            }

            Session::set_flash('success', '削除しました。');
        }

        // 元の画面に戻る
        Response::redirect('post/create');
    }
}