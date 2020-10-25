<?
/******************************************************
' System :ICP会議室ページ
' Content:返信投稿完了
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/auth.php");
include("$inc/mail.php");
include("$inc/remark.php");
include("$inc/room.php");
include("$inc/define.php");
include("$inc/variable.php");
include("$inc/crypt.php");

$inc = "$top/room/inc";
include("$inc/room_common.php");
include("$inc/login_check.php");

// 入力パラメータ
$crypt_id = $_POST['id'];
$remark_id = decrypt_id($crypt_id);
$subject = $_POST['subject'];
$content = $_POST['content'];

// 表示種別取得
$sql = "SELECT rm_member_id,rm_disp_type FROM l_remark WHERE rm_remark_id=$remark_id";
$result = db_exec($sql);
$fetch = pg_fetch_object($result, 0);

	// 宛先種別判定
switch ($fetch->rm_disp_type) {
case DT_REMARK:
	$remark_type = RT_REMARK;
	$to_member_id = $_SESSION[SCD]['disp_member_id'];
	break;
case DT_HELP:
	if (check_participant_auth('司会者'))
		$remark_type = RT_ANSWER;
	else
		$remark_type = RT_HELP;
	$to_member_id = $fetch->rm_member_id;
	break;
}

$job_id = $_SESSION[SCD]['job_id'];
$member_id = $_SESSION[SCD]['member_id'];
$message_id = make_message_id();

set_remark($remark_type, $job_id, $member_id, $to_member_id, $message_id, $remark_id, $subject, $content, RM_WEB);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="Pragma" content="no-cache">
	<link rel="stylesheet" type="text/css" href="../css/default.css">
	<title>送信しました</title>
	<script type="text/javascript">
<!--
opener.top.main.subject.location.reload()
//-->
	</script>
</head>

<body bgcolor="#ffffff" leftmargin="5" marginwidth="5" topmargin="5" marginheight="5" rightmargin="5" bottommargin="5">
<form>
<div align="center">

<table border="0" cellspacing="1" cellpadding="0" bgcolor="#3042ab" width="650">
<tr>
	<td class="text16b"><div style="color:#ffffff;">送信しました。</div></td>
</tr>
<tr>
	<td>
		<table border="0" cellspacing="0" cellpadding="3" bgcolor="#ffffff" width="100%" class="text12">
		<tr class="line0">
			<td>【<?=htmlspecialchars($subject)?>】を送信しました。</td>
		</tr>
		<tr class="line0">
			<td colspan="3" align="center"><input type="button" value="閉じる" class="button" onclick="window.close()"></td>
		</tr>
		</table>
	</td>
</tr>
</table>

</div>

</form>
</body>

</html>
