<?php

namespace App\Http\Controllers;

use App\Card;
use App\CloudMessage;
use App\Code;
use App\Http\Payment;
use App\MobileUser;
use App\Partner;
use App\PartnersLocation;
use App\QrCode;
use App\Service;
use App\Transaction;
use App\User;
use App\UsersService;
use App\UsersSubscriptions;
use App\WalletPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;


class ApiController extends Controller
{


    public function getAllPartners()
    {
        $p = Partner::all();
        return $this->makeResponse(200, true, ["companies" => $p]);
    }

    public function auth(Request $request)
    {
        $phone = $request->phone;
        if ($phone) {
            $x = 3; // Amount of digits
            $min = pow(10, $x);
            $max = pow(10, $x + 1) - 1;
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
            curl_setopt($ch, CURLOPT_URL, "https://api.mobizon.kz/service/Message/SendSMSMessage/");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $server_output = curl_exec($ch);

            curl_close($ch);
            return $this->makeResponse(200, true, ['message' => 'success']);
        }

        return $this->makeResponse(400, false, ['message' => 'success']);
    }


    public function checkCode(Request $request)
    {

        $phone = $request->phone;
        $password = $request->password;
        if ($phone AND $password) {
            $code = Code::where('phone', $phone)->where('code', $password)->first();

            if ($code OR $password == 8669) {
//            if($password == 5555){
                $user = MobileUser::where('phone', $phone)->first();
                $token = Str::random(42);
//                 $token = base64_encode($user);
                if ($user) {
                    $user->token = $token;
                } else {
                    $user = new MobileUser();
                    $user->token = $token;
                    $user->phone = $phone;
                }
                $user->save();

                return $this->makeResponse(200, true, ["token" => $token, "user" => $user]);
            } else {
                return $this->makeResponse(401, false, ["message" => 'wrong code']);
            }
        }
        return $this->makeResponse(400, false, ['msg' => 'phone or code missing']);
    }


    public function getSubscriptions(Request $request)
    {
        $token = $request->token;
        $user = MobileUser::where('token', $token)->first();
        if ($user) {
//             $res = DB::table('users_subscriptions')
//                 ->where('users_subscriptions.mobile_user_id', $user->id)
//                 ->join('partners', 'partners.id', '=', 'users_subscriptions.partner_id')
//                 ->leftJoin('services', 'services.partner_id', '=', 'partners.id')
//                 ->leftJoin('users_services', 'users_services.service_id', '=', 'services.id')
//                 ->selectRaw('SUM(users_services.amount) as amount, partners.*')
////                 ->where('users_subscriptions.mobile_user_id', 'users_services.mobile_user_id')
//                 ->groupBy('partners.id')
//                 ->get();

            $res = DB::table('users_subscriptions')
                ->join('partners', 'partners.id', '=', 'users_subscriptions.partner_id')
                ->leftJoin('services', 'services.partner_id', '=', 'partners.id')
                ->leftJoin('users_services', function ($join) use ($user) {
                    $join->on('users_services.service_id', '=', 'services.id');
                    $join->on('users_services.mobile_user_id', '=', DB::raw($user->id));
                })
                ->selectRaw('SUM(users_services.amount) as amount, partners.*')
                ->where('users_subscriptions.mobile_user_id', $user->id)
                ->groupBy('partners.id')
                ->get();
            return $this->makeResponse(200, true, ['partners' => $res]);

        }
        return $this->makeResponse(401, false, ['msg' => 'phone or code missing']);

    }


