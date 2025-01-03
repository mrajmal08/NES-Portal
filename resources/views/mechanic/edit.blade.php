@extends('layouts.app')
@section('content')
@include('layouts.sidebar')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Update Mechanic</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Mechanic</li>
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
                            <h3 class="card-title">Update Mechanic</h3>
                        </div>
                        <form method="POST" action="{{ route('mechanic.update', [$mechanic->id]) }}">
                            @csrf
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md col-sm mb-2">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" name="name" value="{{ $mechanic->name }}" placeholder="Name" required>
                                    </div>
                                    <div class="col-md col-sm mb-2">
                                        <label for="type">Type</label>
                                        <input type="text" class="form-control" name="type" value="{{ $mechanic->type }}" placeholder="Type">
                                    </div>
                                    <div class="col-md-4 col-sm-12 mb-2">
                                        <label for="phone">Phone</label>
                                        <input type="text" class="form-control" name="phone" value="{{ $mechanic->phone }}" placeholder="Phone">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12 mb-2">
                                    <label for="address">Address</label>
                                    <input type="text" class="form-control" name="address" value="{{ $mechanic->address }}" placeholder="address">
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
