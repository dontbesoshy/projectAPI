<?php

namespace App\Models\News;

use App\Models\News\Enums\NewsStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'url',
        'active',
    ];

    protected $casts = [
        'active' => NewsStatusEnum::class,
    ];
}
