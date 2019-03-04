<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $fillable = ['name','user_id','tel','province','city','county','address','shop_id','total','status','out_trade_no','sn'];
}
