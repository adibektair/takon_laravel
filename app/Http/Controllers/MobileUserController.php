<?php

namespace App\Http\Controllers;

use App\CloudMessage;
use App\CompaniesService;
use App\Group;
use App\GroupsUser;
use App\MobileUser;
use App\Partner;
use App\Service;
use App\Transaction;
use App\UsersService;
use App\UsersSubscriptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class MobileUserController extends Controller
{

    public function index(Request $request)
    {
        return view('mobile_users/index')->with(['id' => $request->id]);
    }


    public function addUserGroup(Request $request)
    {
        if (!$request->id) {
            toastError("Добавьте пользователей в группу, для этого воспользуйтесь поиском!");
            return redirect()->back();
        }
        $group = Group::where('id', $request->group_id)->first();

        foreach ($request->id as $user_id => $phone) {
            $group_user = new GroupsUser();
            $group_user->username = $request->name[$user_id];
            $group_user->mobile_user_id = $user_id;
            $group_user->group_id = $group->id;
            $group_user->save();
        }

        toastSuccess('Пользователи были добавлены в группу');
        return redirect()->route('groups');
    }

    public function sendUser(Request $request)
    {
        $cs_id = $request->cs_id;
        $user = MobileUser::where('phone', $request->phone)->first();
        if (!$user) {
            toastError('Пользователь с таким номером телефона не найден');
            return redirect()->back();
        }
        $cs = CompaniesService::where('id', $cs_id)->first();
        if ($cs->amount < $request->amount) {
            toastError('У вас недостаточно таконов');
            return redirect()->back();
        }

        $m_service = UsersService::where('service_id', $cs->service_id)
            ->where('mobile_user_id', $user->id)
            ->where('deadline', $cs->deadline)
            ->where('company_id', auth()->user()->company_id)
            ->first();
        if ($m_service) {
            $m_service->amount += $request->amount;
        } else {

            $m_service = new UsersService();
            $m_service->amount = $request->amount;
        }
        $m_service->mobile_user_id = $user->id;
        $m_service->company_id = auth()->user()->company_id;
        $m_service->cs_id = $cs->id;

        $m_service->service_id = $cs->service_id;
        $m_service->deadline = $cs->deadline;

        $serv = Service::where('id', $cs->service_id)->first();
        $partner = Partner::where('id', $serv->partner_id)->first();
        $subs = UsersSubscriptions::where('mobile_user_id', $user->id)
            ->where('partner_id', $serv->partner_id)
            ->first();
        if (!$subs) {
            $subs = new UsersSubscriptions();
            $subs->mobile_user_id = $user->id;
            $subs->partner_id = $serv->partner_id;
            $subs->save();
        }
//        $message = new CloudMessage("Вам были отправлены Таконы " . $serv->name, $user->id, "Внимение", $serv->partner_id, $partner->name);
//        $message->sendNotification();

        $c = new CloudMessage();
        $c->sendSilentThroughNode($user->push_id, $user->platform, "Вам были отправлены Таконы " . $serv->name, '', 'Внимение');


        $cs->amount -= $request->amount;
        $cs->save();

        if ($m_service->save()) {
            $exactly_service = Service::where('id', '=', $cs->service_id)->first();
            $parent = Transaction::where('service_id', $exactly_service->id)
                ->where('c_r_id', auth()->user()->company_id)
                ->where('u_s_id', null)
                ->where('cs_id', $cs_id)
                ->orderBy('created_at', 'desc')->first();

            $model = new Transaction();
            $model->parent_id = $parent->id;
            $model->balance = $cs->amount - $request->amount;
            $model->users_service_id = $m_service->id;
            $model->type = 1;
            $model->cs_id = $cs->id;
            $model->service_id = $cs->service_id;
            $model->c_s_id = auth()->user()->company_id;
            $model->u_r_id = $user->id;
            $model->price = $exactly_service->price;
            $model->amount = $request->amount;
            $model->save();
        }


        toastSuccess('Успешно отправлено!');
        return redirect()->back();

    }

    public function groups()
    {
        return view('mobile_users/groups');
    }

    public function addUser(Request $request)
    {
        return view('mobile_users/add')->with(['id' => $request->id]);
    }

//    public function addUserGroup(Request $request)
//    {
//        $GU = GroupsUser::where('group_id', $request->id)->get();
//        $arr = [];
//        foreach ($GU as $g) {
//            array_push($arr, $g->mobile_user_id);
//        }
//        $users = DB::table('mobile_users')
//            ->whereNotIn('id', $arr)
//            ->select('mobile_users.*')
//            ->get();
//
//        $s = DataTables::of($users)
//            ->addColumn('add', function ($group) {
//                return '<button class="btn btn-info" id="' . $group->id . '">Добавить</button>';
//            })
//            ->rawColumns(['add'])->make();
//
//        return $s;
//    }

    public function addUserFinish(Request $request)
    {
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
     * @param \App\MobileUser $mobileUser
     * @return \Illuminate\Http\Response
     */
    public function getGroups(Request $request)
    {
        $groupsQuery = DB::table('groups')
            ->where('company_id', auth()->user()->company_id)
            ->select('groups.*');

        $s = DataTables::of($groupsQuery)->addColumn('checkbox', function ($group) use ($request) {
            return '<a href="/choose-group?id=' . $group->id . '&cs_id=' . $request->id . '"><button class="btn btn-success" >Выбрать</button></a>';
        })
            ->addColumn('remove', function ($group) {
                return '<button class="btn btn-danger" id="' . $group->id . '">Удалить группу</button>';
            })
            ->rawColumns(['checkbox', 'remove'])->make();

        return $s;
    }

    public function removeGroup(Request $request)
    {
        $id = $request->id;
        $gr = Group::where('id', $id)->first();
        $gr->delete();
        return ['success' => true];
    }

    public function removeUser(Request $request)
    {
        GroupsUser::where('mobile_user_id', $request->id)
            ->where('group_id', $request->group_id)
            ->delete();
        return ['success' => true];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\MobileUser $mobileUser
     * @return \Illuminate\Http\Response
     */
    public function chooseGroup(Request $request)
    {
        $group_id = $request->id;
        $group = Group::where('id', $group_id)->first();
        $users = GroupsUser::where('group_id', $group_id)->get();
        $string = '';
        foreach ($users as $user) {
            $string .= $user->mobile_user_id . ',';
        }
        return view('mobile_users/send-group')->with(['ids' => $string, 'name' => $group->name, 'group_id' => $group->id, 'cs_id' => $request->cs_id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\MobileUser $mobileUser
     * @return \Illuminate\Http\Response
     */
    public function saveGroup(Request $request)
    {

        $array = explode(',', $request->ids);
        $group = new Group();
        $group->name = $request->name;
        $group->company_id = auth()->user()->company_id;
        if ($group->save()) {
            foreach ($array as $el) {
                $gu = new GroupsUser();
                $gu->group_id = $group->id;
                $gu->mobile_user_id = $el;
                $gu->save();
            }
            $response = ['success' => true];
        } else {
            $response = ['success' => false];
        }
        return $response;

    }

    public function send(Request $request)
    {
        $ids = $request->ids;

        return view('mobile_users/send')->with(['ids' => $ids, 'cs_id' => $request->cs_id]);
    }

    public function getUsersByIds(Request $request)
    {
        $ids = $request->ids;
        $array = explode(',', $ids);
        $usersQuery = DB::table('mobile_users')->whereIn('id', $array);
        $s = DataTables::of($usersQuery)->make();
        return $s;
    }

    public function setName(Request $request)
    {
        $id = $request->id;
        $name = $request->name;
        $user = MobileUser::where('id', $id)->first();
        $user->name = $name;
        $user->save();

    }

    public function createGroup()
    {
        return view('mobile_users/create-group');
    }

    public function searchUser(Request $request)
    {

        if ($request->group_id) {
            $GU = GroupsUser::where('group_id', $request->group_id)->get();
            $arr = [];
            foreach ($GU as $g) {
                array_push($arr, $g->mobile_user_id);
            }
            $u = MobileUser::where('phone', $request->value)->whereNotIn('id', $arr)->first();
            if ($u) {
                return ['success' => true, "id" => $u->id, "phone" => $u->phone];
            } else {
                return ['success' => false];
            }
        }
        $u = MobileUser::where('phone', $request->value)->first();
        if ($u) {
            return ['success' => true, "id" => $u->id, "phone" => $u->phone];
        } else {
            return ['success' => false];
        }

    }

    public function storeGroup(Request $request)
    {
        if (!$request->id) {
            toastError("Добавьте пользователей в группу, для этого воспользуйтесь поиском!");
            return redirect()->back();
        }
        $group = new Group();
        $group->name = $request->group_name;
        $group->company_id = auth()->user()->company_id;
        if ($group->save()) {
            foreach ($request->id as $user_id => $phone) {

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

    public function all()
    {
        $usersQuery = DB::table('mobile_users');
        $s = DataTables::of($usersQuery)->addColumn('checkbox', function ($user) {
            return '<button class="btn btn-info" data-name="' . $user->phone . '" id="' . $user->id . '">Выбрать</button>';
        })
            ->addColumn('name', function ($user) {
                return '<input type="text" id="' . $user->id . '" class="name"  value="' . $user->name . '" placeholder="Введите имя">';
            })
            ->addColumn('card', function ($user) {
                if(auth()->user()->role_id == 1){
                    return '<a href="/card/'. $user->id .'"><button class="btn btn-default">Карта оплаты</button></a>';
                }else{
                    return  'В разработке';
                }

            })
            ->rawColumns(['name', 'checkbox', 'card'])->make();
        return $s;
    }

    public function cardIndex($id){
        $user = MobileUser::where('id', $id)->first();
        if ($user->card_hash == NULL){
            $str = Str::random(45);
            $user->card_hash = $str;
            $user->passcode = 0000;
            $user->save();
        }
        return view('mobile_users/card')->with(['user' => $user]);
    }
    public function setPasscode(Request $request, $id){
        $user = MobileUser::where('id', $id)->first();
        $user->passcode = $request->passcode;
        $user->save();
        toastSuccess('Сохранено');
        return redirect()->back();
    }
    public function lockCard(Request $request, $id){
        $user = MobileUser::where('id', $id)->first();
        $user->is_enabled = !$user->is_enabled;
        $user->save();
        toastSuccess('Сохранено');
        return redirect()->back();
    }
    public function resetCard($id){
        $user = MobileUser::where('id', $id)->first();
        $str = Str::random(45);
        $user->card_hash = $str;
        $user->passcode = 0000;
        $user->save();
        toastSuccess('Сохранено');
        return redirect()->back();
    }
}
