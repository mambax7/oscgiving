<?php
$modversion['name'] = _oscgiv_MOD_NAME;
$modversion['version'] = "1.0";
$modversion['description'] = _oscgiv_MOD_DESC;
$modversion['credits'] = "Open Source Church Project - http://sourceforge.net/osc";
$modversion['author'] = "Steve McAtee";
$modversion['help'] = "help.html";
$modversion['license'] = "GPL see LICENSE";
$modversion['official'] = 1;
$modversion['image'] = "images/module_logo.png";
$modversion['dirname'] = "oscgiving";
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
$modversion['tables'][0] = "oscgiving_donationamounts";
$modversion['tables'][1] = "oscgiving_donationfunds";
$modversion['tables'][2] = "oscgiving_donations";

// Templates
$modversion['templates'][0]['file'] = 'donationenvelope.html';
$modversion['templates'][0]['description'] = 'Donation Envelopes';

$modversion['blocks'][1]['file'] = "oscgivnav.php";
$modversion['blocks'][1]['name'] = 'OSC Giving Navigation';
$modversion['blocks'][1]['description'] = "OSC Giving Menu";
$modversion['blocks'][1]['show_func'] = "oscgivnav_show";
$modversion['hasSearch'] = 0;
//$modversion['search']['file']="include/search.inc.php";
//$modversion['search']['func']="oscmem_search";
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";
$modversion['hasMain'] = 1;
//$modversion['templates'][1]['file'] = 'cs_index.html';
//$modversion['templates'][1]['description'] = 'cs main template file';
$modversion['hasComments'] = 1;
$modversion['comments']['pageName'] = 'index.php';
$modversion['comments']['itemName'] = 'id';

/*
$i = 1;
$modversion['sub'][$i]['name'] = _oscmembership_viewperson;
$modversion['sub'][$i]['url'] = "index.php";
$i++;
$modversion['sub'][$i]['name'] = _oscmembership_addperson;
$modversion['sub'][$i]['url'] = "persondetailform.php?action=create";
$i++;
$modversion['sub'][$i]['name'] = _oscmembership_addfamily;
$modversion['sub'][$i]['url'] = "familydetailform.php?action=create";
$i++;
$modversion['sub'][$i]['name'] = _oscmembership_viewfamily;
$modversion['sub'][$i]['url'] = "familylistform.php";
$i++;
$modversion['sub'][$i]['name'] = _oscmembership_viewgroup;
$modversion['sub'][$i]['url'] = "groupselect.php";
$i++;
$modversion['sub'][$i]['name'] = _oscmem_addgroup;
$modversion['sub'][$i]['url'] = "groupdetailform.php?action=create";
$i++;
$modversion['sub'][$i]['name'] = _oscmem_customfield;
$modversion['sub'][$i]['url'] = "customfieldselectform.php";
$i++;
$modversion['sub'][$i]['name'] = _oscmem_osclist_famrole_TITLE;
$modversion['sub'][$i]['url'] = "admin/osclistselect_smarty.php?id=4";
$i++;
$modversion['sub'][$i]['name'] = _oscmem_view_cart;
$modversion['sub'][$i]['url'] = "viewcart.php";
*/
?>