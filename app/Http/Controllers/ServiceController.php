<?php

namespace App\Http\Controllers;

use App\Notification;
use App\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
//use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\DataTables;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('services/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('services/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = new Service();
        $model->name = $request->name;
        $model->deadline = $request->deadline;
        $model->price = $request->price;
        $model->description = $request->desc;
        $model->partner_id = auth()->user()->partner_id;
        $model->save();
        toastr()->info('Товар или услуга были отправлены на модерацию');
        return view('services/index');

    }

    public function editSave(Request $request)
    {
        $model = Service::where('id', $request->id)->first();
        $model->name = $request->name;
        $model->deadline = $request->deadline;
        $model->price = $request->price;
        $model->description = $request->desc;
        $model->save();
        toastr()->success('Товар или услуга были изменены');
        return view('services/index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $service = Service::where('id', '=', $request->id)->first();
        return view('services/view')->with(['service' => $service]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     // sharuam jok
    public function edit(Service $service)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $service = Service::where('id', $request->id)->first();
        return view('services/edit')->with(['service' => $service]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service)
    {
        //
    }

    public function getMyServices(){

        $servives = DB::table('services')
            ->where('partner_id', '=', auth()->user()->partner_id)
            ->whereIn('status',  [3, 4])
            ->get();

        $s = DataTables::of($servives)->addColumn('checkbox', function ($service) {
            if($service->status == 3){
                return '<a href="/partner-share-services?id=' . $service->id . '"><button class="btn btn-success">Поделиться</button></a>';
            }else{
                return 'Неактивен';
            }

        })
            ->addColumn('edit', function ($service){
                return '<a href="/edit-service?id=' . $service->id . '"><button class="btn btn-outline-info">Редактировать</button></a>';
            })
            ->rawColumns(['checkbox', 'edit'])
            ->make();


        return $s;
    }

    public function moderationList(){
        $list = DB::table('services')->where('status', '=', 1)
            ->join('partners', 'partners.id', '=', 'services.partner_id')
            ->select('services.*', 'partners.name as partner', 'partners.phone')
            ->get();

        return Datatables::of($list)
            ->addColumn('moderate', function($partner){
                return '<a href="/services/view?id=' . + $partner->id . '"><button class="btn btn-success">Управлять</button></a>';
            })
            ->rawColumns(['moderate'])
            ->make(true);

        return datatables($list)->toJson();
    }

    public function moderate(Request $request){
        $service = Service::where('id', '=', $request->id)->first();
        $service->status = $request->confirm;
        if($service->save()){
            toastr()->success('Успешно!');

            $message = new Notification();

            if($request->confirm == 3){
                $message->status = 'success';
                $message->title = 'Товар или услуга успешно были добавлены в систему';
                $message->message = $service->name . ' был(а) успешно добавлен(а) в систему!';
                $message->reciever_partner_id = $service->partner_id;
            }else{
                $message->status = 'error';
                $message->title = 'Товар или услуга не прошли модерацию';
                $message->message = $service->name . ' не был(а) добавлен(а) в систему по причине - ' . $request->reason;
                $message->reciever_partner_id = $service->partner_id;
            }
            $message->save();
            return view('services/moderation');

        }else{
            abort(501);
        }

    }

}
