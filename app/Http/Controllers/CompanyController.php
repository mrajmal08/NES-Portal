<?php

namespace App\Http\Controllers;

use Flasher\Prime\FlasherInterface;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\Company;
use Validator;
use Redirect;

class CompanyController extends Controller
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
        $company = Company::all();

        return view('company.index', compact('company'));
    }
    public function create()
    {

        return view('company.create');
    }

    public function store(Request $request, FlasherInterface $flasher)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255|unique:companies',
            'phone' => 'required',
            'address' => 'required',

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
            $data['status'] = $request->status;

            Company::create($data);

            $flasher->option('position', 'top-center')->addSuccess('Company added Successfully');
            return redirect()->route('company.index')->with('message', 'Company added Successfully');
        } catch (\Exception $e) {

            $flasher->option('position', 'top-center')->addError('Something went wrong');
            return redirect()->route('company.index')->with('message', 'Something went wrong');
        }
    }

    public function edit($id)
    {
        $company = Company::findOrFail($id);
        return view('company.edit', compact('company'));
    }

    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        $company = Company::find($id);

        if (!$company) {
            $flasher->option('position', 'top-center')->addError('Id not found');
            return redirect()->route('company.index')->with('error', 'Id not found');
        }

        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'max:255',
                Rule::unique('companies')->ignore($company->id),
            ],
            'phone' => 'required',
            'address' => 'required',
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
        if ($request->status) {
            $validatedData['status'] = $request->status;
        }

        $company->update($validatedData);
        $flasher->option('position', 'top-center')->addSuccess('Company updated Successfully');
        return redirect()->route('company.index')->with('message', 'Company updated Successfully');
    }


    public function delete($id)
    {
        $company = Company::find($id);
        if (!$company) {
            return response()->json(['error' => 'Company not found.'], 404);
        }

        try {
            $company->delete();
            return response()->json(['success' => 'Company deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }
}
