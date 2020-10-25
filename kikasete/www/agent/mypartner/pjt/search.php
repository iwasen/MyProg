<?
/******************************************************
' System :きかせて・net事務局・エージェント用ページ
' Content:Myパートナー・Myパートナー検索
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

//メイン処理
set_global2('mypartner', 'Myパートナー管理', 'Myパートナー検索', BACK_TOP);
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
	if (f.monitor_name.value == "") {
		alert("モニター名を入力してください。");
		f.monitor_name.focus();
		return false;
	}
	return true;
}
function onsubmit_form2(f) {
	if (f.room_name.value == "") {
		alert("会議室名を入力してください。");
		f.room_name.focus();
		return false;
	}
	return true;
}
//-->
</script>
<? menu_script() ?>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<table border=0 cellspacing=2 cellpadding=3 width="<?=width('80%')?>">
	<tr>
		<td colspan=2>
			<table border=0 cellspacing=0 cellpadding=0 width="100%">
				<tr>
					<td class="m0">■検索したいモニター情報を入力してください</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="m1" width="22%">モニター名</td>
		<td class="n1">
			<form method="post" name="form1" action="list_menber.php" target="_blank" onsubmit="return onsubmit_form1(this)">
			<input type="text" name="monitor_name" size=40 maxlength=100>
			<input type="submit" value="検索"><br>
			<small>※参加した会議室の一覧を表示します。</small>
			</form>
		</td>
	</tr>
	<tr>
		<td class="m1">会議室</td>
		<td class="n1">
			<form method="post" name="form2" action="list_room.php" target="_blank" onsubmit="return onsubmit_form2(this)">
			<input type="text" name="room_name" size=40 maxlength=100>
			<input type="submit" value="検索"><br>
			<small>※会議室を表示します。</small>
			</form>
		</td>
	</tr>
</table>
<br>
<input type="button" value="　戻る　" onclick="history.back()">

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
