<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nav extends Model
{
    //
    protected $fillable = ['name','pid','url','permission_id'];
}
