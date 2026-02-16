<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\ItemCondition;
use App\Models\Category;
use App\Models\ItemImage;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Order;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'item_condition_id',
        'title',
        'brand_name',
        'description',
        'price',
        'is_sold',
    ];

    // 出品者
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    // 商品状態
    public function condition()
    {
        return $this->belongsTo(ItemCondition::class, 'item_condition_id');
    }

    // 画像（1対1）
    public function image()
    {
        return $this->hasOne(ItemImage::class);
    }

    // カテゴリ（多対多）
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'item_categories');
    }

    // いいね
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // コメント
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // 注文（1対1）
    public function order()
    {
        return $this->hasOne(Order::class);
    }
}
