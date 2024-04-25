<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    public function addstate(){
        return $this->hasOne(State::class,'id','state');
    }

    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }

}
