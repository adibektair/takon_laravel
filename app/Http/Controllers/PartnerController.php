<?php

namespace App\Http\Controllers;

use App\Partner;
use App\Service;
use App\User;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class PartnerController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('partners/partners');

//        return datatables(Partner::all())->toJson();
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('partners/createpartner');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = new Partner;
        $model->name = $request->name;
        $model->phone = $request->phone;
        $model->address = $request->address;
        if($model->save()){
            $user = new User;
            $user->role_id = 2;
            $user->partner_id = $model->id;
            $user->name = 'Админ ' . $request->name;
            $user->email = $request->login;
            $user->password = Hash::make($request->password);
            $user->save();
        }

        $partners = Partner::all();
        toastr()->success('Партнер успешно добавлен!');
        return view('partners/partners')->with(['partners' => $partners]);

    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function show(Partner $partner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {
        $model = Partner::where('id', '=', $request->id)->first();
        $model->name = $request->name;
        $model->phone = $request->address;
        $model->address = $request->address;
        $imageName = $model->name . '.' . request()->avatar->getClientOriginalExtension();
        request()->avatar->move(public_path('avatars'), $imageName);
        $model->image_path = $imageName;
        $model->save();
        toastr()->success('Профиль успешно обновлен');
        return view('profile/index');

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Partner $partner)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Partner $partner)
    {
        //
    }

    public function getServicesPage(Request $request){

        $partner = Partner::where('id', '=', $request->id)->first();

        return view('partners/services')->with(['id' => $request->id, 'name' => $partner->name]); //
    }

    public function buyCurrentService(Request $request) {
        $service = Service::where('id', '=', $request->id)->first();
        return view('companies/form')->with(['service' => $service]);
    }

    public function buyService(Request $request) {
        $service = Service::where('id', '=', $request->id)->first();
        $amount = $request->amount;
        $cost = $amount * $service->price;
        $order = new Order();
        $order->amount = $amount;
        $order->user_id = auth()->user()->id;
        $order->service_id = $service->id;
        $order->cost = $cost;
        $order->save();
        toastr()->success('Ваша заявка успешно добавлена!');
        return redirect()->route('company.services');
    }


    public function getPartnersServices(Request $request){
        return datatables(Service::where('partner_id', '=', $request->id)->get())->toJson();
    }

    public function getPartners(){
                return datatables(Partner::all())->toJson();
    }
}
