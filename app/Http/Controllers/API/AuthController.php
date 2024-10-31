<?php

namespace App\Http\Controllers\API;

use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\API\BaseController as BaseController;

class AuthController extends BaseController
{

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['user'] =  $user;

        return $this->sendResponse($success, 'User register successfully.');
    }


    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }
            //TODO Get the authenticated user
            $user = auth()->user();
            //TODO Attach custom claims, such as the role, to the token
            $token = JWTAuth::claims(['role' => $user->role])->fromUser($user);
            //TODO Include user data with the token in the response

            $responseData = [
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role,
                    ],
                    'token' => $token,
                ],
                'success' => true,
                'message' => "Valid User",
            ];
            return response()->json($responseData);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile()
    {
        $success = auth()->user();

        return $this->sendResponse($success, 'Refresh token return successfully.');
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return $this->sendResponse([], 'Successfully logged out.');
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        $success = $this->respondWithToken(auth()->refresh());

        return $this->sendResponse($success, 'Refresh token return successfully.');
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];
    }
}
