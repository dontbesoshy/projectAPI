<?php

namespace App\Models\Config;

use App\Models\Config\Enums\ConfigTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Config extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * @var string[]
     */
    protected $fillable = [
        'type',
        'active',
        'value',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'active' => 'boolean',
        'type' => ConfigTypeEnum::class,
    ];
}
