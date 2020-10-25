<?
/******************************************************
' System :ICPプロジェクトページ
' Content:会議室ページログイン処理
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

$inc = "$top/inc";
include("$inc/pjt_common.php");
include("$inc/login_check.php");

// 入力パラメータ
$job_id = $_GET['job_id'];

// ジョブコード取得
$sql = "SELECT jb_job_cd FROM t_job WHERE jb_job_id=$job_id";
$job_cd = db_fetch1($sql);

// メールアドレスとパスワードを取得
$sql = "SELECT pm_mail_addr,pm_password"
		. " FROM t_pjt_member"
		. " WHERE pm_member_id=$g_member_id";
$result = db_exec($sql);
if (pg_num_rows($result) > 0)
	$fetch = pg_fetch_object($result, 0);
else
	redirect('login.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="Pragma" content="no-cache">
	<title></title>
</head>

<body onload="document.form1.submit()">
<form method="post" name="form1" action="login_check.php">
	<input type="hidden" name="job_cd" <?=value($job_cd)?>>
	<input type="hidden" name="mail_addr" <?=value($fetch->pm_mail_addr)?>>
	<input type="hidden" name="password" <?=value($fetch->pm_password)?>>
</body>

</html>
