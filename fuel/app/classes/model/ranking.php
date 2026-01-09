<?php

class Model_Ranking extends Model
{
    // ランキングを新規作成するメソッド
    public static function create_ranking($user_id, $name)
    {
        // 1. rankigs テーブルに大会名を保存
        // DB::insert は、成功すると [id, row_count] の配列を返すので、idだけ受け取ります
        list($ranking_id, $rows) = DB::insert('rankings')->set(array(
            'user_id' => $user_id,
            'name'    => $name,
        ))->execute();

        // 2. 既存のすべての画像をこのランキングに登録（初期スコア1500）
        // ※Model_Imageのメソッドを再利用します
        $images = Model_Image::get_all_images(); 
        
        foreach ($images as $img) {
            DB::insert('rates')->set(array(
                'ranking_id' => $ranking_id,
                'image_id'   => $img['id'],
                'score'      => 1500, // 初期レート
            ))->execute();
        }

        return $ranking_id;//この行は必要なのか？
    }

    // 全てのランキングを取得
    public static function get_all()
    {
        return DB::select()->from('rankings')->execute()->as_array();
    }

	// ★追加: IDを指定してランキング情報を1件取得
    public static function get_by_id($id)
    {
        return DB::select()
            ->from('rankings')
            ->where('id', $id)
            ->execute()
            ->current();
    }

    // classes/model/ranking.php に追加

    // ランキングとその関連データを削除する
    public static function delete_ranking($ranking_id)
    {
        // 1. まず、このランキングに関連するスコア(rates)を全削除
        // (これを忘れると、データベースにゴミが残ります)
        DB::delete('rates')
            ->where('ranking_id', $ranking_id)
            ->execute();

        // 2. 最後に、ランキング本体(rankings)を削除
        return DB::delete('rankings')
            ->where('id', $ranking_id)
            ->execute();
    }
}