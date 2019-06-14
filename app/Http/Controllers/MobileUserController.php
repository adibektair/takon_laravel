<?php

namespace App\Http\Controllers;

use App\Group;
use App\GroupsUser;
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
    public function groups()
    {
        return view('mobile_users/groups');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MobileUser  $mobileUser
     * @return \Illuminate\Http\Response
     */
    public function getGroups(Request $request)
    {
        $groups = DB::table('groups')
            ->where('company_id', auth()->user()->company_id)
            ->select('groups.*')
            ->get();

        $s = DataTables::of($groups)->addColumn('checkbox', function ($group) {
            return '<a href="/choose-group?id='.$group->id.'"><button class="btn btn-success" >Выбрать</button></a>';
        })->rawColumns(['checkbox'])->make();

        return $s;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MobileUser  $mobileUser
     * @return \Illuminate\Http\Response
     */
    public function chooseGroup(Request $request)
    {
        $group = Group::where('id', $request->id)->first();
        $group_id = $request->id;
        $users = GroupsUser::where('group_id', $group_id)->get();
        $string = '';
        foreach ($users as $user){
            $string .= $user->mobile_user_id . ',';
        }
        return view('mobile_users/send')->with(['ids' => $string, 'name' =>$group->name, 'cs_id' => $request->cs_id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MobileUser  $mobileUser
     * @return \Illuminate\Http\Response
     */
    public function saveGroup(Request $request){
        $array = explode(',', $request->ids);
        $group = new Group();
        $group->name = $request->name;
        $group->company_id = auth()->user()->company_id;
        if ($group->save()){
            foreach ($array as $el){
                $gu = new GroupsUser();
                $gu->group_id = $group->id;
                $gu->mobile_user_id = $el;
                $gu->save();
            }
        }else{
            $response = ['success' => false];
        }

        $response = ['success' => true];

        return $response;
    }

    public function send(Request $request)
    {
        $ids = $request->ids;

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
            })
            ->rawColumns(['checkbox'])->make();
        return $s;
    }
}
