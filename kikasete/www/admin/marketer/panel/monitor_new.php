<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:スペシャルパネル管理
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

set_global('myenquete', 'Ｍｙアンケート管理', 'パネルモニター追加', BACK_TOP);

$sql = sprintf("SELECT pnl_panel_name FROM t_panel WHERE pnl_panel_id=%s", $_REQUEST['panel_id']);
$panel_name = db_fetch1($sql);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onsubmit_form1(f) {
	if (f.monitor_list.value == "") {
		alert("登録メールアドレスまたはモニターIDを入力してください。");
		f.monitor_list.focus();
		return false;
	}
	return true;
}
//-->
</script>
</head>
<body onload="document.form1.monitor_list.focus()">
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="monitor_confirm.php" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>■パネルモニターリスト情報を入力してください</td>
	</tr>
	<tr>
		<td class="m1" width="25%">パネル名</td>
		<td class="n1"><?=htmlspecialchars($panel_name)?></td>
	</tr>
	<tr>
		<td class="m1">モニターIDまたはメールアドレス</td>
		<td class="n1">
			<textarea class="alpha" name="monitor_list" cols=60 rows=20></textarea>
			<div class="note">（モニターIDまたはメールアドレスをカンマ・改行区切りで入力してください）</div>
		</td>
	</tr>
</table>
<br>
<input type="submit" value="　登録　">
<input type="button" value="キャンセル" onclick="history.back()">
<input type="hidden" name="category_id" value="<?=$_REQUEST['category_id']?>">
<input type="hidden" name="panel_id" value="<?=$_REQUEST['panel_id']?>">
</form>
</div>

<? page_footer() ?>
</body>
</html>
