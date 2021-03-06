<?php

namespace Database\Seeders;

class SeederUtil
{
    public function getTagIdNameObjects()
    {
        $tagNames = $this->getTagNames();
        $len = count($tagNames);
        for ($i = 0; $i < $len; $i++) {
            $tagIdNameObjects[] = [
                'id' => 1 + $i,
                'tagName' => $tagNames[$i],
            ];
        }
        return $tagIdNameObjects;
    }

    public function getTagNames()
    {
        return [
            "1歳",
            "2歳",
            "TikTokレシピ",
            "ありがとう",
            "いいね",
            "いいねした人全員フォローする",
            "いいね返し",
            "おうちごはん",
            "おしゃれ",
            "おしゃれさんと繋がりたい",
            "お弁当",
            "お洒落",
            "お洒落さんと繋がりたい",
            "かわいい",
            "ねこ",
            "アート",
            "イラスト",
            "インスタ映え",
            "インテリア",
            "オシャレ",
            "カフェ",
            "カフェ巡り",
            "カメラ",
            "カメラ女子",
            "クリスマス",
            "コスメ",
            "コーデ",
            "コーヒー",
            "サロンモデル",
            "スイーツ",
            "スタバ",
            "ダイエット",
            "ディズニー",
            "ドッキリ",
            "ネイル",
            "ネイルアート",
            "ネイルデザイン",
            "ネコ",
            "ハロウィン",
            "ビール",
            "ファインダー越しの私の世界",
            "ファッション",
            "フォロバ",
            "フォロー",
            "フォローミー",
            "プレゼント",
            "ヘアアレンジ",
            "ヘアカラー",
            "ヘアスタイル",
            "ポートレート",
            "ママコーデ",
            "メイク",
            "メンバー紹介",
            "モデル",
            "ランチ",
            "ラーメン",
            "一眼レフ",
            "今日のコーデ",
            "写り込みチャレンジ",
            "写真好きな人と繋がりたい",
            "写真撮ってる人と繋がりたい",
            "古着",
            "可愛い",
            "夏",
            "大好き",
            "大好き",
            "女の子",
            "女の子ママ",
            "女子会",
            "女子旅",
            "子どものいる暮らし",
            "学生あるある",
            "幸せ",
            "撮影",
            "料理",
            "旅",
            "旅行",
            "日常",
            "春",
            "暮らし",
            "最高",
            "朝ごはん",
            "東京カメラ部",
            "桜",
            "歌ってみた",
            "水平線",
            "海",
            "海外旅行",
            "焼肉",
            "犬",
            "猫",
            "男の子",
            "男の子ママ",
            "癒し",
            "相互フォロー",
            "秋",
            "空",
            "笑",
            "紅葉",
            "結婚式",
            "美味しい",
            "美容",
            "美容室",
            "自然",
            "花",
            "誕生日",
            "赤ちゃん",
            "踊ってみた",
            "風景",
        ];
    }
}
