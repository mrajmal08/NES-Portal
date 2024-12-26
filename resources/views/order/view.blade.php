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
                    <!-- Main content -->
                    <div class="invoice p-3 mb-3">
                        <!-- title row -->
                        <div class="row">
                            <div class="col-12">
                                <h4>
                                    <i class="fas fa-globe"></i> NEC Portal. Order ID: #00{{ $order->id }}
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
                                <b>Delivery Date:</b> {{ $order->delivery_date }}<br>
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 invoice-col mb-3">
                                <b>Vehicle Details:</b><br>
                                <b>Vehicle Name:</b> {{ $order->vehicle_name }}<br>
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
                                            <th>Product No</th>
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
                                            <td>{{ $product->product_no }}</td>
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
                        </div>

                        <h3 class="mb-4">Order Images:</h3>
                        <div class="row g-2 mb-4">
                            @foreach(explode(',', $order->car_picture) as $image)
                            <div class="col-4">
                                <div class="gallery-item gray">
                                    <a href="{{ asset('images/car_pictures/' . $image) }}?text=1"
                                        data-toggle="lightbox"
                                        data-title="{{ $order->date }}"
                                        data-gallery="gallery">
                                        <img src="{{ asset('images/car_pictures/' . $image) }}?text=1"
                                            class="img-fluid" alt="{{ $order->date }}"
                                            style="width:40px" />
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="row">
                            <div class="col-md-8 col-sm-12">
                                <p class="lead"><b>Order By:</b> <span>{{ auth()->user()->name }}</span></p>
                                <p class="lead"><b>Order Note:</b> <span>{{ $order->notes }}</span></p>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <p class="lead"><b>Total Price:</b> <span class="float-md-right">{{ $order->total_price }}</span></p>
                                <p class="lead mt-3"><b>Signature:</b> <span class="float-md-right">________________________</span></p>
                            </div>
                        </div>

                        <div class="row no-print">
                            <div class="col-12">
                                <button class="btn btn-primary float-right" onclick="window.print()">
                                    <i class="fas fa-print"></i> Print
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<aside class="control-sidebar control-sidebar-dark">
</aside>
</div>

@push('js')

<script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
<!-- <script src="../../plugins/jquery/jquery.min.js"></script> -->
<script src="{{ asset('plugins/ekko-lightbox/ekko-lightbox.min.js') }}"></script>


<script>
    $(function() {
        $(document).on('click', '[data-toggle="lightbox"]', function(event) {
            event.preventDefault();
            $(this).ekkoLightbox({
                alwaysShowClose: true
            });
        });

        $('.filter-container').filterizr({
            gutterPixels: 3
        });
        $('.btn[data-filter]').on('click', function() {
            $('.btn[data-filter]').removeClass('active');
            $(this).addClass('active');
        });
    })
</script>

@endpush
@endsection
