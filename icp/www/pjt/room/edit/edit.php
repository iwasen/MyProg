<?
/******************************************************
' System :ICP会議室ページ
' Content:発言編集
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/auth.php");
include("$inc/crypt.php");
include("$inc/mail.php");
include("$inc/room.php");
include("$inc/format.php");
include("$inc/variable.php");

$inc = "$top/room/inc";
include("$inc/room_common.php");
include("$inc/login_check.php");

// 権限チェック
if (!check_participant_auth('司会者'))
	redirect("$top/room/top.php");

// 入力パラメータ
$crypt_id = $_GET['id'];
$remark_id = decrypt_id($crypt_id);

// 発言取得
$sql = "SELECT rm_job_id,rm_seq_no,rm_member_id,rm_subject,rm_content,rm_remark_type,rm_date"
		. " FROM l_remark"
		. " WHERE rm_remark_id=$remark_id";
$result = db_exec($sql);
if (pg_num_rows($result)) {
	$fetch = pg_fetch_object($result, 0);

	// ニックネーム取得（開始メールの場合は差出人名を取得）
	$nickname = get_nickname($fetch->rm_remark_type, $fetch->rm_job_id, $fetch->rm_member_id);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="content-style-type" content="text/css">
	<meta http-equiv="content-script-type" content="text/javascript">
	<link rel="stylesheet" type="text/css" href="../css/default.css">
	<title>発言編集</title>
<script type="text/javascript">
<!--
function onsubmit_form1(f) {
	return confirm("この発言を更新します。よろしいですか？");
}
//-->
</script>
</head>

<body bgcolor="#ffffff" leftmargin="3" marginwidth="3" topmargin="3" marginheight="3" rightmargin="3" bottommargin="3">
<a name="top"></a>
<form method="post" name="form1" action="edit_comp.php" onsubmit="return onsubmit_form1(this)">
<table border="0" cellspacing="0" cellpadding="4" bgcolor="#e1e1e1" width="100%">
<tr>
	<td width="50%" class="text14b">■発言編集</td>
	<td width="50%" align="right"><input type="submit" value="更新" class="form">
	<input type="button" value="戻る" class="form" onclick="location.href='../contents_top.php?id=<?=$crypt_id?>'"></td>
</tr>
</table>

<table border="0" cellspacing="1" cellpadding="0" bgcolor="#D3AA07" align="center" width="100%">
<tr>
	<td>
		<table border="0" cellspacing="0" cellpadding="4" bgcolor="#fefae9" width="100%">
		<tr>
			<td align="center" width="5%"><img src="../img/subject_icon.gif" alt="" width="14" height="18" border="0"></td>
			<td class="text12b">[<?=$fetch->rm_seq_no?>]<input type="text" name="subject" <?=value($fetch->rm_subject)?> size="50" class="form" title="サブジェクトを編集してください"></td>
		</tr>
		<tr>
			<td align="center"><img src="../img/username_icon.gif" alt="" width="14" height="18" border="0"></td>
			<td class="text12">発言者：<?=htmlspecialchars($nickname)?> （<?=format_datetime($fetch->rm_date)?>）</td>
		</tr>
		</table></td>
</tr>
</table>

<table border="0" cellspacing="1" cellpadding="0" bgcolor="#D3AA07" align="center" width="100%">
<tr>
	<td>
		<table border="0" cellspacing="1" cellpadding="2" bgcolor="#ffffff" align="center" width="100%" class="text12">
		<tr>
			<td><textarea name="content" cols="90" rows="20" class="form" title="本文を編集してください"><?=htmlspecialchars($fetch->rm_content)?></textarea></td>
		</tr>
		</table></td>
</tr>
</table>

<table border="0" cellspacing="0" cellpadding="4" bgcolor="#e1e1e1" width="100%">
<tr>
	<td align="right"><input type="submit" value="更新" class="form">
	<input type="button" value="戻る" class="form" onclick="location.href='../contents_top.php?id=<?=$crypt_id?>'"></td>
</tr>
</table>
<input type="hidden" name="id" <?=value($crypt_id)?>>
</form>

<table border="0" cellspacing="0" cellpadding="10" width="100%" id="backtotop">
<tr>
	<td align="right"><a href="#top" title="このページのトップへ戻る">▲このページのトップへ戻る</a></td>
</tr>
</table>

</body>

</html>
