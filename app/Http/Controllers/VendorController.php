<?php

namespace App\Http\Controllers;

use Flasher\Prime\FlasherInterface;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\VendorHistory;
use App\Models\VendorPurchase;
use Validator;
use Redirect;

class VendorController extends Controller
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
        $vendor = Vendor::orderBy('id', 'Desc')->get();

        return view('vendor.index', compact('vendor'));
    }
    public function create()
    {

        return view('vendor.create');
    }

    public function store(Request $request, FlasherInterface $flasher)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255|unique:vendors',
            'address' => 'required',
            'phone' => 'required',

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
            $data['name'] = $request->name;
            $data['phone'] = $request->phone;
            $data['address'] = $request->address;

            Vendor::create($data);

            $flasher->option('position', 'top-center')->addSuccess('Vendor added Successfully');
            return redirect()->route('vendor.index')->with('message', 'Vendor added Successfully');
        } catch (\Exception $e) {

            $flasher->option('position', 'top-center')->addError('Something went wrong');
            return redirect()->route('vendor.index')->with('message', 'Something went wrong');
        }
    }

    public function edit($id)
    {
        $vendor = Vendor::findOrFail($id);
        return view('vendor.edit', compact('vendor'));
    }

    public function view($id)
    {
        $vendorHistory = VendorHistory::with('vendor')->where('vendor_id', $id)->get();
        $totalPayable = VendorHistory::where('vendor_id', $id)
            ->orderBy('id', 'desc')
            ->value('payable');

        return view('vendor.view', compact('vendorHistory', 'totalPayable', 'id'));
    }

    public function payAmount(Request $request, FlasherInterface $flasher)
    {

        $history = VendorHistory::where('vendor_id', $request->vendor_id)
            ->orderBy('id', 'desc')
            ->first();

        if (!$history) {
            $flasher->option('position', 'top-center')
                ->addWarning('No vendor history found. Please ensure the vendor exists and has a history.');
            return Redirect::back()->with('message', 'No vendor history found. Please ensure the vendor exists and has a history.');
        }

        if ($request->amount > $history->payable) {
            $flasher->option('position', 'top-center')
                ->addWarning('The paid amount should be equal to or less than the payable amount.');
            return Redirect::back()->with('message', 'The paid amount should be equal to or less than the payable amount.');
        }

        $updateAmount = $history->payable - $request->amount;

        VendorHistory::create([
            'vendor_id' => $request->vendor_id,
            'purchase_price' => 0,
            'paid_price' => $request->amount,
            'payable' => $updateAmount,
            'pay_date' => now(),
            'status' => 'Amount Paid',
        ]);

        $flasher->option('position', 'top-center')->addSuccess('The amount was paid successfully.');
        return Redirect::back()->with('message', 'The amount was paid successfully.');
    }


    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        $vendor = Vendor::find($id);

        if (!$vendor) {
            $flasher->option('position', 'top-center')->addError('Id not found');
            return redirect()->route('vendor.index')->with('error', 'Id not found');
        }

        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'max:255',
                Rule::unique('vendors')->ignore($vendor->id),
            ],
            'phone' => 'required',
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


        if ($request->name) {
            $validatedData['name'] = $request->name;
        }
        if ($request->phone) {
            $validatedData['phone'] = $request->phone;
        }
        if ($request->address) {
            $validatedData['address'] = $request->address;
        }

        $vendor->update($validatedData);
        $flasher->option('position', 'top-center')->addSuccess('Vendor updated Successfully');
        return redirect()->route('vendor.index')->with('message', 'Vendor updated Successfully');
    }


    public function delete($id)
    {
        $vendor = Vendor::find($id);
        if (!$vendor) {
            return response()->json(['error' => 'Vendor not found.'], 404);
        }

        try {
            $vendor->delete();
            return response()->json(['success' => 'Vendor deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }
}
