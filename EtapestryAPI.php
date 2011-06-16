<?php
/**
 * eTapestry PHP API, include this file in your project.
 * 
 * @package EtapestryAPI
 * @author Danny Bouman at Howard County Library System <dannybb@gmail.com>
 * @author Anshul Meena at Howard County Library System <anshulmeena1@gmail.com>
 */
require dirname(__FILE__) . '/lib/nusoap.php';
require dirname(__FILE__) . '/lib/utils.php';
require dirname(__FILE__) . '/lib/EtapestryAccount.php';
require dirname(__FILE__) . '/lib/EtapestryJournal.php';

/**
 * Exception class for eTapestry PHP API.
 *
 * @package EtapestryAPI
 */
class EtapestryAPIException extends Exception
{
}

class EtapestryAPI
{
	/**
     * NuSOAP client object
     */
	public $nsc;
	
	/**
	 * Number of times to retry if connection to eTapestry fails
	 */
	private $retryLimit = 5;
	
	/**
     * EtapestryAPI login details and intial endpoint.
     */
	private $loginId;
	private $password;
	private $endpoint;
	
	private $error;
	
	
	/**
	 * Constructor
	 * 
	 * @param string $loginId Login ID
	 * @param string $password Password
	 * @param string $endpoint URL of eTapestry Service
	 */
	public function __construct($loginId = false, $password = false, $endpoint = false)
	{
		$this->loginId = ($loginId ? $loginId : (defined('ETAPESTRYAPI_LOGIN_ID') ? ETAPESTRYAPI_LOGIN_ID : ""));
		$this->password = ($password ? $password : (defined('ETAPESTRYAPI_PASSWORD') ? ETAPESTRYAPI_PASSWORD : ""));
		$this->endpoint = ($endpoint ? $endpoint : (defined('ETAPESTRYAPI_ENDPOINT') ? ETAPESTRYAPI_ENDPOINT : "https://sna.etapestry.com/v2messaging/service?WSDL"));
		
		$this->createNuSOAPClient();
	}
	
	/**
	 * Instantiate NuSOAP client at specified endpoint
	 * 
	 * @param string $endpoint
	 * @return boolean true if no fault or error occurred
	 */
	public function createNuSOAPClient ($retry = 0) 
	{
		// Instantiate nusoap_client
		$this->nsc = new nusoap_client($this->endpoint, true);

		// Did an error occur?
		if ($this->hasFaultOrError($this->nsc)) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * Instantiate a nusoap_client instance and call login method
	 */
	public function login()
	{
		// Invoke login method
		$response = $this->nusoapCall("login", array($this->loginId, $this->password));

		// Determine if the login method returned a value...this will occur
		// when the database you are trying to access is located at a different
		// environment that can only be accessed using the provided endpoint
		if ($response != "")
		{
			$this->endPoint = $response;
			$this->createNuSOAPClient();

			// Invoke login method
			$response = $this->nusoapCall("login", array($loginId, $password));
		}
		
		return $response;
	}
	
	/**
	 * Stops an eTapestry API session by calling the logout
	 * method given a nusoap_client instance.
	 */
	public function logout()
	{
		// Invoke logout method
		$response = $this->nusoapCall("logout");
		
		return $response;
	}

	/**
	 * Method to determine if a NuSoap fault or error occurred.
	 * If so, output any relevant info and stop the code from executing.
	 * 
	 * @param object $nsc NuSoap client
	 */
	public function hasFaultOrError($nsc, $showErrors = TRUE)
	{	
		$this->error = NULL;	
	
		try 
		{
			if ($nsc->fault || $nsc->getError())
			{
			    if (!$nsc->fault)
			    {
			      $message = $nsc->getError();
			    }
			    else
			    {
			      $code = $nsc->faultcode;
			      $message = $nsc->faultstring;
			    }
				
			    throw new EtapestryAPIException($message);
			}
		}
		catch (EtapestryAPIException $e)
		{
			$this->error = $e->getMessage();
			if ($showErrors) {
				echo $this->error."\n";
			}
			
			return true;
		}
		
		return false;
	}

	/**
	 * Performs nusoap_client call and checks for faults/errors
	 *
	 * @param string $operation
	 * @param array $params
	 */
	public function nusoapCall ($operation, $params = array()) 
	{
		$response = $this->nsc->call($operation,$params);
		
		if ($this->hasFaultOrError($this->nsc, FALSE) && stristr($this->error, "wsdl error")) {
			// Retry a call if it has failed to reach api service
			$response = $this->retryCall($operation, $params);
			if ($response === FALSE) {
				echo $this->error;
				return false;
			}
		}
		else if ($this->hasFaultOrError($this->nsc)) {
			return false;
		}
		
		return $response;
	}
	
	/**
	 * Retry a call until retry limit is reached
	 *
	 * @param string $operation
	 * @param array $params
	 */
	private function retryCall ($operation, $params = array()) 
	{	
		$response = false;
		
		for ($try=0; $try<$this->retryLimit; $try++) {
			sleep(5);
			$this->createNuSOAPClient();
			$response = $this->nsc->call($operation,$params);
			if (!$this->hasFaultOrError($this->nsc, FALSE)) {
				break;
			}
		}
		
		return $response;
	}
	
}