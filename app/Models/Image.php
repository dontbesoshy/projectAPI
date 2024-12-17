<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ean',
        'url',
        'name',
        'part_code'
    ];

    /**
     * Image has one part.
     *
     * @return HasMany
     */
    public function parts(): HasMany
    {
        return $this->hasMany(Part::class, 'ean', 'ean');
    }
}
