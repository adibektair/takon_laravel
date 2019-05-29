<?php

namespace App\Http\Controllers;

use App\User;
use Dotenv\Validator;
use Grimthorr\LaravelToast\Toast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('employees/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('employees/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = new User();
        $user->role_id = 4;
        $user->partner_id = auth()->user()->partner_id;
        $user->company_id = auth()->user()->company_id;
        $user->email = $request->email;
        $user->password = md5($request->password);
        $user->name = $request->name;
        if($user->save()){

            toastr()->success('Сотрудник успешно добавлен!');
            return view('employees/index');
        }else{
            abort(400);
        }



    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }

    public function all(){

        $users = User::where('partner_id', '=', auth()->user()->partner_id)->where('role_id', '=', 4)->get();

        return datatables($users)->toJson();

    }
}
