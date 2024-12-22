@extends('layouts.app')

@push('css')

<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/css/bootstrap-select.min.css" />

@endpush

@section('content')

@include('layouts.sidebar')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Update Order</h1>
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
                            <h3 class="card-title">Update New Order</h3>
                        </div>
                        <form method="POST" action="{{ route('order.update', [$order->id]) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <input type="hidden" id="order-id" value="{{ $order->id }}">
                                <div class="row">
                                    <div class="col-md col-sm mb-2">
                                        <label for="company_id">Company</label> <span style="color: red;">*</span>
                                        <select name="company_id" class="form-control" required>
                                            <option disabled selected>--Select One--</option>
                                            @foreach ($company as $item)
                                            <option value="{{ $item->id }}" {{ $item->id == $order->company_id ? 'selected' : '' }}>
                                                {{ $item->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg col-md -col-sm mb-2">
                                        <label for="mechanic_id">Mechanic</label><span style="color: red;">*</span>
                                        <select name="mechanic_id" class="form-control" required>
                                            <option disabled selected>--Select One--</option>
                                            @foreach ($mechanic as $item)
                                            <option value="{{ $item->id }}" {{ $item->id == $order->mechanic_id ? 'selected' : '' }}>
                                                {{ $item->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Product Selection Dropdown -->
                                <div class="row">
                                    <div class="col-md col-sm mb-2">
                                        <label for="product_id">Select Product</label><span style="color: red;">*</span>
                                        <select id="product_id" class="selectpicker form-control" data-live-search="true" multiple>
                                            <option disabled>--Select One--</option>
                                            @foreach ($product as $item)
                                            <option value="{{ $item->id }}"
                                                data-name="{{ $item->name }}"
                                                data-price="{{ $item->price }}"
                                                data-remarks="{{ $item->remarks }}"
                                                data-checked="{{ $item->checked }}"
                                                {{ in_array($item->id, $selectedProducts) ? 'selected' : '' }}>
                                                {{ $item->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Product Table -->
                                <div class="row mt-3">
                                    <div class="col-md col-sm">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Name</th>
                                                    <th>Price</th>
                                                    <th>Quantity</th>
                                                    <th>Remarks</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="details-product-table-body"></tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Service Selection Dropdown -->
                                <div class="row">
                                    <div class="col-md col-sm mb-2">
                                        <label for="service_id">Select Service</label><span style="color: red;">*</span>
                                        <select id="service_id" class="selectpicker form-control" data-live-search="true" multiple>
                                            <option disabled>--Select One--</option>
                                            @foreach ($service as $item)
                                            <option value="{{ $item->id }}"
                                                data-name="{{ $item->name }}"
                                                data-price="{{ $item->price }}"
                                                data-remarks="{{ $item->remarks }}"
                                                data-checked="{{ $item->checked }}"
                                                {{ in_array($item->id, $selectedServices) ? 'selected' : '' }}>
                                                {{ $item->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Service Table -->
                                <div class="row mt-3">
                                    <div class="col-md col-sm">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Name</th>
                                                    <th>Price</th>
                                                    <th>Quantity</th>
                                                    <th>Remarks</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="details-service-table-body"></tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Combined Total -->
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <label style="color:green">Combined Total Price</label>
                                        <input type="text" class="form-control" id="combined-total-price" name="total_price" value="{{ $order->total_price }}" readonly>
                                    </div>
                                </div>

                                <div class="mt-2">
                                    <h2>Vehicle Details:</h2>
                                </div>
                                <div class="row">
                                    <div class="col-md col-sm mb-2">
                                        <label for="name">Car Picture</label>
                                        <input type="file" class="form-control" name="car_picture[]" multiple id="car_picture" accept="application/pdf, image/png, image/jpeg, image/jpg, image/webp" capture="environment" placeholder="Car Picture" capture>
                                    </div>
                                    <div class="col-md col-sm mb-2">
                                        <label for="vehicle_no">Vehicle Number</label>
                                        <input type="text" class="form-control" name="vehicle_no" value="{{ $order->vehicle_no }}" placeholder="Vehicle Number" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12 mb-2">
                                        <label for="vehicle_name">Vehicle Name</label>
                                        <input type="text" class="form-control" name="vehicle_name" value="{{ $order->vehicle_name }}" placeholder="Vehicle Name" required>
                                    </div>
                                    <div class="col-md-6 col-sm-12 mb-2">
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <h2>Client Details:</h2>
                                </div>
                                <div class="row">
                                    <div class="col-md col-sm mb-2">
                                        <label for="client_phone">Client Phone</label><span style="color: gray;"> (optional)</span>
                                        <input type="number" class="form-control" name="client_phone" value="{{ $order->client_phone }}" placeholder="Client Phone">
                                    </div>
                                    <div class="col-md col-sm mb-2">
                                        <label for="client_name">Client Name</label> <span style="color: gray;"> (optional)</span>
                                        <input type="text" class="form-control" name="client_name" value="{{ $order->client_name }}" placeholder="Client Name">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md col-sm mb-2">
                                        <label for="notes">Notes</label>
                                        <textarea type="text" class="form-control" name="notes" rows="3" placeholder="Write Something...">{{ $order->notes }}</textarea>
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

        $('.selectpicker').selectpicker();

        function handleSelectChange(selector) {
            $(selector).on('changed.bs.select', function() {
                const selectedValues = $(this).val();
                const dropdown = $(this);

                dropdown.find('option').each(function() {
                    const optionValue = $(this).val();
                    if (selectedValues && selectedValues.includes(optionValue)) {
                        $(this).hide();
                    } else {
                        $(this).show();
                    }
                });

                dropdown.selectpicker('refresh');
            });
        }

        handleSelectChange('#product_id');
        handleSelectChange('#service_id');
    });
</script>


<script>
    $(document).ready(function() {
        $('.selectpicker').selectpicker();

        function updateTable(tableBodySelector, selectedData, hiddenInputName) {
            const tableBody = $(tableBodySelector);
            tableBody.empty();

            selectedData.forEach(item => {
                const priceInput = item.checked == 1 ?
                    `<input type="number" class="form-control" name="${hiddenInputName}_price[]" value="${item.price}" step="0.01" min="0">` :
                    `<input type="number" class="form-control" name="${hiddenInputName}_price[]" value="${item.price}" step="0.01" min="0" readonly>`;

                tableBody.append(`
                    <tr data-id="${item.id}">
                        <td><input type="hidden" name="${hiddenInputName}[]" value="${item.id}" />${item.id}</td>
                        <td>${item.name}</td>
                        <td>${priceInput}</td>
                        <td><input type="number" name="${hiddenInputName}_qty[]" class="form-control" value="${item.qty || 1}" min="1" /></td>
                        <td><input type="text" name="${hiddenInputName}_remarks[]" class="form-control" value="${item.remarks || ''}" /></td>
                        <td><button class="btn btn-danger btn-sm remove-item" data-id="${item.id}">Remove</button></td>
                    </tr>
                `);
            });
            updateTotalPrice();

        }


        const orderId = $('#order-id').val();

        function fetchAndPopulateData(orderId) {
            $.ajax({
                url: '/get-edit-data',
                type: 'GET',
                data: {
                    order_id: orderId
                },
                dataType: 'json',
                success: function(response) {

                    if (response.products) {
                        updateTable('#details-product-table-body', response.products, 'product');
                    }


                    if (response.services) {
                        updateTable('#details-service-table-body', response.services, 'service');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching data:", error);
                }
            });
        }

        if (orderId) {
            fetchAndPopulateData(orderId);
        } else {
            console.error('Order ID is not available');
        }

        function updateTotalPrice() {
            let total = 0;

            ['#details-product-table-body', '#details-service-table-body'].forEach(selector => {
                $(selector).find('tr').each(function() {
                    const price = parseFloat($(this).find('input[name$="_price[]"]').val()) || 0;
                    const quantity = parseFloat($(this).find('input[name$="_qty[]"]').val()) || 0;
                    total += price * quantity;
                });
            });

            $('#combined-total-price').val(total.toFixed(2));
        }

        $('#product_id').on('change', function() {
            const selectedOptions = $(this).find('option:selected');
            const selectedData = selectedOptions.map(function() {
                return {
                    id: $(this).val(),
                    name: $(this).data('name'),
                    price: $(this).data('price'),
                    remarks: $(this).data('remarks'),
                    checked: $(this).data('checked'),

                };
            }).get();

            updateTable('#details-product-table-body', selectedData, 'product');
        });

        $('#service_id').on('change', function() {
            const selectedOptions = $(this).find('option:selected');
            const selectedData = selectedOptions.map(function() {
                return {
                    id: $(this).val(),
                    name: $(this).data('name'),
                    price: $(this).data('price'),
                    remarks: $(this).data('remarks'),
                    checked: $(this).data('checked'),

                };
            }).get();

            updateTable('#details-service-table-body', selectedData, 'service');
        });


        $(document).on('click', '.remove-item', function() {
            const id = $(this).data('id');
            const parentTable = $(this).closest('tbody');

            $(this).closest('tr').remove();
            if (parentTable.is('#details-product-table-body')) {
                const selectedProducts = $('#product_id').val().filter(value => value != id);
                $('#product_id').val(selectedProducts).trigger('change');
            } else if (parentTable.is('#details-service-table-body')) {
                const selectedServices = $('#service_id').val().filter(value => value != id);
                $('#service_id').val(selectedServices).trigger('change');
            }

            updateTotalPrice();
        });


        $(document).on('input', 'input[type="number"]', function() {
            updateTotalPrice();
        });
    });
</script>



<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/js/bootstrap-select.min.js" integrity="sha512-FHZVRMUW9FsXobt+ONiix6Z0tIkxvQfxtCSirkKc5Sb4TKHmqq1dZa8DphF0XqKb3ldLu/wgMa8mT6uXiLlRlw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

@endpush
@endsection
