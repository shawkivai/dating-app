<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBehaviour extends Model
{
    use HasFactory;

    protected $table = 'user_behaviours';

    protected $fillable = [
        'user_id', 'like_dislike_user_id', 'is_liked', 'is_disliked'
    ];

}
