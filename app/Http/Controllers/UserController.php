<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Flasher\Prime\FlasherInterface;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Validator;
use Redirect;

class UserController extends Controller
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
        $users = User::orderBy('id', 'DESC')->get();

        return view('users.index', compact('users'));
    }
    public function create()
    {

        return view('users.create');
    }

    public function store(Request $request, FlasherInterface $flasher)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|unique:users',
            'password' => 'required',

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
            $data['email'] = $request->email;
            $data['password'] = Hash::make($request->password);
            $data['phone'] = $request->phone;
            $data['address'] = $request->address;
            $data['status'] = $request->status;
            $data['role_id'] = $request->role_id;

            User::create($data);

            $flasher->option('position', 'top-center')->addSuccess('User added Successfully');
            return redirect()->route('users.index')->with('message', 'User added Successfully');
        } catch (\Exception $e) {

            $flasher->option('position', 'top-center')->addError('Something went wrong');
            return redirect()->route('users.index')->with('message', 'Something went wrong');
        }
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        $user = User::find($id);

        if (!$user) {
            $flasher->option('position', 'top-center')->addError('Id not found');
            return redirect()->route('users.index')->with('error', 'Id not found');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
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
        if ($request->email) {
            $validatedData['email'] = $request->email;
        }
        if($request->password != ''){
            $validatedData['password'] = Hash::make($request->password);
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
        if ($request->role_id) {
            $validatedData['role_id'] = $request->role_id;
        }

        $user->update($validatedData);
        $flasher->option('position', 'top-center')->addSuccess('User updated Successfully');
        return redirect()->route('users.index')->with('message', 'User updated Successfully');
    }


    public function delete($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        try {
            $user->delete();
            return response()->json(['success' => 'User deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

}
