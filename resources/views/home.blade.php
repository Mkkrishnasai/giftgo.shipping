@extends('layouts.header')

<style>
    .customer {
        background-repeat: no-repeat; background-position: 0 0; width: 64px; height: 64px; display: inline-block;
    }
    .dashboard-circle {
        width: 195px;
        height: 195px;
        border: 2px solid #aaa;
        padding: 25px;
        margin-top: 10px;
        text-align: center;
        -webkit-border-radius: 50%;
        -moz-border-radius: 50%;
        border-radius: 50%;
        display: table-cell;
        vertical-align: middle;
        text-decoration: none;
        cursor: pointer;
    }
    .dashboard-heading {
        font-size: 15px;
        text-transform: uppercase;
        color: #333;
        margin-top: 65px;
        margin-bottom: 10px;
    }
</style>
@section('section')

            <div class="container" style="margin-top: 10%">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3 block">
                        <div class="dashboard-circle">
                            <div class="customer" style="background-image: url({{ asset('assets/customer.png') }})">
                                <div class="dashboard-heading">
                                    <strong>GYFTGO'S CUSTOMER</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 block">
                        <div class="dashboard-circle">
                            <div class="customer" style="background-image: url({{ asset('assets/stock-item.png') }})">
                                <div class="dashboard-heading">
                                    <strong>Stock Items</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 block">
                        <div class="dashboard-circle">
                            <div class="customer" style="background-image: url({{ asset('assets/Inboundorder.png') }})">
                                <div class="dashboard-heading">
                                    <strong>Inbound Order</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 block">
                        <div class="dashboard-circle">
                            <div class="customer" style="background-image: url({{ asset('assets/OutboundOrder.png') }})">
                                <div class="dashboard-heading">
                                    <strong>Outbound order</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

@endsection
