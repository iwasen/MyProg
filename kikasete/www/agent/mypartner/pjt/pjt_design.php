<?
/******************************************************
' System :�������ơ�net��̳�ɡ�������������ѥڡ���
' Content:My�ѡ��ȥʡ�����ļ��߷׾���
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/format.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_partner.php");
include("$inc/icp_db.php");
include("$inc/icp_func.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// �ݥ������Ϳ�����ߥ�
function decode_point_timing($code) {
	switch ($code) {
	case 1:
		return '���';
	case 2:
		return '����';
	default:
		return '̤����';
	}
}

// �ݥ���ȴ��ܥ롼��
function decode_point_rule($code) {
	switch ($code) {
	case 1:
		return 'ȯ������';
	case 2:
		return '��ȯ��';
	case 3:
		return '���';
	default:
		return '̤����';
	}
}

//�ᥤ�����
set_global2('mypartner', 'My�ѡ��ȥʡ�����', 'My�ѡ��ȥʡ���ļ��߷׾���', BACK_TOP);

// ���������
set_return_page('select_monitor.php', "pjt_id=$pjt_id");
set_return_page('select_update.php', "pjt_id=$pjt_id");
set_return_page('r_job.php', "pjt_id=$pjt_id");
set_return_page('r_job_edit.php', "pjt_id=$pjt_id");
set_return_page('r_job_update.php', "pjt_id=$pjt_id");
set_return_page('edit.php', "pjt_id=$pjt_id");
set_return_page('update.php', "pjt_id=$pjt_id");
set_return_page('index.php', "pjt_id=$pjt_id");

$myp = new my_partner_class;
$myp->read_db($pjt_id);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<? menu_script() ?>
<script type="text/javascript">
<!--
function to_room() {
	window.open("icp_room.php?pjt_id=<?=$pjt_id?>", "room");
}
function delete_job() {
	if (confirm("���β�ļ��������ޤ�����"))
		location.href = "pjt_delete.php?pjt_id=<?=$pjt_id?>";
}
//-->
</script>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<form method="post" name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">��<?=htmlspecialchars($myp->room_name)?></td>
		<td class="lb">
			<input type="button" value="����롡" onclick="location.href='pjt_list.php'">
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" value="1">
<input type="hidden" name="sort_dir" value="1">
<input type="hidden" name="page" value=0>
<input type="hidden" name="pset" value=1>
</form>

<form method="post" name="form1" action="update.php" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="<?=width('80%')?>">
	<tr>
		<td class="m0" width="25%">���ꥯ�롼��</td>
		<td class="m0" align="right" width="75%">
<?
if ($myp->recruit_flag != 7) {
?>
			<input type="button" value="��Ͽ" onclick="location.href='r_job_edit.php?pjt_id=<?=$pjt_id?>'">
<?
}
?>
			<input type="button" value="�ܺ�ɽ��" onclick="location.href='r_job.php?pjt_id=<?=$pjt_id?>'">
		</td>
	</tr>
	<tr>
		<td class="m1">���ơ�����</td>
		<td class="n1">�»���</td>
	</tr>
	<tr>
		<td class="m1">����</td>
		<td class="n1"><?=format_date($myp->recruit_start)?>��<?=format_date($myp->recruit_end)?></td>
	</tr>
	<tr>
		<td class="m1">ȯ����</td>
		<td class="n1"><?=number_format($myp->send_num)?></td>
	</tr>
<?
$sql = "SELECT COUNT(*) FROM t_mp_monitor WHERE pmn_pjt_id=$pjt_id";
$reply_count = db_fetch1($sql);
?>
	<tr>
		<td class="m1">�ֿ���</td>
		<td class="n1"><?=number_format($reply_count)?>(<?=format_percent($reply_count, $myp->send_num, 1)?>)
<?
if ($reply_count > 0) {
?>
		<input type="button" value="CSV���������" onclick="location.href='csv_recv.php?enquete_id=<?=$myp->enquete_id?>'">
<?
}
?>
		</td>
	</tr>
<?
if ($myp->select_sql != '') {
	$sql = "SELECT COUNT(*) FROM t_mp_monitor WHERE $myp->select_sql";
	$sel_count = db_fetch1($sql);
} else
	$sel_count = 0;
?>
	<tr>
		<td class="m1">������</td>
		<td class="n1"><?=$sel_count?>(<?=format_percent($sel_count, $myp->send_num, 1)?>)
		<input type="button" value="����" onclick="location.href='select_monitor.php?pjt_id=<?=$pjt_id?>'">
<?
if ($reply_count > 0 && $myp->select_sql != '') {
?>
		<input type="button" value="CSV���������" onclick="location.href='csv_sel.php?pjt_id=<?=$pjt_id?>'">
<?
}
?>
		</td>
	</tr>
	<tr>
		<td class="m0">����ļ�</td>
		<td class="m0" align="right">
			<input type="button" value="�ܺ�ɽ��" onclick="location.href='pjt_job.php?pjt_id=<?=$pjt_id?>'">
<?
if ($myp->icp_job_id) {
?>
			<input type="button" value="��ļ���" onclick="to_room()">
<?
}
?>
		</td>
	</tr>
	<tr>
		<td class="m1">���ơ�����</td>
		<td class="n1">����Ѥ�</td>
	</tr>
	<tr>
		<td class="m1">����</td>
		<td class="n1"><?=format_date($myp->room_start)?>��<?=format_date($myp->room_end)?></td>
	</tr>
	<tr>
		<td class="m1">���С���</td>
		<td class="n1"><?=number_format($myp->member_num)?></td>
	</tr>
	<tr>
		<td class="m1">��ļ������᡼�륢�ɥ쥹</td>
		<td class="n1"><?=$myp->icp_job_id ? htmlspecialchars(get_icp_room_addr($myp->icp_job_id)) : '��'?></td>
	</tr>
	<tr>
		<td class="m1">ȯ��Ͽ</td>
		<td class="n1">
<?
if ($myp->status < MPS_ROOM_JISSHI) {
?>
			��
<?
} else {
?>
			<input type="button" value="ȯ��Ͽ���������" onclick="location.href='csv_log.php?job_id=<?=$myp->icp_job_id?>'">
<?
}
?>
		</td>
	</tr>
<?
if (SERVICE == 'admin') {
?>
	<tr>
		<td class="m0">���ݥ������Ϳ</td>
		<td class="m0" align="right">
<?
	if ($myp->point_timing == 0) {
?>
			<input type="button" value="��Ͽ" onclick="location.href='../point/edit.php?pjt_id=<?=$pjt_id?>'">
<?
	}
?>
			<input type="button" value="�ܺ�ɽ��" onclick="location.href='../point/index.php?pjt_id=<?=$pjt_id?>'">
		</td>
	</tr>
	<tr>
		<td class="m1">���ơ�����</td>
		<td class="n1">����Ѥ�</td>
	</tr>
	<tr>
		<td class="m1">��Ϳ����</td>
		<td class="n1"><?=decode_point_timing($myp->point_timing)?></td>
	</tr>
	<tr>
		<td class="m1">���ܥ롼��</td>
		<td class="n1"><?=decode_point_rule($myp->point_rule)?></td>
	</tr>
	<tr>
		<td class="m1">�ݥ���ȿ�</td>
		<td class="n1"><?=number_format($myp->point_unit)?>�ݥ����</td>
	</tr>
	<tr>
		<td class="m1">�ܡ��ʥ�����</td>
		<td class="n1"><?=$myp->point_bonus == 't' ? 'ͭ��' : '̵��'?></td>
	</tr>
<?
}
?>
	<tr>
		<td align="center" colspan=2>
			<br>
<?
if (SERVICE == 'admin' || $myp->status < MPS_REC_SHINSEI) {
?>
			<input type="button" value="�������" onclick="delete_job()">
<?
}
?>
			<input type="button" value="����롡" onclick="location.href='pjt_list.php'">
		</td>
	</tr>
</table>
</form>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
