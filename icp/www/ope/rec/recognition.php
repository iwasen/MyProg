<?
/******************************************************
' System :ICP���ڥ졼���ڡ���
' Content:̤��ǧ����־ܺ�
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");

$inc = "$top/inc";
include("$inc/ope_common.php");
include("$inc/login_check.php");
include("$inc/header.php");

// ���ϥѥ�᡼��
$job_id = $_GET['job_id'];

// �ץ������ȡ���������Ƽ���
$sql = "SELECT pj_pjt_cd,pj_name,jb_job_cd,jb_name,jb_start_date,jb_end_date,jb_member_max,jb_room_type,jb_reorder_flag,jb_open_mail_addr,jb_attachment_file"
		. " FROM t_job"
		. " JOIN t_pjt ON pj_pjt_id=jb_pjt_id"
		. " WHERE jb_job_id=$job_id";
$result = db_exec($sql);
if (pg_num_rows($result) > 0) {
	$fetch = pg_fetch_object($result, 0);

	$pjt_cd = $fetch->pj_pjt_cd;
	$pjt_name = $fetch->pj_name;
	$job_cd = $fetch->jb_job_cd;
	$job_name = $fetch->jb_name;
	$start_date = $fetch->jb_start_date;
	$end_date = $fetch->jb_end_date;
	$member_max = $fetch->jb_member_max;
	$room_type = $fetch->jb_room_type;
	$open_mail_addr = $fetch->jb_open_mail_addr;
	$attachment_file = $fetch->jb_attachment_file;
} else
	program_err(__FILE__, __LINE__);

// �ƿ������Ƽ���
if ($fetch->jb_reorder_flag) {
	$sql = "SELECT jr_name,jr_start_date,jr_end_date,jr_member_max,jr_room_type,jr_open_mail_addr,jr_attachment_file"
			. " FROM t_job_reorder"
			. " WHERE jr_job_id=$job_id";
	$result = db_exec($sql);
	if (pg_num_rows($result) > 0) {
		$fetch = pg_fetch_object($result, 0);

		$job_name = $fetch->jr_name;
		$start_date = $fetch->jr_start_date;
		$end_date = $fetch->jr_end_date;
		$member_max = $fetch->jr_member_max;
		$room_type = $fetch->jr_room_type;
		$open_mail_addr = $fetch->jr_open_mail_addr;
		$attachment_file = $fetch->jr_attachment_file;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="Pragma" content="no-cache">
	<title>icp.net ���ڥ졼�����ڡ���</title>
	<link rel="stylesheet" type="text/css" href="<?=$top?>/default.css">
	<script type="text/javascript">
<!--
function on_recognition_comp(job_id) {
	if (confirm("���Υ���֤�ǧ���ޤ���������Ǥ�����"))
		location.href="recognition_comp.php?job_id=<?=$job_id?>";
}
function on_recognition_sorry(job_id) {
	if (confirm("���Υ���֤򺹤��ᤷ�ޤ���������Ǥ�����"))
		location.href="recognition_sorry.php?job_id=<?=$job_id?>";
}
//-->
	</script>
</head>

<body>
<div align="center">

<? ope_header() ?>

<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>

<? ope_menu() ?>

	<!-- ������ƥ�� -->
	<td valign="top">

		<table border="0" cellspacing="0" cellpadding="10" width="100%">
		<tr>
			<td>

				<table border="0" cellspacing="0" cellpadding="3" width="100%" class="pagetitle">
				<tr>
					<td>��̤��ǧ����־ܺ�</td>
				</tr>
				</table>

				<table border="1" cellspacing="0" cellpadding="2" width="100%" class="card">
				<tr>
					<th width="30%">�ץ�������̾�ʥץ�������CD��</th>
					<td width="70%"><?=htmlspecialchars($pjt_name)?>��<?=htmlspecialchars($pjt_cd)?>��</td>
				</tr>
				<tr>
					<th>�����̾�ʥ����CD��</th>
					<td><?=htmlspecialchars($job_name)?>��<?=htmlspecialchars($job_cd)?>��</td>
				</tr>
				<tr>
					<th>��ļ�������</th>
					<td><?=decode_room_type($room_type)?></td>
				</tr>
				<tr>
					<th>�»ܴ���</th>
					<td><?=format_date_j($start_date)?> �� <?=format_date_j($end_date)?></td>
				</tr>
				<tr>
					<th>�����Ϳ�</th>
					<td><?=$member_max ? number_format($member_max) . '��' : '���¤ʤ�'?></td>
				</tr>
				<tr>
					<th>�᡼�륢�ɥ쥹����</th>
					<td><?=$open_mail_addr == DBFALSE ? '�᡼�륢�ɥ쥹�򳫼����ʤ����ִ������' : '�᡼�륢�ɥ쥹�򳫼�������ִ����ʤ���'?></td>
				</tr>
				<tr>
					<th>ź�եե�����</th>
					<td><?=$attachment_file == DBFALSE ? '���Ѥ��ʤ�' : '���Ѥ���'?></td>
				</tr>
				</table>

				<table border="0" cellspacing="0" cellpadding="10" width="100%">
				<tr>
					<td align="center">
						<input type="button" value="����ǧ��" class="btn" onclick="on_recognition_comp()">
						<input type="button" value="�����ᤷ" class="btn" onclick="on_recognition_sorry()">
						<input type="button" value="����󥻥�" class="btn" onclick="history.back()">
					</td>
				</tr>
				</table>

			</td>
		</tr>
		</table>

	</td>
	<!-- ������ƥ�� -->
</tr>
</table>

<? ope_footer() ?>

</div>
</body>

</html>
