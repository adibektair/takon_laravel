<?php

namespace App\Http\Controllers;

use App\Console\Utils;
use App\Conversion;
use App\MobileUser;
use App\Service;
use App\Transaction;
use App\UsersService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ConversionController extends Controller
{

    public function index()
    {
        return view('conversion.index');

    }

    public function all(){
        $conversions = DB::table('conversions as c')
            ->join('services as s1', 's1.id', '=', 'c.first_service_id')
            ->join('services as s2', 's2.id', '=', 'c.second_service_id')
            ->select('c.*', 's1.name as name1', 's2.name as name2')
            ->get();
        $s = DataTables::of($conversions)
            ->addColumn('checkbox', function ($group) {
                        return '<a href="/edit-conversion/' . $group->id . '"><button class="btn btn-success" >Изменить</button></a>';
            })
            ->rawColumns(['checkbox'])
            ->make();

        return $s;
    }

    public function create()
    {
        $services = Service::all();
        return view('conversion.create')->with(['services' => $services]);
    }

    public function store(Request $request)
    {
        $check = Conversion::where('first_service_id', $request->service1)->where('second_service_id', $request->service2)->first();
        if($check){
            toastError('Конвертация для этих услуг уже существует');
            return redirect()->back();
        }
        $check = Conversion::where('first_service_id', $request->service2)->where('second_service_id', $request->service1)->first();
        if($check){
            toastError('Конвертация для этих услуг уже существует');
            return redirect()->back();
        }

        $conversion = new Conversion();
        $conversion->coefficient = $request->coefficient;
        $conversion->first_service_id = $request->service1;
        $conversion->second_service_id = $request->service2;
        $conversion->is_available = true;
        $conversion->save();
        toastSuccess('Сохранено');
        return view('conversion.index');
    }

    public function edit($id)
    {
        $conversion = Conversion::where('id', $id)->first();
        $service1 = Service::where('id', $conversion->first_service_id)->first();
        $service2 = Service::where('id', $conversion->second_service_id)->first();
        return view('conversion.edit')->with(['data' => $conversion, 's1' => $service1, 's2' => $service2]);
    }

    public function save(Request $request)
    {
        $conversion = Conversion::where('id', $request->id)->first();
        $conversion->coefficient = $request->coefficient;
        if($request->is_available){
            $conversion->is_available = true;
        }else{
            $conversion->is_available = false;
        }
        $conversion->save();
        toastSuccess('Сохранено');
        return redirect()->route('conversion.index');
    }

    // API
    public function getConversionsByServiceId(Request $request){
        $utils = new Utils();
        $serviceId = $request->service_id;
        $result = DB::table('conversions as c')
            ->where('c.first_service_id', $serviceId)
            ->orWhere('c.second_service_id', $serviceId)
            ->leftJoin('services as s1', 's1.id', '=', 'c.second_service_id')
            ->leftJoin('services as s2', 's2.id', '=', 'c.first_service_id')
            ->select('c.*', 's1.id as s1_id', 's1.name as s1_name', 's2.id as s2_id', 's2.name as s2_name')
            ->get();
        $total = [];
        if (!$result){
            return $utils->makeResponse(200, false, []);
        }
        foreach ($result as $item){
            $data["conversion"] = $item;
            if($serviceId == $item->s1_id){
                $data["service_id"] = $item->s2_id;
                $data["service_name"] = $item->s2_name;
            }else{
                $data["service_id"] = $item->s1_id;
                $data["service_name"] = $item->s1_name;
            }
            array_push($total, $data);
        }
        return $utils->makeResponse(200, true, ['data' => $total]);
    }

    public function makeConversion(Request $request){
        $utils = new Utils();
        $user = MobileUser::where('token', $request->token)->first();
        $id = $request->id;
        $conversion = Conversion::find($id);
        $us_id = $request->us_id;
        $us = UsersService::find($us_id);
        if($us->amount < $request->amount){
            return $utils->makeResponse(200, false, ['error' => 'Недостаточно средств']);
        }
        $us_new = new UsersService();
        $us_new->mobile_user_id = $user->id;
        if($us->service_id != $conversion->first_service_id){
            $us_new->amount = $conversion->coefficient * $request->amount;
            $us_new->service_id = $conversion->second_service_id;
        }else{
            $us_new->amount = $request->amount / $conversion->coefficient;
            $us_new->service_id = $conversion->first_service_id;
        }
        $us_new->cs_id = 0;

        $us_new->save();
        $us->amount -= $request->amount;
        $us->save();

        $transaction = new Transaction();
        $transaction->u_s_id = $user->id;
        $transaction->u_r_id = $user->id;
        $transaction->type = Transaction::CONVERSION_TYPE;
        $transaction->service_id = $us->service_id;
        $transaction->second_service_id = $us_new->service_id;
        $transaction->save();

        return  $utils->makeResponse(200, true, []);
    }

}
