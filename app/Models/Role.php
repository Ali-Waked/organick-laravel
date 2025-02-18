<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class Role extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = ['name', 'slug'];
    protected static function booted()
    {
        static::saving(function (Role $role) {
            $slug = Str::slug($role->name);
            $count = Role::whereLike('slug', "$slug%")->whereNot('id', $role->id)->count();
            if ($count) {
                $slug .= '-' . $count + 1;
            }
            $role->slug = $slug;
        });
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_roles');
    }
    public function abilities()
    {
        return $this->hasMany(RoleAbility::class);
    }
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeFilter(Builder $builder, ?string $filter = null)
    {
        $builder->when($filter ?? false, function ($builder, $value) {
            $builder->whereLike('name', "%{$value}");
        });
    }
}
