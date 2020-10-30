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

class WalletController extends Controller
{

    public function getBalance(Request $request)
    {
        $input = $request->all();
        $userID = $input['userID'];

        $validator = Validator::make($request->all(), [
            'userID' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (User::where('id', $value)->count() == 0) {
                        $fail($value . ' is assigned to any user.');
                    }
                },
            ],
        ]);

        if ($validator->fails()) {
            return response(['message' => 'Validation errors', 'errors' => $validator->errors(), 'status' => false], 200);
        }

        $user = User::find($userID);
        $user->makeHidden(['password']);

        $returnData = array();
        if (!empty($user)) {
            $returnData = [];

            $account = Account::where('user_id', $user->id)->get();
            $account = $account[0];

            $credPal = new CredPal();
            $balance = $credPal->getWalletBalance($user->id);
            $returnData = array("account" => $account->account_number, "account_type" => $account->account_type, "status" => $account->status, "balance" => number_format($balance));
        }
        return response(['data' => $returnData, 'message' => 'get user wallert balance', 'status' => true, 'statusCode' => env('HTTP_SERVER_CODE_OK')]);
    }
}
