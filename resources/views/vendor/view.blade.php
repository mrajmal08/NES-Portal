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
                    <h1>Vendor History ( <b>{{ App\Models\Vendor::where('id', $id)->value('name') ?? 0 }}</b> )</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Vendor</li>
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
                        <div class="row align-items-cente m-4">
                            <div class="col-md-8 col-sm-12 mb-2">
                                <h4>Total Payable: <b style="color: green;">{{ $totalPayable }}</b></h4>
                            </div>
                            <div class="col-md-4 col-sm-12 ms-auto">
                                <button type="button" class="btn btn-info float-right" data-toggle="modal" data-target="#exampleModal">
                                    Pay Amount
                                </button>
                            </div>
                        </div>

                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Purchase Price</th>
                                        <th>Paid Price</th>
                                        <th>Payable</th>
                                        <th>Pay Date</th>
                                        <th>Discount</th>
                                        <th>Status</th>
                                        <th>Created Date</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($vendorHistory as $item)
                                    <tr>
                                        <td>{{ $item->purchase_price }}</td>
                                        <td>{{ $item->paid_price ?? 0 }}</td>
                                        <td>{{ $item->payable }}</td>
                                        <td>{{ $item->pay_date?? 'N/A' }}</td>
                                        <td>{{ $item->discount }}</td>
                                        <td>{{ $item->status }}</td>
                                        <td>{{ $item->created_at }}</td>
                                    </tr>
                                    @endforeach

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Purchase Price</th>
                                        <th>Paid Price</th>
                                        <th>Payable</th>
                                        <th>Pay Date</th>
                                        <th>Discount</th>
                                        <th>Status</th>
                                        <th>Created Date</th>
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


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Total Payable: <b>{{ $totalPayable }}</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md col-sm ms-auto">
                    <form method="POST" action="{{ route('vendor.payAmount') }}" class="d-flex justify-content-end align-items-center">
                        @csrf
                        <input type="number" class="form-control" name="amount" step="0.01" min="0" />
                        <input type="hidden" class="form-control" name="vendor_id" value="{{ $id }}" />
                        <button type="submit" class="btn btn-info ml-2 mr-2">Submit</button>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>


<!-- @include('layouts.footer') -->

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
                        url: "{{ route('vendor.delete', ':id') }}".replace(':id', id),
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


@endpush
@endsection
