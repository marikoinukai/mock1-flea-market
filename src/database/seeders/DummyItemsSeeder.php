<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Item;
use App\Models\ItemImage;
use App\Models\ItemCondition;
use App\Models\User;

class DummyItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // seller（出品者）を1人決める：いなければ1人作る
        $seller = User::first() ?? User::factory()->create([
            'name' => 'テスト出品者',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        // 「コンディション名 → item_conditions.id」変換用
        $conditionMap = ItemCondition::pluck('id', 'name');

        // 「カテゴリ名 → categories.id」変換用
        $categoryMap = \App\Models\Category::pluck('id', 'name');

        $rows = [
            [
                'title' => '腕時計',
                'price' => 15000,
                'brand_name' => 'Rolax',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
                'condition_name' => '良好',
                'category_names' => ['ファッション', 'メンズ', 'アクセサリー'],
            ],
            [
                'title' => 'HDD',
                'price' => 5000,
                'brand_name' => '西芝',
                'description' => '高速で信頼性の高いハードディスク',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
                'condition_name' => '目立った傷や汚れなし',
                'category_names' => ['家電'],
            ],
            [
                'title' => '玉ねぎ3束',
                'price' => 300,
                'brand_name' => 'なし',
                'description' => '新鮮な玉ねぎ3束のセット',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
                'condition_name' => 'やや傷や汚れあり',
                'category_names' => ['キッチン'],
            ],
            [
                'title' => '革靴',
                'price' => 4000,
                'brand_name' => null,
                'description' => 'クラシックなデザインの革靴',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
                'condition_name' => '状態が悪い',
                'category_names' => ['ファッション', 'メンズ'],
            ],
            [
                'title' => 'ノートPC',
                'price' => 45000,
                'brand_name' => null,
                'description' => '高性能なノートパソコン',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
                'condition_name' => '良好',
                'category_names' => ['家電'],
            ],
            [
                'title' => 'マイク',
                'price' => 8000,
                'brand_name' => 'なし',
                'description' => '高音質のレコーディング用マイク',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
                'condition_name' => '目立った傷や汚れなし',
                'category_names' => ['家電'],
            ],
            [
                'title' => 'ショルダーバッグ',
                'price' => 3500,
                'brand_name' => null,
                'description' => 'おしゃれなショルダーバッグ',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
                'condition_name' => 'やや傷や汚れあり',
                'category_names' => ['ファッション', 'レディース'],
            ],
            [
                'title' => 'タンブラー',
                'price' => 500,
                'brand_name' => 'なし',
                'description' => '使いやすいタンブラー',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
                'condition_name' => '状態が悪い',
                'category_names' => ['キッチン'],
            ],
            [
                'title' => 'コーヒーミル',
                'price' => 4000,
                'brand_name' => 'Starbacks',
                'description' => '手動のコーヒーミル',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
                'condition_name' => '良好',
                'category_names' => ['キッチン'],
            ],
            [
                'title' => 'メイクセット',
                'price' => 2500,
                'brand_name' => null,
                'description' => '便利なメイクアップセット',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
                'condition_name' => '目立った傷や汚れなし',
                'category_names' => ['コスメ'],
            ],
        ];

        foreach ($rows as $row) {
            $conditionId = $conditionMap[$row['condition_name']] ?? null;

            if (!$conditionId) {
                // item_conditions にその名称が無い場合、ここで止まるより作る方が楽
                $conditionId = ItemCondition::create(['name' => $row['condition_name']])->id;
            }

            $item = Item::create([
                'seller_id' => $seller->id,
                'title' => $row['title'],
                'brand_name' => $row['brand_name'],
                'description' => $row['description'],
                'price' => $row['price'],
                'item_condition_id' => $conditionId,
            ]);

            $categoryIds = collect($row['category_names'] ?? [])
                ->map(function ($name) use ($categoryMap) {
                    return $categoryMap[$name] ?? null;
                })
                ->filter()
                ->values()
                ->all();

            if (!empty($categoryIds)) {
                $item->categories()->attach($categoryIds);
            }

            ItemImage::create([
                'item_id' => $item->id,
                // ここにURLをそのまま入れる
                'image_path' => $row['img_url'],
            ]);
        }
    }
}
