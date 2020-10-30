<?php

namespace App\Http\Controllers;

use App\CredPal;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Storage;
use Validator;

/**
 * @OA\Info(
 *   title="CredPal technical assessment project (Lumen/vue)",
 *   version="1.0.0",
 *   @OA\Contact(
 *     email="greendublin007@gmail.com",
 *     name="Idisimagha Dublin-green (Cliquedom Technologies)"
 *
 *   )
 * )
 *
 *
 */

/**
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Bearer Token for authentication",
 *     name="Token based Based",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="bearerAuth",
 * )
 */


class UserController extends Controller
{
    const STATUS = array("ACTIVE", "NOT ACTIVE");
    const CONFIRM_EMAIL = array("YES", "NO");

    private function generationEmailConfirmationKey($name, $email)
    {
        $keyCombination = $name . $email;
        $confirmationKey = md5($keyCombination);
        return $confirmationKey;
    }

    /**
     * @OA\Get(
     *     path="/apiv1/users/",
     *     operationId="/apiv1/users/",
     *     tags={"GetAllUsers"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response="200",
     *         description="Returns some users data",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Error: Token not provided.",
     *         @OA\JsonContent()
     *     ),
     *
     * )
     */
    public function getAllUsers()
    {
        return response(['data' => User::all(), 'message' => 'all users data!', 'status' => true, 'statusCode' => env('HTTP_SERVER_CODE_OK')]);
    }

    public function getUser($id)
    {
        $user = User::find($id);
        if (empty($user)) {
            return response(['message' => 'invalid user ID', 'status' => false, 'statusCode' => env('HTTP_SERVER_CODE_BAD_REQUEST')]);
        }else{
            return response(['data' => User::find($id), 'message' => 'get user by id!', 'status' => true, 'statusCode' => env('HTTP_SERVER_CODE_OK')]);
        }   
    }

