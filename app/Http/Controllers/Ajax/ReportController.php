<?php

namespace App\Http\Controllers\Ajax;

use App\Company;
use App\Group;
use App\GroupsUser;
use App\MobileUser;
use App\Partner;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ReportController extends Controller
{
    public function reportByCompany(Request $request)
    {
        $minDate = $request->minDate;
        $maxDate = $request->maxDate;
        $company = $request->company;
//        $type = $request->type;

        $res = DB::table('transactions')->select('transactions.*', 'services.name')
            ->join('companies_services', 'companies_services.id', '=', 'transactions.cs_id')
            ->join('companies', 'companies.id', '=', 'companies_services.company_id')
            ->join('services', 'services.id', '=', 'transactions.service_id')
            ->where('companies.id', $company);


        if ($minDate) {
            $res = $res->where('transactions.created_at', '>=', $minDate);
        }

        if ($maxDate) {
            $res = $res->where('transactions.created_at', '<=', $maxDate);
        }
//        if ($service) {
//            $res = $res->where('transactions.service_id', '=', $service);
//        }
//        if ($type) {
//            $res = $res->where('transactions.type', '=', $type);
//        }
//        $res = $res->get();

        $s = DataTables::of($res)
            ->addColumn('sender', function ($service) {
                if ($service->type == 1) {
                    if ($service->c_s_id) {
                        $c = Company::where('id', $service->c_s_id)->first();
                        return $c->name;
                    } else {
                        $m = MobileUser::where('id', $service->u_s_id)->first();
                        return $m->phone;
                    }
                } else if ($service->type == 2) {
                    $p = Partner::where('id', $service->p_s_id)->first();
                    return $p->name;
                } else if ($service->type == 3) {
                    $m = MobileUser::where('id', $service->u_s_id)->first();
                    return $m->phone;
                } else if ($service->type == 5) {
                    $m = MobileUser::where('id', $service->u_s_id)->first();
                    return $m->phone;
                }
            })
            ->addColumn('reciever', function ($service) use ($company) {
                if ($service->type == 1) {

                    $m = MobileUser::where('id', $service->u_r_id)->first();
                    return $m->phone;

                } else if ($service->type == 2) {
                    $p = Company::where('id', $service->c_r_id)->first();
                    return $p->name;
                } else if ($service->type == 3) {
                    $m = User::where('id', $service->u_r_id)->first();
                    return $m->name;
                } else if ($service->type == 5) {
                    $p = Company::where('id', $service->c_r_id)->first();
                    return $p->name;
                } else {
                    $p = Company::where('id', $service->c_r_id)->first();
                    return $p->name;
                }
            })
            ->addColumn('sender_name', function ($service) use ($company) {
                if ($service->type == 1) {
                    if (!$service->c_s_id) {
                        $m = MobileUser::where('id', $service->u_s_id)->first();
                        $gr = Group::where('company_id', $company)->get();
                        foreach ($gr as $g) {
                            $gu = GroupsUser::where('group_id', $g->id)
                                ->where('mobile_user_id', $m->id)
                                ->first();
                            if ($gu) {
                                return $gu->username;
                            }
                        }
                    }


                } else if ($service->type == 3) {
                    $m = MobileUser::where('id', $service->u_s_id)->first();
                    $gr = Group::where('company_id', $company)->get();
                    foreach ($gr as $g) {
                        $gu = GroupsUser::where('group_id', $g->id)
                            ->where('mobile_user_id', $m->id)
                            ->first();
                        if ($gu) {
                            return $gu->username;
                        }
                    }
                } else if ($service->type == 5) {
                    $m = MobileUser::where('id', $service->u_s_id)->first();
                    $gr = Group::where('company_id', $company)->get();
                    foreach ($gr as $g) {
                        $gu = GroupsUser::where('group_id', $g->id)
                            ->where('mobile_user_id', $m->id)
                            ->first();
                        if ($gu) {
                            return $gu->username;
                        }
                    }
                }


            })
            ->addColumn('reciever_name', function ($service) use ($company) {
                if ($service->type == 1) {
                    $m = MobileUser::where('id', $service->u_r_id)->first();
                    $gr = Group::where('company_id', $company)->get();
                    foreach ($gr as $g) {
                        $gu = GroupsUser::where('group_id', $g->id)
                            ->where('mobile_user_id', $m->id)
                            ->first();
                        if ($gu) {
                            return $gu->username;
                        }
                    }

                }
            })
            ->make(true);

        return $s;


    }


    public function reportByCompanyAjax(Request $request)
    {
        $json = $request->data;

        $this->jsonToCsv($json, true, true);

    }

    function jsonToCsv($json, $csvFilePath = false, $boolOutputFile = false)
    {

        // See if the string contains something
        if (empty($json)) {
            die("The JSON string is empty!");
        }

        // If passed a string, turn it into an array
        if (is_array($json) === false) {
            $json = json_decode($json, true);
        }

        // If a path is included, open that file for handling. Otherwise, use a temp file (for echoing CSV string)
        if ($csvFilePath !== false) {
            $f = fopen($csvFilePath, 'w+');
            if ($f === false) {
                die("Couldn't create the file to store the CSV, or the path is invalid. Make sure you're including the full path, INCLUDING the name of the output file (e.g. '../save/path/csvOutput.csv')");
            }
        } else {
            $boolEchoCsv = true;
            if ($boolOutputFile === true) {
                $boolEchoCsv = false;
            }
            $strTempFile = 'csvOutput' . date("U") . ".csv";
            $f = fopen($strTempFile, "w+");
        }

        $firstLineKeys = false;
        foreach ($json as $line) {
            if (empty($firstLineKeys)) {
                $firstLineKeys = array_keys($line);
                fputcsv($f, $firstLineKeys);
                $firstLineKeys = array_flip($firstLineKeys);
            }

            // Using array_merge is important to maintain the order of keys acording to the first element
            fputcsv($f, array_merge($firstLineKeys, $line));
        }
        fclose($f);

        // Take the file and put it to a string/file for output (if no save path was included in function arguments)
        if ($boolOutputFile === true) {
            if ($csvFilePath !== false) {
                $file = $csvFilePath;
            } else {
                $file = $strTempFile;
            }

            // Output the file to the browser (for open/save)
            if (file_exists($file)) {
                header('Content-Type: text/csv');
                header('Content-Disposition: attachment; filename=' . basename($file));
                header('Content-Length: ' . filesize($file));
                readfile($file);
            }
        } elseif ($boolEchoCsv === true) {
            if (($handle = fopen($strTempFile, "r")) !== FALSE) {
                while (($data = fgetcsv($handle)) !== FALSE) {
                    echo implode(",", $data);
                    echo "<br />";
                }
                fclose($handle);
            }
        }

        // Delete the temp file
        unlink($strTempFile);

    }
}
