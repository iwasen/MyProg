<?
/******************************************************
' System :ICP�ץ������ȥڡ���
' Content:����ִ�������ɽ���ڡ���
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/auth.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/status.php");

$inc = "$top/inc";
include("$inc/pjt_common.php");
include("$inc/login_check.php");
include("$inc/header.php");

// ���¥����å�
if (!check_staff_auth('����ִ���'))
	redirect("$top/index.php");

// ���ϥѥ�᡼��
$job_id = $_POST['job_id'];

// �����̾�ʥ���֥����ɡ˼���
$job_name_cd = get_job_name_cd($job_id);

// ����־������
$sql = "SELECT * FROM t_job WHERE jb_job_id=" . sql_number($job_id);
$result = db_exec($sql);
if (pg_num_rows($result) > 0) {
	$fetch = pg_fetch_object($result, 0);

	$status = $fetch->jb_status;
} else
	program_err(__FILE__, __LINE__);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="Pragma" content="no-cache">
	<title><?=htmlspecialchars($_SESSION[SCD]['pjt_name'])?>��icp.net</title>
	<link rel="stylesheet" type="text/css" href="<?=$top?>/default.css">
	<script type="text/javascript">
<!--
function goto(page) {
	var f = document.goto1;
	f.action = page;
	f.submit();
}
function order(status) {
	var f = document.goto2;
	f.status.value = status;
	f.submit();
}
function set_mail_status(kind, status) {
	var kind_s, st;
	switch (kind) {
	case "guide":
		kind_s = "����";
		break;
	case "start":
		kind_s = "����";
		break;
	case "end":
		kind_s = "��λ";
		break;
	}
	switch (status) {
	case 1:
		st = confirm(kind_s + "�᡼����������֤��ᤷ�ޤ�����");
		break;
	case 2:
		st = confirm(kind_s + "�᡼��������λ�����ꤷ�ޤ�����");
		break;
	}
	if (st)
		location.href = "mail_status.php?job_id=<?=$job_id?>&kind=" + kind + "&status=" + status;
}
//-->
	</script>
</head>

<body>
<div align="center">

<form method="post" name="goto1">
<input type="hidden" name="job_id" <?=value($job_id)?>>
</form>

<form method="post" name="goto2" action="order.php">
<input type="hidden" name="job_id" <?=value($job_id)?>>
<input type="hidden" name="status">
</form>

<? pjt_header() ?>

<!-- pankuzu -->
<table border="0" cellspacing="0" cellpadding="3" bgcolor="#fdc92b" width="800" class="pankuzu">
<tr>
	<td><a href="../index.php" title="�ۡ���">�ۡ���</a>
		&nbsp;&nbsp;��&nbsp;&nbsp;
		<?=htmlspecialchars($job_name_cd)?>����
		</td>
</tr>
</table>
<!-- pankuzu -->

<!-- ��������� -->
<table border="0" cellspacing="0" cellpadding="0" width="800" class="regist">
<tr>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
	<td width="790" align="right">
<?
if ($fetch->jb_reorder_flag == DBFALSE) {
	if ($status == $g_job_status['������']) {
?>
		<input type="button" value="����ּ»ܿ���" onclick="order(<?=$g_job_status['������']?>)">
<?
	} elseif ($status == $g_job_status['������']) {
?>
		<input type="button" value="����ֿ������" onclick="order(<?=$g_job_status['������']?>)">
<?
	} elseif ($status == $g_job_status['��ǧ��']) {
?>
		<input type="button" value="���괰λ" onclick="order(<?=$g_job_status['�Ե���']?>)">
<?
	} elseif ($status == $g_job_status['�Ե���']) {
?>
		<input type="button" value="���괰λ���" onclick="order(<?=$g_job_status['��ǧ��']?>)">
<?
	} elseif ($status == $g_job_status['�»���']) {
?>
		<input type="button" value="��ļ��ٻ�" onclick="order(<?=$g_job_status['�ٻ���']?>)">
<?
	} elseif ($status == $g_job_status['�ٻ���']) {
?>
		<input type="button" value="��ļ��Ƴ�" onclick="order(<?=$g_job_status['�»���']?>)">
<?
	}
	if ($status == $g_job_status['��ǧ��']
			|| $status == $g_job_status['�Ե���']
			|| $status == $g_job_status['�»���']
			|| $status == $g_job_status['�ٻ���']
			|| $status == $g_job_status['ͱͽ��']) {
?>
	<input type="button" name="" value="����ֺƿ���" onclick="goto('reapply_update.php')"></td>
<?
	}
} else {
?>
	<input type="button" name="" value="����ֺƿ������" onclick="goto('reorder_cancel.php')"></td>
<?
}
?>
	</td>
</tr>
</table>
<!-- ��������� -->

<!-- main -->
<table border="0" cellspacing="0" cellpadding="0" width="800" class="main">
<tr>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
	<td width="760">
		<table border="0" cellspacing="0" cellpadding="3" bgcolor="#e4e4e4" width="760" class="contents">
		<tr>
			<td width="50%">������ִ�������</td>
			<td width="50%" align="right">
<?
if ($status != $g_job_status['��λ']) {
?>
				<a href="javascript:goto('basic_update.php')" title="����֤δ�������򹹿����ޤ�">����</a>
<?
}
?>
			</td>
		</tr>
		</table>

		<table border="1" cellspacing="0" cellpadding="2" width="760" class="card" frame="box">
		<tr>
			<th width="200">�����̾�ʥ����ID��</th>
			<td width="560"><?=htmlspecialchars($fetch->jb_name)?>��<?=$fetch->jb_job_id?>��</td>
		</tr>
		<tr>
			<th>����־ܺ�</th>
			<td><?=nl2br(htmlspecialchars($fetch->jb_description))?></td>
		</tr>
		<tr>
			<th>��ļ�������</th>
			<td><?=decode_room_type($fetch->jb_room_type)?></td>
		</tr>
		<tr>
			<th>��ļ�̾</th>
			<td><?=htmlspecialchars($fetch->jb_room_name)?></td>
		</tr>
		<tr>
			<th>�»ܴ���</th>
			<td><?=format_date_j($fetch->jb_start_date)?> �� <?=format_date_j($fetch->jb_end_date)?></td>
		</tr>
		<tr>
			<th>������ѿͿ�</th>
			<td><?=$fetch->jb_member_max ? number_format($fetch->jb_member_max)  . '��' : '���¤ʤ�'?></td>
		</tr>
		<tr>
			<th>����֥�����</th>
			<td><?=htmlspecialchars($fetch->jb_job_cd)?></td>
		</tr>
		<tr>
			<th>�᡼�륢�ɥ쥹����</th>
			<td><?=$fetch->jb_open_mail_addr == DBFALSE ? '�᡼�륢�ɥ쥹�򳫼����ʤ����ִ������' : '�᡼�륢�ɥ쥹�򳫼�������ִ����ʤ���'?></td>
		</tr>
		<tr>
			<th>ź�եե�����</th>
			<td><?=$fetch->jb_attachment_file == DBFALSE ? '���Ѥ��ʤ�' : '���Ѥ���'?></td>
		</tr>
		<tr>
			<th>����ݡ��ȺѤ߿Ϳ�</th>
				<td>
				<table border="0" cellspacing="0" cellpadding="0" width="100%" class="card">
				<tr>
					<td width="50%"><a href="javascript:goto('list.php')" title="���Υ���֤˴�Ϣ�Ť��Ƥ�����С��Υꥹ�Ȥ�ɽ�����ޤ�"><?=number_format($fetch->jb_member_num)?>��</a></td>
					<td align="right" width="50%">
<?
if (check_staff_auth('���üԥ��Х������ݡ���')) {
?>
						<a href="javascript:goto('member_export.php')" title="���üԥꥹ�ȥ��������">���üԥꥹ�ȥ��������</a>
<?
}
?>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th>����</th>
			<td><?=decode_job_status($status)?><?=$fetch->jb_reorder_flag == DBTRUE ? ' �ʺƿ������' : ''?></td>
		</tr>
			</table>
			</td>
		</tr>
		</table>
	</td>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
</tr>
</table>

<br>

<table border="0" cellspacing="0" cellpadding="0" width="800" class="main">
<tr>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
	<td width="760">
		<table border="0" cellspacing="0" cellpadding="3" bgcolor="#e4e4e4" width="760" class="contents">
		<tr>
			<td width="50%">������᡼������</td>
			<td width="50%" align="right">
<?
if ($status != $g_job_status['��λ']) {
	if ($fetch->jb_guide_status <= 1) {
?>
				<a href="javascript:goto('guidemail_update.php')" title="����֤ΰ���᡼�������򹹿����ޤ�">����</a>
<?
	}
}
?>
			</td>
		</tr>
		</table>

		<table border="1" cellspacing="0" cellpadding="2" width="760" class="card" frame="box">
		<tr>
			<th width="200">���֥�������</th>
			<td width="560"><?=htmlspecialchars($fetch->jb_guide_subject)?></td>
		</tr>
		<tr>
			<th>���п�̾��</th>
			<td><?=htmlspecialchars($fetch->jb_guide_from_name)?></td>
		</tr>
		<tr>
			<th>��ʸ</th>
			<td><pre><?=htmlspecialchars($fetch->jb_guide_content)?></pre></td>
		</tr>
		<tr>
			<th>������</th>
			<td><?=format_datetime_j($fetch->jb_guide_send_date)?></td>
		</tr>
		<tr>
			<th>Ʊ����</th>
			<td><?=str_replace(' ', '<br>', htmlspecialchars($fetch->jb_guide_add_mail))?></td>
		</tr>
		<tr>
			<th>���ơ�����</th>
			<td><?=decode_mail_status($fetch->jb_guide_status)?>
<?
if ($status != $g_job_status['��λ']) {
	if ($fetch->jb_guide_status == 1) {
?>
				&nbsp;&nbsp;<input type="button" value="������λ" onclick="set_mail_status('guide', 2)">
<?
	} elseif ($fetch->jb_guide_status != 0) {
?>
				&nbsp;&nbsp;<input type="button" value="��������᤹" onclick="set_mail_status('guide', 1)">
<?
	}
}
?>
			</td>
		</tr>
		</table>
	</td>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
</tr>
</table>

<br>

<table border="0" cellspacing="0" cellpadding="0" width="800" class="main">
<tr>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
	<td width="760">
		<table border="0" cellspacing="0" cellpadding="3" bgcolor="#e4e4e4" width="760" class="contents">
		<tr>
			<td width="50%">�����ϥ᡼������</td>
			<td width="50%" align="right">
<?
if ($status != $g_job_status['��λ']) {
	if ($fetch->jb_start_status <= 1) {
?>
				<a href="javascript:goto('startmail_update.php')" title="����֤γ��ϥ᡼�������򹹿����ޤ�">����</a>
<?
	}
}
?>
			</td>
		</tr>
		</table>

		<table border="1" cellspacing="0" cellpadding="2" width="760" class="card" frame="box">
		<tr>
			<th width="200">���֥�������</th>
			<td width="560"><?=htmlspecialchars($fetch->jb_start_subject)?></td>
		</tr>
		<tr>
			<th>���п�̾��</th>
			<td><?=htmlspecialchars($fetch->jb_start_from_name)?></td>
		</tr>
		<tr>
			<th>��ʸ</th>
			<td><pre><?=htmlspecialchars($fetch->jb_start_content)?></pre></td>
		</tr>
		<tr>
			<th>������</th>
			<td><?=format_datetime_j($fetch->jb_start_send_date)?></td>
		</tr>
		<tr>
			<th>Ʊ����</th>
			<td><?=str_replace(' ', '<br>', htmlspecialchars($fetch->jb_start_add_mail))?></td>
		</tr>
		<tr>
			<th>���ơ�����</th>
			<td><?=decode_mail_status($fetch->jb_start_status)?>
<?
if ($status != $g_job_status['��λ']) {
	if ($fetch->jb_start_status == 1) {
?>
				&nbsp;&nbsp;<input type="button" value="������λ" onclick="set_mail_status('start', 2)">
<?
	} elseif ($fetch->jb_start_status != 0) {
?>
				&nbsp;&nbsp;<input type="button" value="��������᤹" onclick="set_mail_status('start', 1)">
<?
	}
}
?>
			</td>
		</tr>
		</table>
	</td>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
</tr>
</table>

<br>

<table border="0" cellspacing="0" cellpadding="0" width="800" class="main">
<tr>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
	<td width="760">
		<table border="0" cellspacing="0" cellpadding="3" bgcolor="#e4e4e4" width="760" class="contents">
		<tr>
			<td width="50%">����λ�᡼������</td>
			<td width="50%" align="right">
<?
if ($status != $g_job_status['��λ']) {
	if ($fetch->jb_end_status <= 1) {
?>
				<a href="javascript:goto('endmail_update.php')" title="����֤ν�λ�᡼�������򹹿����ޤ�">����</a>
<?
	}
}
?>
			</td>
		</tr>
		</table>

		<table border="1" cellspacing="0" cellpadding="2" width="760" class="card" frame="box">
		<tr>
			<th width="200">���֥�������</th>
			<td width="560"><?=htmlspecialchars($fetch->jb_end_subject)?></td>
		</tr>
		<tr>
			<th>���п�̾��</th>
			<td><?=htmlspecialchars($fetch->jb_end_from_name)?></td>
		</tr>
		<tr>
			<th>��ʸ</th>
			<td><pre><?=htmlspecialchars($fetch->jb_end_content)?></pre></td>
		</tr>
		<tr>
			<th>������</th>
			<td><?=format_datetime_j($fetch->jb_end_send_date)?></td>
		</tr>
		<tr>
			<th>Ʊ����</th>
			<td><?=str_replace(' ', '<br>', htmlspecialchars($fetch->jb_end_add_mail))?></td>
		</tr>
		<tr>
			<th>���ơ�����</th>
			<td><?=decode_mail_status($fetch->jb_end_status)?>
<?
if ($status != $g_job_status['��λ']) {
	if ($fetch->jb_end_status == 1) {
?>
				&nbsp;&nbsp;<input type="button" value="������λ" onclick="set_mail_status('end', 2)">
<?
	} elseif ($fetch->jb_end_status != 0) {
?>
				&nbsp;&nbsp;<input type="button" value="��������᤹" onclick="set_mail_status('end', 1)">
<?
	}
}
?>
			</td>
		</tr>
		</table>
	</td>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
</tr>
</table>

<br>

<table border="0" cellspacing="0" cellpadding="0" width="800" class="main">
<tr>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
	<td width="760">
		<table border="0" cellspacing="0" cellpadding="3" bgcolor="#e4e4e4" width="760" class="contents">
		<tr>
			<td width="50%">����̾����</td>
			<td width="50%" align="right">
<?
if ($status != $g_job_status['��λ']) {
?>
				<a href="javascript:goto('sign_update.php')" title="�᡼����ղä�����̾������򹹿����ޤ�">����</a>
<?
}
?>
			</td>
		</tr>
		</table>

		<table border="1" cellspacing="0" cellpadding="2" width="760" class="card" frame="box">
		<tr>
			<th width="200">�إå�</th>
			<td width="560"><?=nl2br(format_text($fetch->jb_room_header, '�դ��ʤ�'))?></td>
		</tr>
		<tr>
			<th>�եå�</th>
			<td><?=nl2br(format_text($fetch->jb_room_footer, '�դ��ʤ�'))?></td>
		</tr>
		</table>
	</td>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
</tr>
</table>
<!-- main -->

<!-- ��� -->
<table border="0" cellspacing="0" cellpadding="0" width="800" class="regist">
<tr>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
	<td width="780" align="center"><a href="../index.php" title="�ۡ�������">�ۡ�������</a></td>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
</tr>
</table>
<!-- ��� -->

<? pjt_footer() ?>

</div>
</body>

</html>