    public function getUserByEmail(Request $request)
    {
        $input = $request->all();
        $email = $input['email'];

        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                'email',
                function ($attribute, $value, $fail) {
                    if (User::whereEmail($value)->count() == 0) {
                        $fail($value . ' is assigned to any user.');
                    }
                },
            ],
        ]);

        if ($validator->fails()) {
            return response(['message' => 'Validation errors', 'errors' => $validator->errors(), 'status' => false], 200);
        }

        $user = User::where('email', $email)->first();
        $user->makeHidden(['password']);
        return response(['data' => $user, 'message' => 'get user by email!', 'status' => true, 'statusCode' => env('HTTP_SERVER_CODE_OK')]);
    }

    public function getUserByMobile(Request $request)
    {
        $input = $request->all();
        $mobile = $input['mobile'];

        $validator = Validator::make($request->all(), [
            'mobile' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (User::where('mobile', $value)->count() == 0) {
                        $fail($value . ' is assigned to any user.');
                    }
                },
            ],
        ]);

        if ($validator->fails()) {
            return response(['message' => 'Validation errors', 'errors' => $validator->errors(), 'status' => false], 200);
        }

        $user = User::where('mobile', $mobile)->first();
        $user->makeHidden(['password']);
        return response(['data' => $user, 'message' => 'get user by mobile!', 'status' => true, 'statusCode' => env('HTTP_SERVER_CODE_OK')]);
    }

    public function getIDByReferralCodes(Request $request)
    {
        $input = $request->all();
        $referralCodes = $input['referral_codes'];

        $validator = Validator::make($request->all(), [
            'referral_codes' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (User::where('referral_codes', $value)->count() == 0) {
                        $fail($value . ' is assigned to any user.');
                    }
                },
            ],
        ]);

        if ($validator->fails()) {
            return response(['message' => 'Validation errors', 'errors' => $validator->errors(), 'status' => false], 200);
        }

        $user = User::where('referral_codes', $referralCodes)->first();
        $user->makeHidden([
            'password', 'mobile', 'email', 'email_validated', 'mobile_validated', 'mobile_validated', 'mobile_verification_code', 'referrer_id', 'created_at'
        ]);
        return response(['data' => $user, 'message' => 'get user ID by referral_codes!', 'status' => true, 'statusCode' => env('HTTP_SERVER_CODE_OK')]);
    }

    /**
     * @OA\Post(
     *     path="/auth/createUser/",
     *     operationId="/auth/createUser/",
     *     tags={"CreateUser"},
     *     summary="Create a user and create a new wallet account.",
     *     description="Create a user and create a new wallet account. Depending on configuration sends new registration SMS.",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 schema="PostRequest", 
     *                 type="object", 
     *                 title="PostRequest",
     *                 required={"first_name", "last_name","email","password","referrer_id","mobile"},   
     *                 properties={
     *                     @OA\Property(property="first_name", type="string"),
     *                     @OA\Property(property="last_name", type="string"),
     *                     @OA\Property(property="email", type="string"),
     *                     @OA\Property(property="password", type="string"),
     *                     @OA\Property(property="mobile", type="integer"), 
     *                     @OA\Property(property="referrer_id", type="string", default="0")   
     *                 }                                
     *             )
     *         )
     *     ), 
     *     @OA\Response(
     *         response="200",
     *         description="Returns some users data",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="user created!"),
     *              @OA\Property(property="status", type="boolean", example=true), 
     *              @OA\Property(property="statusCode", type="integer", example=201),
     *              @OA\Property(property="data", type="object", 
     *                  example={"first_name": "first-name",
     *                           "last_name": "last-name",
     *                           "email": "email@gmail.com",
     *                           "mobile": "08095060650"}), 
     *         )
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Returns some users data but does not create new user",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="unable to create user, something went wrong."),
     *              @OA\Property(property="status", type="boolean", example=false), 
     *              @OA\Property(property="statusCode", type="integer", example=400),
     *              @OA\Property(property="data", type="object", 
     *                  example={"first_name": "first-name",
     *                           "last_name": "last-name",
     *                           "email": "email@gmail.com",
     *                           "mobile": "08095060650"}), 
     *         )
     *     ),
     *
     * )
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                'email',
                function ($attribute, $value, $fail) {
                    if (User::whereEmail($value)->count() > 0) {
                        $fail($value . ' is already used.');
                    }
                },
            ],
            'mobile' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (User::where('mobile', '=', $value)->count() > 0) {
                        $fail($value . ' is already used.');
                    }
                },
            ],
            'first_name' => 'required',
            'last_name' => 'required',
            'password' => 'required',
            'referrer_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response(['message' => 'Validation errors', 'errors' => $validator->errors(), 'status' => false], 200);
        }

        $input = $request->all();

        $credPal = new credPal();
        $returnBool = $credPal->userRegistration($input);

        if ($returnBool) {
            return response(['data' => [
                'first_name' => $input['first_name'],
                'last_name' => $input['last_name'],
                'email' => $input['email'],
                'mobile' => $input['mobile'],
            ], 'message' => 'user created!', 'status' => true, 'statusCode' => env('HTTP_SERVER_CODE_CREATED')]);
        } else {
            return response(['data' => [
                'first_name' => $input['first_name'],
                'last_name' => $input['last_name'],
                'email' => $input['email'],
                'mobile' => $input['mobile'],
            ], 'message' => "unable to create user, something went wrong.", 'status' => false, 'statusCode' => env('HTTP_SERVER_CODE_BAD_REQUEST')]);
        }
    }

    public function updateCompanyLogo(Request $request)
    {
        if ($request->hasFile('file')) {
            $input = $request->all();

            try {
                $user = User::findOrFail($input['userID']);
            } catch (ModelNotFoundException $e) {
                return response(['message' => 'Validation errors', 'errors' => "userID is invalid.", 'status' => false], 200);
            }

            $validator = Validator::make($request->all(), [
                'file' => 'required',
                'userID' => 'required'
            ]);

            if ($validator->fails()) {
                return response(['message' => 'Validation errors', 'errors' => $validator->errors(), 'status' => false], 422);
            }

            $user = User::findOrFail($input['userID']);
            $uploadingUser = $user->email;
            $documentName = $request->file('file')->getClientOriginalName();
            $uniqueDocumentName = md5_file($request->file('file')->getRealPath()) . time();
            $ext = pathinfo(storage_path() . $documentName, PATHINFO_EXTENSION);
            $destinationPath = "public/uploads/companylogos/";
            $i = $request->file('file')->move($destinationPath, $uniqueDocumentName . "." . $ext);

            $filePath = url('/') . "/" . $destinationPath . $uniqueDocumentName . '.' . $ext;
            $returnData = [
                "documentName" => $uniqueDocumentName . '.' . $ext,
                "path" => $destinationPath . $uniqueDocumentName . '.' . $ext,
                "uploadedBy" => $uploadingUser,
                'ext' => $ext
            ];

            $user->logo = $destinationPath . $uniqueDocumentName . '.' . $ext;
            $saved = $user->save();
            if ($saved) {
                return response(['data' => $returnData, 'message' => 'logo updated!', 'status' => true, 'statusCode' => env('HTTP_SERVER_CODE_OK')]);
            }
        } else {
            return response(['message' => 'Validation errors', 'errors' => "no file sent", 'status' => false], 200);
        }
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'middle_name' => ''
        ]);

        if ($validator->fails()) {
            return response(['message' => 'Validation errors', 'request' => $request->all(), 'errors' => $validator->errors(), 'status' => false], 422);
        }

        $input = $request->all();
        $middleName = $input['middle_name'];

        $user = User::findOrFail($id);
        $user->middle_name = $middleName;
        $saved = $user->save();

        if ($saved) {
            return response(['data' => [
                'middle_name' => $middleName
            ], 'message' => 'user updated!', 'status' => true, 'statusCode' => env('HTTP_SERVER_CODE_CREATED')]);
        } else {
            return response(['data' => [
                'middle_name' => $middleName
            ], 'message' => "unable to update user, something went wrong.", 'status' => false, 'statusCode' => env('HTTP_SERVER_CODE_BAD_REQUEST')]);
        }
        $user->update($request->all());

        return response()->json($user, 200);
    }

    public function delete($id)
    {
        User::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }

    public function confirmEmail($companyName, $linkKey, Request $request)
    {
        $companyName = urldecode($companyName);
        $linkKey = urldecode($linkKey);

        $user = User::where('name', $companyName)->first();
        $user->status = self::STATUS[0];
        $user->confirmed_email = self::CONFIRM_EMAIL[0];

        $confirmLinkKey = $this->generationEmailConfirmationKey($companyName, $user->email);
        if ($confirmLinkKey ==  $linkKey) {
            $saved = $user->save();

            if ($saved) {
                return response(['data' => $user, 'message' => 'email confirmed!', 'status' => true, 'statusCode' => env('HTTP_SERVER_CODE_OK')]);
            } else {
                return response(['message' => 'email cannot be confirmed', 'status' => false, 'statusCode' => env('HTTP_SERVER_CODE_BAD_REQUEST')]);
            }
        } else {
            return response(['message' => 'invalid key', 'status' => false, 'statusCode' => env('HTTP_SERVER_CODE_BAD_REQUEST')]);
        }
    }
}
