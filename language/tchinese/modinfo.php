<?php
// Module 
//
define("_oscmem_MOD_NAME","OSC Membership Module");
define("_oscmem_MOD_DESC","Xoops OSC Module for Membership.");
define("_oscmem_ADMENU0","OSC Membership Administation");
define("_oscmem_personselect","Person Select");
//Field names
//define("_oscdir_userfile","Church Picture");

define("_oscmem_name","會員姓名");
define("_oscmem_lastname", "姓氏");
define("_oscmem_firstname", "名字");
define("_oscmem_phone","電話號碼");
define("_oscmem_address","地址");
define("_oscmem_city","城市");
define("_oscmem_state","州");
define("_oscmem_post","郵遞區號");
define("_oscmem_email","電子郵件");
define("_oscmem_country","國家");
define("_oscmem_title","Member Directory");
define("_oscmem_birthday","出生日期");
define("_oscmem_month","月");
define("_oscmem_year","年");
define("_oscmem_birthfrom","自");
define("_oscmem_birthto","至");
define("_oscmem_gender","性別");
define("_oscmem_membershipdate","加入日期");
define("_oscmem_memberclass","會員類別");
define("_oscmem_submit","送出");
define("_osc_save","儲存");
define("_osc_select","選擇");
define("_osc_create","建立");
define("_oscmem_actions","動作");
define("_osc_addmember","新增家庭成員");
define("_oscmem_addmember","新增會員");
define("_oscmem_addingmember","&#37325;&#26032;&#23566;&#21521;&#33267;&#26032;&#22686;&#26371;&#21729;&#38913;&#38754;");

define("_oscmem_homephone","家中電話");
define("_oscmem_workphone","公司電話");
define("_oscmem_cellphone","行動電話");

define("_oscmem_groupname", "名稱");
define("_oscmem_groupdescription","簡述");
define("_oscmem_grouptype","類型");

define("_oscmem_workemail","公司電子郵件");

define("_oscmem_male","男性");
define("_oscmem_female","女性");

define("_oscmem_datelastedited","修改日期");
define("_oscmem_editedby","修改者");
define("_oscmem_dateentered","建立日期");
define("_oscmem_enteredby","建立者");
define("_oscmem_birthdayinstructions","&nbsp;&nbsp;格式 (MM月/DD日/西元YYYY年) 例如 01/31/1970");
define("_oscmem_weddingdate","結婚日期");

define("_oscmem_familyname","家庭名稱");

define("_oscmem_persondetail_TITLE","個人資料");
define("_oscmem_familydetail_TITLE","家庭資料");
define("_oscmem_person_list","Person List");
define("_oscmem_family_list","家庭列表");
define("_oscmem_familymember","家庭成員");
define("_oscmem_groupselect_TITLE","小組列表");
define("_oscmem_groupdetail_TITLE","小組資料");
//Messages
define('_oscmem_UPDATESUCCESS','The OSC Membership database has been updated successfully');
define('_oscmem_CREATESUCCESS_individual','&#27492;&#20491;&#20154;&#36039;&#26009;&#24050;&#34987;&#26032;&#22686;&#33267;OSC&#36039;&#26009;&#24235;');

define("_oscmem_CREATESUCCESS_family","&#27492;&#23478;&#24237;&#36039;&#26009;&#24050;&#34987;&#26032;&#22686;&#33267;OSC&#36039;&#26009;&#24235;");
define("_oscmem_addfamily_redirect","&#37325;&#26032;&#23566;&#21521;&#33267;&#26032;&#22686;&#23478;&#24237;&#38913;&#38754;");

define("_oscmem_UPDATESUCCESS_member","&#36984;&#21462;&#26371;&#21729;&#24050;&#34987;&#21152;&#20837;&#35442;&#23478;&#24237;");
define("_oscmem_REMOVEMEMBERSUCCESS","&#35442;&#26371;&#21729;&#24050;&#24478;&#27492;&#23478;&#24237;&#20013;&#31227;&#38500;");
define("_oscmem_nomembers","沒有任何成員");
define("_oscmem_nogroups","目前沒有任何小組，要新增請按<a href=>此處</a>。");

