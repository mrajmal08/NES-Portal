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
    public function index()
    {
        $orders = Order::with(['products', 'services', 'mechanic', 'company'])->get();

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
            'product_id' => 'required',
            'service_id' => 'required',
            'date' => 'required',
            'status' => 'required',
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

            if ($request->hasFile('car_picture')) {

                $file = $request->file('car_picture');
                $timestamp = now()->timestamp;
                $extension = $file->getClientOriginalExtension();
                $filename = rand(99999, 234567) . '_' . $timestamp . '.' . $extension;

                $file->move(public_path('images/car_pictures'), $filename);
                $data['car_picture'] = $filename;
            }

            $data['company_id'] = $request->company_id;
            $data['mechanic_id'] = $request->mechanic_id;
            $data['date'] = $request->date;
            $data['vehicle_no'] = $request->vehicle_no;
            $data['client_name'] = $request->client_name;
            $data['client_phone'] = $request->client_phone;
            $data['status'] = $request->status;
            $data['total_price'] = $request->total_price;
            $data['notes'] = $request->notes;

            $order = Order::create($data);

            if ($order) {
                $orderId = $order->id;
                $productId = $request->product_id ?? [];
                $serviceId = $request->service_id ?? [];
                foreach ($productId as $product) {
                    OrderProduct::create([
                        'order_id' => $orderId,
                        'product_id' => $product
                    ]);
                }

                foreach ($serviceId as $service) {
                    OrderService::create([
                        'order_id' => $orderId,
                        'service_id' => $service
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
        $order = Order::with(['products', 'services'])->findOrFail($id);
        $product = Product::orderBy('id', 'DESC')->get();
        $company = Company::orderBy('id', 'DESC')->get();
        $service = Service::orderBy('id', 'DESC')->get();
        $mechanic = Mechanic::orderBy('id', 'DESC')->get();

        $selectedProducts = $order->products->pluck('id')->toArray();
        $selectedServices = $order->services->pluck('id')->toArray();

        return view('order.edit', compact('order', 'product', 'service', 'company', 'mechanic', 'selectedProducts', 'selectedServices'));
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
            'product_id' => 'required',
            'service_id' => 'required',
            'date' => 'required',
            'status' => 'required',
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

        if ($request->hasFile('car_picture')) {

            $file = $request->file('car_picture');
            $timestamp = now()->timestamp;
            $extension = $file->getClientOriginalExtension();
            $filename = rand(99999, 234567) . '_' . $timestamp . '.' . $extension;

            $file->move(public_path('images/car_pictures'), $filename);
            $data['car_picture'] = $filename;
        }



        if ($request->company_id) {
            $validatedData['company_id'] = $request->company_id;
        }
        if ($request->mechanic_id) {
            $validatedData['mechanic_id'] = $request->mechanic_id;
        }
        if ($request->date) {
            $validatedData['date'] = $request->date;
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

            $productIds = $request->product_id ?? [];
            $serviceIds = $request->service_id ?? [];

            // Update or create OrderProducts
            foreach ($productIds as $productId) {
                OrderProduct::updateOrCreate(
                    ['order_id' => $orderId, 'product_id' => $productId],
                    ['order_id' => $orderId, 'product_id' => $productId]
                );
            }

            // Update or create OrderServices
            foreach ($serviceIds as $serviceId) {
                OrderService::updateOrCreate(
                    ['order_id' => $orderId, 'service_id' => $serviceId],
                    ['order_id' => $orderId, 'service_id' => $serviceId]
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
}
