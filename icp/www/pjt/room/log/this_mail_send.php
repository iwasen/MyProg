<?
/******************************************************
' System :ICP会議室ページ
' Content:発言を自分に送信完了
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
include("$inc/format.php");
include("$inc/mail.php");
include("$inc/room.php");
include("$inc/variable.php");
include("$inc/crypt.php");
include("$inc/remark.php");

$inc = "$top/room/inc";
include("$inc/room_common.php");
include("$inc/login_check.php");

// 入力パラメータ
$crypt_id = $_POST['id'];
$remark_id = decrypt_id($crypt_id);

// ニックネーム取得
$sql = "SELECT pm_nickname FROM t_pjt_member WHERE pm_member_id={$_SESSION[SCD]['member_id']}";
$nickname_to = db_fetch1($sql);

// 送信メッセージ取得
$sql = "SELECT rm_seq_no,rm_job_id,rm_member_id,rm_subject,rm_content,rm_date,rm_remark_type,rm_room_header,rm_room_footer,rm_message_id,rm_parent_remark_id,rm_from"
		. " FROM l_remark"
		. " WHERE rm_remark_id=$remark_id";
$result = db_exec($sql);
if (pg_num_rows($result)) {
	$fetch = pg_fetch_object($result, 0);

	// ニックネーム取得（開始メールの場合は差出人名を取得）
	if ($fetch->rm_remark_type == 2)
		$sql = "SELECT jb_start_from_name FROM t_job WHERE jb_job_id=$fetch->rm_job_id";
	else
		$sql = "SELECT pm_nickname FROM t_pjt_member WHERE pm_member_id=$fetch->rm_member_id";
	$nickname_from = db_fetch1($sql);

	// メール送信
	send_remark($remark_id, "jm_member_id={$_SESSION[SCD]['member_id']}");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="content-style-type" content="text/css">
	<meta http-equiv="content-script-type" content="text/javascript">
	<meta http-equiv="Pragma" content="no-cache">
	<link rel="stylesheet" type="text/css" href="../css/default.css">
	<title>発言の送信完了</title>
</head>

<body bgcolor="#ffffff" leftmargin="3" marginwidth="3" topmargin="3" marginheight="3" rightmargin="3" bottommargin="3">
<a name="top"></a>

<table border="0" cellspacing="1" cellpadding="0" bgcolor="#e24b36" align="center" width="100%">
<tr>
	<td>
		<table border="0" cellspacing="0" cellpadding="0" bgcolor="#f7c8c1" align="center" width="100%">
		<tr>
			<td class="text14b">このメールを<?=htmlspecialchars($nickname_to)?>さんに送信しました。</td>
		</tr>
		<tr>
			<td class="text12">
				<table border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" width="100%" class="text12">
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>【[<?=$fetch->rm_seq_no?>] <?=htmlspecialchars($fetch->rm_subject)?>】<br>
								発言者：<?=htmlspecialchars($nickname_from)?>（<?=format_datetime($fetch->rm_date)?>）を<?=htmlspecialchars($nickname_to)?>さんのアドレスに送信しました。</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td align="center"><input type="button" value="　戻る　" class="button" onclick="location.href='../contents_top.php?id=<?=$crypt_id?>'"></td>
				</tr>
				</table></td>
		</tr>
		</table></td>
</tr>
</table>

</body>

</html>
