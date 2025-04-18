<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Inertia\Inertia;
use Inertia\Response;

use App\Models\{
    Customer
};

class Customers extends Controller {

    /**
     * Display a listing of the resource.
     */
    public function data( Request $request ){

        $data = Customer::orderBy('created_at', 'desc')->get();
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
        return Inertia::render('customers/Index');
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
            'name' => [ 'required', 'string', 'max:100' ],
            'phone' => [ 'required', 'string', 'max:50', 'unique:'.Customer::class.',phone' ],
            'email' => [ 'required', 'email', 'unique:'.Customer::class.',email' ],
        ]);

        $customer = Customer::create( $validated );

        $this->store_notify( $customer );
        return redirect()->route('customers.index');
    }

    /**
     * Display the specified resource.
     */
    public function show( Request $request, string $id ){
        $data = Customer::find( $id );
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
        $data = Customer::find( $id );
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
            'name' => [ 'required', 'string', 'max:100' ],
            'phone' => [ 'required', 'string', 'max:50', 'unique:'.Customer::class.',phone,'.$id ],
            'email' => [ 'required', 'email', 'unique:'.Customer::class.',email,'.$id ],
       ]);

        $customer = Customer::find( $id );
        $customer->update( $validated );

        $this->update_notify( $customer );
        return redirect()->route('customers.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( string $id ){
        $customer = Customer::find( $id );
        $customer->delete();
    }
}
