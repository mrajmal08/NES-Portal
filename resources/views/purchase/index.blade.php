@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@9.17.0/dist/sweetalert2.min.css">

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
                    <h1>All Vendor Purchases</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Vendor Purchase</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="row m-4">
                            <div class="col-md-4 col-sm-12 mb-2">
                                <a href="{{ route('purchase.create') }}" class="btn btn-success">Add Purchase</a>
                            </div>
                            <div class="col-md-8 col-sm-12 ms-auto float-md-end">
                                <form method="GET" action="{{ route('purchase.index') }}" class="row g-2">
                                    <div class="col-md-4 col-sm-12">
                                        <!-- <select class="form-control" name="company">
                                            <option disabled selected>Filter By Vendor</option>
                                            @foreach ($vendors as $vendor)
                                            <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                            @endforeach
                                        </select> -->
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <select class="form-control" name="vendor">
                                            <option disabled selected>Filter By Vendor</option>
                                            @foreach ($vendors as $vendor)
                                            <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 col-sm-12 d-flex justify-content-between">
                                        <button type="submit" class="btn btn-primary w-100 me-2">Filter</button>
                                        <a href="{{ route('purchase.index') }}" class="btn btn-secondary w-100">Cancel</a>
                                    </div>
                                </form>
                            </div>
                        </div>


                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Purchase ID</th>
                                        <th>Vendor</th>
                                        <th>Date</th>
                                        <th>Notes</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($purchase as $item)
                                    <tr>
                                        <td>#00{{ $item->id }}</td>
                                        <td>{{ $item->vendor->name ?? 'N/A' }}</td>
                                        <td>{{ $item->created_at }}</td>
                                        <td>{{ $item->notes }}</td>
                                        <td>
                                            <a href="{{ route('purchase.view', [$item->id]) }}" class="btn btn-sm btn-info my-2 mr-1">
                                                <i class="fas fa-eye">
                                                </i>
                                                View
                                                <a href="{{ route('purchase.edit', [$item->id]) }}" class="btn btn-sm btn-warning my-2 mr-1">
                                                    <i class="fas fa-pencil-alt">
                                                    </i>
                                                    Edit

                                                    <a href="javascript:void(0)"
                                                        class="btn btn-sm btn-danger my-2 delete-record"
                                                        data-id="{{ $item->id }}">
                                                        <i class="fas fa-trash">
                                                        </i>
                                                        Delete </a>
                                        </td>
                                    </tr>
                                    @endforeach

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Purchase ID</th>
                                        <th>Vendor</th>
                                        <th>Date</th>
                                        <th>Notes</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@include('layouts.footer')

<aside class="control-sidebar control-sidebar-dark">
</aside>
</div>

@push('js')

<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
<!-- AdminLTE App -->
<!-- <script src="{{ asset('dist/js/adminlte.min.js') }}"></script> -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9.17.0/dist/sweetalert2.min.js"></script>

<script src="{{ asset('plugins/ekko-lightbox/ekko-lightbox.min.js') }}"></script>


<script>
    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "ordering": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    });
</script>


<script>
    $(document).ready(function() {
        $('.delete-record').click(function(e) {
            e.preventDefault(); // Prevent default behavior of the anchor tag

            var id = $(this).data('id'); // Get the ID from the data attribute
            var row = $(this).closest('tr'); // Identify the corresponding row for deletion

            Swal.fire({
                title: 'Are you sure?',
                text: "You want to delete this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('purchase.delete', ':id') }}".replace(':id', id),
                        type: 'GET',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire(
                                    'Deleted!',
                                    response.success,
                                    'success'
                                );

                                // Remove the row without reloading the page
                                row.fadeOut(500, function() {
                                    $(this).remove();
                                });
                            } else {
                                Swal.fire(
                                    'Error!',
                                    response.error || 'Something went wrong.',
                                    'error'
                                );
                            }
                        },
                        error: function() {
                            Swal.fire(
                                'Error!',
                                'Unable to delete the record. Please try again later.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    });
</script>

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
