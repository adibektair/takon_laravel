<?php

namespace App\Http\Controllers;

use App\CompaniesService;
use App\Partner;
use App\Transaction;
use App\UsersService;
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
    public function return()
    {
        return view('transactions/return');
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
    public function partner()
    {
        return view('transactions/partners');
    }
    public function company()
    {
        return view('transactions/company');
    }
    public function companyMore(Request $request)
    {
        return view('transactions/company-more')->with(['id' => $request->id]);
    }
    public function partnerMore(Request $request){
        return view('transactions/partner-more')->with(['id' => $request->id]);
    }
    public function adminMore(Request $request){
        return view('transactions/admin-more')->with(['id' => $request->id]);
    }

    public function adminEtc(Request $request){
        return view('transactions/admin-etc')->with(['id' => $request->id]);
    }

    public function partnerEtc(Request $request){
        return view('transactions/partner-etc')->with(['id' => $request->id]);
    }
    public function companyEtc(Request $request){
        return view('transactions/company-etc')->with(['id' => $request->id]);
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
                if($service->user) {
                    return '<a href="/transactions/admin/etc?id=' . $service->id . '"><button class="btn btn-success">Подробнее</button></a>';
                }
//                    return '<a href="/transactions/admin/etc?id=' . $service->id . '"><button class="btn btn-success">Подробнее</button></a>';
            })
            ->addColumn('2', function ($service) {
                if($service->company){
                    return $service->company;
                }
                return $service->user;
            })
            ->addColumn('3', function ($service) {
                if($service->type == 4){
                    return $service->price * $service->amount . ' тенге' . ' (Перевод)';
                }
                return $service->price * $service->amount . ' тенге';
            })
            ->make(true);

        return $s;
    }

    public function partnerMoreGet(Request $request){

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
                if($service->user) {
                    return '<a href="/transactions/partner/etc?id=' . $service->id . '"><button class="btn btn-success">Подробнее</button></a>';
                }
            })
            ->addColumn('2', function ($service) {
                if($service->company){
                    return $service->company;
                }
                return $service->user;
            })
            ->addColumn('3', function ($service) {
                if($service->type == 4){
                    return $service->price * $service->amount . ' тенге' . ' (Перевод)';
                }
                return $service->price * $service->amount . ' тенге';
            })
            ->make(true);

        return $s;
    }
    public function companyMoreGet(Request $request){


        $result = DB::table('transactions')
            ->where('parent_id', $request->id)
            ->whereIn('type', [1, 4])
            ->join('services', 'services.id', '=', 'transactions.service_id')
            ->leftJoin('companies', 'companies.id', '=', 'transactions.c_s_id')
            ->leftJoin('companies as c', 'c.id', '=', 'transactions.c_r_id')
            ->leftJoin('mobile_users', 'mobile_users.id', '=', 'transactions.u_r_id')
            ->select('transactions.*', 'services.name as service', 'companies.name as sender', 'c.name as company', 'mobile_users.phone as user')
            ->get();

        $s = DataTables::of($result)
            ->addColumn('1', function ($service) {
                if($service->user) {
                    return '<a href="/transactions/company/etc?id=' . $service->id . '"><button class="btn btn-success">Подробнее</button></a>';
                }
            })
            ->addColumn('2', function ($service) {
                if($service->company){
                    return $service->company;
                }
                return $service->user;
            })
            ->addColumn('3', function ($service) {
                if($service->type == 4){
                    return $service->price * $service->amount . ' тенге' . ' (Перевод)';
                }
                return $service->price * $service->amount . ' тенге';
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
            ->select('transactions.*', 'services.name as service', 'services.partner_id as partner_id', 'companies.name as c1', 'c.name as c2', 'mobile_users.phone as u1', 'm.phone as u2')
            ->get();

        $s = DataTables::of($result)
            ->addColumn('1', function ($service) {

                if($service->type == 3){
                    $partner = Partner::where('id', $service->partner_id)->first();

                    return 'Потрачено у ' . $partner->name;
                }
                if($service->c2){
                    return $service->c2;
                }
                return $service->u1;

            })
            ->addColumn('2', function ($service) {
                if($service->c1){
                    return  $service->c1;
                }
                return $service->u2 ;
            })
            ->addColumn('3', function ($service) {
                return $service->price * $service->amount . ' тенге';
            })
            ->make(true);

        return $s;
    }

    public function partnerEtcGet(Request $request){

        $result = DB::table('transactions')
            ->where('parent_id', $request->id)
            ->join('services', 'services.id', '=', 'transactions.service_id')
            ->leftJoin('companies', 'companies.id', '=', 'transactions.c_s_id')
            ->leftJoin('companies as c', 'c.id', '=', 'transactions.c_r_id')
            ->leftJoin('mobile_users', 'mobile_users.id', '=', 'transactions.u_r_id')
            ->leftJoin('mobile_users as m', 'm.id', '=', 'transactions.u_s_id')
            ->select('transactions.*', 'services.name as service', 'services.partner_id as partner_id', 'companies.name as c1', 'c.name as c2', 'mobile_users.phone as u1', 'm.phone as u2')
            ->get();

        $s = DataTables::of($result)
            ->addColumn('1', function ($service) {

                if($service->type == 3){
                    $partner = Partner::where('id', $service->partner_id)->first();

                    return 'Потрачено у ' . $partner->name;
                }
                if($service->c2){
                    return $service->c2;
                }
                return $service->u1;

            })
            ->addColumn('2', function ($service) {
                if($service->c1){
                    return  $service->c1;
                }
                return $service->u2 ;
            })
            ->addColumn('3', function ($service) {
                return $service->price * $service->amount . ' тенге';
            })
            ->make(true);

        return $s;
    }


    public function companyEtcGet(Request $request){

        $result = DB::table('transactions')
            ->where('parent_id', $request->id)
            ->join('services', 'services.id', '=', 'transactions.service_id')
            ->leftJoin('companies', 'companies.id', '=', 'transactions.c_s_id')
            ->leftJoin('companies as c', 'c.id', '=', 'transactions.c_r_id')
            ->leftJoin('mobile_users', 'mobile_users.id', '=', 'transactions.u_r_id')
            ->leftJoin('mobile_users as m', 'm.id', '=', 'transactions.u_s_id')
            ->select('transactions.*', 'services.name as service', 'services.partner_id as partner_id', 'companies.name as c1', 'c.name as c2', 'mobile_users.phone as u1', 'm.phone as u2')
            ->get();

        $s = DataTables::of($result)
            ->addColumn('1', function ($service) {

                if($service->type == 3){
                    $partner = Partner::where('id', $service->partner_id)->first();

                    return 'Потрачено у ' . $partner->name;
                }
                if($service->c2){
                    return $service->c2;
                }
                return $service->u1;

            })
            ->addColumn('2', function ($service) {
                if($service->c1){
                    return  $service->c1;
                }
                return $service->u2 ;
            })
            ->addColumn('3', function ($service) {
                return $service->price * $service->amount . ' тенге';
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
            ->addColumn('2', function ($service) {
                if($service->type == 4){
                    return $service->price * $service->amount . ' (переданные)';
                }
                return $service->price * $service->amount . ' тенге';
            })
            ->addColumn('3', function ($service) {
                $cs = CompaniesService::where('id', $service->cs_id)->first();

                return $cs->amount;
            })
            ->make(true);
        return $s;
    }


    public function partnerAll(){
        $result = DB::table('transactions')
            ->where('parent_id', Null)
            ->whereIn('type', [2, 4])
            ->where('p_s_id', auth()->user()->partner_id)
            ->join('services', 'services.id', '=', 'transactions.service_id')
            ->join('companies', 'companies.id', '=', 'transactions.c_r_id')
            ->join('partners', 'partners.id', '=', 'transactions.p_s_id')
            ->select('transactions.*', 'services.name as service', 'companies.name as company', 'partners.name as partner')
            ->get();
        $s = DataTables::of($result)
            ->addColumn('1', function ($service) {
                return '<a href="/transactions/partner/more?id=' . $service->id . '"><button class="btn btn-success">Подробнее</button></a>';
            })
            ->addColumn('2', function ($service) {
                if($service->type == 4){
                    return $service->price * $service->amount . ' (переданные)';
                }
                return $service->price * $service->amount . ' тенге';
            })
            ->addColumn('3', function ($service) {
                $cs = CompaniesService::where('id', $service->cs_id)->first();
                return $cs->amount;
            })
            ->addColumn('4', function ($service) {
                $all = UsersService::where('company_id', $service->cs_id)->first();
                return $cs->amount;
            })
            ->make(true);
        return $s;
    }



    public function companyAll(){
        $result = DB::table('transactions')
            ->where('parent_id', Null)
            ->whereIn('type', [2, 4])
            ->where('c_r_id', auth()->user()->company_id)
            ->join('services', 'services.id', '=', 'transactions.service_id')
            ->join('partners', 'partners.id', '=', 'transactions.p_s_id')
            ->select('transactions.*', 'services.name as service', 'partners.name as partner')
            ->get();
        $s = DataTables::of($result)
            ->addColumn('1', function ($service) {
                return '<a href="/transactions/company/more?id=' . $service->id . '"><button class="btn btn-success">Подробнее</button></a>';
            })
            ->addColumn('2', function ($service) {
                if($service->type == 4){
                    return $service->price * $service->amount . ' (переданные)';
                }
                return $service->price * $service->amount . ' тенге';
            })
            ->addColumn('3', function ($service) {
                $cs = CompaniesService::where('id', $service->cs_id)->first();
                return $cs->amount;
            })
            ->addColumn('4', function ($service) {
                $cs = CompaniesService::where('id', $service->cs_id)->first();
                $date = date('Y-m-d', $cs->deadline);
                return $date;
            })
            ->make(true);
        return $s;
    }



    public function returnAll(){

        if(auth()->user()->role_id == 1){
            $result = DB::table('transactions')
                ->where('type', 5)
                ->join('services', 'services.id', '=', 'transactions.service_id')
                ->join('companies', 'companies.id', '=', 'transactions.c_r_id')
                ->join('mobile_users', 'mobile_users.id', '=', 'transactions.u_s_id')
                ->select('transactions.*', 'services.name as service', 'companies.name as company', 'mobile_users.phone as user')
                ->get();
        }else{
            $result = DB::table('transactions')
                ->where('type', 5)
                ->where('transactions.c_r_id', auth()->user()->company_id)
                ->join('services', 'services.id', '=', 'transactions.service_id')
                ->join('companies', 'companies.id', '=', 'transactions.c_r_id')
                ->join('mobile_users', 'mobile_users.id', '=', 'transactions.u_s_id')
                ->select('transactions.*', 'services.name as service', 'companies.name as company', 'mobile_users.phone as user')
                ->get();
        }


        $s = DataTables::of($result)
            ->addColumn('0', function ($service) {
                return $service->price * $service->amount . ' тенге';
            })
            ->make(true);
        return $s;
    }



}
