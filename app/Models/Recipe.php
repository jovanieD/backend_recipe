<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "description",
        "ingredients",
        "procedures",
        "tag",
        "category",
        'price',
        "video_url",
        "img_url",
        "user_id",
        "status"
    ];

    protected $casts=[
        "ingredients"=>"array",
        "procedures"=>"array",
        "img"=>"array",
        "tag"=>"array"
    ];


    //inverse relationship: recipe belongs to the a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function purchasedrecipes()
    {
        return $this->hasMany(PurchasedRecipe::class);
    }



}
