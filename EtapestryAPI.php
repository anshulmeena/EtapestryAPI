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
     * EtapestryAPI login details and intial endpoint.
     */
	private $loginId;
	private $password;
	private $endPoint;
	
	/**
     *  nusoap_client
     */
	public $nsc;
	
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
		$this->endPoint = ($endPoint ? $endPoint : (defined('ETAPESTRYAPI_ENDPOINT') ? ETAPESTRYAPI_ENDPOINT : "https://sna.etapestry.com/v2messaging/service?WSDL"));
	}

	/**
	 * Method to determine if a NuSoap fault or error occurred.
	 * If so, output any relevant info and stop the code from executing.
	 * 
	 * @param object $nsc NuSoap client
	 */
	public function checkFaultOrError($nsc)
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
		 	echo $e->errorMessage();
		 	exit;
		 }
	}
		
}