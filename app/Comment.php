<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //
    protected $fillable = ['body'];

    // $comment->post
    public function post() {
      return $this->belognsTo('App\Post');
    }
}
