<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\restriction;
use App\Helper\Token;
use App\application;
use App\user;

class restrictionController extends Controller
{


    public function createRestiction(Request $request)
    {
        $restriction = new restriction();
        $response = array('code' => 400, 'error_msg' => []);
        if (isset($request)) {
            if (!$request->max_time) array_push($response['error_msg'], 'max_time is required');
            if (!$request->start_hour_restriction) array_push($response['error_msg'], 'start_hour_restriction is required');
            if (!$request->finish_hour_restriction) array_push($response['error_msg'], 'finish_hour_restriction is required');
            if (!$request->user_id) array_push($response['error_msg'], 'user_id is required');
            if (!$request->application_id) array_push($response['error_msg'], 'application_id is required');

            if (!count($response['error_msg']) > 0) {
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
        } else {
            $response['error_msg'] = 'Nothing to create';
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
        $restriction = restriction::find($request->id);
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
}
