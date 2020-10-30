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
                        $fail($value . ' is not assigned to any user.');
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

    public function transfer(Request $request)
    {
        $input = $request->all();
        $fromUserID = $input['from_userID'];
        $toUserID = $input['to_userID'];
        $amount = $input['amount'];
        $description = $input['description'];
        $scheduleTransfer = $input['schedule_transfer'];
        $scheduleDate = $input['schedule_date'];

        $validator = Validator::make($request->all(), [
            'from_userID' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (User::where('id', $value)->count() == 0) {
                        $fail($value . ' is not assigned to any user.');
                    }
                },
            ],
            'to_userID' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (User::where('id', $value)->count() == 0) {
                        $fail($value . ' is not assigned to any user.');
                    }
                },
            ],
            'amount' => [
                'required',
            ]
        ]);

        if ($validator->fails()) {
            return response(['message' => 'Validation errors', 'errors' => $validator->errors(), 'status' => false], 200);
        }

        $fromUser = User::find($fromUserID);
        $fromUser->makeHidden(['password']);

        $toUser = User::find($toUserID);
        $toUser->makeHidden(['password']);

        $returnData = array();
        if (!empty($fromUser) && !empty($toUser)) {
            $credPal = new CredPal();


            if (
                $fromUser->status == $credPal::ACCOUNT_STATUS_OPTIONS[0] &&
                $toUser->status == $credPal::ACCOUNT_STATUS_OPTIONS[0]
            ) {
                $returnData = [];

                $accountFrom = Account::where('user_id', $fromUser->id)->get();
                $accountFrom = $accountFrom[0];

                $accountTo = Account::where('user_id', $toUser->id)->get();
                $accountTo = $accountTo[0];

                if (
                    $accountFrom->status == $credPal::ACCOUNT_STATUS_OPTIONS[0] &&
                    $accountTo->status == $credPal::ACCOUNT_STATUS_OPTIONS[0]
                ) {
                    $transferStatus = $credPal->transfer($input);

                    if ($transferStatus) {
                        return response(['data' => $returnData, 'message' => 'Transfer Successfully', 'status' => true, 'statusCode' => env('HTTP_SERVER_CODE_OK')]);
                    } else {
                        return response(['message' => 'Transfer failed', 'status' => false, 'statusCode' => env('HTTP_SERVER_CODE_BAD_REQUEST')]);
                    }
                } else {
                    return response(['message' => 'Transfer failed (Account(s) IN ACTIVE)', 'status' => false, 'statusCode' => env('HTTP_SERVER_CODE_BAD_REQUEST')]);
                }
            } else {
                return response(['data' => $returnData, 'message' => 'Transfer failed User(s) Account IN ACTIVE', 'status' => true, 'statusCode' => env('HTTP_SERVER_CODE_BAD_REQUEST')]);
            }
        } else {
            return response(['message' => 'Invalid user(s)', 'status' => false, 'statusCode' => env('HTTP_SERVER_CODE_BAD_REQUEST')]);
        }
    }
}
