<?php

class Controller_Home extends Controller
{
    // ----------------------------------------------------
    // 1. ホーム画面（ランキング一覧を表示）
    // ----------------------------------------------------
    public function action_index()
    {
        if (Session::get('user_id') == null) {
            Response::redirect('auth/login');
        }

        if (Input::method() == 'POST')
        {
            $name = Input::post('name');
            if ($name) {
                Model_Ranking::create_ranking(Session::get('user_id'), $name);
                Response::redirect('home');
            }
        }

        // 1. データを取得
        $rankings = Model_Ranking::get_all() ?: array();

        // 2. Viewを作る
        $view = View::forge('home/index');

        // ★ここが修正ポイント！
        // 第3引数を 'false' にすると、自動フィルター（掃除）が無効になります。
        // これで get_class() エラーを強制的に回避できます。
        $view->set('rankings', $rankings, false);

        return $view;
    }

    // ----------------------------------------------------
    // 2. 詳細画面：特定のランキングの順位表を表示
    public function action_view($ranking_id = null)
    {
        if (Session::get('user_id') == null) {
            Response::redirect('auth/login');
        }

        // ランキング情報の取得
        $ranking = Model_Ranking::get_by_id($ranking_id);

        if (!$ranking) {
            Response::redirect('home');
        }

        // 順位表を取得
        $list = Model_Rate::get_ranking_list($ranking_id);

        // ★修正ポイント
        // View::forge の書き方を変更し、set(..., false) を使います。
        
        $view = View::forge('home/view');

        // 第3引数を false にして、自動フィルタリングを無効化
        $view->set('ranking', $ranking, false);
        $view->set('list',    $list,    false);

        return $view;
    }
}