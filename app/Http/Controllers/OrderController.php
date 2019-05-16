<?php

namespace App\Http\Controllers;

use App\CompaniesService;
use App\Company;
use App\Order;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $order = Order::where('id', '=', $request->id)->first();
        return view('orders/view')->with(['order' => $order]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }

    public function all(){
        $orders = DB::table('orders')
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->join('companies', 'companies.id', '=', 'users.company_id')
            ->join('services', 'services.id', '=', 'orders.service_id')
            ->join('partners', 'partners.id', '=', 'services.partner_id')
            ->select('users.name as username', 'companies.phone as userphone', 'partners.name as partner', 'partners.phone as partner_phone', 'services.name as service', 'orders.*')
            ->orderBy('orders.status', 'asc');

        return datatables($orders)->toJson();
    }

    public function save(Request $request){
        $order = Order::where('id', '=', $request->id)->first();
        $order->status = $request->confirm;
        $order->reject_reason = $request->reason;
        if($order->save()){
            $user = User::where('id', '=', $order->user_id)->first();
            $company = Company::where('id', '=', $user->company_id)->first();
            $c_service = new CompaniesService();
            $c_service->service_id = $order->service_id;
            $c_service->company_id = $company->id;
            $c_service->amount = $order->amount;
            $c_service->save();
            return view('orders/index');
        }else{
            abort(501);
        }

    }
}
