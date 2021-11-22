<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Orders;
use App\Models\Products;
use App\Models\Sales;
use App\Models\User;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request ['number_order']
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $orders = new Orders;
        $sales = new Sales;
        $sales = new Sales;

        $order = $orders->join('products', 'products.id', '=', 'orders.product_id')
                        ->join('sales', 'sales.id', '=', 'orders.number_order')
                        ->select('orders.id','products.name','orders.total_price','orders.quantity')
                        ->where('number_order', $request->number_order)->get();
        $user = $sales->join('users', 'users.id', '=', 'sales.user_id')
                    ->select('users.name')->where('sales.id', $request->number_order)->get();

        $response = array(
            'message'   => "Esta es tu lista de orden. Para concluir, dar clic en el botón de pago",
            'orders'  => $order,
            'user'  => $user,
        );

        return $this->response($response, 201);
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
     * @param  \Illuminate\Http\Request  $request ['product', 'quantity', 'number_order']
     * @return \Illuminate\Http\Response 
     */
    public function store(Request $request)
    {
        $orders = new Orders();
        $products = new Products();
        $sales = new Sales();
        $userId = Auth::id();
        $user = User::find($userId);

        if($request->number_order > 0)
        {
            $price = $products->where('id', $request->product)->value('price');
            $name = $products->where('id', $request->product)->value('name');
            $number_order = $request->number_order;
            
            if($orders->where('product_id', $request->product)->where('number_order', $number_order)->exists())
            {
                $quantity = $orders->where('product_id', $request->product)->where('number_order', $number_order)->value('quantity');
                $quantity = $quantity+$request->quantity;
                $price = $price*$quantity;
                $orders->where('product_id', $request->product)
                        ->where('number_order', $number_order)
                        ->update([
                            'quantity'      => $quantity,
                            'total_price'   => $price,
                        ]);
            }
            else
            {
                $orders->product_id     = $request->product;
                $orders->quantity       = $request->quantity;
                $orders->total_price    = $price*$request->quantity;
                $orders->number_order    = $number_order;
                $orders->save();
            }

            $response = array(
                'message'   => "Fue agregado con éxito tu producto: {$name}, puedes seguir añadiendo más productos",
                'products'  => $products->select('id','name','price')->get(),
                'number_order'  => $request->number_order,
                'number_order'  => $request->number_order,
            );
            return $this->response($response, 201);
        }
        else
        {
            $sales->user_id     = $userId;
            $sales->status      = 0;
            $sales->save();     
            $number_order = $sales->id;

            $response = array(
                'message'   => "Hola, te mostramos la lista de nuestros productos, seleccione para agregarlo a su pedido",
                'products'  => $products->select('id','name','price')->get(),
                'number_order'  => $number_order
            );
            return $this->response($response, 200);
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
