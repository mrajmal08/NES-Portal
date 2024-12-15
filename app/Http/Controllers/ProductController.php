<?php

namespace App\Http\Controllers;

use Flasher\Prime\FlasherInterface;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\Product;
use Validator;
use Redirect;

class ProductController extends Controller
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
        $product = Product::all();

        return view('product.index', compact('product'));
    }
    public function create()
    {

        return view('product.create');
    }

    public function store(Request $request, FlasherInterface $flasher)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255|unique:products',
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

            Product::create($data);

            $flasher->option('position', 'top-center')->addSuccess('Product added Successfully');
            return redirect()->route('product.index')->with('message', 'Product added Successfully');
        } catch (\Exception $e) {

            $flasher->option('position', 'top-center')->addError('Something went wrong');
            return redirect()->route('product.index')->with('message', 'Something went wrong');
        }
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('product.edit', compact('product'));
    }

    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        $product = Product::find($id);

        if (!$product) {
            $flasher->option('position', 'top-center')->addError('Id not found');
            return redirect()->route('product.index')->with('error', 'Id not found');
        }

        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'max:255',
                Rule::unique('products')->ignore($product->id),
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

        $product->update($validatedData);
        $flasher->option('position', 'top-center')->addSuccess('Product updated Successfully');
        return redirect()->route('product.index')->with('message', 'Product updated Successfully');
    }


    public function delete($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['error' => 'Product not found.'], 404);
        }

        try {
            $product->delete();
            return response()->json(['success' => 'Product deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }
}
