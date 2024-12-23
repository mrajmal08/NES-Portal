@extends('layouts.app')

@push('css')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/css/bootstrap-select.min.css" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>

@endpush

@section('content')

@include('layouts.sidebar')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Purchase</h1>
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
                <div class="col-md-12">

                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Update Purchase</h3>
                        </div>
                        <form method="POST" action="{{ route('purchase.update', [$purchase->id]) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <input type="hidden" id="purchase-id" value="{{ $purchase->id }}">
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
                                    </div>
                                    <div class="col-md col-sm mb-2">
                                    </div>
                                </div>

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

                                <div class="row mt-3">
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

                                <div class="row mt-3">
                                <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label style="color:green">Combined Total Price</label>
                                            <input type="text" class="form-control" id="combined-total-price" name="total_price" value="{{ $purchase->total_price }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label style="color:red">Discount In (%)</label>
                                            <input type="number" class="form-control" id="discount" name="discount" value="{{ $purchase->discount }}"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md col-sm mb-2">
                                        <label for="invoice_photo">Invoice Photo</label>
                                        <input type="file" class="form-control" name="invoice_photo[]" id="invoice_photo" accept="application/pdf, image/png, image/jpeg, image/jpg, image/webp" capture="environment" placeholder="Invoice Photo" capture>
                                    </div>
                                    <div class="col-md col-sm mb-2">
                                        <!-- <label for="invoice_photo">Invoice Photo</label>
                                        <input type="file" class="form-control" name="invoice_photo" placeholder="Invoice Photo"> -->
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


        const purchaseId = $('#purchase-id').val();

        function fetchAndPopulateData(purchaseId) {
            $.ajax({
                url: '/get-purchase-data',
                type: 'GET',
                data: {
                    purchase_id: purchaseId
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

        if (purchaseId) {
            fetchAndPopulateData(purchaseId);
        } else {
            console.error('Purchase ID is not available');
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
