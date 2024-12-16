@extends('layouts.app')

@push('css')

<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/css/bootstrap-select.min.css" />
@endpush

@section('content')

@include('layouts.sidebar')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Order</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Order Management</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Add New Order</h3>
                        </div>
                        <form method="POST" action="{{ route('order.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md col-sm mb-2">
                                        <label for="company_id">Company</label> <span style="color: red;">*</span>
                                        <select name="company_id" class="form-control">
                                            <option disabled selected>--Select One--</option>
                                            @foreach ($company as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg col-md -col-sm mb-2">
                                        <label for="mechanic_id">Mechanic</label><span style="color: red;">*</span>
                                        <select name="mechanic_id" class="form-control">
                                            <option disabled selected>--Select One--</option>
                                            @foreach ($mechanic as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md col-sm mb-2">
                                        <label for="product_id">Select Product</label><span style="color: red;">*</span>
                                        <select id="product_id" name="product_id[]" class="selectpicker form-control" multiple aria-label="size 3 select example">
                                            <option disabled>--Select One--</option>
                                            @foreach ($product as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md col-sm mb-2">
                                        <label for="service_id">Select Service</label><span style="color: red;">*</span>
                                        <select id="service_id" name="service_id[]" class="selectpicker form-control" multiple aria-label="size 3 select example">
                                            <option disabled>--Select One--</option>
                                            @foreach ($service as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row" id="product-details">
                                    <div class="col-md col-sm" id="product-details-container" class="mt-3"></div>
                                    <div class="col-md col-sm" id="service-details-container" class="mt-3"></div>
                                </div>
                                <div class="row">
                                    <div class="col-md col-sm" id="product-details-price" class="mt-3"></div>
                                </div>

                                <div class="mt-2">
                                    <h2>Vehicle Details:</h2>
                                </div>
                                <div class="row">
                                    <div class="col-md col-sm mb-2">
                                        <label for="name">Car Picture</label>
                                        <input type="file" class="form-control" name="car_picture" id="car_picture" accept="image/*" capture="environment" placeholder="Car Picture" capture>
                                    </div>
                                    <div class="col-md col-sm mb-2">
                                        <label for="vehicle_no">Vehicle Number</label>
                                        <input type="text" class="form-control" name="vehicle_no" placeholder="Vehicle Number">
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <h2>Client Details:</h2>

                                </div>
                                <div class="row">
                                    <div class="col-md col-sm mb-2">
                                        <label for="client_phone">Client Phone</label><span style="color: gray;"> (optional)</span>
                                        <input type="number" class="form-control" name="client_phone" placeholder="Client Phone">
                                    </div>
                                    <div class="col-md col-sm mb-2">
                                        <label for="client_name">Client Name</label> <span style="color: gray;"> (optional)</span>
                                        <input type="text" class="form-control" name="client_name" placeholder="Client Name">
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md col-sm mb-2">
                                        <label for="notes">Notes</label>
                                        <textarea type="text" class="form-control" name="notes" rows="3" placeholder="Write Something..."></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@include('layouts.footer')
<aside class="control-sidebar control-sidebar-dark">
</aside>

@push('js')

<script>
    $(document).ready(function() {
        let totalPrice = 0;


        function updateTotalPrice() {
            $('#combined-total-price').val(totalPrice.toFixed(2));
        }

        // For products
        $('#product_id').on('change', function() {
            let productId = $(this).val();

            if (productId.length > 0) {
                $.ajax({
                    url: '/get-product-details',
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        product_id: productId
                    },
                    success: function(response) {
                        let productTotal = 0;
                        $('#product-details-container').html('');
                        response.forEach(function(product) {
                            productTotal += parseFloat(product.price);
                            let productDetail = `
                            <div class="row product-details">
                                <div class="form-group col-md col-sm">
                                    <label>Product Name</label>
                                    <input type="text" class="form-control" value="${product.name}" readonly>
                                </div>
                                <div class="form-group col-md col-sm">
                                    <label>Product Price</label>
                                    <input type="text" class="form-control" value="${product.price}" readonly>
                                </div>
                                <div class="form-group col-md col-sm">
                                    <label>Remarks</label>
                                    <input type="text" class="form-control" value="${product.remarks}" readonly>
                                </div>
                            </div>
                        `;
                            $('#product-details-container').append(productDetail);
                        });

                        totalPrice += productTotal;
                        updateTotalPrice();
                    },
                    error: function(xhr) {
                        console.error("Error fetching product details:", xhr.responseText);
                    }
                });
            } else {

                $('#product-details-container').html('');
            }
        });

        // For services
        $('#service_id').on('change', function() {
            let serviceId = $(this).val();

            if (serviceId.length > 0) {
                $.ajax({
                    url: '/get-service-details',
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        service_id: serviceId
                    },
                    success: function(response) {
                        let serviceTotal = 0;
                        $('#service-details-container').html('');
                        response.forEach(function(service) {
                            serviceTotal += parseFloat(service.price);
                            let serviceDetail = `
                            <div class="row service-details">
                                <div class="form-group col-md col-sm">
                                    <label>Service Name</label>
                                    <input type="text" class="form-control" value="${service.name}" readonly>
                                </div>
                                <div class="form-group col-md col-sm">
                                    <label>Service Price</label>
                                    <input type="text" class="form-control" value="${service.price}" readonly>
                                </div>
                                <div class="form-group col-md col-sm">
                                    <label>Remarks</label>
                                    <input type="text" class="form-control" value="${service.remarks}" readonly>
                                </div>
                            </div>
                        `;
                            $('#service-details-container').append(serviceDetail);
                        });

                        totalPrice += serviceTotal;
                        updateTotalPrice();
                    },
                    error: function(xhr) {
                        console.error("Error fetching service details:", xhr.responseText);
                    }
                });
            } else {
                $('#service-details-container').html('');
            }
        });


        let combinedTotalPriceField = `
        <div class="row combined-total-price mt-3">
            <div class="form-group col-md-12 col-sm">
                <label style="color:green">Combined Total Price</label>
                <input type="text" class="form-control" id="combined-total-price" name="total_price" value="0.00" readonly>
            </div>
        </div>
    `;
        $('#product-details-price').after(combinedTotalPriceField);
    });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/js/bootstrap-select.min.js" integrity="sha512-FHZVRMUW9FsXobt+ONiix6Z0tIkxvQfxtCSirkKc5Sb4TKHmqq1dZa8DphF0XqKb3ldLu/wgMa8mT6uXiLlRlw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

@endpush
@endsection
