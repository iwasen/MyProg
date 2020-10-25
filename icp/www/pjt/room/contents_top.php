<?
/******************************************************
' System :ICP��ļ��ڡ���
' Content:����ƥ��ɽ��
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/auth.php");
include("$inc/format.php");
include("$inc/decode.php");
include("$inc/room.php");
include("$inc/variable.php");
include("$inc/crypt.php");
include("$inc/select.php");

$inc = "$top/room/inc";
include("$inc/room_common.php");
include("$inc/login_check.php");

// ���ϥѥ�᡼��
$crypt_id = $_GET['id'];
$remark_id = decrypt_id($crypt_id);
$reload_subject = $_GET['reload_subject'];

if ($remark_id) {
	if (isset($_GET['marking'])) {
		$rec['rm_marking_id'] = sql_number($_GET['marking']);
		db_update('l_remark', $rec, "rm_remark_id=$remark_id");
		$reload_subject = true;
	}

	$sql = "SELECT rm_seq_no,rm_date,rm_subject,rm_remark_type,rm_job_id,rm_member_id,rm_content,rm_room_header,rm_room_footer,rm_marking_id,jb_job_id,jb_job_cd,jb_room_header_web,jb_room_footer_web,pj_pjt_cd"
			. " FROM l_remark"
			. " JOIN t_job ON jb_job_id=rm_job_id"
			. " JOIN t_pjt ON pj_pjt_id=jb_pjt_id"
			. " WHERE rm_remark_id=$remark_id";
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_object($result, 0);

		// �˥å��͡�������ʳ��ϥ᡼��ξ��Ϻ��п�̾�������
		$nickname = get_nickname($fetch->rm_remark_type, $fetch->rm_job_id, $fetch->rm_member_id);

		// ��ļ��إå����եå����ղ�
		if ($fetch->jb_room_header_web == DBTRUE && $fetch->rm_room_header != '')
			$ary[] = trim($fetch->rm_room_header);
		$ary[] = trim($fetch->rm_content);
		if ($fetch->jb_room_footer_web == DBTRUE && $fetch->rm_room_footer != '')
			$ary[] = trim($fetch->rm_room_footer);
		$content = join("\n\n", $ary);

		$content = replace_mask_var($_SESSION[SCD]['job_id'], $_SESSION[SCD]['member_id'], $content);

		// ���ơ���������
		$sql = "SELECT jb_status FROM t_job WHERE jb_job_id={$_SESSION[SCD]['job_id']}";
		$status = db_fetch1($sql);
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="content-style-type" content="text/css">
	<meta http-equiv="content-script-type" content="text/javascript">
	<meta http-equiv="Pragma" content="no-cache">
	<link rel="stylesheet" type="text/css" href="css/default.css">
	<script language="JavaScript" type="text/javascript" src="js/default.js"></script>
<script type="text/javascript">
<!--
<?
if ($reload_subject)
	echo "parent.subject.location.reload();\n";

echo "top.current_data.id.value = '$crypt_id';\n";
?>
//-->
</script>
	<title>����ƥ����ʬ</title>
</head>

<body bgcolor="#ffffff" leftmargin="3" marginwidth="3" topmargin="3" marginheight="3" rightmargin="3" bottommargin="3">
<a name="top"></a>

<table border="0" cellspacing="1" cellpadding="0" bgcolor="#D3AA07" align="center" width="100%">
<tr>
	<td>
		<table border="0" cellspacing="0" cellpadding="4" bgcolor="#fefae9" width="100%">
		<tr>
			<td align="center" width="5%"><img src="img/subject_icon.gif" alt="" width="14" height="18" border="0"></td>
<?
if ($remark_id) {
?>
			<td width="60%" class="text12b">[<?=$fetch->rm_seq_no?>] <?=htmlspecialchars($fetch->rm_subject)?></td>
			<td width="35%" colspan="2" align="right" class="text12">
<?
	if (check_participant_auth('�ʲ��')) {
?>
				<form method="get" action="contents_top.php">
					�ޡ������Խ���
					<select name="marking" class="form" onchange="submit()"><? select_marking('-', $fetch->rm_marking_id) ?></select>
					<input type="hidden" name="id" <?=value($crypt_id)?>>
				</form>
<?
	}
?>
			</td>
<?
} else {
?>
			<td width="95%"></td>
<?
}
?>
		</tr>
		<tr>
			<td align="center"><img src="img/username_icon.gif" alt="" width="14" height="18" border="0"></td>
<?
if ($remark_id) {
?>
			<td class="text12">ȯ���ԡ�<?=htmlspecialchars($nickname)?> ��<?=format_datetime($fetch->rm_date)?>��</td>
			<td width="20%" align="right">
<?
	if (check_participant_auth('Webȯ��') && check_participant_auth('ȯ��ON/OFF') && $status == 5) {
?>
				<a href="javascript:Open('send/res.php?id=<?=$crypt_id?>','send_res','680','700')" title="����ȯ�����ֿ��򤹤�"><img src="img/reply.gif" alt="����ȯ�����ֿ��򤹤�" width="120" height="16" border="0"></a>
<?
	}
?>
			</td>
			<td width="15%">
<?
	if (check_participant_auth('�᡼�����') && check_participant_auth('ȯ��ON/OFF')) {
?>
				<a href="log/this_mail.php?id=<?=$crypt_id?>" title="����ȯ����ʬ������"><img src="img/send.gif" alt="����ȯ����ʬ������" width="120" height="16" border="0"></a>
<?
	}
?>
			</td>
<?
} else {
?>
			<td width="95%"></td>
<?
}
?>
		</tr>
		</table></td>
</tr>
</table>

<table border="0" cellspacing="1" cellpadding="0" bgcolor="#D3AA07" align="center" width="100%">
<tr>
	<td>
		<table border="0" cellspacing="1" cellpadding="2" bgcolor="#ffffff" align="center" width="100%" class="text12">
		<tr>
			<td><pre><?=htmlspecialchars($content)?></pre></td>
		</tr>
		</table></td>
</tr>
</table>

<table border="0" cellspacing="0" cellpadding="10" width="100%" id="backtotop">
<tr>
	<td align="right"><a href="#top" title="���Υڡ����Υȥåפ����">�����Υڡ����Υȥåפ����</a></td>
</tr>
</table>

</body>

</html>
