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
     * EtapestryAPI login details and intial endpoint.
     */
	private $loginId;
	private $password;
	private $endpoint;
	
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
	 */
	public function createNuSOAPClient () 
	{
		// Instantiate nusoap_client
		$this->nsc = new nusoap_client($this->endpoint, true);

		// Did an error occur?
		$this->hasFaultOrError($this->nsc);	
	}
	
	/**
	 * Instantiate a nusoap_client instance and call login method
	 */
	public function login()
	{
		// Invoke login method
		$result = $this->nusoapCall("login", array($this->loginId, $this->password));

		// Determine if the login method returned a value...this will occur
		// when the database you are trying to access is located at a different
		// environment that can only be accessed using the provided endpoint
		if ($result != "")
		{
			$this->endPoint = $result;
			$this->createNuSOAPClient();

			// Invoke login method
			$result = $this->nsc->nusoapCall("login", array($loginId, $password));
		}
		
		return $result;
	}
	
	/**
	 * Stops an eTapestry API session by calling the logout
	 * method given a nusoap_client instance.
	 */
	public function logout()
	{
		// Invoke logout method
		$result = $this->nsc->nusoapCall("logout");
		
		return $result;
	}

	/**
	 * Method to determine if a NuSoap fault or error occurred.
	 * If so, output any relevant info and stop the code from executing.
	 * 
	 * @param object $nsc NuSoap client
	 */
	public function hasFaultOrError($nsc)
	{	
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

			    throw new EtapestryAPIException($message, $code);
			}
		}
		catch (EtapestryAPIException $e)
		{
			echo $e->getMessage();
			
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
	private function nusoapCall ($operation, $params = array()) 
	{
		$result = $this->nsc->call($operation,$params);
		if ($this->hasFaultOrError($this->nsc)) {
			return false;
		}
		
		return $result;
	}
		
}