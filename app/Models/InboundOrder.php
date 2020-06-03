<?php

namespace App\Models;

use Alexmg86\LaravelSubQuery\Traits\LaravelSubQueryTrait;
use Illuminate\Database\Eloquent\Model;

class InboundOrder extends Model
{
    use LaravelSubQueryTrait;
    public $timestamps = false;
    protected $table = 'a_inboundorder';

    public function inboundOrderDetail()
    {
        return $this->hasMany(InboundOrderDetail::class, 'Shipping_ID','ID');
    }

    public function InboundIncommingOrders()
    {
        return $this->hasMany(InboundIncomingOrder::class,'Incoming_Order_Details_ID','ID');
    }
}
