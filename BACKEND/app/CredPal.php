<?php

namespace App;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use DB;
use App\User;
use App\Ledger;
use App\Configuration;
use App\Account;
use App\Issue;
use App\CredPal_Interface;
use App\EbulkSms;

class CredPal implements CredPal_Interface
{
    public const ACCOUNT_TYPE = ['CURRENT', 'SAVINGS', 'CREDIT'];
    public const ACCOUNT_STATUS_OPTIONS = ['ACTIVE', 'SUSPENDED', 'NOT ACTIVE'];
    public const VALIDATION_OPTIONS = ['YES', 'NO'];
    public const DEFAULT_REFERRER = 0;
    public const TRANSACTION_STATE = ['SUCCESS', 'FAILED', 'PENDING'];
    public const SEND_REGISTRATION_SMS_CONFIGURATION_KEY = 'SEND_REGISTRATION_SMS';
    public const REFERRAL_BONUS_KEY = 'REFERRAL_BONUS';
    protected const APP_USER_TYPE = ['DEFAULT', 'REGULAR'];

    public function __construct()
    {
        $this->createDefaultAccount();
    }

    private function genMobileValidationCode($user)
    {
        if (!empty($user)) {
            $genCode = rand(100000, 999999);
            $user->mobile_verification_code = $genCode;
            $user->save();

            return $genCode;
        }

        return false;
    }

    private function genReferralCode($mobile, $email)
    {
        $keyCombination = $mobile . $email;
        $confirmationKey = md5($keyCombination);
        return $confirmationKey;
    }

    private function getReceivedTotalFromLedgerByUserIDAndStatus($userID, $transactionStatus)
    {
        $resultSet = DB::select(DB::raw("SELECT SUM(amount) AS total FROM ledger
        WHERE receiver_id = :user_receiving AND status = :ledger_status;
       "), array(
            'user_receiving' => $userID,
            'ledger_status' => $transactionStatus
        ));

        if (!empty($resultSet)) {
            return $resultSet;
        } else {
            return 0.0;
        }
    }

    private function getSentTotalFromLedgerByUserIDAndStatus($userID, $transactionStatus)
    {
        $resultSet = DB::select(DB::raw("SELECT SUM(amount) AS total FROM ledger
        WHERE sender_id = :user_sending AND status = :ledger_status;
       "), array(
            'user_sending' => $userID,
            'ledger_status' => $transactionStatus
        ));

        if (!empty($resultSet)) {
            return $resultSet;
        } else {
            return 0.0;
        }
    }

    public function validateReferrerID($referrerID)
    {
        $user = User::where('referrer_id', $referrerID)->first();
        if (!empty($user)) {
            return $user->id;
        } else {
            return $this::DEFAULT_REFERRER; // 1 is the great elite default referrer
        }
    }

    public function sendMobileValidationSMS($user, $code)
    {
        $username = env('SMS_USERNAME');
        $apikey = env('SMS_API_KEY');
        $sendername = env('SMS_TITLE');
        $recipients = $user->mobile;
        $flash = 0;
        $appName = env('APP_NAME');

        $composedMessage = "{$user->first_name}, your mobile validation code is {$code}.";
        $message = substr($composedMessage, 0, 160); //Limit this message to one page.

        $sms = new EbulkSms();
        #Use the next line for HTTP POST with JSON
        $result = $sms->useJSON(env('SMS_JSON_URL'), $username, $apikey, $flash, $sendername, $message, $recipients);
        return $result;
    }

    private function registrationWelcome($user)
    {
        $username = env('SMS_USERNAME');
        $apikey = env('SMS_API_KEY');
        $sendername = env('SMS_TITLE');
        $recipients = $user->mobile;
        $flash = 0;
        $appName = env('APP_NAME');

        $composedMessage = "{$user->first_name} your {$appName} account is live. Welcome to the {$appName} family!";
        $message = substr($composedMessage, 0, 160); //Limit this message to one page.

        $sms = new EbulkSms();
        #Use the next line for HTTP POST with JSON
        $result = $sms->useJSON(env('SMS_JSON_URL'), $username, $apikey, $flash, $sendername, $message, $recipients);
        return $result;
    }

