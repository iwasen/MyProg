<?
/******************************************************
' System :メール配信サービス
' Content:システムマスタ登録変更画面
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/common.php");
include("$inc/header.php");
include("$inc/database.php");

function password($str) {
	return str_repeat('*', strlen($str));
}

// メイン処理
set_global('システムマスタ変更', BACK_TOP);

$sys_id = 1;

$sql = "SELECT * FROM m_system WHERE sy_system_id=$sys_id";
$result = db_exec($sql);
if (pg_numrows($result))
	$fetch = pg_fetch_object($result, 0);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="edit.php">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>■サイト情報</td>
	</tr>
	<tr>
		<td class="m1" width=30%>サイトトップURL</td>
		<td class="n1" width=70%><?=htmlspecialchars($fetch->sy_site_url)?></td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■メールサーバ設定</td>
	</tr>
	<tr>
		<td class="m1">POP3サーバのホスト名</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_pop_server)?></td>
	</tr>
	<tr>
		<td class="m1">不達メール受信アカウント</td>
		<td class="n1"><?=htmlspecialchars($fetch->sy_envelope_addr)?></td>
	</tr>
	<tr>
		<td class="m1">不達メール受信パスワード</td>
		<td class="n1"><?=password($fetch->sy_envelope_pwd)?></td>
	</tr>
</table>
<br>
<input type="hidden" name="sys_id" <?=value($sys_id)?>>
<input type="hidden" name="next_action">
<input type="submit" value="　修正　">
<input type="button" value="　戻る　" onclick="location.href='../menu.php'">
</form>
</div>

<? page_footer() ?>
</body>
</html>
