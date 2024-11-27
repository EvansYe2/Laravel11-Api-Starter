<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Jiannei\Response\Laravel\Support\Facades\Response;

trait ApiResponse
{
    public function success($code, $data = []): JsonResponse
    {
        $message = __('enum.'.$code.'.Message');
        return Response::success($data,$message);
    }

    public function fail($code, $data = null): JsonResponse
    {
        $message = __('enum.'.$code.'.Message');
        $code = __('enum.'.$code.'.Code');
        return Response::success($data,$message,$code);
    }
}
