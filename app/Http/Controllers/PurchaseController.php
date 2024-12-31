<?php

namespace App\Http\Controllers;

use Flasher\Prime\FlasherInterface;
use Illuminate\Http\Request;
use App\Models\PurchaseProduct;
use App\Models\PurchaseService;
use App\Models\VendorPurchase;
use App\Models\VendorHistory;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Service;
use Carbon\Carbon;
use Validator;
use Redirect;
use DB;

class PurchaseController extends Controller
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
        $purchase = VendorPurchase::with(['vendor', 'products', 'services'])->orderBy('id', 'Desc');
        $vendors = Vendor::orderBy('id', 'DESC')->get();

        if ($request->has('vendor') && $request->vendor !== '') {
            $purchase = $purchase->whereHas('vendor', function ($query) use ($request) {
                $query->where('id', $request->vendor);
            });
        }

        $purchase = $purchase->get();
        return view('purchase.index', compact('purchase', 'vendors'));
    }
    public function create()
    {
        $vendor = Vendor::orderBy('id', 'DESC')->get();
        $product = Product::orderBy('id', 'DESC')->get();
        $service = Service::orderBy('id', 'DESC')->get();

        return view('purchase.create', compact('vendor', 'product', 'service'));
    }

    public function store(Request $request, FlasherInterface $flasher)
    {

        $validator = Validator::make($request->all(), [
            'vendor_id' => 'required',
            'product' => 'required_without:service',
            'service' => 'required_without:product',
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
            $documents = ['invoice_photo'];
            foreach ($documents as $doc) {
                if ($request->hasFile($doc)) {
                    $filenames = [];
                    foreach ($request->file($doc) as $file) {
                        $extension = $file->getClientOriginalExtension();
                        $filename = rand(99999, 234567) . $timestamp . '.' . $extension;
                        $file->move(public_path('images/invoice_photo'), $filename);
                        $filenames[] = $filename;
                    }
                    $data[$doc] = implode(',', $filenames);
                }
            }


            $data['vendor_id'] = $request->vendor_id;
            $data['total_price'] = $request->total_price;
            $data['notes'] = $request->notes;
            $data['discount'] = $request->discount;

            $purchase = VendorPurchase::create($data);

            if ($purchase) {
                $purchaseId = $purchase->id;

                $productId = $request->product ?? [];
                $productQty = $request->product_qty ?? [];
                $productRemarks = $request->product_remarks ?? [];
                $productPrice = $request->product_price ?? [];

                $serviceId = $request->service ?? [];
                $serviceQty = $request->service_qty ?? [];
                $serviceRemarks = $request->service_remarks ?? [];
                $servicePrice = $request->service_price ?? [];


                foreach ($productId as $index => $product) {
                    PurchaseProduct::create([
                        'purchase_id' => $purchaseId,
                        'product_id' => $product,
                        'qty' => $productQty[$index] ?? 0,
                        'remarks' => $productRemarks[$index] ?? '',
                        'price' => $productPrice[$index] ?? '',
                    ]);
                }


                foreach ($serviceId as $index => $service) {
                    PurchaseService::create([
                        'purchase_id' => $purchaseId,
                        'service_id' => $service,
                        'qty' => $serviceQty[$index] ?? 0,
                        'remarks' => $serviceRemarks[$index] ?? 0,
                        'price' => $servicePrice[$index] ?? 0,
                    ]);
                }

                $total_price = is_numeric($request->total_price) ? $request->total_price : 0;
                $discount = is_numeric($request->discount) ? $request->discount : 0;
                $discount_amount = ($total_price * $discount) / 100;
                $final_price = $total_price - $discount_amount;

                $lastEntry = VendorHistory::where('vendor_id', $request->vendor_id)
                    ->orderBy('id', 'DESC')
                    ->first();
                $remainingPayable = $final_price;

                if ($lastEntry) {
                    $remainingPayable += $lastEntry->payable;
                }

                VendorHistory::create([
                    'vendor_id' => $request->vendor_id,
                    'purchase_price' => $final_price,
                    'payable' => $remainingPayable,
                    'status' => 'Added',
                    'discount' => $request->discount,
                ]);
            }


            $flasher->option('position', 'top-center')->addSuccess('Order Purchase added Successfully');
            return redirect()->route('purchase.index')->with('message', 'Order Purchase added Successfully');
        } catch (\Exception $e) {

            $flasher->option('position', 'top-center')->addError('Something went wrong');
            return redirect()->route('purchase.index')->with('message', 'Something went wrong');
        }
    }

    public function view($id)
    {
        $purchase = VendorPurchase::with(['vendor', 'products', 'services'])->findOrFail($id);

        return view('purchase.view', compact('purchase'));
    }

    public function edit($id)
    {
        $purchase = VendorPurchase::with(['vendor', 'products', 'services'])->findOrFail($id);
        $vendor =  Vendor::orderBy('id', 'DESC')->get();
        $product = Product::orderBy('id', 'DESC')->get();
        $service = Service::orderBy('id', 'DESC')->get();

        $selectedProducts = $purchase->products->pluck('id')->toArray();
        $selectedServices = $purchase->services->pluck('id')->toArray();

        return view('purchase.edit', compact('purchase', 'vendor', 'product', 'service', 'selectedProducts', 'selectedServices'));
    }


    public function getPurchaseData(Request $request)
    {
        $purchase = VendorPurchase::findOrFail($request->purchase_id);

        $products = DB::table('purchase_products')
            ->where('purchase_id', $purchase->id)
            ->join('products', 'products.id', '=', 'purchase_products.product_id')
            ->select(
                'products.id as id',
                'products.name as name',
                'purchase_products.price as price',
                'purchase_products.qty as qty',
                'purchase_products.remarks as remarks'
            )
            ->get();


        $services = DB::table('purchase_services')
            ->where('purchase_id', $purchase->id)
            ->join('services', 'services.id', '=', 'purchase_services.service_id')
            ->select(
                'services.id as id',
                'services.name as name',
                'purchase_services.price as price',
                'purchase_services.qty as qty',
                'purchase_services.remarks as remarks'
            )
            ->get();

        return response()->json([
            'products' => $products,
            'services' => $services,
        ]);
    }

    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        $purchase = VendorPurchase::find($id);

        if (!$purchase) {
            $flasher->option('position', 'top-center')->addError('Id not found');
            return redirect()->route('purchase.index')->with('error', 'Id not found');
        }

        $validator = Validator::make($request->all(), [
            'vendor_id' => 'required',
            'product' => 'required_without:service',
            'service' => 'required_without:product',
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
            'invoice_photo'
        ];

        $validatedData = [];
        foreach ($documentFields as $field) {
            if ($request->hasFile($field)) {
                $newFilesArray = [];
                foreach ($request->file($field) as $file) {
                    $extension = $file->getClientOriginalExtension();
                    $filename = rand(99999, 234567) . $timestamp . '.' . $extension;
                    $file->move(public_path('images/invoice_photo'), $filename);
                    $newFilesArray[] = $filename;
                }
                $validatedData[$field] = implode(',', $newFilesArray);
            }
        }



        if ($request->vendor_id) {
            $validatedData['vendor_id'] = $request->vendor_id;
        }
        if ($request->total_price) {
            $validatedData['total_price'] = $request->total_price;
        }
        if ($request->discount) {
            $validatedData['discount'] = $request->discount;
        }
        if ($request->notes) {
            $validatedData['notes'] = $request->notes;
        }

        if ($purchase->update($validatedData)) {
            $purchaseId = $purchase->id;

            $productId = $request->product ?? [];
            $productQty = $request->product_qty ?? [];
            $productRemarks = $request->product_remarks ?? [];
            $productPrice = $request->product_price ?? [];

            $serviceId = $request->service ?? [];
            $serviceQty = $request->service_qty ?? [];
            $serviceRemarks = $request->service_remarks ?? [];
            $servicePrice = $request->service_price ?? [];


            foreach ($productId as $index => $product) {
                purchaseProduct::updateOrCreate(
                    [
                        'purchase_id' => $purchaseId,
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
                purchaseService::updateOrCreate(
                    [
                        'purchase_id' => $purchaseId,
                        'service_id' => $service,
                    ],
                    [
                        'qty' => $serviceQty[$index] ?? 0,
                        'remarks' => $serviceRemarks[$index] ?? '',
                        'price' => $servicePrice[$index] ?? '',
                    ]
                );
            }

            $lastEntry = VendorHistory::where('vendor_id', $request->vendor_id)
                ->orderByDesc('id')
                ->first();

                $total_price = is_numeric($request->total_price) ? $request->total_price : 0;
                $discount = is_numeric($request->discount) ? $request->discount : 0;
                $discount_amount = ($total_price * $discount) / 100;
                $final_price = $total_price - $discount_amount;

                $remainingPayable = $lastEntry->payable ?? 0;
                if ($final_price > $lastEntry->purchase_price) {

                    $price = $final_price - $lastEntry->purchase_price;
                    $remainingPayable =  $remainingPayable + $price;
                } elseif ($final_price < $lastEntry->purchase_price) {

                    $price =  $lastEntry->purchase_price - $final_price;
                    $remainingPayable = $remainingPayable - $price;
                }

                VendorHistory::create([
                    'vendor_id' => $request->vendor_id,
                    'purchase_price' => $final_price,
                    'payable' => $remainingPayable,
                    'status' => 'Updated',
                ]);
        }


        $flasher->option('position', 'top-center')->addSuccess('Order Purchase updated Successfully');
        return redirect()->route('purchase.index')->with('message', 'Order Purchase updated Successfully');
    }


    public function delete($id)
    {
        $purchase = VendorPurchase::find($id);
        if (!$purchase) {
            return response()->json(['error' => 'Order Purchase not found.'], 404);
        }

        try {
            $purchaseId = $purchase->id;

            if ($purchase->delete()) {
                purchaseProduct::where('purchase_id', $purchaseId)->update([
                    'deleted_at' => now()
                ]);
                purchaseService::where('purchase_id', $purchaseId)->update([
                    'deleted_at' => now()
                ]);
            }

            return response()->json(['success' => 'Order deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }
}
