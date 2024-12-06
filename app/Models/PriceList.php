<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PriceList extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'active',
    ];

    /**
     * Price list has many parts.
     *
     * @return HasMany
     */
    public function parts(): HasMany
    {
        return $this->hasMany(Part::class);
    }
}
