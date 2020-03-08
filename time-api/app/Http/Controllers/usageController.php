<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use DateTime;
use App\usage;
use App\user;
use App\application;
use Illuminate\Support\Facades\DB;

class usageController extends Controller
{

    public function csvDataSave(Request $request)
    {
        $response = array('code' => 400, 'error_msg' => []);

        if (!$request->file('csv') || $request->file('csv')->getClientOriginalExtension() !== 'csv') {
            array_push($response['error_msg'], 'CSV is required');
        }
        if (!$request->id) {
            array_push($response['error_msg'], 'id is required');
        } else {
            $user = User::find($request->id);
        }
        if (!isset($user)) array_push($response['error_msg'], 'user not found');

        if (!count($response['error_msg']) > 0 ) {
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

        return response($response, $response['code']);
    }


    public function getUsages($id)
    {
        $response = array('code' => 400, 'error_msg' => []);
        $user = User::find($id);
        $usages = $user->userUsages;
        if (isset($usages)) {

            $response = array('code' => 200, 'usages' => $usages);
        } else {
            array_push($response['error_msg'], 'no usages found');
        }
        return response($response, $response['code']);
    }

    public function totalUseLocation($id)
    {
        $response = array('code' => 400, 'error_msg' => []);
        $user = User::find($id);
        $usages = DB::table('app_usage')->select(
            'user_id',
            'application_id',
            'day',
            'location',
            DB::raw("SUM(useTime) as totalTime")
        )->from('app_usage')->where('user_id', $user->id)->groupBy('user_id', 'application_id', 'day', 'location')->get();
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
}
