<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function handleException($e)
    {
        $class_name = get_class($e);

        if($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
            return response()->error($e->getMessage(), $e->getStatusCode());
        } else if($class_name == "RuntimeException") {
            return response()->error($e->getMessage(), 500);
        } else if($class_name == "Illuminate\Validation\ValidationException") {
            return response()->error($e->getMessage(), 422, ['errors' => $e->errors()]);
        } else if($class_name == "Illuminate\Database\Eloquent\ModelNotFoundException") {
            return response()->error("Record Not Found", Response::HTTP_NOT_FOUND);
        } else {
            return response()->error("Internal Server Error", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
