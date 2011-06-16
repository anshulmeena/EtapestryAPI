<?php

require_once 'EtapestryAPI_Test_Config.php';

class EtapestryAPI_Test extends PHPUnit_Framework_TestCase
{
    
    public function testLogin()
    {
       $account = new EtapestryAccount();
       $this->assertTrue($account->login() !== FALSE);
       $account->logout();
    }

    //test setAccount
    public function testsetAccount()
    {
       $account = new EtapestryAccount();
       
       $account->setAccount('email','test.test@test.com');
       $account->setAccount('name','firstname lastname');
       $this->assertEquals("test.test@test.com", $account->getField("email"));
       $this->assertEquals("firstname lastname", $account->getField("name"));
       
    }
    //testDuplicate account
    public function testDuplicate()
    {
       $account = new EtapestryAccount();
       $account->login();
       $account->setAccount('email','test@test.com');
       $account->setAccount('allowEmailOnlyMatch',1);
       $response = $account->getDuplicateAccount();
       $this->assertTrue($response != NULL);
       $account->logout();
    }
   
}