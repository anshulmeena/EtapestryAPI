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
    
}