<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\restriction;
use App\Helper\Token;
use App\application;
use App\user;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class restrictionController extends Controller
{


    public function createRestiction(Request $request)
    {
        $restriction = new restriction();
        $response = array('code' => 400, 'error_msg' => []);
        if (isset($request)) {
            if (!$request->max_time || !$this->isValidHour($request->max_time)) array_push($response['error_msg'], 'max_time invalid format');
            if (!$request->start_hour_restriction || !$this->isValidHour($request->start_hour_restriction)) array_push($response['error_msg'], 'start_hour_restriction invalid format');
            if (!$request->finish_hour_restriction || !$this->isValidHour($request->finish_hour_restriction)) array_push($response['error_msg'], 'finish_hour_restriction invalid format');
            if (!$request->user_id|| null == User::find($request->user_id)) array_push($response['error_msg'], 'invalid user_id');
            if (!$request->application_id|| null == application::find($request->application_id)) array_push($response['error_msg'], 'application_id is required');
            if (!count($response['error_msg']) > 0) {
                $restriction = restriction::where("user_id", $request->user_id)->where("application_id", $request->application_id)->first();
                if (isset($restriction)) {
                    array_push($response['error_msg'], 'a restriction for this app already exists');
                } else {
                    try {
                        $restriction = new restriction;
                        $restriction->max_time = $request->max_time;
                        $restriction->start_hour_restriction = $request->start_hour_restriction;
                        $restriction->finish_hour_restriction = $request->finish_hour_restriction;
                        $restriction->user_id = $request->user_id;
                        $restriction->application_id = $request->application_id;
                        $restriction->save();
                        $response = array('code' => 200, 'restriction' => $restriction, 'msg' => 'restriction created');
                    } catch (\Exception $exception) {
                        $response = array('code' => 500, 'error_msg' => $exception->getMessage());
                    }
                }
            }
        } 

        return response($response, $response['code']);
    }



    public function getAllRestrictions($id)
    {
        $response = array('code' => 400, 'error_msg' => []);
        if (isset($id)) {
            try {
                $user = User::find($id);
                $restrictions = $user->userRestrictions;
            } catch (\Exception $exception) {
                $response = array('code' => 500, 'error_msg' => $exception->getMessage());
            }
            if (!empty($restrictions)) {
                $response = array('code' => 200, 'application' => $restrictions);
            } else {
                $response = array('code' => 404, 'error_msg' => ['restrictions not found']);
            }
        }

        return response($response, $response['code']);
    }




    public function updateRestriction(Request $request)
    {
        $response = array('code' => 400, 'error_msg' => []);
        if ($request->max_time && !$this->isValidHour($request->max_time)) array_push($response['error_msg'], 'max_time invalid format');
        if ($request->start_hour_restriction && !$this->isValidHour($request->start_hour_restriction)) array_push($response['error_msg'], 'start_hour_restriction invalid format');
        if ($request->finish_hour_restriction && !$this->isValidHour($request->finish_hour_restriction)) array_push($response['error_msg'], 'finish_hour_restriction invalid format');
        $restriction = restriction::find($request->id);
        if (!isset($restriction)) array_push($response['error_msg'], 'restriction not found');
       
        if (!count($response['error_msg']) > 0) {
            try {
                try {
                    $restriction->max_time = $request->max_time ? $request->max_time : $restriction->max_time;
                    $restriction->start_hour_restriction = $request->start_hour_restriction ? $request->start_hour_restriction : $restriction->start_hour_restriction;
                    $restriction->finish_hour_restriction = $request->finish_hour_restriction ? $request->finish_hour_restriction : $restriction->finish_hour_restriction;
                    $restriction->save();
                    $response = array('code' => 200, 'restriction' => $restriction, 'msg' => 'restriction updated');
                } catch (\Exception $exception) {
                    $response = array('code' => 500, 'error_msg' => $exception->getMessage());
                }
            } catch (\Throwable $th) {
                $response = array('code' => 500, 'error_msg' => $exception->getMessage());
            }
        }
        return response($response, $response['code']);
    }


    public function deleteRestriction($id)
    {
        $response = array('code' => 400, 'error_msg' => []);
        if (isset($id)) {
            try {
                $application = restriction::find($id);

                if (!empty($application)) {
                    try {
                        $application->delete();
                        $response = array('code' => 200, 'msg' => 'restriction deleted');
                    } catch (\Exception $exception) {
                        $response = array('code' => 500, 'error_msg' => $exception->getMessage());
                    }
                } else {
                    $response = array('code' => 401, 'error_msg' => 'Unautorized');
                }
            } catch (\Throwable $th) {
                $response = array('code' => 500, 'error_msg' => $exception->getMessage());
            }
        }

        return response($response, $response['code']);
    }

    public function createRestictionPanel(Request $request)
    {
        $restriction = new restriction();
        $response = array('code' => 400, 'error_msg' => []);
        if (isset($request)) {
            if (!$request->max_time) array_push($response['error_msg'], 'max_time is required');
            if (!$request->start_hour_restriction) array_push($response['error_msg'], 'start_hour_restriction is required');
            if (!$request->finish_hour_restriction) array_push($response['error_msg'], 'finish_hour_restriction is required');
            if (!Auth::user()->id) array_push($response['error_msg'], 'user_id is required');
            if (!$request->application_id) array_push($response['error_msg'], 'application_id is required');

            if (!count($response['error_msg']) > 0) {
                try {

                    $restriction = new restriction;
                    $restriction->max_time = $request->max_time;
                    $restriction->start_hour_restriction = $request->start_hour_restriction;
                    $restriction->finish_hour_restriction = $request->finish_hour_restriction;
                    $restriction->user_id = Auth::user()->id;
                    $restriction->application_id = $request->application_id;
                    $restriction->save();
                    $response = array('code' => 200, 'restriction' => $restriction, 'msg' => 'restriction created');
                } catch (\Exception $exception) {
                    $response = array('code' => 500, 'error_msg' => $exception->getMessage());
                }
            }
        } else {
            $response['error_msg'] = 'Nothing to create';
        }
        $restrictions = restriction::where('user_id', Auth::user()->id)->paginate(5)->onEachSide(2);


        if (!!count($restrictions) > 0) {
            $response = array('code' => 200, 'restrictions' => $restrictions);
        } else {
            $response = array('code' => 404, 'error_msg' => ['restrictions not found']);
        }

        return view('restrictions', ['response' => $response]);
    }

    public function updateRestrictionPanel(Request $request, $id)
    {
        $response = array('code' => 400, 'error_msg' => []);
        $restriction = restriction::find($id);
        if (isset($restriction)) {
            try {
                try {
                    $restriction->max_time = $request->max_time ? $request->max_time : $restriction->max_time;
                    $restriction->start_hour_restriction = $request->start_hour_restriction ? $request->start_hour_restriction : $restriction->start_hour_restriction;
                    $restriction->finish_hour_restriction = $request->finish_hour_restriction ? $request->finish_hour_restriction : $restriction->finish_hour_restriction;
                    $restriction->save();
                    $response = array('code' => 200, 'restriction' => $restriction, 'msg' => 'restriction updated');
                } catch (\Exception $exception) {
                    $response = array('code' => 500, 'error_msg' => $exception->getMessage());
                }
            } catch (\Throwable $th) {
                $response = array('code' => 500, 'error_msg' => $exception->getMessage());
            }
        } else {
            $response['error_msg'] = 'Nothing to update';
        }
        $restrictions = restriction::where('user_id', Auth::user()->id)->paginate(5)->onEachSide(2);


        if (!!count($restrictions) > 0) {
            $response = array('code' => 200, 'restrictions' => $restrictions);
        } else {
            $response = array('code' => 404, 'error_msg' => ['restrictions not found']);
        }
        return view('restrictions', ['response' => $response]);
    }

    public function restrictionPanel(Request $request)
    {
        $response = array('code' => 400, 'error_msg' => []);
        if (!Auth::user()->id) array_push($response['error_msg'], 'user_id is required');
        if (!$request->application_id) array_push($response['error_msg'], 'application_id is required');
        if (!count($response['error_msg']) > 0) {
            $restriction = restriction::where("user_id", Auth::user()->id)->where("application_id", $request->application_id)->first();
            if (isset($restriction)) {
                return $this->updateRestrictionPanel($request, $restriction->id);
            } else {
                return $this->createRestictionPanel($request);
            }
        } else {
            return view('restrictions', ['response' => $response]);
        }
    }

    function isValidHour($date, $format = "H:i:s")
    {
        $dateObj = DateTime::createFromFormat($format, $date);
        return $dateObj && $dateObj->format($format) == $date;
    }
}
