<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orders;
use App\Models\Products;
use App\Models\Sales;
use Carbon\Carbon;
class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request ['start_date', 'end_date']
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $orders = new Orders;

        if(empty($request->start_date))
        {
            $response = array(
                'error'     => true,
                'message'   => "Falta fecha de inicio [start_date]"
            );
            return $this->response($response, 400);
        }

        if(empty($request->end_date))
        {
            $response = array(
                'error'     => true,
                'message'   => "Falta fecha final [end_date]"
            );
            return $this->response($response, 400);
        }

        $start_date = Carbon::parse("$request->start_date 00:00:00")->format('Y-m-d H:i:s');
        $end_date = Carbon::parse("$request->end_date 23:59:59")->format('Y-m-d H:i:s');

        $order = $orders->join('products', 'products.id', '=', 'orders.product_id')
                        ->join('sales', 'sales.id', '=', 'orders.number_order')
                        ->select('orders.id','products.name',$orders->raw('SUM(orders.total_price) as total_price'), $orders->raw('SUM(orders.quantity) as quantity'))
                        ->where([['orders.created_at','<=',$start_date],['orders.created_at','>=',$end_date]])
                        ->orwhereBetween('orders.created_at',array($start_date,$end_date))
                        ->orWhereBetween('orders.created_at',array($start_date,$end_date))
                        ->groupBy('products.id')
                        ->orderByRaw('quantity DESC')
                        ->get();

        $response = array(
            'orders'  => $order,
        );

        return $this->response($response, 200);
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
