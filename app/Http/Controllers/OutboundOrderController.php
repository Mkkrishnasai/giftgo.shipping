<?php

namespace App\Http\Controllers;

use App\Models\OutboundOrder;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use Yajra\DataTables\DataTables;

class OutboundOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function datatable()
    {
        $outboundorder = OutboundOrder::get();
        return DataTables::of($outboundorder)
            ->editColumn('Order_ID',function (OutboundOrder $data) {
                return $data->Order_ID;
            })
            ->editColumn('Username', function (OutboundOrder $data) {
                return $data->Username;
            })
            ->editColumn('Date_Added', function (OutboundOrder $data) {
                return Carbon::parse($data->Date)->format('m-d-Y');
            })
            ->editColumn('Added_by', function (OutboundOrder $data) {
                return $data->Added_By;
            })
            ->editColumn('Date_updated', function (OutboundOrder $data) {
                return Carbon::parse($data->Date_Updated)->format('m-d-Y');
            })
            ->editColumn('updated_by', function (OutboundOrder $data) {
                return $data->Updated_By;
            })
            ->editColumn('company_name', function (OutboundOrder $data) {
                return $data->Company;
            })
            ->editColumn('special_instruction', function (OutboundOrder $data) {
                return $data->Special_Instruction;
            })
            ->editColumn('order_status', function (OutboundOrder $data) {
                return $data->Status;
            })
            ->addColumn('action',function () {
                return '<a class="btn btn-sm btn-success"><i style="color: #fff" class="fa fa-eye"></i></a>
                        <a class="btn btn-sm btn-primary"><i style="color: #fff" class="fa fa-pen"></i></a>
                        <a class="btn btn-sm btn-primary"><i style="color: #fff" class="fa fa-info-circle"></i></a>';
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function index()
    {
        $users = User::where('user_level','!=',-1)->get();
        return view('admin/outbound',compact('users'));
    }

    public function storeCSVFile(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(), [
                'csv' => 'required|mimes:csv',
                'Username' => 'required'
            ],[
                'csv.required' => 'Please Select a csv file',
                'csv.mimes' => 'Please choose only csv file',
                'Username.required' => 'Please select Username'
            ]);

            if($validator->fails())
            {
                throw new \Exception($validator->errors()->first(), 1);
            }

            


            return response()->json('success');
        }catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
