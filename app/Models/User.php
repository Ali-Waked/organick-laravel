<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\AbilityStatus;
use App\Enums\UserTypes;
use App\Enums\UserGender;
use App\Services\ImageService;
use App\Traits\HasImage;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens, HasImage;

    const FOLDER = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'avatar',
        'birthday',
        'job_title',
        'gender',
        'type',
        'email_verified_at',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'type' => UserTypes::class,
            'gender' => UserGender::class,
            // 'created_at' => 'datetime:Y-m-d',
            // 'updated_at' => 'datetime:Y-m-d',
        ];
    }

    // public function scopeMember(Builder $builder, ?object $filter)
    // {
    //     $builder->where('type', UserTypes::Member);
    //     $builder->when($filter?->search ?? false, function ($builder, $value) {
    //         $builder->where(function ($builder) use ($value) {
    //             $builder->whereLike('email', "%{$value}%")
    //                 ->orWhereLike('first_name', "%{$value}%")
    //                 ->orWhereLike('last_name', "%{$value}%");
    //         });
    //     });
    //     $builder->when($filter?->sort_by ?? 'created_at', function ($builder, $value) use ($filter) {
    //         $value = strtolower($value);
    //         $orderBy = (strtolower($filter->sortingOrder) == 'desc' ? 'desc' : 'asc');
    //         // if(in_array($value,['created_at','last_active_at']))
    //         if (in_array($value, ['created_at'])) {
    //             $builder->orderBy($value, $orderBy);
    //         } else if ($value == 'name') {
    //             $builder->orderBy('first_name', $orderBy)->orderBy('last_name', $orderBy);
    //         }
    //     });
    // }

    protected function avatar(): Attribute
    {
        // $imageService = App::make(ImageService::class);
        return Attribute::make(
            get: function (?string $avatar) {
                if ($avatar) {
                    if (str_starts_with($avatar, 'http://') || str_starts_with($avatar, 'https://')) {
                        return $avatar;
                    }
                    return $this->getImageUrl($avatar);
                }
                return "https://ui-avatars.com/api/?name={$this->full_name}&color=fff&backgound=blue";
            }
        );
    }
    protected static function booted(): void
    {
        static::creating(function (User $user) {
            if (in_array($user->type, UserTypes::getAllTypes(UserTypes::Driver->value, UserTypes::Moderator->value))) {
                $user->email_verified_at = now();
            }
        });
    }
    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class, 'user_id');
    }

    public function cartItems(): HasMany
    {
        $cart = $this->cart;
        if (!$cart) {
            $cart = $this->cart()->create([]);
        }
        return $cart->items();
    }

    public function feedbacks(): HasMany
    {
        return $this->hasMany(Feedback::class);
    }
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
    public function assignedConversations()
    {
        return $this->belongsToMany(Conversation::class, 'conversation_user')
            ->withPivot('role')
            ->wherePivot('role', 'moderator')
            ->withTimestamps();
    }

    public function myConversations() // if customer
    {
        return $this->hasMany(Conversation::class, 'customer_id');
    }

    public function fullName(): Attribute
    {
        return Attribute::make(
            get: fn() => "{$this->first_name} {$this->last_name}",
        );
    }
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles')->with('abilities');
    }

    public function hasPermission(string $ability): bool
    {
        return $this->roles()->whereHas('abilities', function ($query) use ($ability) {
            $query->where('ability', $ability)
                ->where('status', AbilityStatus::Allow);
        })->exists();
    }


    public function bolgs(): HasMany
    {
        return $this->hasMany(Blog::class);
    }

    public function billingAddress(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }
    // public function socials(): HasMany
    // {
    //     return $this->hasMany(Social::class);
    // }

    // public function scopeWithoutAdmin(Builder $builder)
    // {
    //     $builder->where('type', '<>', UserTypes::Admin);
    // }

    public function isAdmin(): Attribute
    {
        return Attribute::make(
            fn() => $this->type === UserTypes::Admin,
        );
    }
    public function isDriver(): Attribute
    {
        return Attribute::make(
            fn() => $this->type === UserTypes::Driver,
        );
    }
    public function isModerator(): Attribute
    {
        return Attribute::make(
            fn() => $this->type === UserTypes::Moderator,
        );
    }

    public function scopeFilter(Builder $builder, UserTypes $userType = UserTypes::Customer, ?object $filter)
    {
        // $builder->when($userType->value, function ($builder, $value) {
        //     $value = strtolower($value);
        //     if (Auth::user()->is_admin) {
        //         if (UserTypes::tryFrom($value)) {
        //             return $builder->where('type', $value);
        //         }
        //         return;
        //     }
        //     $builder->where('type', UserTypes::Customer->value);
        // });
        if (Auth::user()->is_admin) {
            $builder->where('type', $userType->value);
        } elseif (
            Auth::user()->is_moderator &&
            !in_array($userType->value, UserTypes::getAllTypes(UserTypes::Moderator->value))
        ) {
            $builder->where('type', $userType->value);
        } elseif (Auth::user()->is_driver) {
            $builder->where('type', UserTypes::Customer->value);
        }
        $builder->when($filter?->search ?? false, function ($builder, $value) {
            $builder->where(function ($builder) use ($value) {
                $builder->whereLike('email', "%{$value}%")
                    ->orWhereLike('first_name', "%{$value}%")
                    ->orWhereLike('last_name', "%{$value}%");
            });
        });

        $builder->when($filter?->sort_by ?? 'created_at', function ($builder, $value) use ($filter) {
            $value = strtolower($value);
            $orderBy = strtolower($filter->sortingOrder ?? 'desc') == 'desc' ? 'desc' : 'asc';
            if (in_array($value, ['created_at', 'last_active_at', 'email'])) {
                $builder->orderBy($value, $orderBy);
            } else if ($value == 'name') {
                $builder->orderBy('first_name', $orderBy)->orderBy('last_name', $orderBy);
            }
        });
    }
}
