<?php

namespace App;

interface CredPal_Interface
{

  public function getWalletBalance($userID);
  public function transfer($transactionArray);
  public function getConfiguration($configName);
  public function sendMobileValidationSMS($user, $code);
  public function mobileValidation($user, $code);
  public function validateReferrerID($referrerID);
  public function userRegistration($userArray);
}