define("_oscmem_persondetail","Members");

//Groups
define("_oscmem_groupmember","小組成員");
define("_osc_addgroupmember","加入小組成員");
define("_oscmem_UPDATESUCCESS_member_grooup","&#26371;&#21729;&#24050;&#34987;&#21152;&#20837;&#23567;&#32068;");
define("_oscmem_REMOVEGROUPMEMBERSUCCESS","&#26371;&#21729;&#24050;&#24478;&#23567;&#32068;&#31227;&#38500;");

//Menu
define("_oscmembership_viewperson","會員列表");
define("_oscmembership_addperson", "新增會員");
define("_oscmembership_addfamily","新增家庭");
define("_oscmembership_viewfamily","家庭列表");
define("_oscmem_remove_member","移除成員");
define("_oscmem_edit_member","編輯成員");
define("_oscmem_edit","編輯");
define("_oscmembership_viewgroup","小組列表");
define("_oscmem_addgroup","新增小組");
define("_oscmem_addgroup_redirect","&#37325;&#26032;&#23566;&#21521;&#33267;&#26032;&#22686;&#23567;&#32068;&#38913;&#38754;");


define("_oscmem_familyrole","家中角色");

define("_oscmem_unassigned","未指定");

define("_oscmem_optionname","Option Name");
define("_oscmem_optionsequence","Sequence");
define("_oscmem_osclist_famrole_TITLE","Family Roles");

//define("_oscmem_osclist_TITLE_familyroles","Family Roles");
//define("_oscmem_osclist_TITLE_memberclassifications","Member Classifications");
//define("_oscmem_osclist_TITLE_grouptypes","Group Types");


define("_oscmem_noclass","No Classification");


define("_oscmem_filter","Filter");
define("_oscmem_applyfilter","套用篩選");
define("_oscmem_clearfilter","移除篩選");
define("_oscmem_addtocart","加入工作車");
define("_oscmem_remove","移除");
define("_oscmem_emptycart","清空工作車");
define("_oscmem_emptycarttogroup","清空工作車並到小組列表");
define("_oscmem_emptycarttofamily","清空工作車並到家庭列表");
define("_oscmem_generatelabels","製作標籤");
define("_oscmem_addedtocart","&#26371;&#21729;&#24050;&#34987;&#21152;&#20837;&#24037;&#20316;&#36554;");
define("_oscmem_intersectcart","Intersect Cart");
define("_oscmem_removefromcart","自工作車移除");

define("_oscmem_msg_removedfromcart","&#36984;&#25799;&#30340;&#26371;&#21729;&#24050;&#24478;&#24037;&#20316;&#36554;&#20013;&#31227;&#38500;");
define("_oscmem_msg_intersectedcart","Selected Individuals have been successfully intersected with the cart");

define("_oscmem_view_cart","工作車");
define("_oscmem_cartcontents","工作車內容");
define("_oscmem_memberview","會員列表");
define("_oscmem_yes","是");
define("_oscmem_no","否");

define("_oscmem_reporttitle","OSC 會員報表");
define("_oscmem_directoryreport","會員目錄");
define("_oscmem_reports", "Reports");
define("_oscmem_nav_reports","報表／資料處理");
define("_oscmem_csvimport","CSV 匯入");

define("_oscmem_dirreport_selectclass","選擇要包含的會員類別：");
define("_oscmem_usectl","Use Ctrl Key to select multiple");
define("_oscmem_dirreport_groupmemb","小組");
define("_oscmem_dirreport_headhouse","Which role is the head of household?");
define("_oscmem_dirreport_spouserole","Which role is the spouse?");
define("_oscmem_dirreport_childrole","Which role is a child?");
define("_oscmem_dirreport_infoinclude","要包含的資料：");

