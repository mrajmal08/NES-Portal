@extends('layouts.app')

@push('css')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.css">
<script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.js"></script>

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
                                        <select name="product_id[]" id="choices-multiple-remove-button" class="form-control" multiple>
                                            <option disabled>--Select One--</option>
                                            @foreach ($product as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md col-sm mb-2">
                                        <label for="service_id">Select Service</label><span style="color: red;">*</span>
                                        <select name="service_id[]" id="choices-multiple-remove-button" class="form-control" multiple>
                                            <option disabled>--Select One--</option>
                                            @foreach ($service as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <h2>Vehicle Details:</h2>
                                </div>
                                <div class="row">
                                    <div class="col-md col-sm mb-2">
                                        <label for="name">Car Picture</label>
                                        <input type="file" class="form-control" name="car_picture" placeholder="Car Picture" required>
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

        var multipleCancelButton = new Choices('#choices-multiple-remove-button', {
            removeItemButton: true,
        });


    });
</script>

@endpush
@endsection
