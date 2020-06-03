<?php

namespace App\Http\Controllers;

use App\Imports\OutboundImport;
use App\Models\ItemSku;
use App\Models\OutboundOrder;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Validator;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\ExcelServiceProvider;
use Symfony\Component\Console\Input\Input;
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
            if($request->type == 'Shipstation') {
                $validator = \Validator::make($request->all(), [
                    'file' => 'required|mimes:xlsx',
                    'Username' => 'required'
                ],[
                    'file.required' => 'Please Select a xlsx file',
                    'file.mimes' => 'Please choose only xlsx file',
                    'Username.required' => 'Please select Username'
                ]);
            }elseif ($request->type == 'csv') {
                $validator = \Validator::make($request->all(), [
                    'file' => 'required|mimes:csv,txt',
                    'Username' => 'required'
                ],[
                    'file.required' => 'Please Select a csv file',
                    'file.mimes' => 'Please choose only csv file',
                    'Username.required' => 'Please select Username'
                ]);
            }

            if($validator->fails())
            {
                throw new \Exception($validator->errors()->first(), 1);
            }

            $filename = time().rand(1,4);
            $path = public_path('/storage/imports');
            $file = $request->file('file');
            $ext = $file->getClientOriginalExtension();
            if($file->move($path,$filename.'.'.$ext))
            {
                $tempfile = file(public_path('storage/imports').'/'.$filename.'.'.$ext);
            }

//            $data = $this->getItemdata($username,$sku);
//            \Maatwebsite\Excel\Facades\Excel::load()
//            Excel::load()
//            ExcelServiceProvider::load()
            $import = new OutboundImport($request->Username);
            \Maatwebsite\Excel\Facades\Excel::import($import,public_path('storage/imports').'/'.$filename.'.'.$ext);
            dd($import->getReturnValue());

            return response()->json();
        }catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
