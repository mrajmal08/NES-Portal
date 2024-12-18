@extends('layouts.app')

@push('css')
@endpush

@section('content')
@include('layouts.sidebar')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Invoice</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Invoice</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="callout callout-info">
                        <h5><i class="fas fa-info"></i>Note:</h5>
                        Click the print button at the bottom of the invoice to Print the Invoice of this order.
                    </div>


                    <!-- Main content -->
                    <div class="invoice p-3 mb-3">
                        <!-- title row -->
                        <div class="row">
                            <div class="col-12">
                                <h4>
                                    <i class="fas fa-globe"></i> NES Portal. Invoice No: #00{{ $order->id }}
                                    <small class="float-right">Date: {{ $order->created_at->format('d-m-Y') }}</small>
                                </h4>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- info row -->
                        <div class="row invoice-info mb-3">
                            <div class="col-sm-4 invoice-col mb-3">
                                <b>Company Name:</b> {{ $order->company->name ?? 'N/A' }}<br>
                                <b>Mechanic Name:</b> {{ $order->mechanic->name ?? 'N/A' }}<br>
                                <b>Order Status:</b> {{ $order->status }}<br>
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 invoice-col mb-3">
                                <b>Vehicle Details:</b><br>
                                <b>Vehicle No:</b> {{ $order->vehicle_no }}<br>
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 invoice-col">
                                <b>Client Details </b><br>
                                <b>Client Name:</b> {{ $order->client_name }}<br>
                                <b>Client Phone:</b> {{ $order->client_phone }}<br>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                        <h3>Products Detail:</h3>
                        <!-- Table row -->
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Quantity #</th>
                                            <th>Remarks</th>
                                            <th>Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->products as $product)
                                        <tr>
                                            <td>{{ $product->id }}</td>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->pivot->qty }}</td>
                                            <td>{{ $product->pivot->remarks }}</td>
                                            <td>{{ $product->pivot->price }}</td>
                                        </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                            <!-- /.col -->
                        </div>

                        <h3>Services Detail:</h3>
                        <!-- Table row -->
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Quantity #</th>
                                            <th>Remarks</th>
                                            <th>Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->services as $service)
                                        <tr>
                                            <td>{{ $service->id }}</td>
                                            <td>{{ $service->name }}</td>
                                            <td>{{ $service->pivot->qty }}</td>
                                            <td>{{ $service->pivot->remarks }}</td>
                                            <td>{{ $service->pivot->price }}</td>
                                        </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                            <!-- /.col -->
                        </div>

                        <div class="row">
                            <!-- accepted payments column -->
                            <div class="col-md-8 col-sm-8">
                                <p class="lead">Invoice By: <strong>{{ auth()->user()->name }}</strong></p>
                                <p class="lead">Order Note: <strong>{{ $order->notes }}</strong> </p>
                            </div>
                            <!-- /.col -->
                            <div class="col-md-3 col-sm-3 float-sm-right">
                                <b>Total Price:</b>
                                <p class="float-right">{{ $order->total_price }}</p>
                            </div>
                            <div class="col-md-1 col-sm-1">
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                        <!-- this row will not appear when printing -->
                        <div class="row no-print">
                            <div class="col-12">
                                <button class="btn btn-primary float-right" onclick="window.print()">
                                    <i class="fas fa-print"></i> Print
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- /.invoice -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
@include('layouts.footer')

<aside class="control-sidebar control-sidebar-dark">
</aside>
</div>

@push('js')

<script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
<script src="../../plugins/jquery/jquery.min.js"></script>

@endpush
@endsection
