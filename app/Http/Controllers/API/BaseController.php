<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
     /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    // public function sendResponse($result, $message)
    // {
    // 	$response = [
    //         'success' => true,
    //         'data'    => $result,
    //         'message' => $message,
    //     ];


    //         return response()->json($response, 200);
    //     }


        /**
         * return error response.
         *
         * @return \Illuminate\Http\Response
         */
        // public function sendError($error, $errorMessages = [], $code = 404)
        // {
        //     // $response = [
        //     //     'success' => false,
        //     //     'message' => $error,
        //     // ];


        //     // if(!empty($errorMessages)){
        //     //     $response['data'] = $errorMessages;
        //     // }

        //     // return response()->json($response, $code);
        //     return response()->json(['message' => 'something is wrong', 'success' => false,  "code" => 200], 200);
        // }


    public function sendError($error, $errorMessages = [], $code = 201)
    {

    	$response = [
            'success' => false,
            'code' =>201,
            'message' => $error
        ];

        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);
    }

    public function sendResponse($message, $data = [], $code = 200)
    {

    	$response = [
            'success' => true,
            'code' =>200,
            'message' => $message
        ];

        if(!empty($data)){
            $response['data'] = $data;
        }
        return response()->json($response, $code);
    }

}
