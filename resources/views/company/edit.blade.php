@extends('layouts.app')
@section('content')
@include('layouts.sidebar')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Update Company</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Company</li>
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
                            <h3 class="card-title">Update Company</h3>
                        </div>
                        <form method="POST" action="{{ route('company.update', [$company->id]) }}">
                            @csrf
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-4 col-sm-12">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" name="name" value="{{ $company->name }}" placeholder="Name" required>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <label for="phone">Phone</label>
                                        <input type="number" class="form-control" name="phone" value="{{ $company->phone }}" placeholder="Phone Number">
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <label for="address">Address</label>
                                        <input type="text" class="form-control" name="address" value="{{ $company->address }}" placeholder="Address">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-sm-12 mt-2">
                                        <label for="status">Status</label>
                                        <select name="status" class="form-control">
                                            <option disabled {{ empty($company->status) ? 'selected' : '' }}>--Select One--</option>
                                            <option value="active" {{ $company->status == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ $company->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 col-sm-12 mt-2">
                                        <label for="vat_no">Vat No</label>
                                        <input type="text" class="form-control" name="vat_no" value="{{ $company->vat_no }}" placeholder="">
                                    </div>
                                    <div class="col">
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
