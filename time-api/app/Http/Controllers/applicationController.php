<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\application;

class applicationController extends Controller
{

    public function createApplication(Request $request)
    {
        $response = array('code' => 400, 'error_msg' => []);

        if (isset($request)) {
            if (!$request->name) array_push($response['error_msg'], 'name is required');
            if (!count($response['error_msg']) > 0) {
                try {
                    $user = application::where('name', '=', $request->name);

                    if (!$user->count()) {
                        try {
                            $app = new application();
                            $app->name = $request->name;
                            $app->image = $request->image ? $request->image : null;
                            $app->save();
                            $response = array('code' => 200, 'user' => $app, 'msg' => 'App created');
                        } catch (\Exception $exception) {
                            $response = array('code' => 500, 'error_msg' => $exception->getMessage());
                        }
                    } else {
                        $response = array('code' => 400, 'error_msg' => "App already registered");
                    }
                } catch (\Throwable $th) {
                    $response = array('code' => 500, 'error_msg' => $exception->getMessage());
                }
            }
        } else {
            $response['error_msg'] = 'Nothing to create';
        }

        return response($response, $response['code']);
    }

    public function getApp($id)
    {
        $response = array('code' => 400, 'error_msg' => []);
        if (isset($id)) {
            try {
                $application = application::find($id);
            } catch (\Exception $exception) {
                $response = array('code' => 500, 'error_msg' => $exception->getMessage());
            }
            if (!empty($application)) {
                $response = array('code' => 200, 'application' => $application);
            } else {
                $response = array('code' => 404, 'error_msg' => ['application not found']);
            }
        }

       return response($response,$response['code']);
    }


    public function updateApp(Request $request)
    {
        $response = array('code' => 400, 'error_msg' => []);
        $application = application::find($request->id);
        if (isset($application)) {
            try {
                if (!empty($application)) {
                    try {

                        $application->name = $request->name ? $request->name : $application->name;
                        $application->image = $request->image ? $request->image : $application->image;
                        $application->save();
                        $response = array('code' => 200, 'msg' => 'Application updated');
                    } catch (\Exception $exception) {
                        $response = array('code' => 500, 'error_msg' => $exception->getMessage());
                    }
                }
            } catch (\Throwable $th) {
                $response = array('code' => 500, 'error_msg' => $exception->getMessage());
            }
        } else {
            $response['error_msg'] = 'Nothing to update';
        }
        return response($response, $response['code']);
    }

    public function deleteApp($id)
    {
        $response = array('code' => 400, 'error_msg' => []);

        if (isset($id)) {

            try {
                $application = application::find($id);

                if (!empty($application)) {
                    try {
                        $application->delete();
                        $response = array('code' => 200, 'msg' => 'application deleted');
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
