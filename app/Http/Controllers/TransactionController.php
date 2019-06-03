<?php

namespace App\Http\Controllers;

use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('transactions/admin');
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
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        //
    }

    public function adminMore(Request $request){
        return view('transactions/admin-more')->with(['id' => $request->id]);
    }

    public function adminEtc(Request $request){
        return view('transactions/admin-etc')->with(['id' => $request->id]);
    }

    public function adminMoreGet(Request $request){

        $result = DB::table('transactions')
            ->where('parent_id', $request->id)
            ->whereIn('type', [1, 4])
            ->join('services', 'services.id', '=', 'transactions.service_id')
            ->join('companies', 'companies.id', '=', 'transactions.c_s_id')
            ->leftJoin('companies as c', 'c.id', '=', 'transactions.c_r_id')
            ->leftJoin('mobile_users', 'mobile_users.id', '=', 'transactions.u_r_id')
            ->select('transactions.*', 'services.name as service', 'companies.name as sender', 'c.name as company', 'mobile_users.phone as user')
            ->get();

        $s = DataTables::of($result)
            ->addColumn('1', function ($service) {
                return '<a href="/transactions/admin/etc?id=' . $service->id . '"><button class="btn btn-success">Подробнее</button></a>';
            })
            ->addColumn('2', function ($service) {
                if($service->company){
                    return '<label>'. $service->company . '</label>';
                }
                return '<label>'. $service->user . '</label>';
            })
            ->make(true);

        return $s;
    }

    public function adminEtcGet(Request $request){

        $result = DB::table('transactions')
            ->where('parent_id', $request->id)
            ->join('services', 'services.id', '=', 'transactions.service_id')
            ->leftJoin('companies', 'companies.id', '=', 'transactions.c_s_id')
            ->leftJoin('companies as c', 'c.id', '=', 'transactions.c_r_id')
            ->leftJoin('mobile_users', 'mobile_users.id', '=', 'transactions.u_r_id')
            ->leftJoin('mobile_users as m', 'm.id', '=', 'transactions.u_s_id')
            ->select('transactions.*', 'services.name as service', 'companies.name as c1', 'c.name as c2', 'mobile_users.phone as u1', 'm.phone as u2')
            ->get();

        $s = DataTables::of($result)
            ->addColumn('1', function ($service) {
                if($service->c2){
                    return '<label>'. $service->c2 . '</label>';
                }
                return '<label>'. $service->u1 . '</label>';

            })
            ->addColumn('2', function ($service) {
                if($service->c1){
                    return '<label>'. $service->c1 . '</label>';
                }
                return '<label>'. $service->u2 . '</label>';
            })
            ->make(true);

        return $s;
    }


    public function adminAll(){
        $result = DB::table('transactions')
            ->where('parent_id', Null)
            ->where('type', 2)
            ->join('services', 'services.id', '=', 'transactions.service_id')
            ->join('companies', 'companies.id', '=', 'transactions.c_r_id')
            ->join('partners', 'partners.id', '=', 'transactions.p_s_id')
            ->select('transactions.*', 'services.name as service', 'companies.name as company', 'partners.name as partner')
            ->get();
        $s = DataTables::of($result)
            ->addColumn('1', function ($service) {
                    return '<a href="/transactions/admin/more?id=' . $service->id . '"><button class="btn btn-success">Подробнее</button></a>';
                })
            ->make(true);
        return $s;
    }





}
