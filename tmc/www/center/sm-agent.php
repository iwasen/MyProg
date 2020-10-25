<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:SendmailAgent自動ログイン
'******************************************************/

$top = ".";
$inc = "$top/../inc";
include("$inc/database.php");

$url = rtrim(get_system_info('url_sm-agent'), '/');
$account = explode('/', get_system_info('account_sm-agent', 2));
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
</head>
<body onload="document.form1.submit()">
<form method="post" name="form1" action="<?=$url?>/login/login_check.php">
<input type="hidden" name="login_id" value="<?=$account[0]?>">
<input type="hidden" name="password" value="<?=$account[1]?>">
<input type="hidden" name="system" value="tmc">
</form>
</body>
</html>
