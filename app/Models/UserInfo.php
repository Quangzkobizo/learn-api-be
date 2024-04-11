<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserInfo extends Model
{
    use HasFactory;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'user_id',
        'full_name',
        'date_of_birth',
        'gender',
        'bio',
        'avatar',

    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id','user_id');
    }
}
