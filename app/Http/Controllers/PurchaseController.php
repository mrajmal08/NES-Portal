<?php

namespace App\Http\Controllers;

use Flasher\Prime\FlasherInterface;
use Illuminate\Http\Request;
use App\Models\purchaseProduct;
use App\Models\purchaseService;
use App\Models\VendorPurchase;
use App\Models\Company;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Service;
use Carbon\Carbon;
use Validator;
use Redirect;

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
    public function index()
    {
        $purchase = VendorPurchase::with(['vendor', 'product', 'service'])->get();

        return view('purchase.index', compact('purchase'));
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
            'product_id' => 'required',
            'service_id' => 'required',
            'date' => 'required',
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

            if ($request->hasFile('invoice_photo')) {

                $file = $request->file('invoice_photo');
                $timestamp = now()->timestamp;
                $extension = $file->getClientOriginalExtension();
                $filename = rand(99999, 234567) . '_' . $timestamp . '.' . $extension;

                $file->move(public_path('images/invoice_photo'), $filename);
                $data['invoice_photo'] = $filename;
            }

            $data['vendor_id'] = $request->vendor_id;
            $data['product_id'] = $request->product_id;
            $data['date'] = $request->date;
            $data['service_id'] = $request->service_id;
            $data['notes'] = $request->notes;

            VendorPurchase::create($data);

            $flasher->option('position', 'top-center')->addSuccess('Order Purchase added Successfully');
            return redirect()->route('purchase.index')->with('message', 'Order Purchase added Successfully');
        } catch (\Exception $e) {

            $flasher->option('position', 'top-center')->addError('Something went wrong');
            return redirect()->route('purchase.index')->with('message', 'Something went wrong');
        }
    }

    public function edit($id)
    {
        $purchase = VendorPurchase::with(['vendor', 'product', 'service'])->findOrFail($id);
        $vendor =  Vendor::orderBy('id', 'DESC')->get();
        $product = Product::orderBy('id', 'DESC')->get();
        $service = Service::orderBy('id', 'DESC')->get();

        return view('purchase.edit', compact('purchase', 'vendor', 'product', 'service'));
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
            'product_id' => 'required',
            'service_id' => 'required',
            'date' => 'required',
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

        if ($request->hasFile('invoice_photo')) {

            $file = $request->file('invoice_photo');
            $timestamp = now()->timestamp;
            $extension = $file->getClientOriginalExtension();
            $filename = rand(99999, 234567) . '_' . $timestamp . '.' . $extension;

            $file->move(public_path('images/invoice_photos'), $filename);
            $data['invoice_photo'] = $filename;
        }



        if ($request->vendor_id) {
            $validatedData['vendor_id'] = $request->vendor_id;
        }
        if ($request->product_id) {
            $validatedData['product_id'] = $request->product_id;
        }
        if ($request->service_id) {
            $validatedData['service_id'] = $request->service_id;
        }

        if ($request->date) {
            $validatedData['date'] = $request->date;
        }
        if ($request->notes) {
            $validatedData['notes'] = $request->notes;
        }

        $purchase->update($validatedData);
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
            $purchase->delete();
            return response()->json(['success' => 'Order Purchase deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }
}
