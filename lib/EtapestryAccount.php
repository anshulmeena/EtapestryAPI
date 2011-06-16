<?php
/**
* EtapestryAccount class for eTapestryAPI
* 
* @package EtapestryAPI
*/

class EtapestryAccount extends EtapestryAPI
{
	private $account = array();
	
	/**
	 * Run parent constructor
	 * 
	 * @param string $loginId Login ID
	 * @param string $password Password
	 * @param string $endpoint URL of eTapestry Service
	 */
	public function __construct($loginId = false, $password = false, $endpoint = false)
	{
		parent::__construct($loginId, $password, $endpoint);
	}
	
	
	/**
	* Account Set Method's
	*
	* @param array $field
	* @param string $value
	* @return false
	* @access public
	*/
	public function setAccount($field, $value)
	{
		$this->account[$field] = $value;
	}
	
	/**
	* Account Get Method's
	*
	* @return array
	* @access public
	*/
	public function getAccount()
	{
		return $this->account;
	}

	/**
	* Account Get Method Field 
	*
	* @param field
	* @return string
	* @access public
	*/
	public function getField($field)
	{
		return $this->account[$field];
	}
	
	/**
	* checking for duplicate account (can be multiple) ??
	*
	* @param array $array
	* @return array for multiple accounts.
	* @access public
	*/
	public function getDuplicateAccount()
	{	// Invoke getDuplicateAccounts method
		$response = parent::nusoapCall("getDuplicateAccount", array($this->account));
		return $response;
	}	
	
	public function addAccount()
	{
		$response = parent::nusoapCall("addAccount", array($this->account, false));
	}

}
