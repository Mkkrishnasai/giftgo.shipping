<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutboundOrderDetail extends Model
{
    public $timestamps = false;
    protected $table = 'outbound_order_detail';
    protected $fillable = ['Shipping_ID'
                            ,'Order_ID'
                            ,'Stock_Item'
                            ,'Stock_sku_id'
                            ,'Quantity'];
}
