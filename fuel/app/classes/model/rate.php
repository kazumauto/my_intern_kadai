<?php

class Model_Rate extends Model
{
    // 指定したランキングの現在の順位を取得する
    public static function get_ranking_list($ranking_id)
    {
        // ratesテーブルとimagesテーブルを「合体(JOIN)」させてデータを取ります
        return DB::select('rates.score', 'images.url', 'images.id')
            ->from('rates')
            ->join('images', 'LEFT')->on('rates.image_id', '=', 'images.id') // 画像IDで紐付け
            ->where('rates.ranking_id', $ranking_id)
            ->order_by('rates.score', 'desc') // スコアが高い順
            ->execute()
            ->as_array();
    }

	public static function get_random_pair($ranking_id)
    {
        return DB::select('rates.score', 'rates.image_id', 'images.url') // image_idも必要
            ->from('rates')
            ->join('images', 'LEFT')->on('rates.image_id', '=', 'images.id')
            ->where('rates.ranking_id', $ranking_id)
            ->order_by(DB::expr('RAND()')) // ランダムに並び替え（MySQL用）
            ->limit(2) // 2枚だけ取得
            ->execute()
            ->as_array();
    }

	// ★追加: 勝敗を記録してレートを計算・更新する（イロレーティング）
    public static function update_battle_result($ranking_id, $winner_image_id, $loser_image_id)
    {
        // 1. 現在のレートを取得する
        $winner = DB::select()->from('rates')
            ->where('ranking_id', $ranking_id)
            ->where('image_id', $winner_image_id)
            ->execute()->current();

        $loser = DB::select()->from('rates')
            ->where('ranking_id', $ranking_id)
            ->where('image_id', $loser_image_id)
            ->execute()->current();

        if (!$winner || !$loser) {
            return false;
        }

        // 2. イロレーティングの計算
        $Ra = $winner['score']; // 勝者の現在レート
        $Rb = $loser['score'];  // 敗者の現在レート
        $K = 32; // 変動係数（この数字が大きいほど、1回の対決でレートが激しく動きます）

        // 勝者が勝つ確率（期待値）を計算
        // 式: 1 / (1 + 10 ^ ((相手のレート - 自分のレート) / 400))
        $Ea = 1 / (1 + pow(10, ($Rb - $Ra) / 400));

        // 新しいレートを計算
        // 新レート = 旧レート + K * (勝敗結果(1) - 期待値)
        $Ra_new = $Ra + $K * (1 - $Ea);
        
        // 敗者の計算（勝敗結果は0）
        $Eb = 1 / (1 + pow(10, ($Ra - $Rb) / 400));
        $Rb_new = $Rb + $K * (0 - $Eb);

        // 四捨五入して整数にする
        $Ra_new = round($Ra_new);
        $Rb_new = round($Rb_new);

        // 3. データベースを更新
        DB::update('rates')
            ->value('score', $Ra_new)
            ->where('id', $winner['id'])
            ->execute();
            
        DB::update('rates')
            ->value('score', $Rb_new)
            ->where('id', $loser['id'])
            ->execute();

        return true;
    }
}