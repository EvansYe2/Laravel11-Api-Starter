<?php

namespace App\Api\V1\Controllers;

use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{
    use ApiResponse;
    protected string $guard = 'api';

    public function login(Request $request): JsonResponse
    {
        $payload = $request->only('email', 'password');
        $rule = [
            'email'     => ['required','email'],
            'password'  => ['required','min:5']
        ];
        $message = [
            'email.required'    => 'COMMON_AUTH_EMAIL_EMPTY',
            'email.email'       => 'COMMON_AUTH_EMAIL_ERROR',
            'password.required' => 'COMMON_AUTH_PASSWORD_EMPTY',
            'password.min'      => 'COMMON_AUTH_PASSWORD_ERROR'
        ];
        $validator = Validator::make($payload, $rule, $message);
        if ($validator->fails()) {
            return $this->fail($validator->errors()->first());
        }
        $user = User::query()->where('email', $payload['email'])->first();
        if (!$user || !Hash::check($payload['password'], $user->password)) {
            return $this->fail('COMMON_LOGIN_ERROR');
        }
        $token = $user->createToken('Personal Access Token')->plainTextToken;
        return $this->respondWithToken($token);
    }

    public function register(Request $request): JsonResponse
    {
        $payload = $request->only('name', 'email', 'password');
        $rule = [
            'name'      => ['required'],
            'email'     => ['required','email'],
            'password'  => ['required','min:8']
        ];
        $message = [
            'name.required'     => 'COMMON_AUTH_NAME_EMPTY',
            'email.required'    => 'COMMON_AUTH_EMAIL_EMPTY',
            'email.email'       => 'COMMON_AUTH_EMAIL_ERROR',
            'password.required' => 'COMMON_AUTH_PASSWORD_EMPTY',
            'password.min'      => 'COMMON_AUTH_PASSWORD_ERROR'
        ];
        $validator = Validator::make($payload, $rule, $message);
        if ($validator->fails()) {
            return $this->fail($validator->errors()->first());
        }
        DB::beginTransaction();
        try {
            $user = User::query()->create([
                'name'      => $payload['name'],
                'email'     => $payload['email'],
                'password'  => Hash::make($payload['password'])
            ]);
            $token = $user->createToken('Personal Access Token')->plainTextToken;
            DB::commit();
            return $this->respondWithToken($token);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->fail('COMMON_EMAIL_NOT_REGISTER');
        }
    }

    public function me(): JsonResponse
    {
        $user = Auth::user();
        return $this->success('COMMON_HTTP_OK', [
            'id'    => $user['id'],
            'name'  => $user['name'],
            'email' => $user['email']
        ]);
    }
    protected function respondWithToken(string $token): JsonResponse
    {
        return $this->success('COMMON_TOKEN_GET_SUCCESS', [
            'access_token'  => $token,
            'token_type'    => 'Bearer',
            'expires_in'    => time() + (config('sanctum.expiration') * 60)
        ]);
    }

}
