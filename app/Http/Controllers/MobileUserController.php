<?php

namespace App\Http\Controllers;

use App\MobileUser;
use Illuminate\Http\Request;

class MobileUserController extends Controller
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
     * @param  \App\MobileUser  $mobileUser
     * @return \Illuminate\Http\Response
     */
    public function show(MobileUser $mobileUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MobileUser  $mobileUser
     * @return \Illuminate\Http\Response
     */
    public function edit(MobileUser $mobileUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MobileUser  $mobileUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MobileUser $mobileUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MobileUser  $mobileUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(MobileUser $mobileUser)
    {
        //
    }

    public function all(){
        $users = MobileUser::all();
        return datatables($users)->toJson();
    }
}
