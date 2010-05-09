<?php
// $Id: fund.php,v 1.0 2006/12/29 root Exp $
// *  http://osc.sourceforge.net
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

class  Fund extends XoopsObject {
    var $db;
    var $table;

    function Fund()
    {
        $this->db = &Database::getInstance();
        $this->table = $this->db->prefix('oscgiving_donationfunds');
	$this->initVar('fund_id',XOBJ_DTYPE_INT);
	$this->initVar('fund_Active',XOBJ_DTYPE_INT);
	$this->initVar('fund_Name',XOBJ_DTYPE_TXTBOX);
	$this->initVar('fund_Description',XOBJ_DTYPE_TXTBOX);
	$this->initVar('dna_Amount',XOBJ_DTYPE_TXTBOX);
    }

}    
    

class oscGivingFundHandler extends XoopsObjectHandler
{

    function &create($isNew = true)
    {
        $fund = new Fund();
        if ($isNew) {
            $fund->setNew();
        }
        return $fund;
    }

    function &getFundlist()
    {
    	$funds=array();
    	
	$fund =&$this->create(false);

	$sSQL = "SELECT DISTINCT * FROM " . $this->db->prefix("oscgiving_donationfunds");
	$result = $this->db->query($sSQL);

	$i=0;	
	while ($row = $this->db->fetchArray($result))
	{

		$fund =&$this->create(false);
		$fund->assignVars($row);
//		echo $fund->getVar('fund_Name');
		$funds[$i]=$fund;
		
		$i++;
		
	}

	return $funds;
    }

    function &getDonationsbydatebyfund($thisdate)
    {
	$sql="select da.dna_fun_ID, df.fund_Active, df.fund_Name, df.fund_Description, da.dna_Amount  from " . $this->db->prefix("oscgiving_donation") . " d join " . $this->db->prefix("oscgiving_donationamounts") . " da
on d.don_id = da.don_id join " . $this->db->prefix("oscgiving_donationfunds") . " df on da.dna_fun_ID = df.fund_id 
where d.don_Date=" . $this->db->quoteString($thisdate) . " group by dna_fun_ID";

	$funds=array();
	$fund=$this->create(False);

	$result=$this->db->query($sql);
	$i=0;	
	while ($row = $this->db->fetchArray($result))
	{
		$fund =&$this->create(false);
		$fund->assignVars($row);
		$funds[$i]=$fund;
		$i++;
	}

	return $funds;
    }

    function &getFund($id)
    {
	$fund =&$this->create(false);

	$sSQL = "SELECT DISTINCT * FROM " . $this->db->prefix("oscgiving_donationfunds") . " where fund_id=" . $id;
	$result = $this->db->query($sSQL);

	$i=0;	
	while ($row = $this->db->fetchArray($result))
	{

		$fund =&$this->create(false);
		$fund->assignVars($row);
	}

	return $fund;
    }

	function &update(&$fund)
    	{
		$sql = "UPDATE " . $fund->table
		. " SET "		
		. "fund_Name=" . $this->db->quoteString($fund->getVar('fund_Name')) . " where fund_id=" . $fund->getVar('fund_id');

		if (!$result = $this->db->query($sql)) {
			return false;
			}
			else { return true; }
	
	}

	function &insert(&$fund)
    	{
		$sql = "INSERT into " . $fund->table
		. "(fund_Active, fund_Name, fund_Description) ";
	
		$sql = $sql . "values(" . $this->db->quoteString($fund->getVar('fund_Active'))
		. "," . 
		$this->db->quoteString($fund->getVar('fund_Name'))
		. "," .
		$this->db->quoteString($fund->getVar('fund_Description')) . ")";
		
		if (!$result = $this->db->query($sql)) 
		{
			return false;
			}
			else
			{
			return  $this->db->getInsertId();
			}
	
	
	}

}

?>