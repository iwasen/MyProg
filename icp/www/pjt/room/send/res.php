<?
/******************************************************
' System :ICP会議室ページ
' Content:返信投稿入力
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/crypt.php");
include("$inc/mail.php");
include("$inc/room.php");
include("$inc/variable.php");

$inc = "$top/room/inc";
include("$inc/room_common.php");
include("$inc/login_check.php");

// Re: の付加
function add_re($subject) {
	// 既存のRe;の削除
	while (true) {
		$subject = trim($subject);
		if (strncasecmp($subject, 'Re:', 3) == 0)
			$subject = substr($subject, 3);
		else
			break;
	}
	return "Re: $subject";
}

// 入力パラメータ
$crypt_id = $_GET['id'];
$remark_id = decrypt_id($crypt_id);

// 返信元メッセージ取得
$sql = "SELECT rm_subject,rm_content"
		. " FROM l_remark"
		. " WHERE rm_remark_id=$remark_id";
$result = db_exec($sql);
if (pg_num_rows($result)) {
	$fetch = pg_fetch_object($result, 0);

	$content = replace_mask_var($_SESSION[SCD]['job_id'], $_SESSION[SCD]['member_id'], $fetch->rm_content);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="Pragma" content="no-cache">
	<link rel="stylesheet" type="text/css" href="../css/default.css">
	<title>返信する</title>
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

<body bgcolor="#ffffff" leftmargin="5" marginwidth="5" topmargin="5" marginheight="5" rightmargin="5" bottommargin="5">
<form method="post" name="form1" action="res_chk.php" onsubmit="return onsubmit_form1(this)">
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
			<td><input type="text" name="subject" <?=value(add_re($fetch->rm_subject))?> size="80" class="textbox"></td>
		</tr>
		<tr class="line1">
			<th valign="top">本文</th>
			<th valign="top">：</th>
			<td>
			<textarea name="content" wrap="hard" cols="80" rows="28" class="textbox"><?=htmlspecialchars(make_quote($content))?></textarea></td>
		</tr>
		<tr class="line1">
			<td colspan="3" align="center"><input type="submit" value="これで投稿する" class="button"></td>
		</tr>
		</table>
	</td>
</tr>
</table>

</div>

<input type="hidden" name="id" <?=value($crypt_id)?>>
</form>
</body>

</html>
