<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Inertia\Inertia;
use Inertia\Response;

use App\Models\{
    Product
};

class Products extends Controller {

    /**
     * Display a listing of the resource.
     */
    public function data( Request $request ){

        $data = Product::orderBy('created_at', 'desc')->get();
        if( $request->ajax() ){
            return response()->json([
                "data" => $data
            ], 200 );
        }

    }

    /**
     * Display a listing of the resource.
     */
    public function index() : Response {
        return Inertia::render('products/Index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create( Request $request ){

        if( $request->ajax() ){
            return response()->json([
            ], 200 );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store( Request $request ){
        $validated = $request->validate([
            'sku' => [ 'required', 'string', 'max:20', 'unique:'.Product::class.',sku' ],
            'name' => [ 'required', 'string', 'max:100', 'unique:'.Product::class.',name' ],
            'price' => [ 'required', 'numeric', 'min:0' ],
            'description' => [ 'nullable' ],
            'image' => [ 'nullable' ],
        ]);

        $sale = Product::create( $validated );

        $this->store_notify( $sale );
        return redirect()->route('products.index');
    }

    /**
     * Display the specified resource.
     */
    public function show( Request $request, string $id ){
        $data = Product::find( $id );
        if( $request->ajax() ){
            return response()->json([
                "data" => $data
            ], 200 );
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( Request $request, string $id ){
        $data = Product::find( $id );
        if( $request->ajax() ){
            return response()->json([
                "data" => $data
            ], 200 );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update( Request $request, string $id ){

        $validated = $request->validate([
            'sku' => [ 'required', 'unique:'.Product::class.',sku,'.$id ],
            'name' => [ 'required', 'string', 'max:20', 'unique:'.Product::class.',sku,'.$id ],
            'name' => [ 'required', 'string', 'max:100' ],
            'price' => [ 'required', 'numeric', 'min:0' ],
            'description' => [ 'nullable' ],
            'image' => [ 'nullable' ],
        ]);

        $product = Product::find( $id );
        $product->update( $validated );

        $this->update_notify( $product );
        return redirect()->route('products.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( string $id ){
        $product = Product::find( $id );
        $product->delete();
    }
}
