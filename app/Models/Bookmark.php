<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'recipe_id'
    ];

    //inverse relationship: a bookmark belongs to a user

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //relationship: bookmark has recipe

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
}
