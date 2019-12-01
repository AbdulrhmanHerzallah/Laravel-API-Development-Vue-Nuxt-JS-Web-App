<?php

namespace App\Model;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{

    protected $table = 'likes';

    public function likeable(){
        return $this->morphTo();
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

}
