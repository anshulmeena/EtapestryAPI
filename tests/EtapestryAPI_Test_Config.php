<?php
/**
 * Configuration for Etapestry API Tests
 */

/**
 * Enter your eTapestry API account credentials to run tests
 */
define('ETAPESTRYAPI_LOGIN_ID',"");
define("ETAPESTRYAPI_PASSWORD", "");
//Uncomment the line below if you wish to specify an endpoint
//define("ETAPESTRYAPI_ENDPOINT", "");

require_once dirname(dirname(__FILE__)) . '/EtapestryAPI.php';
require_once 'PHPUnit/Autoload.php';

if (ETAPESTRYAPI_LOGIN_ID == "") {
    die('Enter your eTapestry API account credentials in '.__FILE__.' before running the test suite.');
}
?>