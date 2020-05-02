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
use Illuminate\Support\Facades\Auth;
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


    public function cashier()
    {
        return view('transactions/cashier');
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


    public function cashierGet()
    {
        $model = DB::table('transactions')
            ->where('u_r_id', auth()->user()->id)
            ->where('type', 3)
            ->join('services', 'services.id', '=', 'transactions.service_id')
            ->join('mobile_users', 'mobile_users.id', '=', 'transactions.u_s_id')
            ->join('users', 'users.id', '=', 'transactions.u_r_id')
            ->select('transactions.*', 'services.name as service', 'mobile_users.phone as phone', 'users.email')
            ->orderBy('transactions.id', 'desc');
        $s = DataTables::of($model)
            ->make(true);
        return $s;
    }

    public function searchMake(Request $request)
    {
        if (auth()->user()->role_id == 3) {
            $phone = $request->phone;
            $result = DB::table('transactions')
                ->where('users.phone', $phone)
                ->orWhere('musers.phone', $phone)
                ->leftJoin('mobile_users as users', 'users.id', '=', 'transactions.u_s_id')
                ->leftJoin('mobile_users as musers', 'musers.id', '=', 'transactions.u_r_id')
                ->join('services', 'services.id', '=', 'transactions.service_id')
                ->join('companies_services', 'companies_services.id', '=', 'transactions.cs_id')
                ->where('companies_services.company_id', '=', auth()->user()->company_id)
                ->select('transactions.*', 'musers.phone as muserphone', 'users.phone as sender', 'services.name as service');
            $s = DataTables::of($result)
                ->addColumn('reciever', function ($service) {
                    if ($service->type == 3) {
                        return 'Использовано';
                    } else {
                        return $service->muserphone;
                    }
                })
                ->addColumn('sender', function ($service) use ($phone) {
                    if ($service->c_s_id) {
                        $c = Company::where('id', $service->c_s_id)->first();
                        return $c->name;
                    } else {
                        return $service->sender;
                    }

                })
                ->rawColumns(['reciever', 'sender'])
                ->make(true);
            return $s;
        } else {
            $phone = $request->phone;
            $result = DB::table('transactions')
                ->where('users.phone', $phone)
                ->orWhere('musers.phone', $phone)
                ->leftJoin('mobile_users as users', 'users.id', '=', 'transactions.u_s_id')
                ->leftJoin('mobile_users as musers', 'musers.id', '=', 'transactions.u_r_id')
                ->join('services', 'services.id', '=', 'transactions.service_id')
                ->select('transactions.*', 'musers.phone as muserphone', 'users.phone as sender', 'services.name as service');
            $s = DataTables::of($result)
                ->addColumn('reciever', function ($service) {
                    if ($service->type == 3) {
                        return 'Использовано';
                    } else {
                        return $service->muserphone;
                    }
                })
                ->addColumn('sender', function ($service) use ($phone) {
                    if ($service->c_s_id) {
                        $c = Company::where('id', $service->c_s_id)->first();
                        return $c->name;
                    } else {
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
     * @param  \App\Transaction $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Transaction $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Transaction $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Transaction $transaction
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

    public function partnerMore(Request $request)
    {
        return view('transactions/partner-more')->with(['id' => $request->id]);
    }

    public function adminMore(Request $request)
    {
        return view('transactions/admin-more')->with(['id' => $request->id]);
    }

    public function adminEtc(Request $request)
    {
        return view('transactions/admin-etc')->with(['id' => $request->id]);
    }

    public function partnerEtc(Request $request)
    {
        return view('transactions/partner-etc')->with(['id' => $request->id]);
    }

    public function companyEtc(Request $request)
    {
        return view('transactions/company-etc')->with(['id' => $request->id]);
    }

    public function adminMoreGet(Request $request)
    {

        $result = DB::table('transactions')
            ->where('parent_id', $request->id)
            ->whereIn('type', [1, 4])
            ->join('services', 'services.id', '=', 'transactions.service_id')
            ->join('companies', 'companies.id', '=', 'transactions.c_s_id')
            ->leftJoin('companies as c', 'c.id', '=', 'transactions.c_r_id')
            ->leftJoin('mobile_users', 'mobile_users.id', '=', 'transactions.u_r_id')
            ->select('transactions.*', 'services.name as service', 'companies.name as sender', 'c.name as company', 'mobile_users.phone as user');

        $s = DataTables::of($result)
            ->addColumn('1', function ($service) {
                if ($service->user) {
                    return '<a href="/transactions/admin/etc?id=' . $service->id . '"><button class="btn btn-success">Подробнее</button></a>';
                }
//                    return '<a href="/transactions/admin/etc?id=' . $service->id . '"><button class="btn btn-success">Подробнее</button></a>';
            })
            ->addColumn('2', function ($service) {
                if ($service->company) {
                    return $service->company;
                }
                return $service->user;
            })
            ->addColumn('3', function ($service) {
                if ($service->type == 4) {
                    return $service->price * $service->amount . ' тенге' . ' (Перевод)';
                }
                return $service->price * $service->amount . ' тенге';
            })
            ->make(true);

        return $s;
    }

    public function partnerMoreGet(Request $request)
    {

        $result = DB::table('transactions')
            ->where('cs_id', $request->id)
            ->whereIn('type', [3])
            ->join('services', 'services.id', '=', 'transactions.service_id')
            ->leftJoin('companies as c', 'c.id', '=', 'transactions.c_r_id')
            ->leftJoin('mobile_users', 'mobile_users.id', '=', 'transactions.u_s_id')
            ->leftJoin('users', 'users.id', '=', 'transactions.u_r_id')
            ->select('transactions.*', 'services.name as service', 'mobile_users.phone as sender', 'users.name as user');

        $s = DataTables::of($result)
            ->addColumn('1', function ($service) {
                if ($service->user) {
                    return '<a href="/transactions/partner/etc?id=' . $service->id . '"><button class="btn btn-success">Подробнее</button></a>';
                }
            })
            ->addColumn('2', function ($service) {

                return $service->user;
            })
            ->addColumn('3', function ($service) {
                if ($service->type == 4) {
                    return $service->price * $service->amount . ' тенге' . ' (Перевод)';
                }
                return $service->price * $service->amount . ' тенге';
            })
            ->make(true);

        return $s;
    }

    public function companyMoreGet(Request $request)
    {

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
            ->select('transactions.*', 'services.name as service', 'companies.name as sender', 'c.name as company', 'mobile_users.phone as user', 'mobile_users.name as user_name');

//        }

        $s = DataTables::of($result)
            ->addColumn('1', function ($service) {
                if ($service->user) {
                    return '<a href="/transactions/company/etc?id=' . $service->id . '"><button class="btn btn-success">Подробнее</button></a>';
                }
            })
            ->addColumn('2', function ($service) {
                if ($service->company) {
                    return $service->company;
                }
                return $service->user;
            })
            ->addColumn('3', function ($service) {
                if ($service->type == 4) {
                    return $service->price * $service->amount . ' тенге' . ' (Перевод)';
                }
                return $service->price * $service->amount . ' тенге';
            })
            ->make(true);

        return $s;

    }

    public function adminEtcGet(Request $request)
    {

        $result = DB::table('transactions')
            ->where('parent_id', $request->id)
            ->join('services', 'services.id', '=', 'transactions.service_id')
            ->leftJoin('companies', 'companies.id', '=', 'transactions.c_s_id')
            ->leftJoin('companies as c', 'c.id', '=', 'transactions.c_r_id')
            ->leftJoin('mobile_users', 'mobile_users.id', '=', 'transactions.u_r_id')
            ->leftJoin('mobile_users as m', 'm.id', '=', 'transactions.u_s_id')
            ->select('transactions.*', 'services.name as service', 'services.partner_id as partner_id', 'companies.name as c1', 'c.name as c2', 'mobile_users.phone as u1', 'm.phone as u2');

        $s = DataTables::of($result)
            ->addColumn('1', function ($service) {

                if ($service->type == 3) {
                    $partner = Partner::where('id', $service->partner_id)->first();

                    return 'Потрачено у ' . $partner->name;
                }
                if ($service->c2) {
                    return $service->c2;
                }
                return $service->u1;

            })
            ->addColumn('2', function ($service) {
                if ($service->c1) {
                    return $service->c1;
                }
                return $service->u2;
            })
            ->addColumn('3', function ($service) {
                return $service->price * $service->amount . ' тенге';
            })
            ->make(true);

        return $s;
    }

    public function partnerEtcGet(Request $request)
    {

        $result = DB::table('transactions')
            ->where('parent_id', $request->id)
            ->join('services', 'services.id', '=', 'transactions.service_id')
            ->leftJoin('companies', 'companies.id', '=', 'transactions.c_s_id')
            ->leftJoin('companies as c', 'c.id', '=', 'transactions.c_r_id')
            ->leftJoin('mobile_users', 'mobile_users.id', '=', 'transactions.u_r_id')
            ->leftJoin('mobile_users as m', 'm.id', '=', 'transactions.u_s_id')
            ->select('transactions.*', 'services.name as service', 'services.partner_id as partner_id', 'companies.name as c1', 'c.name as c2', 'mobile_users.phone as u1', 'm.phone as u2');

        $s = DataTables::of($result)
            ->addColumn('1', function ($service) {

                if ($service->type == 3) {
                    $partner = Partner::where('id', $service->partner_id)->first();

                    return 'Потрачено у ' . $partner->name;
                }
                if ($service->c2) {
                    return $service->c2;
                }
                return $service->u1;

            })
            ->addColumn('2', function ($service) {
                if ($service->c1) {
                    return $service->c1;
                }
                return $service->u2;
            })
            ->addColumn('3', function ($service) {
                return $service->price * $service->amount . ' тенге';
            })
            ->make(true);

        return $s;
    }


    public function companyEtcGet(Request $request)
    {

        $result = DB::table('transactions')
            ->where('parent_id', $request->id)
            ->join('services', 'services.id', '=', 'transactions.service_id')
            ->leftJoin('companies', 'companies.id', '=', 'transactions.c_s_id')
            ->leftJoin('companies as c', 'c.id', '=', 'transactions.c_r_id')
            ->leftJoin('mobile_users', 'mobile_users.id', '=', 'transactions.u_r_id')
            ->leftJoin('mobile_users as m', 'm.id', '=', 'transactions.u_s_id')
            ->select('transactions.*', 'services.name as service', 'services.partner_id as partner_id', 'companies.name as c1', 'c.name as c2', 'mobile_users.phone as u1', 'm.phone as u2');

        $s = DataTables::of($result)
            ->addColumn('1', function ($service) {

                if ($service->type == 3) {
                    $partner = Partner::where('id', $service->partner_id)->first();

                    return 'Потрачено у ' . $partner->name;
                }
                if ($service->c2) {
                    return $service->c2;
                }
                return $service->u1;

            })
            ->addColumn('2', function ($service) {
                if ($service->c1) {
                    return $service->c1;
                }
                return $service->u2;
            })
            ->addColumn('3', function ($service) {
                return $service->price * $service->amount . ' тенге';
            })
            ->make(true);

        return $s;
    }

    public function adminAll()
    {
        $result = DB::table('transactions')
            ->where('parent_id', Null)
            ->where('type', 2)
            ->join('services', 'services.id', '=', 'transactions.service_id')
            ->join('companies', 'companies.id', '=', 'transactions.c_r_id')
            ->join('partners', 'partners.id', '=', 'transactions.p_s_id')
            ->select('transactions.*', 'services.name as service', 'companies.name as company', 'partners.name as partner');

        $s = DataTables::of($result)
            ->addColumn('1', function ($service) {
                return '<a href="/transactions/admin/more?id=' . $service->id . '"><button class="btn btn-success">Подробнее</button></a>';
            })
            ->addColumn('2', function ($service) {
                if ($service->type == 4) {
                    return $service->price * $service->amount . ' (переданные)';
                }
                return $service->price * $service->amount . ' тенге';
            })
            ->addColumn('3', function ($service) {
                $cs = CompaniesService::where('id', $service->cs_id)->first();
                if ($cs) {
                    return $cs->amount;
                } else {
                    return 0;
                }

            })
            ->make(true);
        return $s;
    }


    public function partnerAll()
    {
        $result = DB::table('transactions')
            ->where('parent_id', Null)
            ->whereIn('type', [2, 4])
            ->where('p_s_id', auth()->user()->partner_id)
            ->leftJoin('services', 'services.id', '=', 'transactions.service_id')
            ->leftJoin('companies', 'companies.id', '=', 'transactions.c_r_id')
            ->leftJoin('partners', 'partners.id', '=', 'transactions.p_s_id')
            ->select('transactions.*', 'services.name as service', 'companies.name as company', 'partners.name as partner');


        $s = DataTables::of($result)
            ->addColumn('1', function ($service) {
                return '<a href="/transactions/partner/more?id=' . $service->cs_id . '"><button class="btn btn-success">Подробнее</button></a>';
            })
            ->addColumn('2', function ($service) {
                if ($service->type == 4) {
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
                foreach ($tr as $v) {
                    $a += $v->amount;
                }
                return $a;
            })
            ->addColumn('5', function ($service) {
                $cs = CompaniesService::where('id', $service->cs_id)->first();

                $all = UsersService::where('cs_id', $service->cs_id)->get();
                $amount = 0;
                foreach ($all as $v) {
                    $amount += $v->amount;
                }
                return $amount + $cs->amount;
            })
            ->make(true);
        return $s;
    }


    public function companyAll()
    {
        $result = DB::table('transactions')
            ->whereIn('type', [2, 4])
            ->where('c_r_id', auth()->user()->company_id)
            ->join('services', 'services.id', '=', 'transactions.service_id')
            ->leftJoin('partners', 'partners.id', '=', 'services.partner_id')
            ->select('transactions.*', 'services.name as service', 'partners.name as partner');

        $s = DataTables::of($result)
            ->addColumn('1', function ($service) {
                return '<a href="/transactions/company/more?id=' . $service->id . '"><button class="btn btn-success">Подробнее</button></a>';
            })
            ->addColumn('2', function ($service) {
                if ($service->type == 4) {
                    return $service->price * $service->amount . ' (переданные)';
                }
                return $service->price * $service->amount . ' тенге';
            })
            ->addColumn('3', function ($service) {
                $cs = CompaniesService::where('id', $service->cs_id)->first();

                $all = UsersService::where('cs_id', $service->cs_id)->get();
                $amount = 0;
                foreach ($all as $v) {
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
                foreach ($all as $v) {
                    $amount += $v->amount;
                }
                return $service->amount - ($amount + $cs->amount);
            })
            ->make(true);
        return $s;
    }


    public function returnAll()
    {

        if (auth()->user()->role_id == 1) {
            $result = DB::table('transactions')
                ->where('type', 5)
                ->join('services', 'services.id', '=', 'transactions.service_id')
                ->join('companies', 'companies.id', '=', 'transactions.c_r_id')
                ->join('mobile_users', 'mobile_users.id', '=', 'transactions.u_s_id')
                ->select('transactions.*', 'services.name as service', 'companies.name as company', 'mobile_users.phone as user')
                ->get();
        } else {
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

    public function paymentsAll()
    {
        $result = DB::table('transactions')
            ->where('type', 6)
            ->join('services', 'services.id', '=', 'transactions.service_id')
            ->join('mobile_users', 'mobile_users.id', '=', 'transactions.u_r_id')
            ->select('transactions.*', 'services.name as service', 'mobile_users.phone as sender');

        $s = DataTables::of($result)->make(true);

        return $s;
    }

    public function useAll(Request $request)
    {
        $query = DB::table('transactions')
            ->where('transactions.type', 3)
            ->join('services', 'services.id', '=', 'transactions.service_id')
            ->leftJoin('mobile_users', 'mobile_users.id', '=', 'transactions.u_s_id');
        if ($request->id) {
            $query = $query->where('transactions.service_id', $request->id);
        }
        if (auth()->user()->role_id == 1 OR auth()->user()->role_id == 4) {
            $query = $query
                ->leftJoin('users', 'users.id', '=', 'transactions.u_r_id')
                ->leftJoin('companies_services', 'companies_services.id', '=', 'transactions.cs_id')
                ->leftJoin('companies', 'companies.id', '=', 'companies_services.company_id')
                ->select(
                    'transactions.*',
                    'services.name as service',
                    'mobile_users.phone as sender',
                    'mobile_users.name as username',
                    'users.name as reciever',
                    'companies.name as company');
        } else {
            $query = $query
                ->leftJoin('users', 'users.id', '=', 'transactions.u_r_id')
                ->leftJoin('groups_users', 'groups_users.mobile_user_id', '=', 'mobile_users.id')
                ->leftJoin('groups', 'groups.id', '=', 'groups_users.group_id')
                ->join('companies_services', 'companies_services.id', '=', 'transactions.cs_id')
                ->join('companies', 'companies.id', '=', 'companies_services.company_id')
                ->where('companies.id', '=', auth()->user()->company_id)
//                ->groupBy('transactions.id')
                ->select(
                    'transactions.*',
                    'services.name as service',
                    'groups_users.username as username',
                    'mobile_users.phone as sender',
                    'users.name as reciever');
        }
        $result = $query->orderBy('created_at', 'asc');

        $s = DataTables::of($result)
            ->make(true);

        return $s;
    }


    public function report(Request $request)
    {


        $minDate = $request->minDate;
        $maxDate = $request->maxDate;
        $service = $request->service;
        $type = $request->type;

        $res = DB::table('transactions')->select('transactions.*', 'services.name')
            ->join('companies_services', 'companies_services.id', '=', 'transactions.cs_id')
            ->join('companies', 'companies.id', '=', 'companies_services.company_id')
            ->join('services', 'services.id', '=', 'transactions.service_id')
            ->where('companies.id', auth()->user()->company_id);


        if ($minDate) {
            $res = $res->where('transactions.created_at', '>=', $minDate);
        }

        if ($maxDate) {
            $res = $res->where('transactions.created_at', '<=', $maxDate);
        }
        if ($service) {
            $res = $res->where('transactions.service_id', '=', $service);
        }
        if ($type) {
            $res = $res->where('transactions.type', '=', $type);
        }


        $s = DataTables::of($res)
            ->addColumn('sender', function ($service) {
                if ($service->type == 1) {
                    if ($service->c_s_id) {
                        $c = Company::where('id', $service->c_s_id)->first();
                        if ($c) {
                            return $c->name;
                        } else {
                            return '';
                        }
                    } else {
                        $m = MobileUser::where('id', $service->u_s_id)->first();
                        if ($m) {
                            return $m->phone;
                        } else {
                            return '';
                        }
                    }
                } else if ($service->type == 2) {
                    $p = Partner::where('id', $service->p_s_id)->first();
                    if ($p) {
                        return $p->name;
                    } else {
                        return '';
                    }
                } else if ($service->type == 3) {
                    $m = MobileUser::where('id', $service->u_s_id)->first();
                    if ($m) {
                        return $m->phone;
                    } else {
                        return '';
                    }
                } else if ($service->type == 5) {
                    $m = MobileUser::where('id', $service->u_s_id)->first();
                    if ($m) {
                        return $m->phone;
                    } else {
                        return '';
                    }
                }
            })
            ->addColumn('reciever', function ($service) {
                if ($service->type == 1) {

                    $m = MobileUser::where('id', $service->u_r_id)->first();
                    if ($m) {
                        return $m->name;
                    } else {
                        return '';
                    }

                } else if ($service->type == 2) {
                    $p = Company::where('id', $service->c_r_id)->first();
                    if ($p) {
                        return $p->name;
                    } else {
                        return '';
                    }
                } else if ($service->type == 3) {
                    $m = User::where('id', $service->u_r_id)->first();
                    if ($m) {
                        return $m->name;
                    } else {
                        return '';
                    }
                } else if ($service->type == 5) {
                    $p = Company::where('id', $service->c_r_id)->first();
                    if ($p) {
                        return $p->name;
                    } else {
                        return '';
                    }
                } else {
                    $p = Company::where('id', $service->c_r_id)->first();
                    if ($p) {
                        return $p->name;
                    } else {
                        return '';
                    }
                }
            })
            ->addColumn('sender_name', function ($service) {
                if ($service->type == 1) {
                    if (!$service->c_s_id) {
                        $m = MobileUser::where('id', $service->u_s_id)->first();
                        $gr = Group::where('company_id', auth()->user()->company_id)->get();
                        foreach ($gr as $g) {
                            $gu = GroupsUser::where('group_id', $g->id)
                                ->where('mobile_user_id', $m->id)
                                ->first();
                            if ($gu) {
                                return $gu->username;
                            }
                        }
                    }
                } else if ($service->type == 3) {
                    $m = MobileUser::where('id', $service->u_s_id)->first();
                    $gr = Group::where('company_id', auth()->user()->company_id)->get();
                    foreach ($gr as $g) {
                        $gu = GroupsUser::where('group_id', $g->id)
                            ->where('mobile_user_id', $m->id)
                            ->first();
                        if ($gu) {
                            return $gu->username;
                        }
                    }
                } else if ($service->type == 5) {
                    $m = MobileUser::where('id', $service->u_s_id)->first();
                    $gr = Group::where('company_id', auth()->user()->company_id)->get();
                    foreach ($gr as $g) {
                        $gu = GroupsUser::where('group_id', $g->id)
                            ->where('mobile_user_id', $m->id)
                            ->first();
                        if ($gu) {
                            return $gu->username;
                        }
                    }
                }
            })
            ->addColumn('reciever_name', function ($service) {
                if ($service->type == 1) {
                    $m = MobileUser::where('id', $service->u_r_id)->first();
                    $gr = Group::where('company_id', auth()->user()->company_id)->get();
                    foreach ($gr as $g) {
                        $gu = GroupsUser::where('group_id', $g->id)
                            ->where('mobile_user_id', $m->id)
                            ->first();
                        if ($gu) {
                            return $gu->username;
                        }
                    }

                }
            })
            ->make(true);

        return $s;

    }

    public function reportTest(Request $request)
    {


        $minDate = $request->minDate;
        $maxDate = $request->maxDate;
        $mobileUserId = $request->mobileUserId;
        if ($maxDate) {
            $maxDate = $maxDate . ' 23:59:59';
        }
        $serviceId = $request->serviceId;
//        $service = $request->service;
//        $type = $request->type;

        $report = DB::select('select
              t.id,
               case
                 when t.type = 1
                         then case
                                when company_sender.id is not null then company_sender.phone
                                when mobile_user_sender.id is not null then mobile_user_sender.phone
                   end
                   when t.type = 2
                         then p.phone
                 when t.type = 3
                         then mobile_user_sender.phone
                 when t.type = 4
                         then p.phone
                 when t.type = 5
                         then mobile_user_sender.phone
                   end sender,
               case
                 when t.type = 1
                         then case
                                when company_sender.id is not null then company_sender.name
                                when mobile_user_sender.id is not null then mobile_user_sender.name
                   end
               when t.type = 2
                     then p.name
                 when t.type = 3
                         then mobile_user_sender.name
                 when t.type = 4
                         then p.name
                 when t.type = 5
                         then mobile_user_sender.phone
                   end sender_name,
               s.name  service_name,
               case
                 when t.type in (1,3)
                         then t.amount
                   end sent,
               case
                   when t.type in (2)
                     then t.amount
                 when t.type in(4, 5)
                         then t.amount
                   end received,
               t.created_at
        from transactions t
               inner join companies_services cs on t.cs_id = cs.id
               inner join services s on cs.service_id = s.id
               inner join companies c on cs.company_id = c.id
               left join partners p on t.p_s_id = p.id
               left join mobile_users mobile_user_receiver on mobile_user_receiver.id = t.u_r_id
               left join users cashier_user_receiver on cashier_user_receiver.id = t.u_r_id
               left join companies company_receiver on company_receiver.id = t.c_r_id
               left join mobile_users mobile_user_sender on mobile_user_sender.id = t.u_s_id
               left join companies company_sender on company_sender.id = t.c_s_id
        
        WHERE c.id = ?
        ' . ($minDate ? ' and t.created_at >=  \'' . $minDate . '\'' : '') . '
        ' . ($maxDate ? ' and t.created_at <=  \'' . $maxDate . '\'' : '') . '
        ' . ($mobileUserId ? ' and mobile_user_sender.id =  ' . $mobileUserId : '') . '
        ' . ($serviceId ? ' and s.id =  ' . $serviceId : '') . '
        order by t.created_at asc', [Auth::user()->company_id]);
        return DataTables::of($report)->make(true);

    }


}
