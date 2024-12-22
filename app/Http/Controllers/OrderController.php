<?php

namespace App\Http\Controllers;

use Flasher\Prime\FlasherInterface;
use Illuminate\Http\Request;
use App\Models\OrderProduct;
use App\Models\OrderService;
use App\Models\Company;
use App\Models\Mechanic;
use App\Models\Order;
use App\Models\Product;
use App\Models\Service;
use Carbon\Carbon;
use Validator;
use Redirect;
use DB;

class OrderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {

        $orders = Order::with(['products', 'services', 'mechanic', 'company'])->orderBy('id', 'Desc');

        if ($request->has('status') && $request->status !== '') {
            $orders = $orders->where('status', $request->status);
        }

        $orders = $orders->get();

        return view('order.index', compact('orders'));
    }
    public function create()
    {
        $product = Product::orderBy('id', 'DESC')->get();
        $company = Company::orderBy('id', 'DESC')->get();
        $service = Service::orderBy('id', 'DESC')->get();
        $mechanic = Mechanic::orderBy('id', 'DESC')->get();

        return view('order.create', compact('product', 'company', 'service', 'mechanic'));
    }

    public function store(Request $request, FlasherInterface $flasher)
    {

        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'mechanic_id' => 'required',
            'product' => 'required',
            'service' => 'required',
            'vehicle_no' => 'required',
            'vehicle_name' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            foreach ($errors as $error) {
                $flasher->options([
                    'timeout' => 3000,
                    'position' => 'top-center',
                ])->option('position', 'top-center')->addError('Validation Error', $error);
                return Redirect::back()->withErrors($validator)->withInput();
            }
        }

        try {

            $timestamp = Carbon::now()->timestamp;
            $documents = ['car_picture'];
            foreach ($documents as $doc) {
                if ($request->hasFile($doc)) {
                    $filenames = [];
                    foreach ($request->file($doc) as $file) {
                        $extension = $file->getClientOriginalExtension();
                        $filename = rand(99999, 234567) . $timestamp . '.' . $extension;
                        $file->move(public_path('images/car_pictures'), $filename);
                        $filenames[] = $filename;
                    }
                    $data[$doc] = implode(',', $filenames);
                }
            }

            $data['company_id'] = $request->company_id;
            $data['mechanic_id'] = $request->mechanic_id;
            // $data['date'] = $request->date;
            $data['vehicle_name'] = $request->vehicle_name;
            $data['vehicle_no'] = $request->vehicle_no;
            $data['client_name'] = $request->client_name;
            $data['client_phone'] = $request->client_phone;
            $data['status'] = 'process';
            $data['total_price'] = $request->total_price;
            $data['notes'] = $request->notes;
            $order = Order::create($data);

            if ($order) {
                $orderId = $order->id;

                $productId = $request->product ?? [];
                $productQty = $request->product_qty ?? [];
                $productRemarks = $request->product_remarks ?? [];
                $productPrice = $request->product_price ?? [];

                $serviceId = $request->service ?? [];
                $serviceQty = $request->service_qty ?? [];
                $serviceRemarks = $request->service_remarks ?? [];
                $servicePrice = $request->service_price ?? [];


                foreach ($productId as $index => $product) {
                    OrderProduct::create([
                        'order_id' => $orderId,
                        'product_id' => $product,
                        'qty' => $productQty[$index] ?? 0,
                        'remarks' => $productRemarks[$index] ?? '',
                        'price' => $productPrice[$index] ?? '',
                    ]);
                }


                foreach ($serviceId as $index => $service) {
                    OrderService::create([
                        'order_id' => $orderId,
                        'service_id' => $service,
                        'qty' => $serviceQty[$index] ?? 0,
                        'remarks' => $serviceRemarks[$index] ?? 0,
                        'price' => $servicePrice[$index] ?? 0,

                    ]);
                }
            }


            $flasher->option('position', 'top-center')->addSuccess('Order added Successfully');
            return redirect()->route('order.index')->with('message', 'Order added Successfully');
        } catch (\Exception $e) {

            $flasher->option('position', 'top-center')->addError('Something went wrong');
            return redirect()->route('order.index')->with('message', 'Something went wrong');
        }
    }

    public function edit($id)
    {
        $order = Order::with(['products', 'services', 'mechanic', 'company'])->findOrFail($id);
        $product = Product::orderBy('id', 'DESC')->get();
        $company = Company::orderBy('id', 'DESC')->get();
        $service = Service::orderBy('id', 'DESC')->get();
        $mechanic = Mechanic::orderBy('id', 'DESC')->get();

        $selectedProducts = $order->products->pluck('id')->toArray();
        $selectedServices = $order->services->pluck('id')->toArray();

        return view('order.edit', compact('order', 'product', 'service', 'company', 'mechanic', 'selectedProducts', 'selectedServices'));
    }

    public function getEditData(Request $request)
    {
        $order = Order::findOrFail($request->order_id);


        $products = DB::table('order_products')
            ->where('order_id', $order->id)
            ->join('products', 'products.id', '=', 'order_products.product_id')
            ->select(
                'products.id as id',
                'products.name as name',
                'order_products.price as price',
                'order_products.qty as qty',
                'order_products.remarks as remarks'
            )
            ->get();


        $services = DB::table('order_services')
            ->where('order_id', $order->id)
            ->join('services', 'services.id', '=', 'order_services.service_id')
            ->select(
                'services.id as id',
                'services.name as name',
                'order_services.price as price',
                'order_services.qty as qty',
                'order_services.remarks as remarks'
            )
            ->get();

        return response()->json([
            'products' => $products,
            'services' => $services,
        ]);
    }



    public function view($id)
    {
        $order = Order::with(['products', 'services', 'mechanic', 'company'])->findOrFail($id);

        return view('order.view', compact('order'));
    }

    public function updateStatus(Request $request, FlasherInterface $flasher)
    {
        $request->validate([
            'id' => 'required|integer',
            'status' => 'required|string|in:process,delivered,completed',
        ]);

        try {
            $order = Order::findOrFail($request->id);
            $order->status = $request->status;
            $order->delivery_date = now();
            $order->save();

            return response()->json(['message' => 'Order status updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update status.'], 500);
        }
    }

    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        $order = Order::find($id);
        if (!$order) {
            $flasher->option('position', 'top-center')->addError('Id not found');
            return redirect()->route('order.index')->with('error', 'Id not found');
        }

        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'mechanic_id' => 'required',
            'product' => 'required',
            'service' => 'required',
            'vehicle_no' => 'required',
            'vehicle_name' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            foreach ($errors as $error) {
                $flasher->options([
                    'timeout' => 3000,
                    'position' => 'top-center',
                ])->option('position', 'top-center')->addError('Validation Error', $error);
                return Redirect::back()->withErrors($validator)->withInput();
            }
        }

        $timestamp = Carbon::now()->timestamp;
        $documentFields = [
            'car_picture'
        ];

        $validatedData = [];
        foreach ($documentFields as $field) {
            if ($request->hasFile($field)) {
                $newFilesArray = [];
                foreach ($request->file($field) as $file) {
                    $extension = $file->getClientOriginalExtension();
                    $filename = rand(99999, 234567) . $timestamp . '.' . $extension;
                    $file->move(public_path('images/car_pictures'), $filename);
                    $newFilesArray[] = $filename;
                }

                $validatedData[$field] = implode(',', $newFilesArray);
            }
        }



        if ($request->company_id) {
            $validatedData['company_id'] = $request->company_id;
        }
        if ($request->mechanic_id) {
            $validatedData['mechanic_id'] = $request->mechanic_id;
        }
        if ($request->vehicle_name) {
            $validatedData['vehicle_name'] = $request->vehicle_name;
        }
        if ($request->vehicle_no) {
            $validatedData['vehicle_no'] = $request->vehicle_no;
        }
        if ($request->client_name) {
            $validatedData['client_name'] = $request->client_name;
        }
        if ($request->client_phone) {
            $validatedData['client_phone'] = $request->client_phone;
        }
        if ($request->status) {
            $validatedData['status'] = $request->status;
        }
        if ($request->total_price) {
            $validatedData['total_price'] = $request->total_price;
        }
        if ($request->notes) {
            $validatedData['notes'] = $request->notes;
        }

        if ($order->update($validatedData)) {
            $orderId = $order->id;

            $productId = $request->product ?? [];
            $productQty = $request->product_qty ?? [];
            $productRemarks = $request->product_remarks ?? [];
            $productPrice = $request->product_price ?? [];

            $serviceId = $request->service ?? [];
            $serviceQty = $request->service_qty ?? [];
            $serviceRemarks = $request->service_remarks ?? [];
            $servicePrice = $request->service_price ?? [];


            foreach ($productId as $index => $product) {
                OrderProduct::updateOrCreate(
                    [
                        'order_id' => $orderId,
                        'product_id' => $product,
                    ],
                    [
                        'qty' => $productQty[$index] ?? 0,
                        'remarks' => $productRemarks[$index] ?? '',
                        'price' => $productPrice[$index] ?? '',
                    ]
                );
            }


            foreach ($serviceId as $index => $service) {
                OrderService::updateOrCreate(
                    [
                        'order_id' => $orderId,
                        'service_id' => $service,
                    ],
                    [
                        'qty' => $serviceQty[$index] ?? 0,
                        'remarks' => $serviceRemarks[$index] ?? '',
                        'price' => $servicePrice[$index] ?? '',
                    ]
                );
            }
        }


        $order->update($validatedData);
        $flasher->option('position', 'top-center')->addSuccess('Order updated Successfully');
        return redirect()->route('order.index')->with('message', 'Order updated Successfully');
    }


    public function delete($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['error' => 'Order not found.'], 404);
        }

        try {
            $orderId = $order->id;

            if ($order->delete()) {
                OrderProduct::where('order_id', $orderId)->update([
                    'deleted_at' => now()
                ]);
                OrderService::where('order_id', $orderId)->update([
                    'deleted_at' => now()
                ]);
            }

            return response()->json(['success' => 'Order deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

    public function getProductDetails(Request $request)
    {

        $products = Product::whereIn('id', $request->product_id)->get();

        if ($products->isNotEmpty()) {
            return response()->json($products);
        }

        return response()->json(['error' => 'Product not found'], 404);
    }

    public function  getServiceDetails(Request $request)
    {
        $services = Service::whereIn('id', $request->service_id)->get();

        if ($services->isNotEmpty()) {
            return response()->json($services);
        }

        return response()->json(['error' => 'Product not found'], 404);
    }

    public function getSelectedDetails(Request $request)
    {
        $products = Product::whereIn('id', $request->product_ids)->get(['name', 'price', 'remarks']);
        $services = Service::whereIn('id', $request->service_ids)->get(['name', 'price', 'remarks']);

        return response()->json([
            'products' => $products,
            'services' => $services
        ]);
    }
}
