<?php

namespace App\Http\Controllers;
use App\Code;
use App\MobileUser;
use App\Partner;
use App\QrCode;
use App\Service;
use App\User;
use App\UsersService;
use App\UsersSubscriptions;
use Couchbase\UserSettings;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
                $token = Str::random(42);
//                 $token = base64_encode($user);
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
                ->leftJoin('users_services', function($leftJoin)use($user)
                {
                $leftJoin->on('users_services.service_id', '=', 'services.id')
                    ->where('users_services.mobile_user_id', $user->id);
                })
//                ->leftJoin('users_services', 'users_services.service_id', '=', 'services.id')
                ->where('services.status', 3)
//                ->where('users_services.mobile_user_id', $user->id)
                ->select('services.id', 'services.price', 'services.name', 'services.created_at')
                ->selectRaw('SUM(DISTINCT users_services.amount) AS usersAmount')
                ->groupBy('services.id', 'services.price', 'services.name', 'services.created_at')
                ->get();

            return $this->makeResponse(200, true, ['services' => $partner]);
        }
        return $this->makeResponse(401, false, ['msg' => 'phone or code missing']);
    }


    public function getUsersServices(Request $request){
        $token = $request->token;
        $service_id = $request->service_id;
        $user = MobileUser::where('token', $token)->first();
        if($user){
            $services = DB::table('users_services')
                ->join('companies', 'companies.id', '=', 'users_services.company_id')
                ->join('services', 'services.id', '=', 'users_services.service_id')
                ->join('companies_services', 'companies_services.company_id', '=', 'companies.id')
                ->where('users_services.mobile_user_id', $user->id)
                ->where('users_services.service_id', $service_id)
                ->select('companies.name as company', 'services.*', 'users_services.id', 'users_services.amount as usersAmount', 'companies_services.deadline')
                ->distinct('users_services.id')
                ->groupBy('users_services.id')
                ->get();


            return $this->makeResponse(200, true, ['services' => $services]);
        }
        return $this->makeResponse(401, false, ['msg' => 'phone or code missing']);
    }

    public function generateQR(Request $request){
        $token = $request->token;
        $id = $request->takon_id;
        $amount = $request->amount;
        $user = MobileUser::where('token', $token)->first();
        if($user){

            $us = UsersService::where('id', $id)->first();
            if($us->amount < $amount){
                return $this->makeResponse(400, false, ['msg' => 'Недостаточно таконов']);
            }
            $string =  Str::random(65);
            $model = new QrCode();
            $model->users_service_id = $us->id;
            $model->amount = $amount;
            $model->hash = $string;
            $model->save();
            return $this->makeResponse(200, true, ['msg' => $string]);

        }
        return $this->makeResponse(401, false, ['msg' => 'phone or code missing']);

    }

    public function scanQR(Request $request){
        $token = $request->token;
        $string = $request->qrstring;

        $user = MobileUser::where('token', $token)->first();
        if($user){

            $qr = QrCode::where('hash', $string)->first();
            if(!$qr){
                return $this->makeResponse(400, false, ['msg' => 'Недостаточно таконов']);
            }

            $us = UsersService::where('id', $qr->users_service_id)->first();
            if($qr->amount > $us->amount){
                return $this->makeResponse(400, false, ['msg' => 'Недостаточно таконов']);
            }
            $usr = UsersService::where('service_id', $us->service_id)
                ->where('mobile_user_id', $user->id)
                ->where('deadline', $us->deadline)
                ->where('company_id', $us->company_id)
                ->first();
            if($usr){
                $usr->amount += $qr->amount;
            }else{
                $usr = new UsersService();
                $usr->mobile_user_id = $user->id;
                $usr->service_id = $us->service_id;
                $usr->amount = $qr->amount;
                $usr->company_id = $us->company_id;
                $usr->deadline = $us->deadline;
            }
            $usr->save();
            $us->amount -= $qr->amount;
            $us->save();
            $qr->delete();

            return $this->makeResponse(200, true, ['msg' => $string]);

        }
        return $this->makeResponse(401, false, ['msg' => 'phone or code missing']);

    }

    public function sendTakon(Request $request){
        $id = $request->takon_id;
        $token = $request->token;
        $amount = $request->amount;
        $phone = $request->phone;
        $user = MobileUser::where('token', $token)->first();
        if($user){

            $reciever = MobileUser::where('phone', $phone)->first();
            $us = UsersService::where('id', $id)->first();
            $service = Service::where('id', $us->service_id)->first();
            if($reciever){
                if($us->amount < $amount){
                    return $this->makeResponse(400, false, ['msg' => 'Недостаточно таконов']);
                }
                $model = new UsersService();
                $model->mobile_user_id = $reciever->id;
                $model->amount = $amount;
                $model->service_id = $service->id;
                $model->company_id = $us->company_id;
                $model->deadline = $us->deadline;
                $model->save();

                $us->amount -= $amount;
                $us->save();

                // TODO: Stats
                return $this->makeResponse(200, true, ['msg' => 'Таконы успешно переданы']);

            }
            else{
                return $this->makeResponse(400, true, ['msg' => 'user not found']);

            }
        }
        return $this->makeResponse(401, false, ['msg' => 'unauthorized']);

    }



    public function logIn(Request $request){
        $email = $request->email;

        $password = $request->password;

        $user = User::where('email', $email)
            ->where('password', md5($request->password))
            ->first();
        if($user){
            $token = Str::random(42);
            $user->token = $token;
            $user->save();
            return $this->makeResponse(200, true, ['token' => $token]);
        }
        return $this->makeResponse(401, false, ['hash' => Hash::make($password),'message'=>'Данные для авторизации неверны', 'error' => 'incorrect auth data']);
    }

    public function scanCashierQR(Request $request){
        $string = $request->qrstring;
        $token = $request->token;
        $user = User::where('token', $token)->first();
        if($user){
            $model = QrCode::where('hash', $string)->first();
            if ($model){
                $us = UsersService::where('id', $model->users_service_id)->first();
                if($us->amount < $model->amount){
                    return $this->makeResponse(400, false, ['message'=>'Недостаточно средств']);
                }
                $us->amount -= $model->amount;
                $us->save();
                $model->delete();
                // TODO: Stats
                return $this->makeResponse(200, true, ['message'=>'Успешно!']);
            }
            return $this->makeResponse(400, false, ['message'=>'QR не найден']);
        }
        return $this->makeResponse(401, false, ['message'=>'Данные для авторизации неверны', 'error' => 'incorrect auth data']);
    }

    public function makeResponse(int $code, Bool $success, Array $other){
        $json = array_merge($other, ['success' => $success]);
        return \response()->json($json)->setStatusCode($code);
    }
}
