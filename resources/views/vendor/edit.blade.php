@extends('layouts.app')
@section('content')
@include('layouts.sidebar')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Update Vendor</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Vendor</li>
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
                            <h3 class="card-title">Update Vendor</h3>
                        </div>
                        <form method="POST" action="{{ route('vendor.update', [$vendor->id]) }}">
                            @csrf
                            <div class="card-body">

                                <div class="row">
                                    <div class="col">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" name="name" value="{{ $vendor->name }}" placeholder="Name" required>
                                    </div>
                                    <div class="col">
                                        <label for="phone">Phone</label>
                                        <input type="number" class="form-control" name="phone" value="{{ $vendor->phone }}" placeholder="Phone" step="0.01" min="0">
                                    </div>
                                    <div class="col">
                                        <label for="address">Address</label>
                                        <input type="text" class="form-control" name="address" value="{{ $vendor->address }}" placeholder="Address">
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
@endsection
