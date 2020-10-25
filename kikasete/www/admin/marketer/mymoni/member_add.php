<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:Ｍｙモニターメンバー追加処理
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/my_monitor.php");

set_global('marketer', 'マーケター管理｜Ｍｙモニター管理', 'Ｍｙモニターメンバー追加', BACK_TOP);

$mymoni = new my_monitor_class;
$mymoni->read_db($room_id);
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
	var f = document.form1;
	if (f.member.value == '') {
		alert("追加するメンバーを入力してください。");
		f.member.focus();
		return false;
	}
	return confirm("メンバーを追加します。よろしいですか？");
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="member_update.php" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>■Ｍｙモニターメンバー追加</td>
	</tr>
	<tr>
		<td class="m1" width="20%">ルーム名</td>
		<td class="n1" width="80%"><?=htmlspecialchars($mymoni->room_name)?></td>
	</tr>
	<tr>
		<td class="m1">追加メンバー</td>
		<td class="n1">
			<textarea name="member" cols=80 rows=15></textarea>
			<br><span class="note">（１行ずつモニターIDと自己紹介文をカンマ区切りで入力してください）</span>
		</td>
	</tr>
</table>

<br>
<input type="submit" value="　追加　" onclick="document.form1.next_action.value='add'">
<input type="button" value="　戻る　" onclick="history.back()">
<input type="hidden" name="next_action">
<input type="hidden" name="room_id" value="<?=$room_id?>">
</form>
</div>

<? page_footer() ?>
</body>
</html>
