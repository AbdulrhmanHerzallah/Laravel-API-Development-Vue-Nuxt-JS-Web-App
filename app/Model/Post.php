<?php

namespace App\Model;

use App\User;
use App\Model\Topic;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';


    protected $fillable = ['body'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function posts(){
        return $this->belongsTo(Topic::class);
    }

    public function likes(){
        return $this->morphMany(Like::class,'likeable');
    }

}
