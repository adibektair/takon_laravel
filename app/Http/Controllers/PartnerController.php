<?php

namespace App\Http\Controllers;

use App\Partner;
use App\User;
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
        $partners = Partner::all();
        return view('partners/partners')->with(['partners' => $partners]);

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
            $user->name = 'Админ ' . $request->name;
            $user->email = $request->login;
            $user->password = Hash::make($request->password);
            $user->save();
        }

        $partners = Partner::all();

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
    public function edit(Partner $partner)
    {
        //
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

    public function getPartners(){
        return datatables(Partner::all())->toJson();
    }
}
