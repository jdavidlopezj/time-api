<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use DateTime;
use App\usage;
use App\user;
use App\application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class usageController extends Controller
{

    public function csvDataSave(Request $request)
    {
        $response = array('code' => 400, 'error_msg' => []);

        if (!$request->file('csv') || $request->file('csv')->getClientOriginalExtension() !== 'csv') {
            array_push($response['error_msg'], 'CSV is required');
            Log::error("No csv");
        }
        if (!$request->id) {
            array_push($response['error_msg'], 'id is required');
            Log::error("No User id");
        } else {
            $user = User::find($request->id);
        }
        if (!isset($user)) {
            array_push($response['error_msg'], 'user not found');
            Log::error("user not found");
        }

        if (!count($response['error_msg']) > 0) {
            try {
                $csv = array_map('str_getcsv', file($request->file('csv')->getRealPath()));
                $countArray = count($csv);
                for ($i = 1; $i < $countArray; $i++) {
                    $openDate = new DateTime($csv[$i][0]);
                    $application = $csv[$i][1];
                    $openLocation = $csv[$i][3] . "," . $csv[$i][4];
                    $i++;
                    $closeDate =  new DateTime($csv[$i][0]);
                    $timeUsed = $closeDate->getTimestamp() - $openDate->getTimestamp();
                    $existingApplication = application::where('name', $application)->first();
                    Log::alert("iterando");
                    if (isset($existingApplication)) {
                        $usage = new usage;
                        $usage->day = $openDate;
                        $usage->useTime = $timeUsed;
                        $usage->location = $openLocation;
                        $usage->user_id = $user->id;
                        $usage->application_id = $existingApplication->id;
                        $usage->save();
                    } else {
                        Log::alert("creando aplicacion");
                        $newApp = $this->newApplication($application);
                        $usage = new usage;
                        $usage->day = $openDate;
                        $usage->useTime = $timeUsed;
                        $usage->location = $openLocation;
                        $usage->user_id = $user->id;
                        $usage->application_id = $newApp->id;
                        $usage->save();
                    }
                }
                $response = array('code' => 200, 'msg' => 'Data created');
            } catch (\Exception $exception) {
                $response = array('code' => 500, 'error_msg' => $exception->getMessage());
            }
        }

        return response($response, $response['code']);
    }


    public function getUsages($id)
    {
        $response = array('code' => 400, 'error_msg' => []);
        $user = User::find($id);
        if (isset($user)) {
            $usages = $user->userUsages;
            if (isset($usages)) {
                $response = array('code' => 200, 'usages' => $usages);
            } else {
                array_push($response['error_msg'], 'no usages found');
            }
            return response($response, $response['code']);
        } else {
            array_push($response['error_msg'], 'User not found');
            return response($response, $response['code']);
        }
    }

    public function totalUseLocation($id)
    {
        $response = array('code' => 400, 'error_msg' => []);
        $user = User::find($id);
        if (isset($user)) {
            $usages = DB::table('app_usage')->select(
                'user_id',
                'application_id',
                'day',
                'location',
                DB::raw("SUM(useTime) as totalTime")
            )->from('app_usage')->where('user_id', $user->id)->groupBy('user_id', 'application_id', 'day', 'location')->get();
        } else {
            array_push($response['error_msg'], 'User not found');
            return response($response, $response['code']);
        }

        if (isset($usages)) {

            $response = array('code' => 200, 'usages' => $usages);
        } else {
            array_push($response['error_msg'], 'no data found');
        }
        return response($response, $response['code']);
    }

    public function newApplication($name, $image = null)
    {
        $application = new application;
        $application->name = $name;
        $application->image = $image;
        $application->save();
        return $application;
    }
    //// panel y esas cosas
    public function getUsagesPanel()
    {
        $response = array('code' => 400, 'error_msg' => []);
        // $user = User::find(1);
        //$usages = $user->userUsages->paginate(5)->onEachSide(2);
        $usages = usage::where('user_id', '1')->paginate(5)->onEachSide(2);

        if (isset($usages)) {
            $response = array('code' => 200, 'usages' => $usages);
        } else {
            array_push($response['error_msg'], 'no usages found');
        }
        return view('csv', ['response' => $response]);
    }


    public function csvDataSavePanel(Request $request)
    {
        $response = array('code' => 400, 'error_msg' => []);

        if (!$request->file('csv') || $request->file('csv')->getClientOriginalExtension() !== 'csv') {
            array_push($response['error_msg'], 'CSV is required');
        }
        if (!Auth::user()->id) {
            array_push($response['error_msg'], 'id is required');
        } else {
            $user = User::find(Auth::user()->id);
        }
        if (!isset($user)) array_push($response['error_msg'], 'user not found');

        if (!count($response['error_msg']) > 0) {
            try {
                $csv = array_map('str_getcsv', file($request->file('csv')->getRealPath()));
                $countArray = count($csv);
                for ($i = 1; $i < $countArray; $i++) {
                    $openDate = new DateTime($csv[$i][0]);
                    $application = $csv[$i][1];
                    $openLocation = $csv[$i][3] . "," . $csv[$i][4];
                    $i++;
                    $closeDate =  new DateTime($csv[$i][0]);
                    $timeUsed = $closeDate->getTimestamp() - $openDate->getTimestamp();
                    $existingApplication = application::where('name', $application)->first();
                    if (isset($existingApplication)) {
                        $usage = new usage;
                        $usage->day = $openDate;
                        $usage->useTime = $timeUsed;
                        $usage->location = $openLocation;
                        $usage->user_id = $user->id;
                        $usage->application_id = $existingApplication->id;
                        $usage->save();
                    } else {
                        $newApp = $this->newApplication($application);
                        $usage = new usage;
                        $usage->day = $openDate;
                        $usage->useTime = $timeUsed;
                        $usage->location = $openLocation;
                        $usage->user_id = $user->id;
                        $usage->application_id = $newApp->id;
                        $usage->save();
                    }
                }
                $response = array('code' => 200, 'msg' => 'Data created');
            } catch (\Exception $exception) {
                $response = array('code' => 500, 'error_msg' => $exception->getMessage());
            }
        }
        // return $response ;
        $usages = usage::where('user_id', Auth::user()->id)->paginate(5)->onEachSide(2);

        if (isset($usages)) {
            $response = array('code' => 200, 'usages' => $usages);
        } else {
            array_push($response['error_msg'], 'no usages found');
        }
        return view('csv', ['response' => $response]);
    }

    public function totalUseAplication($id)
    {
        $response = array('code' => 400, 'error_msg' => []);
         $user = User::find(Auth::user()->id); 
        $application = application::find($id);
        if (isset($user) && isset($application)) {
            $totalUse = DB::table('app_usage')->select(
                'user_id',
                'application_id',
                DB::raw("SUM(useTime) as totalTime")
            )->from('app_usage')->where('user_id', $user->id)->where('application_id', $id)->groupBy('user_id', 'application_id')->get();
            $allUses = DB::table('app_usage')->select(
                'user_id',
                'application_id',
                'day',
                'location',
                DB::raw("SUM(useTime) as totalTime")
            )->from('app_usage')->where('user_id', $user->id)->where('application_id', $id)->groupBy('user_id', 'application_id', 'day', 'location')->get();
        } else {
            array_push($response['error_msg'], 'User not found');
            return response($response, $response['code']);
        }

        if (isset($totalUse)) {

            $response = array('code' => 200, 'total_use' => $totalUse,  'all_uses' => $allUses, 'application' => $application);
        } else {
            array_push($response['error_msg'], 'no data found');
        }
        return view('detail', ['response' => $response]);

    }
}
