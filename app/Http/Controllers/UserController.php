<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;
use Grimthorr\LaravelToast\Toast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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
        $user->password = md5($request->password);
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
    public function generateQR(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        if(!$user->hash){
            $user->hash = Str::random(50);
        }
        $user->save();
        return view('employees/qr')->with(['user' => $user]);
    }

    public function all(){

        $usersQuery = User::where('partner_id', '=', auth()->user()->partner_id)->where('role_id', '=', 4);

        return Datatables::of($usersQuery)
            ->addColumn('edit', function($user){
                return '<a href="/edit-user?id=' . $user->id .'"><button class="btn btn-outline-info">Редактировать</button></a>';
            })
            ->addColumn('qr', function($user){
                return '<a href="/generate-qr?id=' . $user->id .'"><button class="btn btn-info">Распечатать QR код</button></a>';
            })

            ->rawColumns(['edit', 'qr'])
            ->make(true);

    }

    public function admin_credential_rules(array $data)
    {
        $messages = [
            'current-password.required' => 'Пожалуйста введите текущий пароль',
            'password.required' => 'Пожалуйста введите пароль',
            'password_confirmation.required' => 'Пожалуйста введите повторный пароль'
        ];

        $validator = Validator::make($data, [
            'current-password' => 'required',
            'password' => 'required|same:password',
            'password_confirmation' => 'required|same:password',
        ], $messages);

        return $validator;
    }

    public function postCredentials(Request $request)
    {
        if(Auth::Check())
        {
            $request_data = $request->All();
            $validator = $this->admin_credential_rules($request_data);
            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else
            {
                $current_password = Auth::User()->password;
                if(Hash::check($request_data['current-password'], $current_password))
                {
                    $user_id = Auth::User()->id;
                    $obj_user = User::find($user_id);
                    $obj_user->password = Hash::make($request_data['password']);
                    $obj_user->save();
                    toastr()->success('Пароль успешно обновлён!');
                    return redirect()->route('profile.company');
                }
                else
                {
                    $error = array('current-password' => 'Пожалуйста введите правильный текущий пароль');
                    return redirect()->back()->withErrors($error)->withInput();
                }
            }
        }
        else
        {
            return redirect()->to('/');
        }
    }
}

