<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Item;
use App\Models\Order;
use App\Models\Like;
use App\Models\Comment;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'postal_code',
        'address_line1',
        'address_line2',
        'icon_path',
    ];

    // 出品した商品
    public function sellingItems()
    {
        return $this->hasMany(Item::class, 'seller_id');
    }

    // 購入した注文
    public function orders()
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
