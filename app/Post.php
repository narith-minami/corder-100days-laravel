<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //
    protected $fillable = ['title', 'body', 'category_id', 'category_name'];

    // $post->comments
    public function comments() {
      return $this->hasMany('App\Comment');
    }
}
