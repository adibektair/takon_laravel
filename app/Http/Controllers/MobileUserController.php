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
    public function addUser(Request $request)
    {
        return view('mobile_users/add')->with(['id' => $request->id]);
    }
    public function addUserGroup(Request $request)
    {
        $GU = GroupsUser::where('group_id', $request->id)->get();
        $arr = [];
        foreach ($GU as $g){
            array_push($arr, $g->mobile_user_id);
        }
        $users = DB::table('mobile_users')
            ->whereNotIn('id', $arr)
            ->select('mobile_users.*')
            ->get();

        $s = DataTables::of($users)
            ->addColumn('add', function ($group) {
                return '<button class="btn btn-info" id="'. $group->id .'">Добавить</button>';
            })
            ->rawColumns(['add'])->make();

        return $s;
    }
    public function addUserFinish(Request $request){
        $id = $request->id;
        $group_id = $request->group_id;
        $g = new GroupsUser();
        $g->group_id = $group_id;
        $g->mobile_user_id = $id;
        $g->save();

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
        })
            ->addColumn('remove', function ($group) {
                return '<button class="btn btn-danger" id="'. $group->id .'">Удалить группу</button>';
            })
            ->rawColumns(['checkbox', 'remove'])->make();

        return $s;
    }

    public function removeGroup(Request $request){
        $id = $request->id;
        $gr = Group::where('id', $id)->first();
        $gr->delete();
        return ['success' => true];
    }

    public function removeUser(Request $request){
        $id = $request->id;
        $gr = GroupsUser::where('mobile_user_id', $id)->where('group_id', $request->group_id)->first();
        $gr->delete();
        return ['success' => true];
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
        return view('mobile_users/send-group')->with(['ids' => $string, 'name' =>$group->name, 'group_id' => $group->id, 'cs_id' => $request->cs_id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MobileUser  $mobileUser
     * @return \Illuminate\Http\Response
     */
    public function saveGroup(Request $request)
    {

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

    public function setName(Request $request){
        $id = $request->id;
        $name = $request->name;
        $user = MobileUser::where('id', $id)->first();
        $user->name = $name;
        $user->save();

    }

    public function createGroup(){
        return view('mobile_users/create-group');
    }

    public function searchUser(Request $request){

        $u = MobileUser::where('phone', 'LIKE', "%".$request->value."%")->first();
        if ($u){
            return ['success' => true, "id" => $u->id, "phone" => $u->phone];
        }else{
            return ['success' => false];
        }

    }
    public function storeGroup(Request $request){
        if(!$request->id){
            toastError("Добавьте пользователей в группу, для этого воспользуйтесь поиском!");
            return redirect()->back();
        }
        $group = new Group();
        $group->name = $request->group_name;
        $group->company_id = auth()->user()->company_id;
        if ($group->save()){
            foreach ($request->id as $user_id => $phone){

                $group_user = new GroupsUser();
                $group_user->username = $request->name[$user_id];
                $group_user->mobile_user_id = $user_id;
                $group_user->group_id = $group->id;
                $group_user->save();
            }
        }
        toastSuccess('Группа пользователей была добавлена');
        return redirect()->route('groups');
    }
    public function all(){
//        $users = MobileUser::all();
        $users = DB::table('mobile_users')->get();
//        dd($users);
        $s = DataTables::of($users)->addColumn('checkbox', function ($user) {
                return '<button class="btn btn-info" data-name="'.$user->phone.'" id="'.$user->id.'">Выбрать</button>';
            })
            ->addColumn('name', function ($user) {
            return '<input type="text" id="' . $user->id .'" class="name"  value="' . $user->name . '" placeholder="Введите имя">';
             })

            ->rawColumns(['name', 'checkbox'])->make();
        return $s;
    }
}
