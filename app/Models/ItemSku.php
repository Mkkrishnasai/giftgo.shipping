<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemSku extends Model
{
    protected $table = 'item_sku';
    public $timestamps = false;

    public function stock_items()
    {
        return $this->hasOne(StockItems::class, 'Stock_ID','Item_ID');
    }
}
