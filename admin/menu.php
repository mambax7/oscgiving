<?php
$admini=0;

$adminmenu[$admini]['title'] = _oscmem_admin_osclist_familyroles;
$adminmenu[$admini]['link'] = "admin/osclistselect_smarty.php?id=2";

$admini++;
$adminmenu[$admini]['title'] = _oscmem_admin_osclist_memberclassification;
$adminmenu[$admini]['link'] = "admin/osclistselect_smarty.php?id=1";

$admini++;
$adminmenu[$admini]['title'] = _oscmem_admin_customfield;
$adminmenu[$admini]['link'] = "admin/customfieldselectform.php";

$admini++;
$adminmenu[$admini]['title'] = _oscmem_admin_osclist_grouptype;
$adminmenu[$admini]['link'] = "admin/osclistselect_smarty.php?id=3";

$admini++;
$adminmenu[$admini]['title'] = _oscmem_admin_osclist_permissions;
$adminmenu[$admini]['link'] = "admin/perm.php?id=4";

?>