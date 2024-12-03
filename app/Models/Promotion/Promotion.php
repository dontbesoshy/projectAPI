<?php

namespace App\Models\Promotion;

use App\Models\Promotion\Enums\PromotionStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'url',
        'active',
    ];

    protected $casts = [
        'active' => PromotionStatusEnum::class,
    ];
}
