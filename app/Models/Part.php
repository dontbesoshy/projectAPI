<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Part extends Model
{
    use HasFactory;

    protected $fillable = [
        'price_list_id',
        'ean',
        'name',
        'code',
        'price',
    ];

    /**
     * Part belongs to price list.
     *
     * @return BelongsTo
     */
    public function priceList(): BelongsTo
    {
        return $this->belongsTo(PriceList::class);
    }

    /**
     * Part has one image.
     *
     * @return HasOne
     */
    public function image(): HasOne
    {
        return $this->hasOne(Image::class, 'part_code', 'code')->latest();
    }
}
