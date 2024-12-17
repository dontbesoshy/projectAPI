<?php

namespace App\Models;

use App\Models\User\User;
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

    /**
     * Price list has many parts with trashed.
     *
     * @return HasMany
     */
    public function partsWithTrashed(): HasMany
    {
        return $this->hasMany(Part::class)->withTrashed();
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
