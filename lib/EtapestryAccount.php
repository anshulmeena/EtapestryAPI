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
