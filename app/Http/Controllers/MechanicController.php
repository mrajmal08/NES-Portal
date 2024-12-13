<?php

namespace App\Http\Controllers;

use Flasher\Prime\FlasherInterface;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\Mechanic;
use Validator;
use Redirect;

class MechanicController extends Controller
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
        $mechanic = Mechanic::all();

        return view('mechanic.index', compact('mechanic'));
    }
    public function create()
    {

        return view('mechanic.create');
    }

    public function store(Request $request, FlasherInterface $flasher)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255|unique:mechanics',
            'type' => 'required',

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
            $data['type'] = $request->type;
            $data['address'] = $request->address;

            Mechanic::create($data);

            $flasher->option('position', 'top-center')->addSuccess('Mechanic added Successfully');
            return redirect()->route('mechanic.index')->with('message', 'Mechanic added Successfully');
        } catch (\Exception $e) {

            $flasher->option('position', 'top-center')->addError('Something went wrong');
            return redirect()->route('mechanic.index')->with('message', 'Something went wrong');
        }
    }

    public function edit($id)
    {
        $mechanic = Mechanic::findOrFail($id);
        return view('mechanic.edit', compact('mechanic'));
    }

    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        $mechanic = Mechanic::find($id);

        if (!$mechanic) {
            $flasher->option('position', 'top-center')->addError('Id not found');
            return redirect()->route('mechanic.index')->with('error', 'Id not found');
        }

        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'max:255',
                Rule::unique('mechanics')->ignore($mechanic->id),
            ],
            'type' => 'required',
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
        if ($request->type) {
            $validatedData['type'] = $request->type;
        }
        if ($request->address) {
            $validatedData['address'] = $request->address;
        }

        $mechanic->update($validatedData);
        $flasher->option('position', 'top-center')->addSuccess('Mechanic updated Successfully');
        return redirect()->route('mechanic.index')->with('message', 'Mechanic updated Successfully');
    }


    public function delete($id)
    {
        $mechanic = Mechanic::find($id);
        if (!$mechanic) {
            return response()->json(['error' => 'Mechanic not found.'], 404);
        }

        try {
            $mechanic->delete();
            return response()->json(['success' => 'Mechanic deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }
}
