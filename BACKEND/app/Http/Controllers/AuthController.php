<?php

namespace App\Http\Controllers;

use App\User;
use App\CredPal;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller as BaseController;

class AuthController extends BaseController
{
    const STATUS = array("ACTIVE", "NOT ACTIVE");

    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    private $request;

    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $credPal = new credPal(); // Create this Model to init the __construct method not using this here
    }

    /**
     * Create a new token.
     *
     * @param  \App\User   $user
     * @return string
     */
    protected function jwt(User $user)
    {
        $payload = [
            'iss' => "lumen-jwt", // Issuer of the token
            'sub' => $user->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued.
            'exp' => time() + 60 * 60, // Expiration time
        ];

        // As you can see we are passing `JWT_SECRET` as the second parameter that will
        // be used to decode the token in the future.
        return JWT::encode($payload, env('JWT_SECRET'));
    }

    /**
     * Authenticate a user and return the token if the provided credentials are correct.
     *
     * @param  \App\User   $user
     * @return mixed
     */
    public function authenticate(User $user)
    {
        $this->validate($this->request, [
            'mobile' => 'required',
            'password' => 'required',
        ]);

        // Find the user by email and status
        $user = User::where('mobile', $this->request->input('mobile'))->first();

        if (!$user) {
            // You wil probably have some sort of helpers or whatever
            // to make sure that you have the same response format for
            // differents kind of responses. But let's return the
            // below respose for now.
            return response()->json([
                'error' => 'Mobile does not exist.',
            ], env('HTTP_SERVER_CODE_BAD_REQUEST'));
        }

        // Verify the password and generate the token
        $password = $this->request->input('password');
        if (Hash::check($password, $user->password)) {
            return response()->json([
                'token' => $this->jwt($user),

            ], env('HTTP_SERVER_CODE_OK'));
        }

        // Bad Request response
        return response()->json([
            'error' => 'Mobile and password combination is wrong.',
        ], env('HTTP_SERVER_CODE_BAD_REQUEST'));
    }
}
