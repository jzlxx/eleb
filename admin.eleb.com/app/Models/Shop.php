<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    //
    protected $fillable = ['shop_category_id','shop_name','shop_img','brand','on_time','fengniao','bao','piao','zhun','start_send','send_cost','notice','discount','shop_rating'];

    public function ShopCategory()
    {
        return $this->belongsTo(ShopCategory::class);
    }
}
