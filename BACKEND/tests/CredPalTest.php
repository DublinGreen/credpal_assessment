<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class CredPalTest extends TestCase
{

    public function testRootEndPoint()
    {
        $this->get('/');

        $this->assertEquals(
            $this->app->version(),
            $this->response->getContent()
        );
    }


    public function testLogin()
    {
        $response = $this->call('POST', '/auth/login');

        $this->assertEquals(422, $response->status());
    }

    public function testCreateUser()
    {
        $response = $this->call('POST', '/auth/createUser');

        $this->assertEquals(200, $response->status());
    }

    public function testCreateUserReturnJson()
    {
        $this->post(
            '/auth/createUser'
        )
            ->seeJson([
                'message' => 'Validation errors',
                "status" => false,
            ]);
    }

    public function testGetIDByReferralCodes()
    {

        $response = $this->call('GET', 'auth/getIDByReferralCodes/');

        $this->assertEquals(405, $response->status());
    }

    public function testGetAccountByAccountNumber()
    {
        $response = $this->call('POST', '/apiv1/getAccountByAccountNumber/2139686956');
        $this->assertEquals(405, $response->status());
    }

    public function testtransferFunds()
    {
        $response = $this->call('POST', '/apiv1/transferFunds/');
        $this->assertEquals(401, $response->status());
    }

    public function testgetAccountNumberByUserID()
    {
        $response = $this->call('POST', '/apiv1/getAccountNumberByUserID/');
        $this->assertEquals(401, $response->status());
    }

    public function testupdateUser()
    {
        $response = $this->call('POST', '/apiv1/updateUser/');
        $this->assertEquals(404, $response->status());
    }



    public function testgetAccountNumberByUserIDReturnJson()
    {
        $this->post(
            '/apiv1/getAccountNumberByUserID/'
        )
            ->seeJson([
                "error" => "Token not provided.",
            ]);
    }

    public function testtransferFundsJson()
    {
        $this->post(
            '/apiv1/transferFunds/'
        )
            ->seeJson([
                "error" => "Token not provided.",
            ]);
    }
}
