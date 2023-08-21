<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $with = ['user', 'comments'];

    protected $fillable = [
        'content',
        'image',
        'tags' ,
        'user_id',
        'post_id' 
    ];

    public function comments(){
        return $this->hasMany(Comment::class); 
    }

    public function user(){
        return $this->belongsTo(User::class); 
    }
}
