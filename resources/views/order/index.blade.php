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
                    <h1>Order Managements</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Order Managements</li>
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
                        <div class="card-header">
                            <a href="{{ route('order.create') }}" class="btn btn-success">Add New Order</a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped table-responsive">
                                <thead>
                                    <tr>
                                        <th>Car Picture</th>
                                        <th>Company</th>
                                        <th>Mechanics</th>
                                        <th>Products</th>
                                        <th>Services</th>
                                        <th>Date/Time</th>
                                        <th>Vehicle No</th>
                                        <th>Client Name</th>
                                        <th>Client Phone</th>
                                        <th>Status</th>
                                        <th>Total Price</th>
                                        <th>Notes</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $item)
                                    <tr>
                                        <td>
                                            <div class="">
                                                <a href="{{ asset('images/car_pictures') . '/' . $item->car_picture }}?text=1"
                                                    data-toggle="lightbox"
                                                    data-title="{{ $item->date }}"
                                                    data-gallery="gallery">
                                                    <img src="{{ asset('images/car_pictures') . '/' . $item->car_picture }}?text=1"
                                                        class="img-fluid" alt="{{ $item->date }}"
                                                        style="width:40px" />
                                                </a>
                                            </div>
                                        </td>

                                        <td>{{ $item->company->name ?? 'N/A' }}</td>
                                        <td>{{ $item->mechanic->name ?? 'N/A' }}</td>
                                        <td>
                                            <ul>
                                                @foreach ($item->products as $product)
                                                <li>{{ $product->name }}</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>
                                            <ul>
                                                @foreach ($item->services as $service)
                                                <li>{{ $service->name }}</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>{{ $item->date }}</td>
                                        <td>{{ $item->vehicle_no }}</td>
                                        <td>{{ $item->client_name }}</td>
                                        <td>{{ $item->client_phone }}</td>
                                        <td>{{ $item->status }}</td>
                                        <td>{{ $item->total_price }}</td>
                                        <td>{{ $item->notes }}</td>
                                        <td>
                                            <a href="{{ route('order.edit', [$item->id]) }}" class="btn btn-sm btn-warning my-2 mr-1">
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
                                        <th>Car Picture</th>
                                        <th>Company</th>
                                        <th>Mechanics</th>
                                        <th>Products</th>
                                        <th>Services</th>
                                        <th>Date/Time</th>
                                        <th>Vehicle No</th>
                                        <th>Client Name</th>
                                        <th>Client Phone</th>
                                        <th>Status</th>
                                        <th>Total Price</th>
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
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9.17.0/dist/sweetalert2.min.js"></script>
<script src="{{ asset('plugins/ekko-lightbox/ekko-lightbox.min.js') }}"></script>

<script>
    $(function() {
        $("#example1").DataTable({
            "lengthChange": false,
            "autoWidth": true,
            "ordering": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            "processing": true,
            "language": {
                "processing": "<div class='dataTables-loader'>Loading...</div>"
            }
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
                        url: "{{ route('order.delete', ':id') }}".replace(':id', id),
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