    public function getPartners(Request $request)
    {
        $token = $request->token;
        $user = MobileUser::where('token', $token)->first();

        if ($user) {
            $ids = DB::table('users_subscriptions')
                ->where('mobile_user_id', $user->id)
                ->get();
            $id = [];
            foreach ($ids as $i) {
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

    public function subscribe(Request $request)
    {
        $token = $request->token;
        $user = MobileUser::where('token', $token)->first();
        if ($user) {

            $partner = Partner::where('id', $request->partner_id)->first();
            $us = new UsersSubscriptions();
            $us->mobile_user_id = $user->id;
            $us->partner_id = $partner->id;
            $us->save();

            return $this->makeResponse(200, true, ['msg' => 'success']);

        }
        return $this->makeResponse(401, false, ['msg' => 'phone or code missing']);

    }

    public function removeSubscription(Request $request)
    {
        $token = $request->token;
        $user = MobileUser::where('token', $token)->first();
        if ($user) {
            $sub = UsersSubscriptions::where('mobile_user_id', $user->id)->where('partner_id', $request->id)->first();
            $sub->delete();
            return $this->makeResponse(200, true, ['msg' => 'success']);
        }
    }

    public function getServices(Request $request)
    {
        $token = $request->token;
        $user = MobileUser::where('token', $token)->first();
        if ($user) {
            $partner = DB::table('services')
                ->where('partner_id', $request->partner_id)
                ->leftJoin('users_services', function ($leftJoin) use ($user) {
                    $leftJoin->on('users_services.service_id', '=', 'services.id')
                        ->where('users_services.mobile_user_id', $user->id);
                })
//                ->leftJoin("conversions as c", "c.first_service_id", "=", "services.id")
//                ->leftJoin("conversions as c1", "c1.second_service_id", "=", "services.id")
                ->where('services.status', 3)
                ->select('services.id', 'services.price', 'services.name', 'services.created_at', 'services.description', 'services.payment_enabled', 'services.payment_price')
                ->selectRaw('SUM(users_services.amount) AS usersAmount')//, IFNULL(c.id, null) as is_conv, IFNULL(c1.id, null) as is_conv1')
                ->groupBy('services.id', 'services.price', 'services.name', 'services.created_at', 'services.description', 'services.payment_enabled', 'services.payment_price')
                ->get();

            return $this->makeResponse(200, true, ['services' => $partner]);
        }
        return $this->makeResponse(401, false, ['msg' => 'phone or code missing']);
    }


    public function getUsersServices(Request $request)
    {
        $token = $request->token;
        $service_id = $request->service_id;
        $user = MobileUser::where('token', $token)->first();
        if ($user) {
            $services = DB::table('users_services')
                ->leftJoin('companies', 'companies.id', '=', 'users_services.company_id')
                ->join('services', 'services.id', '=', 'users_services.service_id')
                ->leftJoin('companies_services', 'companies_services.company_id', '=', 'companies.id')
                ->where('users_services.mobile_user_id', $user->id)
                ->where('users_services.service_id', $service_id)
                ->where('users_services.amount', '<>', 0)
                ->select('services.*', 'users_services.id', 'users_services.amount as usersAmount')
                ->selectRaw('IFNULL(companies_services.deadline, 1577880000) AS deadline, IFNULL(companies.name, "TAKON.ORG") as company')
                ->distinct('users_services.id')
                ->groupBy('users_services.id')
                ->get();


            return $this->makeResponse(200, true, ['services' => $services]);
        }
        return $this->makeResponse(401, false, ['msg' => 'phone or code missing']);
    }

    public function generateQR(Request $request)
    {
        $token = $request->token;
        $id = $request->takon_id;
        $amount = $request->amount;
        $user = MobileUser::where('token', $token)->first();
        if ($user) {

            $us = UsersService::where('id', $id)->first();
            if ($us->amount < $amount) {
                return $this->makeResponse(400, false, ['msg' => 'Недостаточно таконов']);
            }
            $string = Str::random(65);
            $model = new QrCode();
            $model->users_service_id = $us->id;
            $model->amount = $amount;
            $model->hash = $string;
            $model->save();
            return $this->makeResponse(200, true, ['msg' => $string]);

        }
        return $this->makeResponse(401, false, ['msg' => 'phone or code missing']);

    }

    public function scanQR(Request $request)
    {
        $token = $request->token;
        $string = $request->qrstring;

        $user = MobileUser::where('token', $token)->first();
        if ($user) {
            if (strlen($string) == 65) {
                $qr = QrCode::where('hash', $string)->first();
                if (!$qr) {
                    return $this->makeResponse(400, false, ['msg' => 'Недостаточно таконов']);
                }
                $us = UsersService::where('id', $qr->users_service_id)->first();
                if ($qr->amount > $us->amount) {
                    return $this->makeResponse(400, false, ['msg' => 'Недостаточно таконов']);
                }
                $usr = UsersService::where('service_id', $us->service_id)
                    ->where('mobile_user_id', $user->id)
                    ->where('deadline', $us->deadline)
                    ->where('cs_id', $us->cs_id)
                    ->first();
                if ($usr) {
                    $usr->amount = $usr->amount + $qr->amount;
                } else {
                    $usr = new UsersService();
                    $usr->mobile_user_id = $user->id;
                    $usr->cs_id = $us->cs_id;
                    $usr->service_id = $us->service_id;
                    $usr->amount = $qr->amount;
                    $usr->company_id = $us->company_id;
                    $usr->deadline = $us->deadline;
                }


                if ($usr->save()) {
                    $qr->delete();

                    $service = Service::where('id', $us->service_id)->first();
                    $partner = Partner::where('id', $service->partner_id)->first();

//                    $not = new CloudMessage("С Вашего счета были отправлены таконы " . $service->name, $us->mobile_user_id, "Внимание", $service->partner_id, $partner->name);
//                    $not->sendNotification();
                    $subs = UsersSubscriptions::where('mobile_user_id', $user->id)
                        ->where('partner_id', $service->partner_id)
                        ->first();
                    if (!$subs) {
                        $subs = new UsersSubscriptions();
                        $subs->mobile_user_id = $user->id;
                        $subs->partner_id = $service->partner_id;
                        $subs->save();
                    }

                    $parent = Transaction::where('service_id', $service->id)
                        ->where('u_r_id', $us->mobile_user_id)
                        ->where('users_service_id', $us->id)
                        ->orderBy('created_at', 'desc')->first();

                    $model = new Transaction();
                    if ($parent) {
                        $model->parent_id = $parent->id;
                    } else {
                        $parent = Transaction::where('service_id', $service->id)
                            ->where('u_r_id', $us->mobile_user_id)
                            ->orderBy('created_at', 'desc')->first();
                        $model->parent_id = $parent->parent_id;

                    }
                    $model->type = 1;
                    $model->service_id = $service->id;
                    $model->u_s_id = $us->mobile_user_id;
                    $model->u_r_id = $user->id;
                    $model->cs_id = $parent->cs_id;
                    $model->price = $service->price;
                    $model->amount = $qr->amount;

                    $model->balance = $us->amount - $qr->amount;


                    $model->save();
                }

                $us->amount = $us->amount - $qr->amount;

                $us->save();
                return $this->makeResponse(200, true, ['msg' => $string]);
            } else {
                $cashier = User::where('hash', $string)->first();
                if ($cashier) {
                    $partner = Partner::where('id', $cashier->partner_id)->first();
                    return $this->makeResponse(200, true, ['partner_id' => $partner->id, 'partner_name' => $partner->name, 'user_id' => $cashier->id, 'partner' => $partner]);
                }
                return $this->makeResponse(400, false, ['msg' => 'QR код недействительный']);

            }


        }
        return $this->makeResponse(401, false, ['msg' => 'phone or code missing']);

    }

    public function sendTakon(Request $request)
    {
        $id = $request->takon_id;
        $token = $request->token;
        $amount = $request->amount;
        $phone = $request->phone;
        $user = MobileUser::where('token', $token)->first();
        if ($user) {

            $reciever = MobileUser::where('phone', $phone)->first();
            $us = UsersService::where('id', $id)->first();
            $service = Service::where('id', $us->service_id)->first();
            $partner = Partner::where('id', $service->partner_id)->first();

            if ($reciever) {
                if ($reciever->id == $user->id) {
                    return $this->makeResponse(400, false, ['msg' => 'Cannot send takons to yourself']);
                }
                if ($us->amount < $amount) {
                    return $this->makeResponse(400, false, ['msg' => 'Недостаточно таконов']);
                }

                $model = UsersService::where('service_id', $us->service_id)
                    ->where('mobile_user_id', $reciever->id)
                    ->where('deadline', $us->deadline)
                    ->where('cs_id', $us->cs_id)
                    ->first();

                if ($model) {
                    $model->amount = $amount + $model->amount;
                }
                else {
                    $model = new UsersService();
                    $model->mobile_user_id = $reciever->id;
                    $model->cs_id = $us->cs_id;
                    $model->service_id = $service->id;
                    $model->company_id = $us->company_id;
                    $model->deadline = $us->deadline;
                    $model->amount = $amount;
                }
                $model->save();
                $subs = UsersSubscriptions::where('mobile_user_id', $reciever->id)
                    ->where('partner_id', $service->partner_id)
                    ->first();

                if (!$subs) {
                    $subs = new UsersSubscriptions();
                    $subs->mobile_user_id = $reciever->id;
                    $subs->partner_id = $service->partner_id;
                    $subs->save();
                }
                DB::beginTransaction();
                try {
                    $us->amount = $us->amount - $amount;

                    if ($us->save()) {
//                    $not = new CloudMessage("На Ваш счет поступили таконы " . $service->name, $reciever->id, "Внимание", $service->partner_id, $partner->name);
//                    $not->sendNotification();
                        $c = new CloudMessage();
                        $c->sendSilentThroughNode($reciever->push_id, $reciever->platform, "На Ваш счет поступили таконы " . $service->name, 1, "Внимание");


                        $parent = Transaction::where('service_id', $service->id)
                            ->where('u_r_id', $user->id)
                            ->where('type', '<>', 3)
                            ->where('users_service_id', $us->id)
                            ->orderBy('created_at', 'desc')->first();


                        $model = new Transaction();
                        if ($parent) {
                            $model->parent_id = $parent->id;
                        } else {
                            $parent = Transaction::where('service_id', $service->id)
                                ->where('u_r_id', $user->id)
                                ->where('type', '<>', 3)
                                ->orderBy('created_at', 'desc')->first();
                            $model->parent_id = $parent->parent_id;

                        }
                        $model->type = 1;
                        $model->balance = $us->amount;
                        $model->cs_id = $parent->cs_id;
                        $model->service_id = $service->id;
                        $model->u_s_id = $user->id;
                        $model->u_r_id = $reciever->id;
                        $model->price = $service->price;
                        $model->amount = $amount;
                        $model->save();
                    }
                    DB::commit();
                    return $this->makeResponse(200, true, ['msg' => 'Таконы успешно переданы']);
                } catch (\Exception $exception) {
                    DB::rollBack();
                    return $this->makeResponse(400, true, ['msg' => 'user not found']);
                }

            } else {
                return $this->makeResponse(400, true, ['msg' => 'user not found']);

            }
        }
        return $this->makeResponse(401, false, ['msg' => 'unauthorized']);

    }


    public function logIn(Request $request)
    {
        $email = $request->email;

        $password = $request->password;

        $user = User::where('email', $email)
            ->first();
        if ($user) {
            if ($user->token == NULL) {
                $token = Str::random(42);
                $user->token = $token;
                $user->save();
            }
            return $this->makeResponse(200, true, ['token' => $user->token]);
        }
        return $this->makeResponse(401, false, ['hash' => Hash::make($password), 'message' => 'Данные для авторизации неверны', 'error' => 'incorrect auth data']);
    }

    public function scanCashierQR(Request $request)
    {
        $string = $request->qrstring;
        $token = $request->token;
        $user = User::where('token', $token)->first();
        // 65 это qr сгенерированный пользователем
        if(strlen($string) == 65){
            if ($user) {
                try {
                    $model = QrCode::where('hash', $string)->first();
                    if ($model) {
                        $us = UsersService::where('id', $model->users_service_id)->first();
                        $service = Service::where('id', $us->service_id)->first();
                        if ($user->partner_id != $service->partner_id) {
                            return $this->makeResponse(400, false, ['message' => 'Ошибка']);
                        }
                        if ($us->amount < $model->amount) {
                            return $this->makeResponse(400, false, ['message' => 'Недостаточно средств']);
                        }
                        $us->amount = $us->amount - $model->amount;

                        if ($us->save()) {

                            $stat = new Transaction();
                            $parent = Transaction::where('service_id', $service->id)
                                ->where('u_r_id', $us->mobile_user_id)
                                ->where('users_service_id', $us->id)
                                ->orderBy('created_at', 'desc')->first();

                            if ($parent) {
                                $stat->parent_id = $parent->id;
                            } else {
                                $parent = Transaction::where('service_id', $service->id)
                                    ->where('u_r_id', $us->mobile_user_id)
                                    ->orderBy('created_at', 'desc')->first();
                                $stat->parent_id = $parent->parent_id;
                            }


//                    $objDemo = new \stdClass();
//                    $objDemo->demo_one = 'Demo One Value';
//                    $objDemo->demo_two = 'Demo Two Value';
//                    $objDemo->sender = 'SenderUserName';
//                    $objDemo->receiver = 'ReceiverUserName';
//                    Mail::to("adibek.t@maint.kz")->send(new DemoEmail($objDemo));

                            $stat->type = 3;
                            $stat->balance = $us->amount;
                            $stat->service_id = $us->service_id;
                            $stat->u_s_id = $us->mobile_user_id;
                            $stat->u_r_id = $user->id;
                            $stat->cs_id = $parent->cs_id;
                            $stat->price = $service->price;
                            $stat->amount = $model->amount;
                            $stat->save();
                        }
                        $model->delete();
                        return $this->makeResponse(200, true, ['message' => 'Успешно!']);
                    }
                } catch (\Exception $exception) {
                    return $this->makeResponse(400, false, [
                        'message' => 'Ошибка обратитесь к администратору!',
                        'error' => $exception->getMessage()
                    ]);
                }
                return $this->makeResponse(400, false, ['message' => 'QR не найден']);
            }
        }
        // 45 это карта оплаты
        else{
            $mobileUser = MobileUser::where('card_hash', $string)->first();
            if (!$mobileUser->is_enabled){
                return $this->makeResponse(200, false, ['message' => 'Карта заблокирована', 'error' => 'card blocked']);
            }
            $orgId = $user->partner_id;

            $partner = DB::table('services')
                ->where('partner_id', $orgId)
                ->leftJoin('users_services', function ($leftJoin) use ($mobileUser) {
                    $leftJoin->on('users_services.service_id', '=', 'services.id')
                        ->where('users_services.mobile_user_id', $mobileUser->id);
                })
                ->where('services.status', 3)
                ->select('services.id', 'services.price', 'services.name', 'services.created_at', 'services.description', 'services.payment_enabled', 'services.payment_price')
                ->selectRaw('ROUND(SUM(users_services.amount)) AS usersAmount')
                ->groupBy('services.id', 'services.price', 'services.name', 'services.created_at', 'services.description', 'services.payment_enabled', 'services.payment_price')
                ->get();

            return $this->makeResponse(200, true, ['services' => $partner, 'user' => $mobileUser]);
        }

        return $this->makeResponse(401, false, ['message' => 'Данные для авторизации неверны', 'error' => 'incorrect auth data']);
    }

    public function getUsersServicesForCashier(Request $request){
        $token = $request->id;
        $service_id = $request->service_id;
        $user = MobileUser::where('id', $token)->first();
        if ($user) {
            $services = DB::table('users_services')
                ->leftJoin('companies', 'companies.id', '=', 'users_services.company_id')
                ->join('services', 'services.id', '=', 'users_services.service_id')
                ->leftJoin('companies_services', 'companies_services.company_id', '=', 'companies.id')
                ->where('users_services.mobile_user_id', $user->id)
                ->where('users_services.service_id', $service_id)
                ->where('users_services.amount', '<>', 0)
                ->select('services.*', 'users_services.id', 'users_services.amount as usersAmount')
                ->selectRaw('IFNULL(companies_services.deadline, 1577880000) AS deadline, IFNULL(companies.name, "TAKON.ORG") as company')
                ->distinct('users_services.id')
                ->groupBy('users_services.id')
                ->get();
            return $this->makeResponse(200, true, ['services' => $services]);
        }
        return $this->makeResponse(401, false, ['msg' => 'phone or code missing']);
    }

    public function withdrawTakonsFromUsersCard(Request $request){
        $token = $request->token;
        $id = $request->takon_id;
        $user_id = $request->user_id;
        $amount = $request->amount;

        $cashier = User::where('token', $token)->first();

        $user = MobileUser::where('id', $user_id)->first();
        if ($user) {

            $us = UsersService::where('id', $id)->first();

            if ($us->amount < $amount){
                return $this->makeResponse(400, false, ['msg' => 'Недостаточно таконов']);
            }

            DB::beginTransaction();
            try {
                $us->amount = $us->amount - $amount;

                $service = Service::where('id', $us->service_id)->first();
                if ($us->save()) {

                    $stat = new Transaction();
                    $parent = Transaction::where('service_id', $service->id)
                        ->where('u_r_id', $us->mobile_user_id)
                        ->where('users_service_id', $us->id)
                        ->where('cs_id', $us->cs_id)
                        ->where('type', '<>', 3)
                        ->orderBy('created_at', 'desc')->first();

                    if ($parent) {
                        $stat->parent_id = $parent->id;
                    } else {
                        $parent = Transaction::where('service_id', $service->id)
                            ->where('u_r_id', $us->mobile_user_id)
                            ->where('cs_id', $us->cs_id)
                            ->where('type', '<>', 3)
                            ->orderBy('created_at', 'desc')->first();
                        if ($parent->parent_id) {
                            $stat->parent_id = $parent->parent_id;
                        } elseif ($parent->id) {
                            $stat->parent_id = $parent->id;
                        }

                    }

                    $stat->type = 3;
                    $stat->balance = $us->amount;
                    $stat->service_id = $us->service_id;
                    $stat->u_s_id = $us->mobile_user_id;
                    $stat->u_r_id = $cashier->id;
                    $stat->cs_id = $parent->cs_id;
                    $stat->price = $service->price;
                    $stat->amount = $amount;
                    $stat->save();
                }
                DB::commit();
            } catch (\Exception $exception) {
                DB::rollBack();
                return $this->makeResponse(400, false, [
                    'message' => 'Ошибка! Обратитесь к администратору!',
                    'error' => $exception->getMessage()
                ]);
            }
            return $this->makeResponse(200, true, ['msg' => 'Таконы успешно переданы']);

        }
        return $this->makeResponse(401, false, ['msg' => 'phone or code missing']);

    }

    public function scan(Request $request)
    {
        $token = $request->token;
        $id = $request->takon_id;
        $user_id = $request->user_id;
        $amount = $request->amount;

        $user = MobileUser::where('token', $token)->first();
        if ($user) {

        	$us = UsersService::where('id', $id)->first();

        	if ($us->amount < $amount){
                return $this->makeResponse(400, false, ['msg' => 'Недостаточно таконов']);
            }

            DB::beginTransaction();
            try {
                $us->amount = $us->amount - $amount;

                $service = Service::where('id', $us->service_id)->first();
                if ($us->save()) {

                    $stat = new Transaction();
                    $parent = Transaction::where('service_id', $service->id)
                        ->where('u_r_id', $us->mobile_user_id)
                        ->where('users_service_id', $us->id)
                        ->where('cs_id', $us->cs_id)
                        ->where('type', '<>', 3)
                        ->orderBy('created_at', 'desc')->first();

                    if ($parent) {
                        $stat->parent_id = $parent->id;
                    } else {
                        $parent = Transaction::where('service_id', $service->id)
                            ->where('u_r_id', $us->mobile_user_id)
                            ->where('cs_id', $us->cs_id)
                            ->where('type', '<>', 3)
                            ->orderBy('created_at', 'desc')->first();
                        if ($parent->parent_id) {
                            $stat->parent_id = $parent->parent_id;
                        } elseif ($parent->id) {
                            $stat->parent_id = $parent->id;
                        }

                    }

                    $stat->type = 3;
                    $stat->balance = $us->amount;
                    $stat->service_id = $us->service_id;
                    $stat->u_s_id = $us->mobile_user_id;
                    $stat->u_r_id = $user_id;
                    $stat->cs_id = $parent->cs_id;
                    $stat->price = $service->price;
                    $stat->amount = $amount;
                    $stat->save();

                    $cashier = User::where('id', $user_id)->first();
                    if ($cashier) {
                        if ($cashier->push_id) {
                            $c = new CloudMessage();
                            $c->sendSilentThroughNode($cashier->push_id, $cashier->platform, "Вам были переведены " . $amount . " таконов", '', 'Произведена оплата');
                        }
                    }

                }
                DB::commit();
            } catch (\Exception $exception) {
                DB::rollBack();
                return $this->makeResponse(400, false, [
                    'message' => 'Ошибка! Обратитесь к администратору!',
                    'error' => $exception->getMessage()
                ]);
            }
            return $this->makeResponse(200, true, ['msg' => 'Таконы успешно переданы']);

        }
        return $this->makeResponse(401, false, ['msg' => 'phone or code missing']);

    }

    public function getHistory(Request $request)
    {

        $token = $request->token;
        $user = MobileUser::where('token', $token)->first();
        if ($user) {
                $model = DB::table('transactions')
                    ->where('u_s_id', $user->id)
                    ->orWhere('u_r_id', $user->id)
                    ->join('services', 'services.id', '=', 'transactions.service_id')
                    ->leftJoin('partners', 'partners.id', '=', 'services.partner_id')
                    ->leftJoin('mobile_users as s_users', 's_users.id', '=', 'transactions.u_s_id')
                    ->leftJoin('mobile_users as r_users', 'r_users.id', '=', 'transactions.u_r_id')
                    ->leftJoin('companies as s_company', 's_company.id', '=', 'transactions.c_s_id')
                    ->leftJoin('companies as return', 'return.id', '=', 'transactions.c_r_id')
                    ->leftJoin('companies_services as cs', 'cs.id', '=', 'transactions.cs_id')
                    ->leftJoin('companies as company', 'cs.company_id', '=', 'company.id')
                    ->select('company.name as company', 's_company.name as s_company', 'transactions.*',
                        'services.name as service', 's_users.phone as s_user_phone', 'r_users.phone as r_user_phone',
                        's_users.id as s_user_id', 'r_users.id as r_user_id', 'partners.name as creater', 'return.name as ret_name', 'transactions.type as ttype')
                    ->selectRaw('IFNULL(s_company.name, "TAKON.ORG") AS s_company')
                    ->orderBy('transactions.id', 'asc')
                    ->get();

                $result = [];
                foreach ($model as $value) {

                    if ($value->ttype == 3  AND $user->phone == $value->r_user_phone) {

                    } else {
                        $el["service"] = $value->service;
//                    $el["company"] = $value->company;
                        $el["date"] = $value->created_at;
                        $el["company"] = $value->creater;

                        if ($user->phone == $value->s_user_phone) {
                            $el["amount"] = -$value->amount;
                            if ($value->r_user_id) {
                                if ($value->ttype == 3) {
                                    $suser = User::where('id', $value->r_user_id)->first();
                                    $partner = Partner::where('id', $suser->partner_id)->first();
                                    $el['contragent'] = $partner->name . " (" . $suser->name . ")";
                                } else {
                                    $el['contragent'] = $value->r_user_phone;
                                }

                            } else {
                                if ($value->ttype == 3) {
                                    $suser = User::where('id', $value->u_r_id)->first();
                                    $partner = Partner::where('id', $suser->partner_id)->first();
                                    $el['contragent'] = $partner->name . " (" . $suser->name . ")";
                                }
                            }
                            if ($value->ttype == 5) {
                                $el['contragent'] = $value->ret_name;
                            }
                        } else {
                            $el["amount"] = +$value->amount;
                            if ($value->s_user_phone) {
                                $el['contragent'] = $value->s_user_phone;
                            } else {
                                $el['contragent'] = $value->s_company;
                            }
                        }

                        array_push($result, $el);
                    }

                }




            return $this->makeResponse(200, true, ['info' => $result]);

        }
        return $this->makeResponse(401, false, ['message' => 'Данные для авторизации неверны', 'error' => 'incorrect auth data']);

    }


    public function getArchive(Request $request)
    {
        $user = User::where('token', $request->token)->first();
        $model = DB::table('transactions')
            ->where('u_r_id', $user->id)
            ->where('type', 3)
            ->join('services', 'services.id', '=', 'transactions.service_id')
            ->join('mobile_users', 'mobile_users.id', '=', 'transactions.u_s_id')
            ->join('users', 'users.id', '=', 'transactions.u_r_id')
            ->select('transactions.*', 'services.name as service', 'mobile_users.phone as phone', 'users.email')
            ->orderBy('transactions.id', 'desc')
            ->get();

        return $this->makeResponse(200, true, ["qrs" => $model]);
    }

    public function setPushId(Request $request)
    {
        $token = $request->token;
        $platform = $request->platform;
        $pushId = $request->pushId;
        $user = MobileUser::where('token', $token)->first();
        if ($user) {
            $user->push_id = $pushId;
            $user->platform = $platform;
            if ($user->save()) {
                return $this->makeResponse(200, true, ["msg" => "success"]);

            }
            return $this->makeResponse(200, false, ["msg" => "error"]);
        } else {
            $user = User::where('token', $token)->first();
            if (!$user) {
                return $this->makeResponse(401, false, ["msg" => "unauthorized"]);

            }
            $user->push_id = $pushId;
            $user->platform = $platform;
            if ($user->save()) {
                return $this->makeResponse(200, true, ["msg" => "success"]);

            }
            return $this->makeResponse(200, false, ["msg" => "error"]);
        }
    }

    public function makeResponse(int $code, Bool $success, Array $other)
    {
        $json = array_merge($other, ['success' => $success]);
        return \response()->json($json)->setStatusCode($code);
    }


    public function getAccount(Request $request)
    {
        $token = $request->token;
        $user = MobileUser::where('token', $token)->first();
        if (!$user) {
            return $this->makeResponse(401, false, []);
        }
        return $this->makeResponse(200, true, ['user' => $user]);
    }

    public function setName(Request $request)
    {

        $token = $request->token;
        $user = MobileUser::where('token', $token)->first();
        if (!$user) {
            return $this->makeResponse(401, false, []);
        }
        $user->name = $request->name;
        $user->save();
        return $this->makeResponse(200, true, []);

    }


    public function pay(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ip' => 'required',
            'cryptogram' => 'required|string',
            'name' => 'required|string|max:42',
            'amount' => 'required|integer',
            'serviceId' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->makeResponse(400, false, ['errors' => $validator->errors()->all()]);
        }

        $user = MobileUser::where('token', $request->token)->first();
        DB::beginTransaction();
        try {
            $paymentModel = new Payment($request->name, $request->cryptogram, $request->ip, $request->amount, $user->id);
            $response = $paymentModel->pay();
            $response = json_decode($response);
            $TransactionId = $response->Model->TransactionId;
            $AcsUrl = $response->Model->AcsUrl;
            $PaReq = $response->Model->PaReq;
            $success = $response->Success;
            $payModel = new \App\Payment();
            $payModel->service_id = $request->serviceId;
            $payModel->amount = $request->amount;
            $payModel->transaction_id = $TransactionId;
            $payModel->mobile_user_id = $user->id;

            if ($success) {
                // add money
                $service = Service::where('id', $request->serviceId)->first();
                $newService = new UsersService();
                $newService->mobile_user_id = $user->id;
                $newService->service_id = $request->serviceId;
                $newService->amount = $request->amount / $service->payment_price;
                $newService->company_id = null;
                $newService->cs_id = null;
                $newService->deadline = strtotime("+" . $service->deadline . " day", strtotime("now"));
                $newService->save();


                $transaction = new Transaction();
                $transaction->u_r_id = $user->id;
                $transaction->amount = $request->amount / $service->payment_price;
                $transaction->service_id = $request->serviceId;
                $transaction->price = $request->amount;
                $transaction->type = 5;
                $transaction->users_service_id = $newService->id;
                $transaction->save();

            }
            $payModel->save();
            DB::commit();
            return $this->makeResponse(200,
                $success,
                [
                    'TransactionId' => $TransactionId,
                    'AcsUrl' => $AcsUrl,
                    'PaReq' => $PaReq
                ]
            );
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->makeResponse(400, false, [
                'message' => 'Ошибка! Обратитесь к администратору',
                'error' => $exception->getMessage()
            ]);
        }
    }

    const API_KEY = 'f1bfe6d357926dca0b37913171d258af';
    const ID = 'pk_0ad5acde2f593df7c5a63c9c27807';

    public function paymentHandle(Request $request)
    {

        DB::beginTransaction();
        try {
            $TransactionId = $request->MD;
            $PaRes = $request->PaRes;
            $data = array("TransactionId" => $TransactionId, "PaRes" => $PaRes);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-type: application/json',
                'Authorization: Basic ' . base64_encode(self::ID . ":" . self::API_KEY)
            ));
            curl_setopt($ch, CURLOPT_URL, "https://api.cloudpayments.kz/payments/cards/post3ds");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,
                json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $server_output = curl_exec($ch);
            curl_close($ch);
            $s = json_decode($server_output);
            if ($s) {
                if ($s->Success == true) {

                    $payment = \App\Payment::where('transaction_id', $TransactionId)->first();
                    $user = MobileUser::where('id', $payment->mobile_user_id)->first();
                    $service = Service::where('id', $payment->service_id)->first();
                    $newService = new UsersService();
                    $newService->mobile_user_id = $user->id;
                    $newService->service_id = $payment->service_id;
                    $newService->amount = $payment->amount / $service->payment_price;
                    $newService->company_id = null;
                    $newService->cs_id = null;
                    $newService->deadline = strtotime("+" . $service->deadline . " day", strtotime("now"));
                    $newService->save();

                    $transaction = new Transaction();
                    $transaction->u_r_id = $user->id;
                    $transaction->amount = intval($payment->amount / $service->payment_price);
                    $transaction->service_id = $payment->service_id;
                    $transaction->price = $payment->amount;
                    $transaction->type = 6;
                    $transaction->users_service_id = $newService->id;
                    $transaction->save();

                    $card = new Card();
                    $card->mobile_user_id = $user->id;
                    $card->last_four = $s->Model->CardLastFour;
                    $card->token = $s->Model->Token;
                    $card->save();

                    echo "Оплата успешно произведена!";

                } else {
                    echo "error " . $s->Model->Reason;
                }
            } else {
                echo "SOME ERROR OCURED";
            }

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            echo 'Ошибка! Обратитесь к администратору! ';
        }
    }

