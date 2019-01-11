<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    //
    protected $fillable = ['from', 'to', 'text'];

    public function sender() {
        return $this->belongsTo(User::class, 'from');
    }
}
