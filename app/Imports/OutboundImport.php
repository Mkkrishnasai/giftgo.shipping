<?php


namespace App\Imports;

use App\Models\ItemSku;
use App\Models\OutboundOrder;
use App\Models\OutboundOrderDetail;
use App\Models\ShippingMethod;
use App\Models\StockItems;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use DB;

class OutboundImport implements ToCollection,WithHeadingRow
{
    protected $username;
    public $mismatch_sku;
    public $mismatch_shipping;

    public function __construct($username)
    {
        $this->username = $username;
    }

    public function collection(Collection $rows)
    {
//        $rows->each(function($row) {
//
//            $shipdate = $row['ship_date']->format('Y-m-d');
//
//        });
        $mismatched_sku = '';
        $mismatchedShipng = '';
        $entry_type = 'Shipstation';

        $datas = $rows->slice(1);

        foreach ($datas as $index=>$row)
        {
            $SKU_id = '';
            $error = 0;
            $order_id 	    = trim($row['order_id']);
            $sku 		    = trim($row['sku']);
            $quantity 	    = trim($row['quantity']);
            $shipdate 	    = Date::excelToDateTimeObject($row['ship_date'])->format('Y-m-d');
            $shipto 	    = trim($row['store_name']);
            $shipingmethod      = trim($row['shipping_method']);
            $item_id 		= $method_id = '';
            if($sku != '')
            {
                $items = $this->getItemdata($this->username, $sku);

                if($items['exists'] == 'true'){
                    $data = $items['data'];
                    $item_id = $data->Item_ID;
                    $SKU_id = $data->SKU_id;
                }else{
                    $items = $this->getItemDataFromStockItems($this->username, $sku);
                    if($items['exists'] == 'true')
                    {
                        $item_id = $items['data'];
                    }else{
                        $error = 1;
                        $mismatched_sku .= $index.',';
                    }
                }
                $method_id = $this->checkShippingMethod($shipingmethod);
                if($method_id['exists'] == 'true')
                {
                    $method_id = $method_id['data'];
                }else{
                    $method_id = '';
                    $error = 1;
                    $shippingMth = 1;
                    $mismatchedShipng .= $index.",";
                }

                if($error != 1)
                {
                    $insert =  $this->insertOrder($order_id,$item_id,$quantity,$SKU_id,$shipdate,$shipto,$entry_type);
                    if($insert)
                    {
                        Log::info('success');
                        return 'hello';
                    }
                }

            }
        }
        $this->mismatch_sku = $mismatched_sku;
        $this->mismatch_shipping = $mismatchedShipng;
        return true;
    }


    public function getReturnValue()
    {
        return [
            'mismatch_sku' => $this->mismatch_sku,
            'mismatch_shipping' => $this->mismatch_shipping
        ];
    }
    public function getItemdata($username, $sku)
    {
        $data = ItemSku::with('stock_items')
            ->whereHas('stock_items', function ($q) use ($username) {
                return $q->where('Username',$username);
            })->where('SKU',$sku)->first();
        if($data)
        {
            return ['exists' => 'true' , 'data' => $data];
        }
        return ['exists' => 'false' , 'data' => ''];
    }


    public function getItemDataFromStockItems($username, $sku)
    {
//        SELECT Stock_ID FROM a_stock_items WHERE LOWER(`Stock_Number`)='".strtolower($sku)."' AND Username='".$upload_username."'"

        $data = StockItems::where('Stock_Number',$sku)
                            ->where('Username',$username)
                            ->pluck('Stock_ID')
                            ->first();
        if($data)
        {
            return ['exists' => 'true', 'data' => $data];
        }
        return ['exists' => 'false', 'data' => ''];
    }

    public function checkShippingMethod($shipping_method)
    {
        $data = ShippingMethod::where('Method_Name',$shipping_method)->pluck('Method_ID')->first();
        if($data)
        {
            return ['exists' => 'true', 'data' => $data];
        }
        return ['exists' => 'false', 'data' => ''];
    }

    public function insertOrder($order_id,$item_id,$quantity,$sku_id,$shipdate,$shipto,$entry_type)
    {
        try{
            DB::BeginTransaction();

            $order = OutboundOrder::where('Order_ID',$order_id)->first();
            if($order)
            {
                $outbound_id = $order->Outboundorder_ID;
                $orderdetails = OutboundOrder::where('Outboundorder_ID',$outbound_id)->update(['status' => 'Pending']);

                $order_exit = OutboundOrderDetail::where('order_id',$order_id)->where('Stock_Item',$item_id)->select('ID','Quantity')->first();

                if($order_exit)
                {
                    $outbounddetails_id = $order_exit->ID;
                    $Quantity = $order_exit->Quantity;
                    $updtdQty = $Quantity+$quantity;

                    $outbounddetails = OutboundOrderDetail::where('ID',$outbounddetails_id)
                        ->update([
                            'Quantity' => $updtdQty,
                            'Stock_sku_id' => $sku_id
                        ]);

                }else{
                    $outbounddetails_id = OutboundOrderDetail::create([
                        'Shipping_ID' => $outbound_id,
                        'Order_ID' => $order_id,
                        'Stock_Item' => $item_id,
                        'Stock_sku_id' => $sku_id,
                        'Quantity' => $quantity,
                    ])->id;

                }
            }else{
                $method_id = 0;
                $outbound_id = OutboundOrder::create([
                    'Order_ID' => $order_id,
                    'Date' => Carbon::parse($shipdate),
                    'Shipping_Method' => $method_id,
                    'Company' => $shipto,
                    'Username'=> $this->username,
                    'Date_Added'=> Carbon::now()->format('Y-m-d'),
                    'Added_By'=> Auth::user()->Username,
                    'Date_Updated' => Carbon::now()->format('Y-m-d'),
                    'Updated_By' => Auth::user()->Username,
                    'status' => 'Pending',
                    'Entry_Type' => $entry_type,
                ])->id;

                $outbounddetails_id = OutboundOrderDetail::create([
                    'Shipping_ID' => $outbound_id,
                    'Order_ID' => $order_id,
                    'Stock_Item' => $item_id,
                    'Stock_sku_id' => $sku_id,
                    'Quantity' => $quantity,
                ])->id;

            }
            DB::commit();
            Log::info('outbound id : '.$outbound_id. ' details_id : '.$outbounddetails_id);
            return true;
        }catch (\Exception $e)
        {
            DB::rollback();
            Log::info($e->getMessage());
            return false;
        }
    }
}

