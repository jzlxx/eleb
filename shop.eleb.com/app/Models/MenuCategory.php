<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuCategory extends Model
{
    //
    protected $fillable = ['name','description','type_accumulation','shop_id','is_selected'];
}
