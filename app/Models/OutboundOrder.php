<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutboundOrder extends Model
{
    protected $table = 'outbound_order';
    public $timestamps = false;
    protected $fillable = ['Order_ID'
                    ,'Date'
                    ,'Shipping_Method'
                    ,'Company'
                    ,'Username'
                    ,'Date_Added'
                    ,'Added_By'
                    ,'Date_Updated'
                    ,'Updated_By'
                    ,'status'
                    ,'Entry_Type'];
}
