<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:マーケターページへログイン
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/login_check.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

$marketer_id = $_POST['marketer_id'];
$url = $_POST['url'];

$sql = "SELECT mr_password FROM t_marketer WHERE mr_marketer_id=$marketer_id";
$password = db_fetch1($sql);

$fetch = get_system_info('sy_url_marketer');
$url_marketer = $fetch->sy_url_marketer;
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
</head>
<body onload="document.form1.submit()">
<form method="post" name="form1" action="<?=$url_marketer?>mk_login.php">
<input type="hidden" name="marketer_id" <?=value($marketer_id)?>>
<input type="hidden" name="password" <?=value($password)?>>
<input type="hidden" name="url" <?=value($url)?>>
</form>
</body>
</html>
