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
		parent::_construct($loginId, $password, $endpoint);
	}
	
	
	/**
	* Account Set Method's
	*
	* @param array $field
	* @param string $value
	* @return false
	*/
	public function setAccount($field, $value)
	{
		$account[$field] = $value;
	}
	
	public function addAccount()
	{
		$response = parent::nusoapCall("addAccount", array($this->account, false));
	}

}
