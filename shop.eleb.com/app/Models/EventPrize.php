<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventPrize extends Model
{
    //
    protected $fillable = ['name','events_id','description','member_id'];
}
