<?php

namespace App\Http\Controllers;

use App\CredPal;
use App\User;
use App\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Storage;
use Validator;


class AccountController extends Controller
{
    public const STATUS = array('ACTIVE', 'SUSPENDED', 'NOT ACTIVE');
    public const ACCOUNT_TYPE = array('CURRENT', 'SAVINGS', 'CREDIT');


    /**
     * @OA\Get(
     *     path="/apiv1/getAccountByAccountNumber/{accountNumber}",
     *     operationId="/apiv1/getAccountByAccountNumber/{accountNumber}",
     *     tags={"getAccountByAccountNumber"},
     *     summary="Get account details by account number.",
     *     description="Get account details by account number",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *     name="accountNumber",
     *     in="path",
     *     required=true,
     *     description="accountNumber to get account details"
     *     ),    
     *     @OA\Response(
     *         response="200",
     *         description="Returns some account data",
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
    public function getAccountByAccountNumber($accountNumber)
    {
        $arrayGet = ['accountNumber' => $accountNumber];
        $validator = Validator::make($arrayGet, [
            'accountNumber' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (Account::where('account_number', $value)->count() == 0) {
                        $fail($value . ' is not a valid account number.');
                    }
                },
            ],
        ]);

        if ($validator->fails()) {
            return response(['message' => 'Validation errors', 'errors' => $validator->errors(), 'status' => false], 200);
        }

        $account = Account::where('account_number', $accountNumber)->first();
        return response(['data' => $account, 'message' => 'get account by account number!', 'status' => true, 'statusCode' => env('HTTP_SERVER_CODE_OK')]);
    }
}
