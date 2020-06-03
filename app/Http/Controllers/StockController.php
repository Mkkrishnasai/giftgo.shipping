<?php

namespace App\Http\Controllers;

use App\Models\InboundOrder;
use App\Models\InboundOrderDetail;
use App\Models\StockItems;
use Illuminate\Http\Request;
use DB;

class StockController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function datatable()
    {
        $datas = StockItems::get();
        $arr = [];
        foreach ($datas as $data)
        {
            array_push($arr,$this->getStockData($data));
        }
        dd($arr);
    }

    public function getStockData($stock)
    {
        DB::connection()->enableQueryLog();
        $inboundQty = 0;
        $outboundQty = 0;
        $inQty = InboundOrderDetail::where('Stock_Item',$stock->Stock_ID)->pluck('Quantity');
        if($inQty)
        {
            foreach ($inQty as $q){
                $inboundQty = $inboundQty+$q;
            }
        }

        $leftQty2 = $incomingQty = 0;
        $leftQty = $stock->Quantity-$stock->Defect_Items_Stock;

        if($stock->Stock_Item_Type != 'complete')
        {

            $data = DB::select('SELECT SUM(IIOR.Open_Quantity) total_quantity
                                            FROM a_inboundorder INORD
                                            INNER JOIN `a_inboundorder_detail` ODES ON INORD.ID=ODES.Shipping_ID
                                            INNER JOIN `inbound_incoming_order` IIOR ON ODES.ID=IIOR.Incoming_Order_Details_ID
                                            WHERE INORD.Status="Received" AND ODES.Stock_Item="'.$stock->Stock_ID.'"');
            $incomingQty = $data[0]->total_quantity;
        }

        $leftQty1_pending = 0;

        $outstockSql = DB::select('SELECT sum(Quantity)
                                        total_quantity FROM  `outbound_order_detail` where `Stock_Item`="'.$stock->Stock_ID.'"');

        $total_quantity = $outstockSql[0]->total_quantity;

        $outstockPndSql = DB::select('SELECT SUM(OIORD.Open_Quantity)
                                        total_open_quantity
                                         FROM outbound_order OUTORD
                                         INNER JOIN `outbound_order_detail` ODES ON OUTORD.Outboundorder_ID=ODES.Shipping_ID
                                         INNER JOIN `outbound_incoming_order` OIORD ON ODES.ID=OIORD.Outbound_Order_Details_ID
                                          WHERE OUTORD.Status="Shipped" AND ODES.Stock_Item="'.$stock->Stock_ID.'"');

        $total_open_quantity = $outstockPndSql[0]->total_open_quantity;

        $leftQty1_pending = $total_quantity - $total_open_quantity;

        $outstockSql = DB::select('SELECT SUM(OIORD.Open_Quantity) total_quantity FROM outbound_order OUTORD
                                      INNER JOIN `outbound_order_detail` ODES ON OUTORD.Outboundorder_ID=ODES.Shipping_ID
                                      INNER JOIN `outbound_incoming_order` OIORD ON ODES.ID=OIORD.Outbound_Order_Details_ID
                                      WHERE OUTORD.Status="Shipped" AND ODES.Stock_Item="'.$stock->Stock_ID.'"');

        echo $leftQty1 = (!empty($outstockSql[0]->total_quantity)) ? $outstockSql[0]->total_quantity : 0;
        echo '...';

        if($stock->Stock_Item_Type == 'raw')
        {
            $outstockSql = DB::select('SELECT SUM(assamlbe_quantity) assamlbe_quantity FROM a_complete_stock WHERE Stock_Raw_ID="'.$stock->Stock_ID.'"');
            echo $leftQty2 = (!empty($outstockSql[0]->assamlbe_quantity)) ? $outstockSql[0]->assamlbe_quantity : 0;
            echo '///////';
        }
        echo $stock->Quantity;
        echo 'stqty';
        echo $incomingQty;
        echo '...';
        echo $leftQty = ($stock->Quantity + $incomingQty) - ($leftQty1 + $leftQty2 + $stock->Defect_Items_Stock);

        $item_required = 0;

        if($leftQty < 0){
            $item_required = ($leftQty1_pending + $leftQty1 + $leftQty2 + $stock->Defect_Items_Stock) - ($stock->Quantity + $incomingQty);

            if($leftQty < $leftQty1_pending)
            {
                $item_required = $leftQty1_pending - $leftQty;
            }
            else if($leftQty == 0)
            {
                if($leftQty < $leftQty1_pending)
                {
                    $item_required = $leftQty1_pending - $leftQty;
                }
            }else if($leftQty > 0)
            {
                if($leftQty < $leftQty1_pending)
                {
                    $item_required = $leftQty1_pending  - $leftQty;
                }
            }
        }
        dd('hello');
        return ['sku' => $stock->Stock_Number,
                'Username' => $stock->Username,
                'item_name' => $stock->Stock_Name,
                'Stock_item_type' => $stock->Stock_Item_Type,
                'Defect_Items_Stock' => $stock->Defect_Items_Stock,
                'in_stock' => ($leftQty && $leftQty != '') ? $leftQty : 0,
                'stock_used' => ($leftQty1+$leftQty2) ? $leftQty1+$leftQty2 : 0,
                'qty_on_orders' => $leftQty1_pending ? $leftQty1_pending : 0,
                'stock_req' => $item_required ? $item_required : 0,
                'Weight' => $stock->Weight,
                'reorder_lever' => $stock->reorder_level,
            ];



    }

}
