<?php

namespace App\Http\Controllers;

use App\Partner;
use App\PartnersLocation;
use \Validator;
use \Session;
use Illuminate\Http\Request;

class PartnersLocationController extends Controller
{
    public function index($id)
    {
        $partner = Partner::findOrFail($id);
        $partnersLocations = PartnersLocation::where('partner_id', $id)->get();
        return view('partners.details', compact("partnersLocations", "partner"));
    }

    public function create($id)
    {
        $partner = Partner::findOrFail($id);
        return view('partners.locations.create', compact("partner"));
    }

    public function store($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'longitude' => 'required',
            'latitude' => 'required',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        } else {
            $partnersLocation = new PartnersLocation();
            $partnersLocation->fill($request->all());
            $partnersLocation->partner_id = $id;
            $partnersLocation->save();
            Session::flash('success', 'Локация успешно добавлена!');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $partnersLocation = PartnersLocation::find($id);
        if (!$partnersLocation) {
            Session::flash('error', ' Локация не существует!');
            return redirect()->back();
        }

        return view('partners.locations.edit', compact('partnersLocation'));
    }

    public function delete($id)
    {
        $partnersLocation = PartnersLocation::find($id);
        if ($partnersLocation) {
            $partnersLocation->delete();
            Session::flash('success', 'Локация успешно удалена!');
        } else {
            Session::flash('error', 'Локация не существует!');
        }
        return redirect()->back();
    }


    public function update(Request $request, $id)
    {
        $partnersLocation = PartnersLocation::find($id);
        if (!$partnersLocation) {
            Session::flash('error', 'Локация не существует!');
            return redirect()->back();
        }

        $validator = Validator::make($request->all(), [
            'longitude' => 'required',
            'latitude' => 'required',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            Session::flash('error', 'Ошибка!');
            return redirect()->back()->withErrors($validator);
        } else {
            $partnersLocation->fill($request->all());
            $partnersLocation->save();
            Session::flash('success', 'Локация успешно обновлена!');
            return redirect()->back();
        }
    }
}
