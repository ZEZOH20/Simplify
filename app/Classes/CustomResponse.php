<?php

namespace App\Classes;

trait CustomResponse
{
     function success($message, $data = [], $status = 200)
    {
        return response([
            'messsage' => $message,
            'data' => $data,
            'status'=>$status
        ],  $status);
    }
     function failure($message, $data = [], $status = 422)
    {
        return response([
            'messsage' => $message,
            'data' => $data,
            'status'=>$status
        ],  $status);
    }
}
