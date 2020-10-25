<?
/******************************************************
' System :ICPオペレータページ
' Content:プロジェクトページログイン
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

$inc = "$top/inc";
include("$inc/ope_common.php");
include("$inc/login_check.php");

// 入力パラメータ
$pjt_id = $_GET['pjt_id'];

$sql = "SELECT pj_pjt_cd,pm_mail_addr,pm_password"
		. " FROM t_pjt"
		. " JOIN t_pjt_member ON pm_pjt_id=pj_pjt_id AND pm_operator_flag=1"
		. " WHERE pj_pjt_id=$pjt_id";
$result = db_exec($sql);
if (pg_num_rows($result) > 0) {
	$fetch = pg_fetch_object($result, 0);

} else
	program_err(__FILE__, __LINE__);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="Pragma" content="no-cache">
	<title></title>
</head>

<body onload="document.form1.submit()">
<form method="post" name="form1" action="../../<?=$fetch->pj_pjt_cd?>/login/login_check.php">
	<input type="hidden" name="mail_addr" <?=value($fetch->pm_mail_addr)?>>
	<input type="hidden" name="password" <?=value($fetch->pm_password)?>>
</body>

</html>
