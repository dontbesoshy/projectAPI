<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'part_code',
        'url',
        'name',
    ];

    /**
     * Image belongs to a part.
     *
     * @return BelongsTo
     */
    public function part(): BelongsTo
    {
        return $this->belongsTo(Part::class, 'part_code', 'code');
    }
}
