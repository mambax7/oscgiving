<?php
include("../../mainfile.php");
$xoopsOption['template_main'] = 'cs_index.html';

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _AD_NORIGHT);
}



//verify permission
if ( !is_object($xoopsUser) || !is_object($xoopsModule) || !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
    exit(_oscmem_access_denied);
}

include XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar('dirname') . "/include/functions.php";

if(!hasPerm("oscmembership_view",$xoopsUser)) exit(_oscmem_access_denied);


include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/class/person.php';

include(XOOPS_ROOT_PATH."/header.php");

$person_handler = &xoops_getmodulehandler('person', 'oscmembership');
if (isset($_POST['id'])) $id=$_POST['id'];
if (isset($_GET['id'])) $id=$_GET['id']; 
if(isset($_POST['type'])) $type=$_POST['type'];
if(isset($_GET['type'])) $type=$_GET['type'];


//Check to see if items returned were selected
if(isset($_POST['topid']))
{
	$topid=$_POST['topid'];
	for($i=0;$i<$topid+1;$i++)
	{
		//add selected to family
		if($_POST[$i]>0){
		switch(true)
		{
			case($type=="family"):
			$person_handler->addToFamily($_POST[$i],$id);
			case($type=="group"):
			$person_handler->addToGroup($_POST[$i],$id);
		}
		}
	}
	
	switch(true)
	{
		case($type=="family"):
			redirect_header("familydetailform.php? id=" . $id,3,_oscmem_UPDATESUCCESS_member);
		case($type=="group"):
			redirect_header("groupdetailform.php? id=" . $id,3,_oscmem_UPDATESUCCESS_member_grooup);
		break;
	}
}

$searcharray=array();
$searcharray[0]='';
$result = $person_handler->searchmembers($searcharray,true);

$form = new XoopsThemeForm(_oscmem_persondetail_TITLE, "personselectform", "personselect.php", "post", true);

$id_hidden = new XoopsFormHidden("id",$id);
$type_hidden = new XoopsFormHidden("type",$type);

$form->addElement($id_hidden);
$form->addElement($type_hidden);

$submit_button = new XoopsFormButton("", "personselectsubmit", _osc_select, "submit");

//echo "<h2 class=comTitle>" . _oscmem_personselect . "</h2>";

$inner_table="<table class='outer'  >";
$inner_table = $inner_table . "<tr><th>&nbsp;</th><th>" . _oscmem_name . "</th><th>" . _oscmem_address . "</th>";
$inner_table = $inner_table . "<th>" . _oscmem_city . "," . _oscmem_state . "</th>";
$inner_table = $inner_table . "<th>" . _oscmem_email . "</th>";
$inner_table = $inner_table . "<th>&nbsp;</th>";
$inner_table = $inner_table . "</tr>";

$db = &Database::getInstance();

$person=new Person();

$memberresult='';
$rowcount=0;
while($row = $db->fetchArray($result)) 
{
	$rowcount++;
	$person->assignVars($row);
	$memberresult = $memberresult . "<tr>";
	$memberresult=$memberresult . "<td><input type=checkbox name=" . $rowcount . " value=" . $person->getVar('id') . ">";
	$memberresult=$memberresult . "<td>";
	$memberresult=$memberresult . $person->getVar('lastname') . ", " . $person->getVar('firstname') . "</td>";
	$memberresult=$memberresult . "<td>" . $person->getVar('address1') . "</td>";
	$memberresult=$memberresult . "<td>" . $person->getVar('city') . ", " . $person->getVar('state') . "</td>";
	$memberresult=$memberresult . "<td>" . $person->getVar('email') . "</td>";
	$memberresult = $memberresult . "</tr>";

}

$topid_hidden = new XoopsFormHidden("topid",$rowcount);

$memberresult = $memberresult . "</table>";

$table_label = new XoopsFormLabel('', $inner_table . $memberresult);
$form->addElement($topid_hidden);
$form->addElement($table_label);
$form->addElement($submit_button);
$form->display();

include(XOOPS_ROOT_PATH."/footer.php");
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
echo "module/index.php";


?>