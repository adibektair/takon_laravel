<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function reportByCompany()
    {
        $companies = Company::all();
        return view('reports.reportByCompany', compact('companies'));
    }

}
