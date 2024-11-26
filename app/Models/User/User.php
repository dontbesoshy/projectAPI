<?php

namespace App\Models\User;

use App\Enums\User\UserTypeEnum;
use App\Models\Cart;
use App\Models\PriceList;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'type',
        'company_name',
        'company_address',
        'login',
        'login_counter',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'type' => UserTypeEnum::class,
    ];

    /**
     * User has one register token.
     *
     * @return HasOne
     */
    public function registerToken(): HasOne
    {
        return $this->hasOne(RegisterToken::class);
    }

    /**
     * User belongs to many price lists.
     *
     * @return BelongsToMany
     */
    public function priceLists(): BelongsToMany
    {
        return $this->belongsToMany(PriceList::class);
    }

    /**
     * Check if user is admin.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->type === UserTypeEnum::ADMIN;
    }

    /**
     * User has one cart.
     *
     * @return HasOne
     */
    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }

    /**
     * User has many favorite parts.
     *
     * @return HasMany
     */
    public function favoriteParts(): HasMany
    {
       return $this->hasMany(FavoritePart::class);
    }
}