define("_oscmem_address_label","Address:");
define("_oscmem_familyhomephone","家庭電話");
define("_oscmem_familyworkphone","家庭公司電話");
define("_oscmem_familycellphone","家庭行動電話");
define("_oscmem_familyemail","家庭電子郵件");
define("_oscmem_personalphone","個人電話");
define("_oscmem_personalworkphone","個人公司電話");
define("_oscmem_personalcell","個人行動電話");
define("_oscmem_personalemail","個人電子郵件");
define("_oscmem_personalworkemail","個人公司電子郵件");

define("_oscmem_informationinclude","要包含的資料");
define("_oscmem_diroptions","目錄選項");
define("_oscmem_altfamilyname","雙列表 － 另外使用家庭名稱");
define("_oscmem_althead","只使用家庭名稱來列表");
define("_oscmem_dirsort","目錄排序");
define("_oscmem_orderbyfirstname","以名字排序");
define("_oscmem_directorytitle","Church Directory Options");

define("_oscmem_titlepagesettings","封面頁設定");

define("_oscmem_usetitlepageyn","使用封面");
define("_oscmem_churchname_label","教會名稱");
define("_oscmem_disclaimer","免責聲明");

define("_oscmem_directory","Directory");
define("_oscmem_page","Page");

define("_oscmem_incorrectdt_membershipdate","Incorrect Membership Date Format");
define("_oscmem_incorrectdt_weddingdate","Incorrect Wedding Date Format");

define("_oscmem_CREATESUCCESS_group","&#23567;&#32068;&#24050;&#34987;&#24314;&#31435;");
define("_oscmem_csvexport","CSV 匯出");

define("_oscmem_cvsexport_infoinclude","要匯出的欄位");


define("_oscmem_season_select","Select a season");
define("_oscmem_season_spring","Spring");
define("_oscmem_season_summer","Summer");
define("_oscmem_season_fall","Fall");
define("_oscmem_season_winter","Winter");

define("_oscmem_filters","篩選");
define("_oscmem_recordstoexport","Records to Export");
define("_oscmem_classificationstoexport","匯出的會員類別");

define("_oscmem_fromfilterbelow","基於下列規則...");
define("_oscmem_fromcart","People in Cart (filters ignored)");

define("_oscmem_cvsexport_customfields","要匯出的自訂欄位");
define("_oscmem_rolestoexport","匯出的家中角色");

define("_oscmem_gender_nofilter","不篩選性別");

define("_oscmem_filter_from","From");
define("_oscmem_filter_to","To");

define("_oscmem_otheremail","Other Email");
define("_oscmem_envelopenumber","信封編號");
define("_oscmem_csv_birthanniversary","生日／紀念日");
define("_oscmem_csv_ageyearsmarried","年齡及結婚年");
define("_oscmem_csv_familyrole","家中角色");
define("_oscmem_csv_familyname","家庭名稱");
define("_oscmem_csv_ministry","Ministry");
define("_oscmem_anniversary","紀念日");

define("_oscmem_csv_individual","CSV Individual Records");
define("_oscmem_csv_combinefamily","CSV Combine Families");
define("_oscmem_csv_addtocart","Add Individuals to Cart");
define("_oscmem_csv_outputmethod","輸出方式");

define("_oscmem_csv_skipincompleteaddress","略過不完整的郵件地址資料");
define("_oscmem_csv_skipnoenvelope","Skip Records with No Envelope Number<br>(Individual Records Only)");

define("_oscmem_admin_osclist_familyroles","修改家中角色");
define("_oscmem_admin_osclist_memberclassification","修改會員類別");
define("_oscmem_admin_osclist_grouptype","修改小組類別");
define("_oscmem_admin_osclist_permissions","修改權限");
define("_oscmem_admin_customfield","修改自訂欄位");

define("_oscmem_view","檢視");

define("_oscmem_defaultcountry_value",22);
define("_oscmem_defaultcountry_text"," &nbsp;&nbsp;(Default Country is USA)");

define("_oscmem_accessdenied","Access Denied");

?>