<?php

namespace App\Http\Controllers;

use App\CloudMessage;
use App\CompaniesService;
use App\Company;
use App\MobileUser;
use App\Notification;
use App\Partner;
use App\Service;
use App\Transaction;
use App\User;
use App\UsersService;
use App\UsersSubscriptions;
use Grimthorr\LaravelToast\Toast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\DataTables;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('companies/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('companies/create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = new Company();
        $model->name = $request->name;
        $model->phone = $request->phone;
        $model->address = $request->address;
        if($model->save()){
            $user = new User;
            $user->role_id = 3;
            $user->company_id = $model->id;
            $user->name = 'Админ ' . $request->name;
            $user->email = $request->login;
            $user->password = Hash::make($request->password);
            $user->save();
        }
        toastr()->success('Юр. лицо успешно добавлено!');
        return view('companies/index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        //
    }
    public function sendTakons(Request $request){

        $service_ids = $request->service_id;
        $user_ids = $request->id;
        $r_s = array();
        foreach ($service_ids as $key => $id){
            if(array_key_exists($id, $r_s)){
                $r_s[$id] += $request->amount[$key];
            }else{
                $r_s[$id] = $request->amount[$key];
            }
        }
        foreach ($r_s as $key => $value){
            $service = CompaniesService::where('id', '=', $key)->first();
            if($service->amount < $value){
                 toast()->error('У Вас недостаточно таконов для данной операции!');
                 return view('mobile_users/index');
            }
        }


        foreach ($user_ids as $k => $v){

            $c_service = CompaniesService::where('id', '=', $service_ids[$k])->first();
            $m_service = new UsersService();
            $m_service->mobile_user_id = $v;
            $m_service->company_id = auth()->user()->company_id;
            $m_service->service_id = $c_service->service_id;
            $m_service->amount = $request->amount[$k];
            $m_service->deadline = $c_service->deadline;

            $serv = Service::where('id', $c_service->service_id)->first();
            $partner = Partner::where('id', $serv->partner_id)->first();
            $subs = UsersSubscriptions::where('mobile_user_id', $v)
                ->where('partner_id', $serv->partner_id)
                ->first();
            if(!$subs){
                $subs = new UsersSubscriptions();
                $subs->mobile_user_id = $v;
                $subs->partner_id = $serv->partner_id;
                $subs->save();
            }
            $user = MobileUser::where('id', $v)->first();
            $message = new CloudMessage("Вам были отправлены Таконы " . $serv->name, $user->push_id, $user->platform, "Внимение", $serv->partner_id, $partner->name);
            $message->sendNotification();


            if($m_service->save()){
                if($request->amount[$k] > 0){
                    $exactly_service = Service::where('id', '=', $c_service->service_id)->first();
                    $parent = Transaction::where('service_id', $exactly_service->id)
                        ->where('c_r_id', auth()->user()->company_id)
                        ->where('u_s_id', null)
                        ->orderBy('created_at', 'desc')->first();

                    $model = new Transaction();
                    if ($parent->parent_id){
                        $model->parent_id = $parent->parent_id;
                    }else{
                        $model->parent_id = $parent->id;
                    }
                    $model->balance = $c_service->amount - $request->amount[$k];
                    $model->users_service_id = $m_service->id;
                    $model->type = 1;
                    $model->service_id = $c_service->service_id;
                    $model->c_s_id = auth()->user()->company_id;
                    $model->u_r_id = $v;
                    $model->price = $exactly_service->price;
                    $model->amount = $request->amount[$k];
                    $model->save();
                }

            }
            $c_service->amount -= $request->amount[$k];
            $c_service->save();
        }
        toastr()->success('Спасибо! Вы успешно отправили таконы своим пользователям!');
        return redirect()->route('company.services');

    }

    public function getServices(){

        $services = DB::table('companies_services')->where('company_id', '=', auth()->user()->company_id)
            ->join('services', 'services.id', '=', 'companies_services.service_id')
            ->join('partners', 'partners.id', '=', 'services.partner_id')
            ->select('companies_services.*', 'partners.name as partner', 'services.name as service', 'services.price')
            ->get();

        $s = DataTables::of($services)->addColumn('checkbox', function ($service) {
            return '<a href="/share-services?id=' . $service->id .'"><button class="btn btn-success">Передать</button></a> <a href="/mobile_users?id=' . $service->id .'"><button class="btn btn-outline-success">Раздать</button></a>';
        })
            ->addColumn('return', function($user){
                $date = date('Y-m-d', $user->deadline);
                return $date;
            })
            ->rawColumns(['return', 'checkbox'])
            ->make(true);





        return $s;
    }

    // Share takons with other companies
    public function shareServices(Request $request){
        $id = $request->id;
        $company_service = CompaniesService::where('id', '=', $id)->first();
        $service = Service::where('id', '=', $company_service->service_id)->first();
        return view('companies/share')->with(['com_ser' => $company_service, 'service' => $service]);
    }

    public function share(Request $request){
        $com_ser = CompaniesService::where('id', '=', $request->id)->first();
        $service = Service::where('id', '=', $com_ser->service_id)->first();
        $company = Company::where('id', '=', auth()->user()->company_id)->first();
        $reciever = Company::where('id', '=', $request->company_id)->first();
        if($com_ser->amount < $request->amount){
            toastr()->error('Ошибка. У Вас недостаточно таконов для данной операции');
            return redirect()->back();
        }
        $com_ser->amount -= $request->amount;
        $com_ser->save();

        $rec_ser = CompaniesService::where('service_id', '=', $com_ser->service_id)
            ->where('company_id', '=', $request->company_id)
            ->where('deadline', $com_ser->deadline)
            ->first();
        if($rec_ser){
            $rec_ser->amount += $request->amount;
        }else{
            $rec_ser = new CompaniesService();
            $rec_ser->service_id = $com_ser->service_id;
            $rec_ser->amount = $request->amount;
            $rec_ser->company_id = $request->company_id;
        }

        if($rec_ser->save()){
            $parent = Transaction::where('service_id', $service->id)
                ->where('c_r_id', auth()->user()->company_id)
                ->orderBy('created_at', 'desc')->first();


            $model = new Transaction();
            if ($parent->parent_id){
                $model->parent_id = $parent->parent_id;
            }else{
                $model->parent_id = $parent->id;
            }
            $model->type = 4;
            $model->balance = $com_ser->amount;
            $model->service_id = $service->id;
            $model->c_s_id = auth()->user()->company_id;
            $model->c_r_id = $reciever->id;
            $model->price = $service->price;
            $model->amount = $request->amount;
            $model->save();
        }
        toastr()->success('Спасибо. Ваши таконы были успешно переданы');
        $not = new Notification();
        $not->make('info', 'Внимание!', 'Вам было отправлено ' . $request->amount . ' таконов товара/услуги ' . $service->name . ' от компании ' . $company->name,
            null, $request->company_id, false);


        $not1 = new Notification();
        $not1->make('info', 'Внимание!',  $request->amount . ' таконов товара/услуги ' . $service->name . ' от компании ' . $company->name . ' были отправлены ' . $reciever->name,
            null, $request->company_id, true);

        return view('companies/services');
    }

    public function getReturn(){
        $users = DB::table('users_services')
            ->where('company_id', auth()->user()->company_id)
            ->where('users_services.amount', '<>', 0)
            ->join('services', 'services.id', '=', 'users_services.service_id')
            ->join('mobile_users', 'mobile_users.id', '=', 'users_services.mobile_user_id')
            ->select('users_services.*', 'mobile_users.phone', 'services.name as service')->get();

        return Datatables::of($users)
            ->addColumn('return', function($user){
                return '<a href="/return-takon?id=' . $user->id . '"><button class="btn btn-success">Возврат</button></a>';
            })
            ->rawColumns(['return'])
            ->make(true);
    }

    public function finish(Request $request){

        $us = UsersService::where('id', $request->id)->first();
        $user = MobileUser::where('id', $us->mobile_user_id)->first();
        $service = Service::where('id', $us->service_id)->first();
        $company = Company::where('id', auth()->user()->company_id)->first();
        if($us->amount < $request->amount){
            toastr()->error('Введите корректные данные');
            return \redirect()->back();
        }

        $us->amount -= $request->amount;
        $cs = CompaniesService::where('service_id', $service->id)
            ->where('company_id', $company->id)
            ->where('deadline', $us->deadline)
            ->first();

        if($us->save()){
            $model = new Transaction();
            $model->u_s_id =$user->id;
            $model->balance = $us->amount;
            $model->c_r_id = $company->id;
            $model->amount = $request->amount;
            $model->service_id = $service->id;
            $model->type = 5;
            $model->price = $service->price;
            $model->cs_id = $cs->id;
            $model->users_service_id = $us->id;
            $model->save();

        }

        if($cs){
            $cs->amount += $request->amount;
        }else{
            toastr()->error('Произошла непредвиденная ошибка, обратитесь к администратору приложения');
            return \redirect()->back();
        }
        $cs->save();

        toastr()->success('Таконы успешно возвращены');
        return view('companies/return');

    }

    public function returnTakon(Request $request){
        $us = UsersService::where('id', $request->id)->first();
        $user = MobileUser::where('id', $us->mobile_user_id)->first();
        $service = Service::where('id', $us->service_id)->first();
        return view('companies/finish-return')->with(['us' => $us, 'user' => $user, 'service' => $service]);
    }

    public function all(){

            $companies = Company::all();
            return Datatables::of($companies)
                ->addColumn('email', function($company){
                    $user = User::where('role_id', 3)->where('company_id', $company->id)->first();
                    return $user->email;

                })
                ->rawColumns(['email'])
                ->make(true);
//            return datatables($companies)->toJson();
    }
}
