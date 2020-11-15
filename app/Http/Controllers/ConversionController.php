<?php

namespace App\Http\Controllers;

use App\Conversion;
use App\Service;
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
