@extends('layouts.app')
@section('content')
@include('layouts.sidebar')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Update Service</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Service</li>
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
                            <h3 class="card-title">Update Service</h3>
                        </div>
                        <form method="POST" action="{{ route('service.update', [$service->id]) }}">
                            @csrf
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md col-sm mb-2">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" name="name" value="{{ $service->name }}" placeholder="Name" required>
                                    </div>
                                    <div class="col-md col-sm mb-2">
                                        <label for="price">Price</label>
                                        <input type="number" class="form-control" name="price" value="{{ $service->price }}" placeholder="Price" step="0.01" min="0">
                                    </div>
                                    <div class="col-md col-sm mb-2">
                                        <label for="status">Status</label>
                                        <select name="status" class="form-control">
                                            <option disabled {{ empty($service->status) ? 'selected' : '' }}>--Select One--</option>
                                            <option value="active" {{ $service->status == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ $service->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md col-sm mb-2">
                                        <label for="remarks">Remarks</label>
                                        <input type="text" class="form-control" name="remarks" value="{{ $service->remarks }}" placeholder="Write Something ...">
                                    </div>
                                    <div class="col-md col-sm mb-2">

                                    </div>
                                    <div class="col-md col-sm mb-2">
                                    </div>
                                </div>
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        name="checked"
                                        value="1"
                                        id="remarksCheckbox"
                                        type="checkbox"
                                        {{ $service->checked == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remarksCheckbox">Checkbox</label>
                                    <span style="color: gray;">(optional)</span>
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
