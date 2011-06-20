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

    //Test to make sure setters and getters work
    public function testSetAccount()
    {
       $account = new EtapestryAccount();
       
       $account->setAccount('email','example@example.com');
       $account->setAccount('name','FirstName LastName');
       $this->assertEquals("example@example.com", $account->getField("email"));
       $this->assertEquals("FirstName LastName", $account->getField("name"));
       
    }
    
    //Test to make sure no duplicate is found
    public function testDuplicateNotFound()
    {
       $account = new EtapestryAccount();
       $account->login();
       $account->setAccount('name','FirstName LastName');
       $account->setAccount('email','example@example.com');
       $account->setAccount('allowEmailOnlyMatch',FALSE);
       $response = $account->getDuplicateAccount();
       $this->assertEquals(NULL,$response);
       $account->logout();
    }
    
    public function testAddAccount()
    {
       $account = new EtapestryAccount();
       $account->login();
       $account->setAccount('accountRoleType', '0');
       $account->setAccount('personaType','Primary Contact');
       $account->setAccount('primaryPersona', '1');
       
       $account->setAccount('firstName','Test');
       $account->setAccount('lastName','Test');
       $account->setAccount('longSalutation', 'Mr. Test');
       $account->setAccount('shortSalutation', 'Test');
       
       $phones = array();
       $phones[] = array("number" => "123-123-1234", "type" => "Voice");
       $account->setAccount('phones', $phones);
       
       $account->setAccount('email','test@example.com');
       
       $account->setAccount('address', '123 Fake Street');
       $account->setAccount('city', 'Mycity');
       $account->setAccount('state', 'NY');
       $account->setAccount('postalCode', '12345');
       $account->setAccount('country', 'US');
       $account->setAccount('county', '');
       
       $account->setAccount('note', '__test_note__');
        
       $refid = $account->addAccount();
       $this->assertNotNull($refid);
       
       // Now apply defined values to newly created account
       $definedValues = array();
       $definedValues[] = array("fieldName" => "Record Type", "value" => "Individual");
       $account->applyDefinedValues($refid, $definedValues);
       
       $account->logout();
    }
    
}