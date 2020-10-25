<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:パスワード変更処理
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/decode.php");

// パスワード更新
$sql = sprintf("SELECT stf_password FROM t_staff WHERE stf_staff_id=%s AND stf_password=%s",
		sql_number($g_staff_id),
		sql_char(md5($_POST['password_old'])));
$result = db_exec($sql);
if (pg_num_rows($result) == 0)
	$msg = '入力したパスワードが違います。';
else {
	$sql = sprintf("UPDATE t_staff SET stf_password=%s WHERE stf_staff_id=%s",
			sql_char(md5($_POST['password_new'])),
			sql_number($g_staff_id));
	$result = db_exec($sql);
	$msg = 'パスワードを変更しました。';
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
</head>
<body onLoad="document.all.ok.focus()">

<? center_header(decode_privilege($g_privilege) . '情報変更｜パスワード変更') ?>

<div align="center">
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="　戻る　" onclick="location.href='change.php'"></p>
</div>

<? center_footer() ?>

</body>
</html>
