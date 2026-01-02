<?php

class Controller_Post extends Controller
{
    public function action_create()
    {
        if (Session::get('user_id') == null) {
            Response::redirect('auth/login');
        }

        if (Session::get('authority') != 100) {
            Session::set_flash('error', '管理者権限がありません。');
            Response::redirect('home');
        }

        return View::forge('post/create');
    }

    public function action_save()
    {
        $config = array(
            'path' => DOCROOT.'assets/img/uploads',
            'randomize' => true,
            'ext_whitelist' => array('img', 'jpg', 'jpeg', 'gif', 'png'),
        );

        Upload::process($config);

        if (Upload::is_valid())
        {
            Upload::save();
            $file = Upload::get_files(0);

            // 自作メソッドを使用
            Model_Image::add_image($file['saved_as']);

            Response::redirect('home');
        }
        else
        {
            return 'アップロードに失敗しました。';
        }
    }
}