<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」キャンペーン事務局用ページ
' Content:販売店ページへ遷移
'******************************************************/

$top = ".";
$inc = "$top/../inc";
include("login_check.php");
include("$inc/common.php");
include("$inc/database.php");

// セッションに販売店コードを保存
$_SESSION['s_dealer_cd'] = $_GET['dealer_cd'];

// 販売店ページへ遷移
$url = get_system_info('url_dealer_page');
redirect($url . 'monthly.php');
?>