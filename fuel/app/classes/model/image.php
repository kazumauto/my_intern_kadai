<?php

class Model_Image extends Model
{
    // 画像データを保存するメソッド
    public static function add_image($filename)
    {
        return DB::insert('images')->set(array(
            'url'        => $filename,
            'created_at' => time(),
            'updated_at' => time(),
        ))->execute();
    }
}