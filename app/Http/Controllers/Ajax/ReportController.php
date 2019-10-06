<?php

namespace App\Http\Controllers\Ajax;

use App\Company;
use App\Group;
use App\GroupsUser;
use App\MobileUser;
use App\Partner;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ReportController extends Controller
{
    public function reportByCompany(Request $request)
    {
        $minDate = $request->minDate;
        $maxDate = $request->maxDate;
        $company = $request->company;
//        $type = $request->type;

        $res = DB::table('transactions')->select('transactions.*', 'services.name')
            ->join('companies_services', 'companies_services.id', '=', 'transactions.cs_id')
            ->join('companies', 'companies.id', '=', 'companies_services.company_id')
            ->join('services', 'services.id', '=', 'transactions.service_id')
            ->where('companies.id', $company);


        if ($minDate) {
            $res = $res->where('transactions.created_at', '>=', $minDate);
        }

        if ($maxDate) {
            $res = $res->where('transactions.created_at', '<=', $maxDate);
        }
//        if ($service) {
//            $res = $res->where('transactions.service_id', '=', $service);
//        }
//        if ($type) {
//            $res = $res->where('transactions.type', '=', $type);
//        }
        $res = $res->get();

        $s = DataTables::of($res)
            ->addColumn('sender', function ($service) {
                if ($service->type == 1) {
                    if ($service->c_s_id) {
                        $c = Company::where('id', $service->c_s_id)->first();
                        return $c->name;
                    } else {
                        $m = MobileUser::where('id', $service->u_s_id)->first();
                        return $m->phone;
                    }
                } else if ($service->type == 2) {
                    $p = Partner::where('id', $service->p_s_id)->first();
                    return $p->name;
                } else if ($service->type == 3) {
                    $m = MobileUser::where('id', $service->u_s_id)->first();
                    return $m->phone;
                } else if ($service->type == 5) {
                    $m = MobileUser::where('id', $service->u_s_id)->first();
                    return $m->phone;
                }


            })
            ->addColumn('reciever', function ($service) use ($company) {
                if ($service->type == 1) {

                    $m = MobileUser::where('id', $service->u_r_id)->first();
                    return $m->phone;

                } else if ($service->type == 2) {
                    $p = Company::where('id', $service->c_r_id)->first();
                    return $p->name;
                } else if ($service->type == 3) {
                    $m = User::where('id', $service->u_r_id)->first();
                    return $m->name;
                } else if ($service->type == 5) {
                    $p = Company::where('id', $service->c_r_id)->first();
                    return $p->name;
                } else {
                    $p = Company::where('id', $service->c_r_id)->first();
                    return $p->name;
                }
            })
            ->addColumn('sender_name', function ($service) use ($company) {
                if ($service->type == 1) {
                    if (!$service->c_s_id) {
                        $m = MobileUser::where('id', $service->u_s_id)->first();
                        $gr = Group::where('company_id', $company)->get();
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
                    $gr = Group::where('company_id', $company)->get();
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
                    $gr = Group::where('company_id', $company)->get();
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
            ->addColumn('reciever_name', function ($service) use ($company) {
                if ($service->type == 1) {
                    $m = MobileUser::where('id', $service->u_r_id)->first();
                    $gr = Group::where('company_id', $company)->get();
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
}
