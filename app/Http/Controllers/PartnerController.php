<?php

namespace App\Http\Controllers;

use App\CompaniesService;
use App\Company;
use App\Notification;
use App\Partner;
use App\Service;
use App\Transaction;
use App\User;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;


class PartnerController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('partners/partners');

//        return datatables(Partner::all())->toJson();
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('partners/createpartner');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = new Partner;
        $model->name = $request->name;
        $model->phone = $request->phone;
        $model->address = $request->address;
        if ($model->save()) {
            $user = new User;
            $user->role_id = 2;
            $user->partner_id = $model->id;
            $user->name = 'Админ ' . $request->name;
            $user->email = $request->login;
            $user->password = Hash::make($request->password);
            $user->save();
        }

        $partners = Partner::all();
        toastr()->success('Партнер успешно добавлен!');
        return redirect()->route('partners_list')->with(['partners' => $partners]);

    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Partner $partner
     * @return \Illuminate\Http\Response
     */
    public function show(Partner $partner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Partner $partner
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {
        $this->validate($request, [
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $model = Partner::where('id', '=', $request->id)->first();
        $model->name = $request->name;
        $model->phone = $request->phone;
        $model->description = $request->desc;
        $model->address = $request->address;
        if (request()->avatar) {
            $imageName = strtotime('now') . $model->name . '.' . request()->avatar->getClientOriginalExtension();
            request()->avatar->move(public_path('public/avatars'), $imageName);
            $model->image_path = $imageName;
        }
        $model->save();
        toastr()->success('Профиль успешно обновлен');
        return view('profile/index');

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Partner $partner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Partner $partner)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Partner $partner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Partner $partner)
    {
        //
    }

    public function getServicesPage(Request $request)
    {

        $partner = Partner::where('id', '=', $request->id)->first();

        return view('partners/services')->with(['id' => $request->id, 'name' => $partner->name]); //
    }

    public function buyCurrentService(Request $request)
    {
        $service = Service::where('id', '=', $request->id)->first();
        return view('companies/form')->with(['service' => $service]);
    }

    public function buyService(Request $request)
    {
        $service = Service::where('id', '=', $request->id)->first();
        $amount = $request->amount;
        $cost = $amount * $service->price;
        $order = new Order();
        $order->amount = $amount;
        $order->user_id = auth()->user()->id;
        $order->service_id = $service->id;
        $order->cost = $cost;
        $order->save();
        $message = new Notification();
        $message->status = 'info';
        $message->title = 'У Вас появилась новая заяка!';
        $message->message = 'Ваш товар/услугу ' . $service->name . ' желает приобрести юр. лицо в количестве - ' . $amount;
        $message->reciever_partner_id = $service->partner_id;
        $message->save();
        toastr()->info('Ваша заявка была отправлена на модерацию');
        return redirect()->route('company.services');
    }

    public function shareServices(Request $request)
    {
        $id = $request->id;
        $service = Service::where('id', '=', $id)->first();
        return view('partners/share')->with(['service' => $service]);
    }

    public function share(Request $request)
    {
        $partner = Partner::where('id', '=', auth()->user()->partner_id)->first();
        $service = Service::where('id', '=', $request->id)->first();
        $reciever = Company::where('id', '=', $request->company_id)->first();

        // TODO: add moderation
        // TODO: deadline check

        $deadline = strtotime("+" . $service->deadline . " day", strtotime("now"));
        $rec_ser = CompaniesService::where('service_id', '=', $request->id)
            ->where('company_id', '=', $request->company_id)
            ->where('deadline', '=', $deadline)
            ->first();
        if ($rec_ser) {
            $rec_ser->amount += $request->amount;
        } else {
            $rec_ser = new CompaniesService();
            $rec_ser->service_id = $request->id;
            $rec_ser->amount = $request->amount;
            $rec_ser->deadline = $deadline;
            $rec_ser->company_id = $request->company_id;
        }
        if ($rec_ser->save()) {
            $model = new Transaction();
            $model->type = 4;
            $model->cs_id = $rec_ser->id;
            $model->service_id = $service->id;
            $model->p_s_id = $partner->id;
            $model->c_r_id = $reciever->id;
            $model->price = $service->price;
            $model->amount = $request->amount;
            $model->save();
        }


        toastr()->success('Спасибо. Ваши таконы были успешно переданы');
        $not = new Notification();
        $not->make('info', 'Внимание!', 'Вам было отправлено ' . $request->amount . ' таконов товара/услуги ' . $service->name . ' от компании ' . $partner->name,
            null, $request->company_id, false);

        $not1 = new Notification();
        $not1->make('info', 'Внимание!', $request->amount . ' таконов товара/услуги ' . $service->name . ' от партнера' . $partner->name . ' были отправлены ' . $reciever->name,
            null, $request->company_id, true);
        return view('services/index');

    }


    public function getPartnersServices(Request $request)
    {

        $services = Service::where('partner_id', '=', $request->id)->where('status', '=', 3)->get();
        return Datatables::of($services)
            ->addColumn('service', function ($service) {
                return '<a href="/buy-current-service?id=' . $service->id . '"><button class="btn btn-success">Приобрести</button></a>';
            })
            ->rawColumns(['service'])
            ->make(true);

//        return datatables()->toJson();
    }

    public function getPartners()
    {
        $partners = Partner::all();
        return Datatables::of($partners)
            ->addColumn('buy', function ($partner) {
                return '<a href="/partners-services?id=' . $partner->id . '"><button class="btn btn-success">Посмотреть товары и услуги</button></a>';
            })
            ->addColumn('locations', function ($partner) {
                return '<a class="btn btn-success" href="' . route('partners.location', ['id' => $partner->id]) . '">Локации партнеров</a>';
            })
            ->addColumn('email', function ($partner) {
                $user = User::where('role_id', 2)->where('partner_id', $partner->id)->first();
                return $user->email;
            })
            ->rawColumns(['buy', 'email', 'locations'])
            ->make(true);


//        return datatables()->toJson();
    }
}
