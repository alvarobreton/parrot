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
    private $orders;
    private $sales;
    private $products;

    function __construct() {
        
        $this->orders = new Orders;
        $this->sales = new Sales;
        $this->products = new Products;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request ['number_order']
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $order = $this->orders->join('products', 'products.id', '=', 'orders.product_id')
                        ->join('sales', 'sales.id', '=', 'orders.number_order')
                        ->select('orders.id','products.name','orders.total_price','orders.quantity')
                        ->where('number_order', $request->number_order)->get();
        $user = $this->sales->join('users', 'users.id', '=', 'sales.user_id')
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
        $userId = Auth::id();

        if($request->number_order > 0)
        {
            $response = $this->checkProduct($request);
            return $this->response($response, 201);
        }
        else
        {
            $this->sales->user_id     = $userId;
            $vsales->status      = 0;
            $this->sales->save();     
            $this->number_order = $sales->id;

            $response = array(
                'message'   => "Hola, te mostramos la lista de nuestros productos, seleccione para agregarlo a su pedido",
                'products'  => $this->products->select('id','name','price')->get(),
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

    /**
     *
     * @param  array $requests
     * @return \Illuminate\Http\Response
     */
    public function checkProduct($requests)
    {
        $price = $this->products->where('id', $requests->product)->value('price');
        $name = $this->products->where('id', $requests->product)->value('name');

        if($this->orders->where('product_id', $requests->product)->where('number_order', $requests->number_order)->exists())
        {
            $quantity = $this->orders->where('product_id', $requests->product)->where('number_order', $requests->number_order)->value('quantity');
            $quantity = $quantity+$requests->quantity;

            $price = $price*$quantity;

            $this->orders->where('product_id', $requests->product)
                    ->where('number_order', $requests->number_order)
                    ->update([
                        'quantity'      => $quantity,
                        'total_price'   => $price,
                    ]);
        }
        else
        {
            $this->orders->product_id     = $requests->product;
            $this->orders->quantity       = $requests->quantity;
            $this->orders->total_price    = $price*$requests->quantity;
            $this->orders->number_order    = $requests->number_order;
            $this->orders->save();
        }

        $response = array(
            'message'   => "Fue agregado con éxito tu producto: {$name}, puedes seguir añadiendo más productos",
            'products'  => $this->products->select('id','name','price')->get(),
            'number_order'  => $requests->number_order,
        );

        return $response;
    }
}
