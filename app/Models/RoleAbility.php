<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoleAbility extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'status',
        'ability',
        'role_id',
    ];
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
    // public function getUpdatedAtColumn(): null
    // {
    //     return null;
    // }
}
