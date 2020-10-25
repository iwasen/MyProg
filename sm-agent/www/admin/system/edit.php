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

// メイン処理
set_global('システムマスタ', BACK_TOP);

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
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	return confirm("システムマスタ情報を更新します。よろしいですか？");
}

//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>■サイト情報</td>
	</tr>
	<tr>
		<td class="m1" width=30%>サイトトップURL</td>
		<td class="n1" width=70%>
			<input class="alpha" type="text" name="site_url" size=70 maxlength=100 <?=value($fetch->sy_site_url)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m0" colspan=2>■メールサーバ設定</td>
	</tr>
	<tr>
		<td class="m1">POP3サーバのホスト名</td>
		<td class="n1">
			<input class="alpha" type="text" name="pop_server" size=70 maxlength=100 <?=value($fetch->sy_pop_server)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">不達メール受信アカウント</td>
		<td class="n1">
			<input class="alpha" type="text" name="envelope_addr" size=70 maxlength=100 <?=value($fetch->sy_envelope_addr)?>>
			<span class="note">（半角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">不達メール受信パスワード</td>
		<td class="n1">
			<input class="alpha" type="password" name="envelope_pwd" size=20 maxlength=20 <?=value($fetch->sy_envelope_pwd)?>>
			<span class="note">（半角２０文字まで）</span>
		</td>
	</tr>
</table>
<br>
<input type="hidden" name="sys_id" <?=value($sys_id)?>>
<input type="submit" value="　更新　">
<input type="button" value="　戻る　" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
