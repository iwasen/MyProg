<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:アフィリエイト登録者一覧
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/affiliate.php");
$inc = "$top/inc";
include("$inc/login_check.php");

if (isset($_GET['affiliate_mgr_id'])) {
	$affiliate_mgr_id = $_GET['affiliate_mgr_id'];
	$_SESSION['affiliate_mgr_id'] = $affiliate_mgr_id;
} elseif (isset($_SESSION['affiliate_mgr_id']))
	$affiliate_mgr_id = $_SESSION['affiliate_mgr_id'];

$sql = "SELECT afm_affiliate_type FROM t_affiliate_mgr WHERE afm_affiliate_mgr_id=$affiliate_mgr_id";
switch (db_fetch1($sql)) {
case AFFILIATE_WEB:
	redirect("web_reg_list.php?affiliate_id=$affiliate_id");
	break;
case AFFILIATE_MAIL:
	redirect("mail_reg_list.php?affiliate_id=$affiliate_id");
	break;
default:
	redirect("list.php");
	break;
}
?>