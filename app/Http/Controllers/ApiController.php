<?php

namespace App\Http\Controllers;
use App\Code;
use App\MobileUser;
use App\Partner;
use App\Service;
use App\UsersSubscriptions;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Boolean;
use phpDocumentor\Reflection\Types\Integer;


class ApiController extends Controller
{
    public function auth(Request $request)
    {
        $phone = $request->phone;
        if($phone){
            $x = 3; // Amount of digits
            $min = pow(10,$x);
            $max = pow(10,$x+1)-1;
            $value = rand($min, $max);
            $code = new Code();
            $code->phone = $phone;
            $code->code = $value;
            $code->save();
            $data = array
            (
                'recipient' => $phone,
                'text' => 'Код авторизации TAKON: ' . $value,
                'apiKey' => 'b60ce3cf8697fa6d2ef145c429eea815128dc7ca'
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-type: application/x-www-form-urlencoded'
            ));
            curl_setopt($ch, CURLOPT_URL,"https://api.mobizon.kz/service/Message/SendSMSMessage/");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
           // $server_output = curl_exec($ch);
            curl_close ($ch);
            return $this->makeResponse(200, true, ['message' => 'success']);
        }

        return $this->makeResponse(400,  false, ['message' => 'success']);
    }



    public function checkCode(Request $request){

        $phone = $request->phone;
        $password = $request->password;
        if($phone AND $password){
            $code = Code::where('phone', $phone)->where('code', $password)->first();

            if($code){

                $user = MobileUser::where('phone', $phone)->first();
//                $token = Str::random(42);
                 $token = base64_encode($user);
                if($user){
                    $user->token = $token;
                }else{
                    $user = new MobileUser();
                    $user->token = $token;
                    $user->phone = $phone;
                }
                $user->save();

                return $this->makeResponse(200,  true, ["token" => $token]);
            }else{
                return $this->makeResponse(401,  false, ["message" => 'wrong code']);
            }
        }
        return $this->makeResponse(400, false, ['msg' => 'phone or code missing']);
    }


    public function getSubscriptions(Request $request){
        $token = $request->token;
        $user = MobileUser::where('token', $token)->first();
        if($user){
             $res = DB::table('users_subscriptions')
                 ->where('mobile_user_id', $user->id)
                ->join('partners', 'partners.id', '=', 'users_subscriptions.partner_id')
                ->select('partners.*')
                ->get();
             return $this->makeResponse(200, true, ['partners' => $res]);

        }
        return $this->makeResponse(401, false, ['msg' => 'phone or code missing']);

    }


    public function getPartners(Request $request){
        $token = $request->token;
        $user = MobileUser::where('token', $token)->first();

        if($user){
            $ids = DB::table('users_subscriptions')
                ->where('mobile_user_id', $user->id)
                ->get();
            $id = [];
            foreach ($ids as $i){
                array_push($id, $i->partner_id);
            }

            $res = DB::table('partners')
                ->whereNotIn('id', $id)
                ->select('partners.*')
                ->get();
            return $this->makeResponse(200, true, ['partners' => $res]);

        }
        return $this->makeResponse(401, false, ['msg' => 'phone or code missing']);

    }

    public function subscribe(Request $request){
        $token = $request->token;
        $user = MobileUser::where('token', $token)->first();
        if($user){

            $partner = Partner::where('id', $request->partner_id)->first();
            $us = new UsersSubscriptions();
            $us->mobile_user_id = $user->id;
            $us->partner_id = $partner->id;
            $us->save();

            return $this->makeResponse(200, true, ['msg' => 'success']);

        }
        return $this->makeResponse(401, false, ['msg' => 'phone or code missing']);

    }

    public function getServices(Request $request){
        $token = $request->token;
        $user = MobileUser::where('token', $token)->first();
        if($user){
            $partner = DB::table('services')
                ->where('partner_id', $request->partner_id)
                ->join('users_services', 'users_services.service_id', '=', 'services.id')
                ->where('services.status', 3)
                ->where('users_services.mobile_user_id', $user->id)
                ->select('services.id', 'services.price', 'services.name', 'services.created_at', 'SUM(users_services.amount)')
                ->get();

            return $this->makeResponse(200, true, ['services' => $partner]);
        }
        return $this->makeResponse(401, false, ['msg' => 'phone or code missing']);
    }



    public function makeResponse(int $code, Bool $success, Array $other){
        $json = array_merge($other, ['success' => $success]);
        return \response()->json($json)->setStatusCode($code);
    }
}
