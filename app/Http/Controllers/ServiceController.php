<?php

namespace App\Http\Controllers;

use Flasher\Prime\FlasherInterface;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\Service;
use Validator;
use Redirect;

class ServiceController extends Controller
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
        $service = Service::orderBy('id', 'Desc')->get();

        return view('service.index', compact('service'));
    }
    public function create()
    {

        return view('service.create');
    }

    public function store(Request $request, FlasherInterface $flasher)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255|unique:services',
            'price' => 'required',

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
            $data['price'] = $request->price;
            $data['status'] = 'active';
            $data['remarks'] = $request->remarks;
            $validatedData['checked'] = $request->has('checked') ? 1 : 0;

            Service::create($data);

            $flasher->option('position', 'top-center')->addSuccess('Service added Successfully');
            return redirect()->route('service.index')->with('message', 'Service added Successfully');
        } catch (\Exception $e) {

            $flasher->option('position', 'top-center')->addError('Something went wrong');
            return redirect()->route('service.index')->with('message', 'Something went wrong');
        }
    }

    public function edit($id)
    {
        $service = Service::findOrFail($id);
        return view('service.edit', compact('service'));
    }

    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        $service = Service::find($id);

        if (!$service) {
            $flasher->option('position', 'top-center')->addError('Id not found');
            return redirect()->route('service.index')->with('error', 'Id not found');
        }

        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'max:255',
                Rule::unique('services')->ignore($service->id),
            ],
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


        if ($request->name) {
            $validatedData['name'] = $request->name;
        }
        if ($request->price) {
            $validatedData['price'] = $request->price;
        }
        if ($request->status) {
            $validatedData['status'] = $request->status;
        }
        if ($request->remarks) {
            $validatedData['remarks'] = $request->remarks;
        }
        $validatedData['checked'] = $request->has('checked') ? 1 : 0;

        $service->update($validatedData);
        $flasher->option('position', 'top-center')->addSuccess('Service updated Successfully');
        return redirect()->route('service.index')->with('message', 'Service updated Successfully');
    }


    public function delete($id)
    {
        $service = Service::find($id);
        if (!$service) {
            return response()->json(['error' => 'Service not found.'], 404);
        }

        try {
            $service->delete();
            return response()->json(['success' => 'Service deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }
}
