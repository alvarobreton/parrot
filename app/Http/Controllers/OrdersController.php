<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orders;
use App\Models\Products;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response ['product', 'quantity]
     */
    public function store(Request $request)
    {
        $orders = new Orders();
        $products = new Products();

        if(!empty($request->product))
        {
            $price = $products->where('id', $request->product)->value('price');
            
            
            if($orders->where('product_id', $request->product)->exists())
            {
                $quantity = $orders->where('product_id', $request->product)->where('user_id', 1)->value('quantity');
                $quantity = $quantity+$request->quantity;
                $price = $price*$quantity;
                $orders->where('product_id', $request->product)
                        ->where('user_id', 1)
                        ->update([
                            'quantity'      => $quantity,
                            'total_price'   => $price,
                        ]);
            }
            else
            {
                $orders->user_id        = 1;
                $orders->product_id     = $request->product;
                $orders->quantity       = $request->quantity;
                $orders->total_price    = $price*$request->quantity;
                $orders->save();
            }

            $response = array(
                'message'   => "Fue agregado con éxito tu producto, puedes seguir añadiendo más productos",
                'products'  => $products->select('id','name','price')->get(),
            );
            return $this->response($response, 201);
        }

        if(empty($orders->exists()))
        {
            $response = array(
                'message'   => "Hola, te mostramos la lista de nuestros productos, seleccione para agregarlo a su pedido",
                'products'  => $products->select('id','name','price')->get(),
            );
            return $this->response($response, 201);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Get validation response for the request.
     *
     * @param  array $messages
     * @param  int  $status
     * @return \Illuminate\Http\Response
     */
    public function response(array $messages, int $status)
    {
        return response()->json($messages, $status);
    }
}
