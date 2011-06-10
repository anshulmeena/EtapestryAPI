<?php
/**
 * Configuration for Etapestry API Tests
 */

/**
 * Enter your eTapestry API account credentials to run tests
 */
define('ETAPESTRYAPI_LOGIN_ID',"");
define("ETAPESTRYAPI_PASSWORD", "");
define("ETAPESTRYAPI_ENDPOINT", "");

require_once dirname(dirname(__FILE__)) . '/EtapestryAPI.php';
require_once 'PHPUnit/Framework.php';

if (ETAPESTRYAPI_LOGIN_ID == "") {
    die('Enter your eTapestry API account credentials in '.__FILE__.' before running the test suite.');
}

?>