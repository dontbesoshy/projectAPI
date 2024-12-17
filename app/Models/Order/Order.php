<?php

namespace App\Models\Order;

use App\Models\Cart;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'cart_id',
        'comment',
        'url',
    ];

    /**
     * Part belongs to price list.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Order has one cart.
     *
     * @return HasOne
     */
    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class)->withTrashed();
    }
}