    private function createAccount($user, $accountType)
    {
        if (!empty($user)) {
            $genAccountNumber = rand(1000000000, 9999999999);

            $account = Account::where('user_id', $user->id);
            if (!empty($account->user_id) && empty($account->account_number)) {
                $issue = Issue::create([
                    'status' => 'PENDING',
                    'handled_by' => 0,
                    'user_id' => $user->id,
                    'message' => 'Account number is empty'
                ]);
                $saved = $issue->save();

                return false;
            } else {
                $newAccount = Account::create([
                    'user_id' => $user->id,
                    'account_number' => $genAccountNumber,
                    'account_type' => $accountType,
                    'status' => $this::ACCOUNT_STATUS_OPTIONS[0]
                ]);
                $newAccount->save();
                return true;
            }
        }

        return false;
    }

    public function mobileValidation($user, $code)
    {
        if (!empty($user)) {
            if ($user->mobile_verification_code === $code) {
                $user->mobile_validated = $this::VALIDATION_OPTIONS[0];
                $user->save();
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    public function userRegistration($userArray)
    {
        $newUser = User::create([
            'first_name' => $userArray['first_name'],
            'middle_name' => '',
            'last_name' => $userArray['last_name'],
            'email' => $userArray['email'],
            'mobile' => $userArray['mobile'],
            'referrer_id' => $userArray['referrer_id'],
            'referral_codes' => $this->genReferralCode($userArray['mobile'], $userArray['email']),
            'password' => Hash::make($userArray['password']),
            'type' => $this::APP_USER_TYPE[1],
            'status ' => 'ACTIVE', // FOR NOW UNTIL MOBILE VALIDATION CAN BE DONE
        ]);

        $bool = $newUser->save();

        if ($bool) {
            $user = User::where('mobile', '=', $userArray['mobile'])->first();
            if ($user->referrer_id != 0) {
                $returnValue = $this->getConfiguration($this::REFERRAL_BONUS_KEY);
                if ($returnValue == 'YES') {
                    $bonus = env('REFERAL_BONUS');
                    $defaultUser = User::where('type', '=', $this::APP_USER_TYPE[0])->first();

                    if (!empty($defaultUser)) {
                        $bonusLedger = Ledger::create([
                            'sender_id' => $defaultUser->id,
                            'receiver_id' => $user->id,
                            'status' => $this::TRANSACTION_STATE[0],
                            'amount' => $bonus,
                            'description' => 'referral bonus'
                        ]);
                        $bool = $bonusLedger->save();
                    }
                }
            }

            $returnBoolean = $this->createAccount($user, $this::ACCOUNT_TYPE[2]);


            $returnValue = $this->getConfiguration($this::SEND_REGISTRATION_SMS_CONFIGURATION_KEY);
            if ($returnValue == 'YES') {
                $this->registrationWelcome($user);
            }
            return $returnBoolean;
        } else {
            return false;
        }
    }

    public function transfer($transactionArray)
    {
        // print_r($transactionArray);
        $returnBool = false;
        if ($transactionArray['schedule_transfer'] == 'yes') {
            // schedule transfer
            $balance = $this->getWalletBalance($transactionArray['from_userID']);
            if ($transactionArray['amount'] <= $balance) {
                $ledger = Ledger::create([
                    'sender_id' => $transactionArray['from_userID'],
                    'receiver_id' => $transactionArray['to_userID'],
                    'status' => $this::TRANSACTION_STATE[0],
                    'amount' => $transactionArray['amount'],
                    'description' => $transactionArray['description'],
                    'schedule_transfer' => $transactionArray['schedule_transfer'],
                    'schedule_date' => $transactionArray['schedule_date']
                ]);
                $returnBool = $ledger->save();
            }
        } else {
            $balance = $this->getWalletBalance($transactionArray['from_userID']);
            if ($transactionArray['amount'] <= $balance) {
                $ledger = Ledger::create([
                    'sender_id' => $transactionArray['from_userID'],
                    'receiver_id' => $transactionArray['to_userID'],
                    'status' => $this::TRANSACTION_STATE[0],
                    'amount' => $transactionArray['amount'],
                    'description' => $transactionArray['description'],
                    'schedule_transfer' => $transactionArray['schedule_transfer'],
                    'schedule_date' => $transactionArray['schedule_date']
                ]);
                $returnBool = $ledger->save();
            }
        }

        if ($returnBool) {
            return true;
        } else {
            return false;
        }
    }

    private function createDefaultAccount()
    {
        $CREDPAL_USER =
            [
                'first_name' => "CredPal",
                'middle_name' => 'CredPal',
                'last_name' => "CredPal",
                'email' => "credpa@credPal.com",
                'mobile' => "07011112222",
                'referrer_id' => 0,
                'referral_codes' => '000001111122222',
                'password' => Hash::make("CredPal"),
                'email_validated' => $this::VALIDATION_OPTIONS[0],
                'mobile_validated' => $this::VALIDATION_OPTIONS[0],
                'mobile_verification_code' => '1234567890',
                'status' => $this::ACCOUNT_STATUS_OPTIONS[0],
                'type' => $this::APP_USER_TYPE[0]
            ];

        //Check if created before now
        $user = User::where('type', '=', $this::APP_USER_TYPE[0])->first();
        if (empty($user)) {

            $newUser = User::create([
                'first_name' => $CREDPAL_USER['first_name'],
                'middle_name' => $CREDPAL_USER['middle_name'],
                'last_name' => $CREDPAL_USER['last_name'],
                'email' => $CREDPAL_USER['email'],
                'mobile' => $CREDPAL_USER['mobile'],
                'referrer_id' => $CREDPAL_USER['referrer_id'],
                'referral_codes' => $CREDPAL_USER['referral_codes'],
                'password' => $CREDPAL_USER['password'],
                'email_validated' => $CREDPAL_USER['email_validated'],
                'mobile_validated' => $CREDPAL_USER['mobile_validated'],
                'mobile_verification_code' => $CREDPAL_USER['mobile_verification_code'],
                'status' => $CREDPAL_USER['status'],
                'type' => $this::APP_USER_TYPE[0],
            ]);

            $bool = $newUser->save();

            if ($bool) {
                $user = User::where('mobile', '=', $CREDPAL_USER['mobile'])->first();
                $returnBoolean = $this->createAccount($user, $this::ACCOUNT_TYPE[0]);

                //Create default money for default account
                $defaultDeposit = env('DEFAULT_ACCOUNT_INIT_DEPOSIT');
                $defaultLedger = Ledger::create([
                    'sender_id' => 0,
                    'receiver_id' => $user->id,
                    'status' => $this::TRANSACTION_STATE[0],
                    'amount' => $defaultDeposit,
                    'description' => 'DEFAULT_ACCOUNT_INIT_DEPOSIT'
                ]);
                $bool = $defaultLedger->save();

                if (
                    $returnBoolean &&
                    $bool
                ) {
                    return $bool;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getWalletBalance($userID)
    {

        $user = User::find($userID);
        if (!empty($user)) {
            $creditTotal = $this->getReceivedTotalFromLedgerByUserIDAndStatus($userID, $this::TRANSACTION_STATE[0]);
            $debitTotal = $this->getSentTotalFromLedgerByUserIDAndStatus($userID, $this::TRANSACTION_STATE[0]);

            $total = $creditTotal[0]->total - $debitTotal[0]->total;
            return $total;
        } else {
            return 0.00;
        }
    }

    public function getConfiguration($configName)
    {
        $resultSet = Configuration::where('name', $configName)->first();

        if (!empty($resultSet)) {
            return $resultSet->value;
        } else {
            return "";
        }
    }
}
