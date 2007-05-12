<?php
$modversion['name'] = _oscgiv_MOD_NAME;
$modversion['version'] = "3.01";
$modversion['description'] = _oscgiv_MOD_DESC;
$modversion['credits'] = "Open Source Church Project - http://sourceforge.net/osc";
$modversion['author'] = "Steve McAtee";
$modversion['help'] = "help.html";
$modversion['license'] = "GPL see LICENSE";
$modversion['official'] = "3.01";
$modversion['image'] = "images/module_logo.png";
$modversion['dirname'] = "oscgiving";
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
$modversion['tables'][0] = "oscgiving_donationamounts";
$modversion['tables'][1] = "oscgiving_donationfunds";
$modversion['tables'][2] = "oscgiving_donations";
$modversion['tables'][3] = "oscgiving_settings";

// Templates
$modversion['templates'][0]['file'] = 'donationenvelope.html';
$modversion['templates'][0]['description'] = 'Donation Envelopes';
$modversion['templates'][1]['file'] = 'donationeditor.html';
$modversion['templates'][1]['description'] = 'Donation Editor';
$modversion['templates'][2]['file'] = 'donationreports.html';
$modversion['templates'][2]['description'] = 'Donation Reports Menu';
$modversion['templates'][3]['file'] = 'oscgivreports.html';
$modversion['templates'][3]['description'] = 'Donation Reports';
$modversion['templates'][4]['file'] = 'fundlist.html';
$modversion['templates'][4]['description'] = 'Funds';


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

?>