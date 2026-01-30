<?php

class Controller_Battle extends Controller
{
    // 対決画面の表示
    public function action_index($ranking_id = null)
    {
        // ログインチェック
        if ( ! Auth::check()) {
            Response::redirect('auth/login');
        }

        // ランキング情報の取得（存在チェック）
        $ranking = Model_Ranking::get_by_id($ranking_id);
        if (!$ranking) {
            Response::redirect('home');
        }

        // ▼▼▼ 追加：自分のランキングでなければ追い出す ▼▼▼
        $auth_info = Auth::get_user_id(); // ログイン情報を取得 ([0]=>ドライバID, [1]=>ユーザーID)
        $my_id = $auth_info[1];           // 自分のIDを取り出す

        // ランキングの作成者ID と 自分のID が違ったら...
        if ($ranking['user_id'] != $my_id) {
            Session::set_flash('error', '自分以外のランキングには投票できません。');
            Response::redirect('home');
        }
        // ▲▲▲ ここまで ▲▲▲

        // ★追加: NULL を 空文字に変換するおまじない
        // これをやることで、Viewの前に働くSecurityクラスがエラーを吐かなくなります
        foreach ($ranking as $key => $value) {
            if (is_null($value)) {
                $ranking[$key] = '';
            }
        }

        // 対戦する2枚の画像を取得
        $players = Model_Rate::get_random_pair($ranking_id);

        // 画像が2枚揃わない場合（登録画像が少ないなど）はホームに戻す
        if (count($players) < 2) {
            Session::set_flash('error', '画像が足りないため対決できません。');
            Response::redirect('home/view/'.$ranking_id);
        }

        // Viewの作成（フィルター無効化を忘れずに！）
        $view = View::forge('battle/index');
        $view->set('ranking', $ranking);
        $view->set('player1', $players[0], false);
        $view->set('player2', $players[1], false);

        return $view;
    }

    // ★変更: 投票API
    // 画面遷移（リダイレクト）せず、JSONデータを返すように変更します
    public function action_vote()
    {
        // 1. Ajax通信以外は拒否（セキュリティ）
        if ( ! Input::is_ajax())
        {
             // Ajax以外でアクセスされたら404エラーにするなど
             return Response::forge('不正なアクセスです', 404);
        }

        // 2. データの受け取り（POST送信で受け取る形に変えます）
        $ranking_id = Input::post('ranking_id');
        $winner_id  = Input::post('winner_id');
        $loser_id   = Input::post('loser_id');

        // 3. スコア更新（ロジックは今まで通り）
        Model_Rate::update_battle_result($ranking_id, $winner_id, $loser_id);

        // 4. ★ここが重要：次の対戦ペアを取得
        $next_pair = Model_Rate::get_random_pair($ranking_id);

        // 5. JSON形式で返すデータをまとめる
        $response_data = array(
            'status'    => 'success',  // 成功フラグ
            'next_pair' => $next_pair  // 次の画像の配列
        );

        // 6. JSONとして出力
        // Response::forgeは、HTML表示以外のことをするときに使う。
        return Response::forge(json_encode($response_data), 200, array(
            'Content-Type' => 'application/json',
        ));
    }

}