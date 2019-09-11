<?php

namespace App\Http\Controllers;

use App\CompaniesService;
use App\Company;
use App\Group;
use App\GroupsUser;
use App\MobileUser;
use App\Partner;
use App\Transaction;
use App\User;
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

    public function payments()
    {
        return view('transactions/payments');
    }


    public function use()
    {
        return view('transactions/use');
    }
    public function search()
    {
        return view('transactions/search');
    }
    public function searchGo(Request $request)
    {
        return view('transactions/search-go')->with(['phone' => $request->phone]);
    }
    public function searchMake(Request $request)
    {
        if(auth()->user()->role_id == 3){
            $phone = $request->phone;
            $result = DB::table('transactions')
                ->where('users.phone', $phone)
                ->orWhere('musers.phone', $phone)
                ->leftJoin('mobile_users as users', 'users.id', '=', 'transactions.u_s_id')
                ->leftJoin('mobile_users as musers', 'musers.id', '=', 'transactions.u_r_id')
                ->join('services', 'services.id', '=', 'transactions.service_id')
                ->join('companies_services', 'companies_services.id', '=', 'transactions.cs_id')
                ->where('companies_services.company_id', '=', auth()->user()->company_id)
                ->select('transactions.*', 'musers.phone as muserphone', 'users.phone as sender', 'services.name as service')
                ->get();
            $s = DataTables::of($result)
                ->addColumn('reciever', function ($service) {
                    if($service->type == 3){
                        return 'Использовано';
                    }else{
                        return $service->muserphone;
                    }
                })
                ->addColumn('sender', function ($service) use($phone) {
                    if($service->c_s_id){
                        $c = Company::where('id', $service->c_s_id)->first();
                        return $c->name;
                    }else{
                        return $service->sender;
                    }

                })
                ->rawColumns(['reciever', 'sender'])
                ->make(true);
            return $s;
        }else{
            $phone = $request->phone;
            $result = DB::table('transactions')
                ->where('users.phone', $phone)
                ->orWhere('musers.phone', $phone)
                ->leftJoin('mobile_users as users', 'users.id', '=', 'transactions.u_s_id')
                ->leftJoin('mobile_users as musers', 'musers.id', '=', 'transactions.u_r_id')
                ->join('services', 'services.id', '=', 'transactions.service_id')
                ->select('transactions.*', 'musers.phone as muserphone', 'users.phone as sender', 'services.name as service')
                ->get();
            $s = DataTables::of($result)
                ->addColumn('reciever', function ($service) {
                    if($service->type == 3){
                        return 'Использовано';
                    }else{
                        return $service->muserphone;
                    }
                })
                ->addColumn('sender', function ($service) use($phone) {
                    if($service->c_s_id){
                        $c = Company::where('id', $service->c_s_id)->first();
                        return $c->name;
                    }else{
                        return $service->sender;
                    }

                })
                ->rawColumns(['reciever', 'sender'])
                ->make(true);
            return $s;
        }

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
            ->where('cs_id', $request->id)
            ->whereIn('type', [3])
            ->join('services', 'services.id', '=', 'transactions.service_id')
            ->leftJoin('companies as c', 'c.id', '=', 'transactions.c_r_id')
            ->leftJoin('mobile_users', 'mobile_users.id', '=', 'transactions.u_s_id')
            ->leftJoin('users', 'users.id', '=', 'transactions.u_r_id')
            ->select('transactions.*', 'services.name as service', 'mobile_users.phone as sender', 'users.name as user')
            ->get();

        $s = DataTables::of($result)
            ->addColumn('1', function ($service) {
                if($service->user) {
                    return '<a href="/transactions/partner/etc?id=' . $service->id . '"><button class="btn btn-success">Подробнее</button></a>';
                }
            })
            ->addColumn('2', function ($service) {

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

//        $tr = Transaction::where('id', $request->id)->first();
//        if($tr->parent_id){
//            $result = DB::table('transactions')
//                ->where('parent_id', $tr->parent_id)
//                ->whereIn('type', [1, 4])
//                ->join('services', 'services.id', '=', 'transactions.service_id')
//                ->leftJoin('companies', 'companies.id', '=', 'transactions.c_s_id')
//                ->leftJoin('companies as c', 'c.id', '=', 'transactions.c_r_id')
//                ->leftJoin('mobile_users', 'mobile_users.id', '=', 'transactions.u_r_id')
//                ->select('transactions.*', 'services.name as service', 'companies.name as sender', 'c.name as company', 'mobile_users.phone as user')
//                ->get();
//
//        }else{
        $result = DB::table('transactions')
            ->where('parent_id', $request->id)
            ->whereIn('type', [1, 4])
            ->join('services', 'services.id', '=', 'transactions.service_id')
            ->leftJoin('companies', 'companies.id', '=', 'transactions.c_s_id')
            ->leftJoin('companies as c', 'c.id', '=', 'transactions.c_r_id')
            ->leftJoin('mobile_users', 'mobile_users.id', '=', 'transactions.u_r_id')
//            ->leftJoin('groups_users', 'groups_users.mobile_user_id', '=', 'mobile_users.id')
//            ->leftJoin(
//                'groups',
//                'groups.id',
//                '=',
//                'groups_users.group_id'
//            )
//            ->where('groups.company_id', auth()->user()->company_id)
            ->select('transactions.*', 'services.name as service', 'companies.name as sender', 'c.name as company', 'mobile_users.phone as user')
            ->get();

//        }

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
            ->leftJoin('services', 'services.id', '=', 'transactions.service_id')
            ->leftJoin('companies', 'companies.id', '=', 'transactions.c_r_id')
            ->leftJoin('partners', 'partners.id', '=', 'transactions.p_s_id')
            ->select('transactions.*', 'services.name as service', 'companies.name as company', 'partners.name as partner')
            ->get();


        $s = DataTables::of($result)
            ->addColumn('1', function ($service) {
                return '<a href="/transactions/partner/more?id=' . $service->cs_id . '"><button class="btn btn-success">Подробнее</button></a>';
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
                $tr = Transaction::where('type', 3)->where('cs_id', $service->cs_id)->get();
                $a = 0;
                foreach ($tr as $v){
                    $a += $v->amount;
                }
                return $a;
            })
            ->addColumn('5', function ($service) {
                $cs = CompaniesService::where('id', $service->cs_id)->first();

                $all = UsersService::where('cs_id', $service->cs_id)->get();
                $amount = 0;
                foreach ($all as $v){
                    $amount += $v->amount;
                }
                return $amount + $cs->amount;
            })
            ->make(true);
        return $s;
    }



    public function companyAll(){
        $result = DB::table('transactions')
            ->whereIn('type', [2, 4])
            ->where('c_r_id', auth()->user()->company_id)
            ->join('services', 'services.id', '=', 'transactions.service_id')
            ->leftJoin('partners', 'partners.id', '=', 'services.partner_id')
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

                $all = UsersService::where('cs_id', $service->cs_id)->get();
                $amount = 0;
                foreach ($all as $v){
                    $amount += $v->amount;
                }
                return $amount + $cs->amount;

            })
            ->addColumn('4', function ($service) {
                $cs = CompaniesService::where('id', $service->cs_id)->first();
                $date = date('Y-m-d', $cs->deadline);
                return $date;
            })
            ->addColumn('5', function ($service) {
                $cs = CompaniesService::where('id', $service->cs_id)->first();

                $all = UsersService::where('cs_id', $service->cs_id)->get();
                $amount = 0;
                foreach ($all as $v){
                    $amount += $v->amount;
                }
                return $service->amount - ($amount + $cs->amount);
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

    public function paymentsAll(){
        $result = DB::table('transactions')
            ->where('type', 6)
            ->join('services', 'services.id', '=', 'transactions.service_id')
            ->join('mobile_users', 'mobile_users.id', '=', 'transactions.u_r_id')
            ->select('transactions.*', 'services.name as service', 'mobile_users.phone as sender')
            ->get();

        $s = DataTables::of($result)->make(true);

        return $s;
    }

    public function useAll(Request $request){

        if(auth()->user()->role_id == 1 OR auth()->user()->role_id == 4){

            if ($request->id){
                $result = DB::table('transactions')
                    ->where('transactions.type', 3)
                    ->where('transactions.service_id', $request->id)
                    ->join('services', 'services.id', '=', 'transactions.service_id')
                    ->leftJoin('mobile_users', 'mobile_users.id', '=', 'transactions.u_s_id')
                    ->leftJoin('users', 'users.id', '=', 'transactions.u_r_id')
                    ->leftJoin('companies_services', 'companies_services.id', '=', 'transactions.cs_id')
                    ->leftJoin('companies', 'companies.id', '=', 'companies_services.company_id')
                    ->select('transactions.*', 'services.name as service', 'mobile_users.phone as sender', 'mobile_users.name as username', 'users.name as reciever', 'companies.name as company')
                    ->orderBy('created_at', 'asc')

                    ->get();

            }else{
                $result = DB::table('transactions')
                    ->where('transactions.type', 3)
                    ->join('services', 'services.id', '=', 'transactions.service_id')
                    ->leftJoin('mobile_users', 'mobile_users.id', '=', 'transactions.u_s_id')
                    ->leftJoin('companies_services', 'companies_services.id', '=', 'transactions.cs_id')
                    ->leftJoin('companies', 'companies.id', '=', 'companies_services.company_id')
                    ->leftJoin('users', 'users.id', '=', 'transactions.u_r_id')
                    ->select('transactions.*', 'services.name as service', 'mobile_users.phone as sender', 'mobile_users.name as username', 'users.name as reciever', 'companies.name as company')
                    ->orderBy('created_at', 'asc')

                    ->get();

            }
        }else{

            if ($request->id){
                $result = DB::table('transactions')
                    ->where('transactions.type', 3)
                    ->where('transactions.service_id', $request->id)
                    ->join('services', 'services.id', '=', 'transactions.service_id')
                    ->leftJoin('mobile_users', 'mobile_users.id', '=', 'transactions.u_s_id')
                    ->leftJoin('users', 'users.id', '=', 'transactions.u_r_id')
                    ->join('companies_services', 'companies_services.id', '=', 'transactions.cs_id')
                    ->join('companies', 'companies.id', '=', 'companies_services.company_id')
                    ->where('companies.id', '=', auth()->user()->company_id)
                    ->leftJoin('groups_users', 'groups_users.mobile_user_id', '=', 'mobile_users.id')
                    ->leftJoin(
                        'groups',
                        'groups.id',
                        '=',
                        'groups_users.group_id'
                    )
                    ->where('groups.company_id', auth()->user()->company_id)
                    ->select('transactions.*', 'services.name as service', 'mobile_users.phone as sender', 'groups_users.username as username', 'users.name as reciever')
//                    ->selectRaw('distinct(transactions.id) as idd')
                    ->groupBy('transactions.id')
                    ->orderBy('created_at', 'asc')
                    ->get();

            }else{
                $result = DB::table('transactions')
                    ->where('transactions.type', 3)
                    ->join('services', 'services.id', '=', 'transactions.service_id')
                    ->leftJoin('mobile_users', 'mobile_users.id', '=', 'transactions.u_s_id')
                    ->leftJoin('users', 'users.id', '=', 'transactions.u_r_id')
                    ->leftJoin('groups_users', 'groups_users.mobile_user_id', '=', 'mobile_users.id')
                    ->leftJoin(
                        'groups',
                        'groups.id',
                        '=',
                        'groups_users.group_id'
                    )
                    ->select('transactions.*', 'services.name as service','groups_users.username as username',  'mobile_users.phone as sender', 'users.name as reciever')
                    ->join('companies_services', 'companies_services.id', '=', 'transactions.cs_id')
                    ->join('companies', 'companies.id', '=', 'companies_services.company_id')
                    ->where('companies.id', '=', auth()->user()->company_id)
                    ->orderBy('created_at', 'asc')
                    ->groupBy('transactions.id')

                    //                    ->seletRaw('distinct(transactions.id) as idd')
                    ->get();
            }
        }


        $s = DataTables::of($result)

            ->make(true);

        return $s;
    }


    public function report(Request $request){

        $minDate = $request->minDate;
        $maxDate = $request->maxDate;
        $service = $request->service;
        $type = $request->type;

        $res = DB::table('transactions')->select('transactions.*', 'services.name')
            ->join('companies_services', 'companies_services.id', '=', 'transactions.cs_id')
            ->join('companies', 'companies.id', '=', 'companies_services.company_id')
            ->join('services', 'services.id', '=', 'transactions.service_id')
            ->where('companies.id', auth()->user()->company_id);

        if($minDate){
            $res = $res->where('transactions.created_at', '>=',$minDate );
        }

        if($maxDate){
            $res = $res->where('transactions.created_at', '<=',$maxDate );
        }
        if($service){
            $res = $res->where('transactions.service_id', '=', $service);
        }
        if($type){
            $res = $res->where('transactions.type', '=', $type);
        }
        $res = $res->get();

        $s = DataTables::of($res)
            ->addColumn('sender', function ($service) {
                if($service->type == 1){
                    if ($service->c_s_id){
                        $c = Company::where('id', $service->c_s_id)->first();
                        return $c->name;
                    }else{
                        $m = MobileUser::where('id', $service->u_s_id)->first();
                        return $m->phone;
                    }
                }
                else if($service->type == 2){
                    $p = Partner::where('id', $service->p_s_id)->first();
                    return $p->name;
                }
                else if($service->type == 3){
                    $m = MobileUser::where('id', $service->u_s_id)->first();
                    return $m->phone;
                }
                else if($service->type == 5){
                    $m = MobileUser::where('id', $service->u_s_id)->first();
                    return $m->phone;
                }


            })
            ->addColumn('reciever', function ($service) {
                if($service->type == 1){

                    $m = MobileUser::where('id', $service->u_r_id)->first();
                    return $m->phone;

                }else if($service->type == 2){
                    $p = Company::where('id', $service->c_r_id)->first();
                    return $p->name;
                }else if($service->type == 3){
                    $m = User::where('id', $service->u_r_id)->first();
                    return $m->name;
                }
                else if($service->type == 5){
                    $p = Company::where('id', $service->c_r_id)->first();
                    return $p->name;
                }else{
                    $p = Company::where('id', $service->c_r_id)->first();
                    return $p->name;
                }
            })
            ->addColumn('sender_name', function ($service) {
                if($service->type == 1){
                    if (!$service->c_s_id){
                        $m = MobileUser::where('id', $service->u_s_id)->first();
                        $gr = Group::where('company_id', auth()->user()->company_id)->get();
                        foreach ($gr as $g){
                            $gu = GroupsUser::where('group_id', $g->id)
                                ->where('mobile_user_id', $m->id)
                                ->first();
                            if($gu){
                                return $gu->username;
                            }
                        }
                    }


                }

                else if($service->type == 3){
                    $m = MobileUser::where('id', $service->u_s_id)->first();
                    $gr = Group::where('company_id', auth()->user()->company_id)->get();
                    foreach ($gr as $g){
                        $gu = GroupsUser::where('group_id', $g->id)
                            ->where('mobile_user_id', $m->id)
                            ->first();
                        if($gu){
                            return $gu->username;
                        }
                    }
                }
                else if($service->type == 5){
                    $m = MobileUser::where('id', $service->u_s_id)->first();
                    $gr = Group::where('company_id', auth()->user()->company_id)->get();
                    foreach ($gr as $g){
                        $gu = GroupsUser::where('group_id', $g->id)
                            ->where('mobile_user_id', $m->id)
                            ->first();
                        if($gu){
                            return $gu->username;
                        }
                    }
                }


            })
            ->addColumn('reciever_name', function ($service) {
                if($service->type == 1){
                    $m = MobileUser::where('id', $service->u_r_id)->first();
                    $gr = Group::where('company_id', auth()->user()->company_id)->get();
                    foreach ($gr as $g){
                        $gu = GroupsUser::where('group_id', $g->id)
                            ->where('mobile_user_id', $m->id)
                            ->first();
                        if($gu){
                            return $gu->username;
                        }
                    }

                }
            })


            ->make(true);

        return $s;

    }


}
