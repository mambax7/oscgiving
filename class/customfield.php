<?php
// $Id: customfield.php,v 1.1.1.1 2006/03/12 14:57:25 root Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //

class  Customfield extends XoopsObject {
    var $db;
    var $table;

    function Customfield()
    {
        $this->db = &Database::getInstance();
        $this->table = $this->db->prefix("oscmembership_person_custom_master");
	$this->initVar('custom_Order',XOBJ_DTYPE_INT);
	$this->initVar('custom_Field',XOBJ_DTYPE_TXTBOX);
        $this->initVar('custom_Name', XOBJ_DTYPE_TXTBOX);
	$this->initVar('custom_Special', XOBJ_DTYPE_INT);
        $this->initVar('custom_Side', XOBJ_DTYPE_TXTBOX);
        $this->initVar('type_ID', XOBJ_DTYPE_INT);
    }

}    
    

class oscMembershipCustomfieldHandler extends XoopsObjectHandler
{

    function &create($isNew = true)
    {
        $customfield = new Customfield();
        if ($isNew) {
            $customfield->setNew();
        }
        return $customfield;
    }

    function &get($id)
    {
        $customfield =&$this->create(false);
        if (isset($id)) 
	{
		$sql = "SELECT * FROM " . $this->db->prefix("oscmembership_person_custom_master") . " WHERE custom_Field = '" . $id . "'";
		if (!$result = $this->db->query($sql)) 
		{
			//echo "<br />NewbbForumHandler::get error::" . $sql;
			return false;
		} 
		if($row = $this->db->fetchArray($result)) 
		{
			$customfield->assignVars($row);
		}

		
        }
        return $customfield;
    }
    
	function &update(&$customfield)
	{
		$sql = "UPDATE " . $customfield->table
		. " SET "
		. "custom_Order=" . ($customfield->getVar('custom_Order'))
		. ",custom_Name=" . 	
		$this->db->quoteString($customfield->getVar('custom_Name'))
		. ",custom_Special=" . 	
		$this->db->quoteString($customfield->getVar('custom_Special'))
		. ",custom_Side=" . 	
		$this->db->quoteString($customfield->getVar('custom_Side'))
		. ",type_ID=" . 	
		($customfield->getVar('type_ID'))
		.
		" where custom_Field=" . $this->db->quoteString($customfield->getVar('custom_Field'));
	
		if (!$result = $this->db->query($sql)) {
			echo "<br />oscmembershipHandler::get error::" . $sql;
			return false;
			}
	
	}

	     
	function &insert(&$customfield)
    	{

		//Find last custom field
		$fields = mysql_list_fields(XOOPS_DB_NAME, $this->db->prefix("oscmembership_person_custom"));
		$last = mysql_num_fields($fields) - 1;
		
		// Set the new field number based on the highest existing.  Chop off the "c" at the beginning of the old one's name.
		// The "c#" naming scheme is necessary because MySQL 3.23 doesn't allow numeric-only field (table column) names.
		
		$newFieldNum = substr(mysql_field_name($fields, $last), 1) + 1;

		$customfield->assignVar('custom_Field','c' . $newFieldNum);

		$sql = "INSERT into " . $customfield->table
		. "(custom_Order, custom_Field, custom_Name, custom_Special, custom_Side, type_ID) ";
	
		$sql = $sql . "values(" . ($customfield->getVar('custom_Order'))
		. "," . 
		$this->db->quoteString($customfield->getVar('custom_Field'))
		. "," . 
		$this->db->quoteString($customfield->getVar('custom_Name'))
		. "," . 
		$this->db->quoteString($customfield->getVar('custom_Special'))
		. "," . 
		$this->db->quoteString($customfield->getVar('custom_Side'))
		. "," .
		$this->db->quoteString($customfield->getVar('type_ID'))
		.  ")";

		if (!$result = $this->db->query($sql)) 
		{
			echo "<br />oscmembershipHandler::get error::" . $sql;
			return false;
		}
		else
		{
		
  // Insert into the custom fields table
  		$sql = "ALTER TABLE " . $this->db->prefix("oscmembership_person_custom") . " ADD `" . $customfield->getVar('custom_Field') . "` ";
  		switch($customfield->getVar('type_ID'))
  		{
  			case 1:$sql .= "ENUM('false', 'true')";
  				break;
  			case 2:$sql .= "DATE";
  				break;
  			case 3:$sql .= "VARCHAR(50)";
  				break;
  			case 4:$sql .= "VARCHAR(100)";
  				break;
  			case 5:$sql .= "TEXT";
  				break;
  			case 6:$sql .= "YEAR";
  				break;
  			case 7:$sql .= "ENUM('" . _oscmem_season_winter . "', '" . _oscmem_season_spring . "', '" . _oscmem_season_summer . "', '" . _oscmem_season_fall . "')";
  				break;
  			case 8:$sql .= "INT";
  				break;
  			case 9:$sql .= "MEDIUMINT(9)";
  				break;
  			case 10:$sql .= "DECIMAL(10,2)";
  				break;
  			case 11:$sql .= "VARCHAR(30)";
  				break;
  			case 12:$sql .= "TINYINT(4)";
  		}
  		$sql .= " DEFAULT NULL ;";
  		
  		if (!$result = $this->db->query($sql)) 
  		{
  			echo "<br />oscmembershipHandler::get error::" . $sql;
  			return false;
  		}
  		else
  		{
  			return $this->db->getInsertId();
  		}
		}
	}

	function &getcustomfieldtypes()
	//Search on criteria and return result
	{
		$result='';
		
		$sql = "SELECT * FROM " . $this->db->prefix("oscmembership_list"). " WHERE id=4";
	
		$return_array=array();
		
		if (!$result = $this->db->query($sql)) 
		{
			//echo "<br />NewbbForumHandler::get error::" . $sql;
			return false;
		}
		
		return $result;
			
	}
	
	
	
}


?>