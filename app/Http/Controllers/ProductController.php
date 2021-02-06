<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    //
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    protected $messages=[
        'name.required' => 'The :attribute It is mandatory, it only supports letters and its length must be less than 255 characters.',
        'name.string' => 'The :attribute it only supports letters.',
        'name.regex'=>'The :attribute it only supports letters.',
        'name.max'=>' The :attribute length must be less than 255 characters.',
        'email.required' => 'The :attribute It is mandatory.',
        'email.string'=>'The :attribute It must be a valid email.',
        'email.email'=>'The :attribute It must be a valid email.',
        'email.max'=>'The :attribute less than 50 characters.',
        'description.required' => 'The :attribute It is mandatory.',
        'address.required' => 'The :attribute is required.',
        'address.string' =>'The :attribute It must be string.',
        'addess.max' => 'The :attribute It must be less than 100 characters.',
        'cp.required' => 'The :attribute is mandatory',
        'cp.string' =>'The :attribute It must be string.',
        'cp.size' =>'The :attribute It must be 5 numeric characters.',
        'cp.regex'=>'The :attribute It must be 5 numeric characters.',
    ];


    public function index()
    {
        //\Log::info("*****");
        $data = Product::all()->where('user_id','=',auth()->user()->id);
        return Inertia::render('Products', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        //\Log::info("*****");
        Validator::make($request->all(),
            [
            'name' => ['required','string','max:255','regex:/^[\pL\s\-]+$/u'],
            'email' => ['required','string','email','max:50'],
            'description' => ['required'],
            'address' => ['required','string','max:100'],
            'cp' => ['required','string','size:5','regex:/^[0-9]+$/'],
            ],
            $this->messages
        )->validate();

        $product = new Product();
        $this->saveProduct($product,$request,true);
        //Product::create($request->all());

        return redirect()->back()
            ->with('message', 'Product Created Successfully.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function update(Request $request)
    {
        Validator::make($request->all(),
            [
            'name' => ['required','string','max:255','regex:/^[\pL\s\-]+$/u'],
            'email' => ['required','string','email','max:50'],
            'description' => ['required'],
            'address' => ['required','string','max:100'],
            'cp' => ['required','string','size:5','regex:/^[0-9]+$/'],
            ],
            $this->messages
        )->validate();

        if ($request->has('id')) {
            //Product::find($request->input('id'))->update($request->all());
            $product = Product::find($request->input('id'));
            $this->saveProduct($product,$request,false);
            return redirect()->back()
                ->with('message', 'Product Updated Successfully.');
        }
    }

    private function saveProduct($product, Request $request, $new = false){
        $product->name = $request->name;
        $product->email = $request->email;
        $product->description = $request->description;
        $product->address = $request->address;
        $product->cp = $request->cp;
        if($new){
            $product->user_id = auth()->user()->id;
        }
        $product->save();

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function destroy(Request $request)
    {
        if ($request->has('id')) {
            Product::find($request->input('id'))->delete();
            return redirect()->back();
        }
    }
}
