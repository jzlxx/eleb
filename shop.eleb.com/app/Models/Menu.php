<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    //

    protected $fillable = ['goods_name','goods_price','description','tips','goods_img','category_id','shop_id','month_sales','rating_count','satisfy_count','satisfy_rate','rating'];

    public function Menucategory()
    {
        return $this->belongsTo(MenuCategory::class,'category_id','id');
    }
}
