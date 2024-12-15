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
                    <h1>Update Purchase</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Vendor Purchase</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md col-sm">

                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Update Purchase</h3>
                        </div>
                        <form method="POST" action="{{ route('purchase.update', [$purchase->id]) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md col-sm mb-2">
                                        <label for="vendor_id">Vendor</label> <span style="color: red;">*</span>
                                        <select name="vendor_id" class="form-control">
                                            <option disabled selected>--Select One--</option>
                                            @foreach ($vendor as $item)
                                            <option value="{{ $item->id }}" {{ $item->id == $purchase->vendor_id ? 'selected' : '' }}>
                                                {{ $item->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md col-sm mb-2">
                                        <label for="product_id">Product</label><span style="color: red;">*</span>
                                        <select name="product_id" class="form-control">
                                            <option disabled selected>--Select One--</option>
                                            @foreach ($product as $item)
                                            <option value="{{ $item->id }}" {{ $item->id == $purchase->product_id ? 'selected' : '' }}>
                                                {{ $item->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md col-sm mb-2">
                                        <label for="service_id">Select Service</label><span style="color: red;">*</span>
                                        <select name="service_id" class="form-control">
                                            <option disabled>--Select One--</option>
                                            @foreach ($service as $item)
                                            <option value="{{ $item->id }}" {{ $item->id == $purchase->service_id ? 'selected' : '' }}>
                                                {{ $item->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md col-sm mb-2">
                                        <label for="date">Date Time</label> <span style="color: red;">*</span>
                                        <input type="datetime-local" class="form-control" name="date" value="{{ $purchase->date }}" placeholder="Date and Time">
                                    </div>
                                    <div class="col-md col-sm mb-2">
                                        <label for="invoice_photo">Invoice Photo</label>
                                        <input type="file" class="form-control" name="invoice_photo" value="{{ $purchase->invoice_photo }}" placeholder="Invoice Photo">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md col-sm mb-2">
                                        <label for="notes">Notes</label>
                                        <textarea type="text" class="form-control" name="notes" rows="3" placeholder="Write Something...">{{ $purchase->notes }}</textarea>
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
