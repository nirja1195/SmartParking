<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthApiController extends Controller
{
    /**
     * Issue API token for valid credentials.
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
            'device_name' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return ApiResponse::error('Validation failed.', Response::HTTP_UNPROCESSABLE_ENTITY, $validator->errors());
        }

        $user = User::where('email', $request->input('email'))->first();

        if (! $user || ! Hash::check($request->input('password'), $user->password)) {
            return ApiResponse::error('Invalid credentials provided.', Response::HTTP_UNAUTHORIZED);
        }

        if (! $user->is_active) {
            return ApiResponse::error('Account is deactivated.', Response::HTTP_FORBIDDEN);
        }

        $deviceName = $request->input('device_name', 'mobile-app');
        $token = $user->createToken($deviceName)->plainTextToken;

        return ApiResponse::success([
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => new UserResource($user),
        ], 'Authenticated successfully.');
    }

    /**
     * Register a new customer via API.
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:25',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return ApiResponse::error('Validation failed.', Response::HTTP_UNPROCESSABLE_ENTITY, $validator->errors());
        }

        $customerRole = Role::where('slug', Role::CUSTOMER)->first();

        $user = User::create([
            'role_id' => $customerRole ? $customerRole->id : 2,
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'password' => Hash::make($request->input('password')),
            'is_active' => true,
        ]);

        $token = $user->createToken('api-client')->plainTextToken;

        return ApiResponse::success([
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => new UserResource($user),
        ], 'Customer registration successful.', Response::HTTP_CREATED);
    }

    /**
     * Current authenticated user profile.
     */
    public function profile(Request $request): JsonResponse
    {
        return ApiResponse::success(new UserResource($request->user()), 'User profile fetched.');
    }

    /**
     * Revoke current API token.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()?->delete();

        return ApiResponse::success(null, 'Token successfully revoked.');
    }
}
