<?php

include("../../mainfile.php");
include_once XOOPS_ROOT_PATH.'/header.php';

//include(XOOPS_ROOT_PATH."/header.php");
// language files

$language = $xoopsConfig['language'] ;

$xoopsOption['template_main'] = 'simple.html';

//redirect
if (!$xoopsUser)
{
    redirect_header(XOOPS_URL."/user.php", 3, _AD_NORIGHT);
}


//verify permission
if ( !is_object($xoopsUser) || !is_object($xoopsModule) || !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
    exit(_oscmem_access_denied);
}


$xoopsTpl->assign("test","hi");

include_once XOOPS_ROOT_PATH."/footer.php";


?>