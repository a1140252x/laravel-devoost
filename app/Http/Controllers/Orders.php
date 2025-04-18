<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Inertia\Inertia;
use Inertia\Response;

use App\Models\{
    Product,
    Customer,
    Order,
    OrderItem
};

class Orders extends Controller {

    /**
     * Display a listing of the resource.
     */
    public function data( Request $request ){

        $data = Order::with([
            'items',
            'customer:id,name',
        ])
        ->withCount('items')
        ->orderBy('created_at', 'desc')
        ->get();

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
        return Inertia::render('orders/Index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create( Request $request ){

        if( $request->ajax() ){
            return response()->json([
                //'products' => Product::all(),
                'customers' => Customer::select('id', 'name', 'phone', 'email')->get(),
            ], 200 );
        }

        return Inertia::render( 'orders/Create',[
            'products' => Product::all(),
            'customers' => Customer::all(),
        ]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store( Request $request ){
        $validated = $request->validate([
            'customer_id' => [ 'required' ],
        ]);

        $order = Order::create( $validated );

        $this->store_notify( $order );
        return redirect()->route('orders.index');
    }

    /**
     * Display the specified resource.
     */
    public function show( Request $request, string $id ){

        //OrderItem::create();
        //dd( OrderItem::all() );

        $data = Order::with([
            'items' => [
                'product'
            ],
            'customer',
        ])->find( $id );
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
        $data = Order::find( $id );
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

        $order = Order::find($id);

        if( $request["@update"] == 'cancel' ){
            $order->status = "cancelled";
            $order->save();
            $this->update_notify( $order );
            return redirect()->route('orders.index');
        }

        if( $request["@update"] == 'add-item' ){

            $validated = $request->validate([
                'product_id' => [ 'required' ],
                'quantity' => [ 'required', 'numeric', 'min:0' ],
            ]);

            $product = Product::find( $validated['product_id'] );

            $order_item = OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'price' => $product->price,
                'quantity' => $validated['quantity'],
                'total' => floatval($product->price) * floatval($validated['quantity']),
            ]);

            $order->total_price = OrderItem::where('order_id', $order->id)->sum('total');
            $order->save();

            $this->update_notify( $order );
            return redirect()->route('orders.index');

        }

        if( $request["@update"] == 'remove-item' ){

            $validated = $request->validate([
                'item_id' => [ 'required' ],
            ]);

            $order_item = OrderItem::find( $validated['item_id'] );
            $order_item->delete();

            $order->total_price = OrderItem::where('order_id', $order->id)->sum('total');
            $order->save();

            $this->update_notify( $order );
            return redirect()->route('orders.index');

        }

        $validated = $request->validate([
            'sku' => [ 'required', 'unique:'.Order::class.',sku,'.$id ],
            'name' => [ 'required', 'string', 'max:20', 'unique:'.Order::class.',sku,'.$id ],
            'name' => [ 'required', 'string', 'max:100' ],
            'price' => [ 'required', 'numeric', 'min:0' ],
            'description' => [ 'nullable' ],
            'image' => [ 'nullable' ],
            'active' => [ 'required' ],
        ]);

        $order = Order::find( $id );
        $order->update( $validated );

        $this->update_notify( $order );
        return redirect()->route('orders.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( string $id ){
        $order = Order::find( $id );
        $order->delete();
    }
}
