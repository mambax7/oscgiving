<?php
// ------------------------------------------------------------------------- //
if (!defined('XOOPS_ROOT_PATH')) {
	die("XOOPS root path not defined");
}
class OscgivRadio extends XoopsFormRadio {

	/**
	 * Prepare HTML for output and break <br> on options
	 * 
	 * @return	string	HTML
	 */
	
	function renderbreak(){
		$ret = "";
		foreach ( $this->getOptions() as $value => $name ) {
			$ret .= "<input type='radio' name='".$this->getName()."' value='".$value."'";
			$selected = $this->getValue();
			if ( isset($selected) && ($value == $selected) ) {
				$ret .= " checked='checked'";
			}
			$ret .= $this->getExtra()." />".$name."\n<br>";
		}
		return $ret;
	}

}
?>