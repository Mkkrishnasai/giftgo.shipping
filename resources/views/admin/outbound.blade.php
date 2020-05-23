@extends('layouts.header')

@section('section')
    <div class="row" style="margin-top: 20px;">
        <div class="col-lg-2">

        </div>
        <div class="col-lg-8" style="border: 1px solid grey">
            <div class="row">

            </div>
            <form id="csvupload" enctype="multipart/form-data">
                {{ csrf_field() }}
            <div class="row">
                    <div class="col-md-4" style="margin: 10px;">
                        <select class="selectpicker form-control" data-size="5" title="Select Username">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->Username }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4" style="margin: 10px;">
                        <input type="file" name="csv" class="form-control">
                    </div>
                    <div class="col-md-2" style="margin: 10px;">
                        <input type="submit" name="upload" class="btn btn-success" value="Upload">
                    </div>
            </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-1">

        </div>
        <div class="col-lg-12" style="margin-top: 40px;">
            <div class="table-responsive table-bordered">
                <table id="outboundordertable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th><input type="checkbox" name="checkall"></th>
                        <th>Order ID</th>
                        <th>Username</th>
                        <th>Date Added</th>
                        <th>Added By</th>
                        <th>Date Updated</th>
                        <th>Updated By</th>
                        <th>Company Name</th>
                        <th>Special Instruction</th>
                        <th>Order Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>

        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // $('#outboundordertable').DataTable();
            var table = $('#outboundordertable').DataTable({
                ordering: false,
                processing: true,
                serverSide: true,
                ajax: {
                    url : '{{ route('outbound_order_datatable') }}',
                    type : "GET",
                },
                columns: [
                    {data : 'checkbox' , render:function (checkbox) {
                            return '<input type="checkbox" name="checkbox">'
                        },'name' : 'checkbox'},
                    {data: 'Order_ID', name: 'Order_ID'},
                    {data: 'Username', name: 'Username'},
                    {data: 'Date_Added', name: 'Date_Added'},
                    {data: 'Added_by', name: 'Added_by'},
                    {data: 'Date_updated', name: 'Date_updated'},
                    {data: 'updated_by', name: 'updated_by'},
                    {data: 'company_name', name: 'company_name'},
                    {data: 'special_instruction', name: 'special_instruction'},
                    {data: 'order_status', name: 'order_status'},
                    {data: 'action', name: 'action'},

                ],
                'language': {
                    'processing': 'loading ...'
                }
            });
        });
        $(document).ready(function () {
            $('.selectpicker').selectpicker({
                liveSearch: true,
                maxOptions: 1,
            });
        });

        $(document).on('submit', '#csvupload', function (e) {
            e.preventDefault();
            var fd = new FormData(this);
            $.ajax({
                method : 'POST',
                url  : '{{ route('storecsvfile') }}',
                data : fd,
                contentType: false,
                cache: false,
                processData: false,
                success : function (data) {
                    console.log(data)
                },
                error : function (data) {
                    console.log(data);
                }
            });
        })
    </script>
@endsection
