<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;

trait HttpResponses
{
    //Configura as respostas para as requisições erro ou sucesso
    //SERA USADO NO CONTROLLER
    //success
    public function successResponse($message, $status = 200, array|Model|JsonResource $data = [])
    {
        return response()->json([
            'massage' => $message,
            'status' => $status,
            'data' => $data
        ], $status);
    }

    //error
    public function errorResponse($message, $status, $data = [], $errors = [])
    {
        return response()->json([
            'massage' => $message,
            'status' => $status,
            'errors' => $errors,
            'data' => $data,
            
        ], $status);
    }


    //
    public function showAll($collection, $status = 200)
    {
        return $this->successResponse(['data' => $collection], $status);
    }

    public function showOne($instance, $status = 200)
    {
        return $this->successResponse(['data' => $instance], $status);
    }

    public function showMessage($message, $status = 200)
    {
        return $this->successResponse(['data' => $message], $status);
    }
}
