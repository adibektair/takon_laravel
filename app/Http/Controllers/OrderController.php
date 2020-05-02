<?php

namespace App\Http\Controllers;

use App\CompaniesService;
use App\Company;
use App\Order;
use App\Service;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Order $order
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
     * @param  \App\Order $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Order $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
    }

    public function all()
    {
        $ordersQuery = DB::table('orders')
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->join('companies', 'companies.id', '=', 'users.company_id')
            ->join('services', 'services.id', '=', 'orders.service_id')
            ->join('partners', 'partners.id', '=', 'services.partner_id')
            ->select('users.name as username', 'companies.phone as userphone', 'partners.name as partner', 'partners.phone as partner_phone', 'services.name as service', 'orders.*')
            ->orderBy('orders.status', 'asc');


        return Datatables::of($ordersQuery)
            ->addColumn('status', function ($order) {
                if ($order->status == 1) {
                    return '<a href="/orders/view?id=' . $order->id . '"><button class="btn btn-success">Управлять</button></a>';
                } elseif ($order->status == 2) {
                    return '<label class="text-semibold">Отклонено</label>';
                } else {
                    return '<label class="text-semibold">Подтверждено</label>';
                }
            })
            ->addColumn('summ', function ($order) {
                return '<label>' . $order->amount . ' (' . $order->cost . ' тенге)</label>';
            })
            ->rawColumns(['status', 'summ'])
            ->make(true);
        return datatables($ordersQuery)->toJson();
    }

    public function save(Request $request)
    {
        $order = Order::where('id', '=', $request->id)->first();
        $order->status = $request->confirm;
        $order->reject_reason = $request->reason;

        $user = User::where('id', '=', $order->user_id)->first();
        $company = Company::where('id', '=', $user->company_id)->first();

        $service = Service::where('id', '=', $order->service_id)->first();
        if ($order->save()) {
//            $message = new Notification();
//            $message1 = new Notification();
            if ($request->confirm == 3) {


                $c_service = new CompaniesService();
                $c_service->service_id = $order->service_id;
                $c_service->company_id = $company->id;
                $c_service->amount = $order->amount;
                $c_service->deadline = strtotime("+" . $service->deadline . " day", strtotime("now"));
                if ($c_service->save()) {
                    $model = new Transaction();
                    $model->cs_id = $c_service->id;
                    $model->service_id = $service->id;
                    $model->type = 2;
                    $model->p_s_id = $service->partner_id;
                    $model->c_r_id = $company->id;
                    $model->price = $service->price;
                    $model->amount = $order->amount;
                    $model->save();
                }
            }

            return view('orders/index');
        } else {
            // TODO: add Internal server error page
            abort(501);
        }

    }
}
