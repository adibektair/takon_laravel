<?php

namespace App\Http\Controllers;

use App\MobileUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class MobileUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        return view('mobile_users/index')->with(['id' => $request->id]);
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
    public function send(Request $request)
    {
        $ids = $request->ids;
        $array = explode(',', $ids);

        return view('mobile_users/send')->with(['ids' => $ids, 'cs_id' => $request->cs_id]);
    }

    public function getUsersByIds(Request $request){
        $ids = $request->ids;
        $array = explode(',', $ids);
        $users = DB::table('mobile_users')->whereIn('id', $array)->get();

        $s = DataTables::of($users)->make();
        return $s;
    }

    public function all(){
//        $users = MobileUser::all();
        $users = DB::table('mobile_users')->get();
//        dd($users);
        $s = DataTables::of($users)->addColumn('checkbox', function ($user) {
            return '<button class="btn btn-info" data-name="'.$user->phone.'" id="'.$user->id.'">Выбрать</button>';
            })->make();
        return $s;
    }
}
