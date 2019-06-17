<?php

namespace App\Http\Controllers;

use App\User;
use Grimthorr\LaravelToast\Toast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;

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
            return redirect()->route('emplyees.index');
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
    public function edit(Request $request)
    {
        $id = $request->id;
        $user = User::where('id', $id)->first();
        return view('partners/user')->with(['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:42',
            'name' => 'required|string|max:255',
            'password' => 'required|string|max:255',

        ]);
        if ($validator->fails()) {
          
            toastr()->error($validator->errors());
            return redirect()->back();
        }

        $user = User::where('id', $request->id)->first();
        $user->email = $request->email;
        $user->name = $request->name;
        $user->password = Hash::make($request->password1);
        $user->save();
        toastr()->success('Успешно');
        return redirect()->route('emplyees.index');
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

        return Datatables::of($users)
            ->addColumn('edit', function($user){
                return '<a href="/edit-user?id=' . $user->id .'"><button class="btn btn-outline-info">Редактировать</button></a>';
            })
            ->rawColumns(['edit'])
            ->make(true);

    }
}
