<?php

class Controller_Battle extends Controller
{
    // 対決画面の表示
    public function action_index($ranking_id = null)
    {
        // ログインチェック
        if (Session::get('user_id') == null) {
            Response::redirect('auth/login');
        }

        // ランキング情報の取得（存在チェック）
        $ranking = Model_Ranking::get_by_id($ranking_id);
        if (!$ranking) {
            Response::redirect('home');
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
        $view->set('ranking', $ranking, false);
        $view->set('player1', $players[0], false);
        $view->set('player2', $players[1], false);

        return $view;
    }

    // ★追加: 投票処理
    // URLの形: /battle/vote/ランキングID/勝った画像ID/負けた画像ID
    public function action_vote($ranking_id, $winner_id, $loser_id)
    {
        // ログインチェック
        if (Session::get('user_id') == null) {
            Response::redirect('auth/login');
        }

        // モデルを呼び出してスコア更新
        Model_Rate::update_battle_result($ranking_id, $winner_id, $loser_id);

        // すぐに次の対決へ（リロード）
        Response::redirect('battle/index/' . $ranking_id);
    }
}