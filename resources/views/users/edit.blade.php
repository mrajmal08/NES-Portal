@extends('layouts.app')
@section('content')
@include('layouts.sidebar')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Update User</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Users</li>
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
                            <h3 class="card-title">Update User</h3>
                        </div>
                        <form method="POST" action="{{ route('users.update', [$user->id]) }}">
                            @csrf
                            <div class="card-body">

                                <div class="row">
                                    <div class="col mb-2">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" name="name" value="{{ $user->name }}" placeholder="Name" required>
                                    </div>
                                    <div class="col mb-2">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" name="email" value="{{ $user->email }}" placeholder="Email" required>
                                    </div>
                                    <div class="col mb-2">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" name="password" placeholder="Password">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <label for="phone">Phone</label>
                                        <input type="number" class="form-control" name="phone" value="{{ $user->phone }}" placeholder="Phone Number">
                                    </div>
                                    <div class="col">
                                        <label for="address">Address</label>
                                        <input type="text" class="form-control" name="address" value="{{ $user->address }}" placeholder="Address">
                                    </div>
                                    <div class="col">
                                        <label for="status">Status</label>
                                        <select name="status" class="form-control">
                                            <option disabled {{ empty($user->status) ? 'selected' : '' }}>--Select One--</option>
                                            <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ $user->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <label for="role_id">Role</label>
                                        <select name="role_id" id="role_id" class="form-control">
                                            <option disabled {{ empty($user->role_id) ? 'selected' : '' }}>--Select One--</option>
                                            @foreach ($roles as $role)
                                            <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col">
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
