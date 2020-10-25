<?
/******************************************************
' System :ICP会議室ページ
' Content:新規投稿入力
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/auth.php");

$inc = "$top/room/inc";
include("$inc/room_common.php");
include("$inc/login_check.php");

// 権限チェック
if (!(check_participant_auth('Web発言') && check_participant_auth('発言ON/OFF')))
	redirect("$top/room/top.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="Pragma" content="no-cache">
	<link rel="stylesheet" type="text/css" href="../css/default.css">
	<title>新規に投稿する</title>
	<script type="text/javascript">
<!--
function onsubmit_form1(f) {
	if (f.subject.value == "") {
		alert("サブジェクトが入力されていないようです。");
		f.subject.focus();
		return false;
	}
	if (f.content.value == "") {
		alert("本文が入力されていないようです。");
		f.content.focus();
		return false;
	}
	return true;
}
//-->
	</script>
</head>

<body bgcolor="#ffffff" leftmargin="5" marginwidth="5" topmargin="5" marginheight="5" rightmargin="5" bottommargin="5" onload="document.form1.subject.focus()">
<form method="post" name="form1" action="new_chk.php" onsubmit="return onsubmit_form1(this)">
<div align="center">

<table border="0" cellspacing="1" cellpadding="0" bgcolor="#3042ab" width="650">
<tr>
	<td class="text16b"><div style="color:#ffffff;">メール投稿フォーム</div></td>
</tr>
<tr>
	<td>
		<table border="0" cellspacing="0" cellpadding="3" bgcolor="#ffffff" width="100%" class="text12">
		<tr class="line0">
			<th width="100">サブジェクト</th>
			<th width="10">：</th>
			<td><input type="text" name="subject" value="" size="80" class="textbox"></td>
		</tr>
		<tr class="line1">
			<th valign="top">本文</th>
			<th valign="top">：</th>
			<td><textarea name="content" wrap="hard" cols="80" rows="20" class="textbox"></textarea></td>
		</tr>
		<tr class="line1">
			<td colspan="3" align="center"><input type="submit" value="これで投稿する" class="button"></td>
		</tr>
		</table>
	</td>
</tr>
</table>

</div>

</form>
</body>

</html>