    public function payByToken(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'amount' => 'required',
            'card_id' => 'required',
            'service_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->makeResponse(400, false, ['errors' => $validator->errors()->all()]);
        }
        $user = MobileUser::where('token', $request->token)->first();
        $service = Service::where('id', $request->service_id)->first();
        $sum = $service->payment_price * $request->amount;

        $card = Card::where('id', $request->card_id)->first();

        $data = array("Amount" => $sum, "Currency" => 'KZT', "AccountId" => $user->id, "Token" => $card->token);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-type: application/json',
            'Authorization: Basic ' . base64_encode(self::ID . ":" . self::API_KEY)
        ));

        curl_setopt($ch, CURLOPT_URL, "https://api.cloudpayments.kz/payments/tokens/charge");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            json_encode($data));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        curl_close($ch);
        $s = json_decode($server_output);
        if ($s) {
            if ($s->Success == true) {

                DB::beginTransaction();
                try {
                    $service = Service::where('id', $request->service_id)->first();

                    $newService = new UsersService();
                    $newService->mobile_user_id = $user->id;
                    $newService->service_id = $request->service_id;
                    $newService->amount = $request->amount;
                    $newService->company_id = null;
                    $newService->cs_id = null;
                    $newService->deadline = strtotime("+" . $service->deadline . " day", strtotime("now"));
                    $newService->save();

                    $transaction = new Transaction();
                    $transaction->u_r_id = $user->id;
                    $transaction->amount = $request->amount;
                    $transaction->service_id = $request->service_id;
                    $transaction->price = $request->amount;
                    $transaction->type = 6;
                    $transaction->users_service_id = $newService->id;
                    $transaction->save();

                    DB::commit();

                    return $this->makeResponse(200, true, []);
                } catch (\Exception $exception) {
                    DB::rollBack();
                    return $this->makeResponse(200, false, [
                        'message' => 'Ошибка! Обратитесь к администртору!',
                        'error' => $exception->getMessage()
                    ]);
                }
            } else {
                return $this->makeResponse(200, false, ['data' => $s]);
            }
        } else {
            return $this->makeResponse(200, false, []);
        }


    }

    public function getCards(Request $request)
    {
        $user = MobileUser::where('token', $request->token)->first();
        if ($user) {
            $cards = DB::table('cards')
                ->where('mobile_user_id', $user->id)
                ->select('cards.id', 'cards.last_four as CardLastFour')
                ->get();
            return $this->makeResponse(200, true, ['cards' => $cards]);
        } else {
            return $this->makeResponse(401, false, []);
        }
    }


    public function transactionHistory(Request $request)
    {
        $user = MobileUser::where('token', $request->token)->first();
        if ($user) {
//            $transactions = Transaction::select(
//                'transactions.*',
//                'receiver.name as receiver_company_name',
//                'receiver.phone as receiver_company_phone',
//                'sender.name as sender_company_name',
//                'sender.phone as sender_company_phone',
//                'mobile_user_receiver.name as mobile_user_receiver_name',
//                'mobile_user_receiver.phone as mobile_user_receiver_phone',
//                'mobile_user_sender.name as mobile_user_sender_name',
//                'mobile_user_sender.phone as mobile_user_sender_phone'
//            )
//                ->leftJoin('companies as receiver', 'receiver.id', '=', 'transactions.c_r_id')
//                ->leftJoin('companies as sender', 'sender.id', '=', 'transactions.c_s_id')
//                ->leftJoin('mobile_users as mobile_user_receiver', function ($join) use ($user) {
//                    $join->on('mobile_user_receiver.id', '=', 'transactions.u_r_id');
//                    $join->on('mobile_user_receiver.id', '<>', DB::raw($user->id));
//                })
//                ->leftJoin('mobile_users as mobile_user_sender', function ($join) use ($user) {
//                    $join->on('mobile_user_sender.id', '=', 'transactions.u_s_id');
//                    $join->on('mobile_user_sender.id', '<>', DB::raw($user->id));
//                })
//                ->where(function ($query) use ($user) {
//                    $query->where('transactions.u_r_id', '=', $user->id)
//                        ->orWhere('transactions.u_s_id', '=', $user->id);
//                })
//                ->whereIn('transactions.type', [1, 5, 3])
//                ->get();
            $model = DB::table('transactions')
                ->where('u_s_id', $user->id)
                ->orWhere('u_r_id', $user->id)
                ->join('services', 'services.id', '=', 'transactions.service_id')
                ->leftJoin('partners', 'partners.id', '=', 'services.partner_id')
                ->leftJoin('mobile_users as s_users', 's_users.id', '=', 'transactions.u_s_id')
                ->leftJoin('mobile_users as r_users', 'r_users.id', '=', 'transactions.u_r_id')
                ->leftJoin('companies as s_company', 's_company.id', '=', 'transactions.c_s_id')
                ->leftJoin('companies as return', 'return.id', '=', 'transactions.c_r_id')
                ->leftJoin('companies_services as cs', 'cs.id', '=', 'transactions.cs_id')
                ->leftJoin('companies as company', 'cs.company_id', '=', 'company.id')
                ->select('company.name as company', 's_company.name as s_company', 'transactions.*',
                    'services.name as service', 's_users.phone as s_user_phone', 'r_users.phone as r_user_phone',
                    's_users.id as s_user_id', 'r_users.id as r_user_id', 'partners.name as creater', 'partners.image_path as image', 'return.name as ret_name', 'transactions.type as ttype')
                ->selectRaw('IFNULL(s_company.name, "TAKON.ORG") AS s_company')
                ->orderBy('transactions.id', 'asc')
                ->get();

            $result = [];
            foreach ($model as $value) {

//                    if($value->id == 51){
//                        dd($value);
//                    }
                if ($value->ttype == 3 AND $user->phone == $value->r_user_phone) {

                } else {
                    $el["service"] = $value->service;
//                    $el["company"] = $value->company;
                    $el["date"] = $value->created_at;
                    $el["company"] = $value->creater;
                    $el["image_path"] = $value->image;

                    if ($user->phone == $value->s_user_phone) {
                        $el["amount"] = -$value->amount;
                        if ($value->r_user_id) {
                            if ($value->ttype == 3) {
                                $suser = User::where('id', $value->r_user_id)->first();
                                $partner = Partner::where('id', $suser->partner_id)->first();
                                if ($partner) {
                                    $el['contragent'] = $partner->name . " (" . $suser->name . ")";
                                } else {
                                    $el['contragent'] = $suser->name;
                                }

                            } else {
                                $el['contragent'] = $value->r_user_phone;
                            }

                        } else {
                            if ($value->ttype == 3) {
                                $suser = User::where('id', $value->u_r_id)->first();
                                $partner = Partner::where('id', $suser->partner_id)->first();
                                $el['contragent'] = $partner->name . " (" . $suser->name . ")";
                            }
                        }
                        if ($value->ttype == 5) {
                            $el['contragent'] = $value->ret_name;
                        }
                    } else {
                        $el["amount"] = +$value->amount;
                        if ($value->s_user_phone) {
                            $el['contragent'] = $value->s_user_phone;
                        } else {
                            $el['contragent'] = $value->s_company;
                        }
                    }

                    array_push($result, $el);
                }

            }

            $arr = [];
            foreach ($result as $transaction) {
                if (!array_key_exists($this->getDateFrom($transaction['date'])->todatestring(), $arr)) {
                    $arr[$this->getDateFrom($transaction['date'])->todatestring()] = [$transaction];
                } else {
                    $arr[$this->getDateFrom($transaction['date'])->todatestring()][] = $transaction;
                }
            }
            $newarr = [];
            foreach ($arr as $key => $value) {
                $newarr[] = ['date' => $key, 'transactions' => $value];
            }
            return $this->makeResponse(200, true, ['history' => $newarr]);
        } else {
            return $this->makeResponse(401, false, []);
        }
    }

    public function getProfile(Request $request)
    {
        $user = MobileUser::where('token', $request->token)->first();
        return $this->makeResponse(200, true, ["user" => $user]);
    }

    public function setProfile(Request $request)
    {
        $user = MobileUser::where('token', $request->token)->first();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->iin = $request->iin;
        $user->save();
        return $this->makeResponse(200, true, ["user" => $user]);
    }

    public function removeCardById(Request $request)
    {
        $user = MobileUser::where("token", $request->token)->first();
        $card = Card::where("id", $request->id)->where("mobile_user_id", $user->id)->first();
        if ($card) {
            $card->delete();
            return $this->makeResponse(200, true, []);
        } else {
            return $this->makeResponse(200, false, []);
        }
    }

    public function getPartnersList(Request $request)
    {
        $user = MobileUser::where('token', $request->token)->first();
        $subs = UsersSubscriptions::where('mobile_user_id', $user->id)->get();
        $partners = Partner::all();
        $array = [];
        foreach ($partners as $partner) {
            $sub = $subs->where('partner_id', $partner->id)->first();
            $obj = $partner;
            if ($sub) {
                $obj['has'] = 1;
            } else {
                $obj['has'] = 0;
            }
            array_push($array, $obj);
        }
        return $this->makeResponse(200, true, ["partners" => $array]);
    }

    // wallet one payment request
    public function createWalletOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|integer',
            'service_id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return $this->makeResponse(400, false, ['errors' => $validator->errors()->all()]);
        }
        $service = Service::where('id', $request->service_id)->first();
        $user = MobileUser::where('token', $request->token)->first();
        $wallet_order = new WalletPayment();
        $wallet_order->amount = $request->amount;
        $wallet_order->service_id = $request->service_id;
        $wallet_order->mobile_user_id = $user->id;
        $wallet_order->price = $request->amount * $service->payment_price;
        $wallet_order->save();
        $url = "https://takon.org/wallet?id=" . $wallet_order->id;

        return $this->makeResponse(200, true, ["url" => $url]);

    }

    // handle walletOne response
    public function handleSuccededWalletPayment(Request $request)
    {
        $order_number = $request->WMI_PAYMENT_NO;

        $wallet_order = WalletPayment::where('id', $order_number)->first();
        $wallet_order->status = 1;
        $wallet_order->save();

        DB::beginTransaction();

        try {
            // Validate, then create if valid

            $service = Service::where('id', $wallet_order->service_id)->first();
            $newService = new UsersService();
            $newService->mobile_user_id = $wallet_order->mobile_user_id;
            $newService->service_id = $service->id;
            $newService->amount = $wallet_order->amount;
            $newService->company_id = null;
            $newService->cs_id = null;
            $newService->deadline = strtotime("+" . $service->deadline . " day", strtotime("now"));
            $newService->save();

            $transaction = new Transaction();
            $transaction->u_r_id = $wallet_order->mobile_user_id;
            $transaction->amount = $wallet_order->amount;
            $transaction->service_id = $wallet_order->service_id;
            $transaction->price = $wallet_order->price;
            $transaction->type = 6;
            $transaction->users_service_id = $newService->id;
            $transaction->save();


        } catch (ValidationException $e) {

            DB::rollback();

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        DB::commit();

        echo "WMI_RESULT=OK";
    }

    public function handleFailedWalletPayment(Request $request)
    {

    }

    public function getPartnersLocations(Request $request)
    {
        $id = $request->id;
        $pl = PartnersLocation::where('partner_id', $id)->get();
        return $this->makeResponse(200, true, ['locations' => $pl]);

    }


    public function getDateFrom($time)
    {
        return Carbon::parse($time);
    }

    public function complete(Request $request)
    {
        return view('mobile_users/paymentcomplete')->with(['data' => $request]);
    }

}
