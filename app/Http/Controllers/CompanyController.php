<?php

namespace App\Http\Controllers;

use App\CompaniesService;
use App\Company;
use App\MobileUser;
use App\Service;
use App\User;
use App\UsersService;
use Grimthorr\LaravelToast\Toast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

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
            // TODO: Stats
            $c_service = CompaniesService::where('id', '=', $service_ids[$k])->first();
            $m_service = new UsersService();
            $m_service->mobile_user_id = $v;
            $m_service->service_id = $c_service->service_id;
            $m_service->amount = $request->amount[$k];
            $m_service->save();
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
        return datatables($services)->toJson();
    }

    public function all(){

            $companies = Company::all();
            return datatables($companies)->toJson();
    }
}
