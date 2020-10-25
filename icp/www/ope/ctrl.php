<?
$top = ".";
$inc = "$top/../inc";
include("$inc/common.php");

$inc = "$top/inc";
include("$inc/ope_common.php");

session_start();

if ($_POST['search_flg']) {
	$_SESSION[SCD]['search_pjt'] = $_POST['search_pjt'];
	$_SESSION[SCD]['search_org'] = $_POST['search_org'];
	$_SESSION[SCD]['search_ope'] = $_POST['search_ope'];
	$onload = ' onload="top.main.location.reload()"';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="Pragma" content="no-cache">
	<title></title>
	<script type="text/javascript">
<!--
function on_search_pjt(search_pjt) {
	document.search.search_pjt.value = search_pjt;
	document.search.submit();
}
function on_search_org(search_org) {
	document.search.search_org.value = search_org;
	document.search.submit();
}
function on_search_ope(search_ope) {
	document.search.search_ope.value = search_ope;
	document.search.submit();
}
//-->
	</script>
</head>

<body<?=$onload?>>
<form method="post" name="search" action="ctrl.php">
	<input type="hidden" name="search_pjt" value="<?=htmlspecialchars($_SESSION[SCD]['search_pjt'])?>">
	<input type="hidden" name="search_org" value="<?=htmlspecialchars($_SESSION[SCD]['search_org'])?>">
	<input type="hidden" name="search_ope" value="<?=htmlspecialchars($_SESSION[SCD]['search_ope'])?>">
	<input type="hidden" name="search_flg" value="1">
</form>
</body>

</html>
