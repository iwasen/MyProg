<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:My�ѡ��ȥʡ�����ǧ����
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/format.php");
include("$inc/list.php");
include("$inc/my_partner.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

//�ᥤ�����
set_global('mypartner', 'My�ѡ��ȥʡ�����', 'My�ѡ��ȥʡ���ǧ����', BACK_TOP);

// ���������
set_return_page('r_job.php');
set_return_page('pjt_detail.php');
set_return_page('select_monitor.php');
set_return_page('select_update.php');
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function sort_list(sort, dir, index) {
	var f = document.form1;
	f["sort_col[" + index + "]"].value = sort;
	f["sort_dir[" + index + "]"].value = dir;
	f.submit();
}
function onclick_project(project_id) {
	var win;
	win = window.open("../project_info.php?project_id=" + project_id, "project_info", "resizable=yes,width=600,height=400");
	win.focus();
}
function onclick_job(pjt_id, status) {
	var check = "";
	if (status == <?=MPS_REC_SHINSEI?>) {
		if (confirm("���Σͣ��ѡ��ȥʡ���ֻ�̳�ɥ����å���פȤ��ޤ�����"))
			check = "&check=1";
	}
	location.href = "../pjt/r_job.php?pjt_id=" + pjt_id + check;
}

function onclick_shounin(pjt_id) {
	var f = document.form2;
	if (showModalDialog("shounin.php?pjt_id=" + pjt_id,f,"status:off;help:off;dialogWidth:350px;dialogHeight:150px")) {
		f.pjt_id.value = pjt_id;
		f.flag.value = 1;
		f.submit();
	}
}
function onclick_return(pjt_id) {
	var f = document.form2;
	if (confirm("�ץ�������ID=" + pjt_id + "�Υꥯ�롼�ȥ���֤򺹤��ᤷ�ޤ���������Ǥ�����")) {
		f.pjt_id.value = pjt_id;
		f.flag.value = 2;
		f.submit();
	}
}
//-->
</script>
</head>
<body>

<? page_header() ?>

<form method="post" name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">��̤��ǧ�Υꥯ�롼�ȥ���ְ���</td>
		<td class="lb">
			<input type="button" value="����롡" onclick="location.href='../../menu.php'">
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col[0]" <?=value($sort_col[0])?>>
<input type="hidden" name="sort_dir[0]" <?=value($sort_dir[0])?>>
<input type="hidden" name="pset" value=1>

<?
// �����Ⱦ��
$order_by = order_by2(1, 0, 0, 'ppj_pjt_id', 'ppj_room_name', 'mr_name1_kana,mr_name2_kana', 'ppj_recruit_start', 'ppj_recruit_end', 'ppj_send_num');

$sql = "SELECT ppj_pjt_id,ppj_room_name,mr_name1,mr_name2,ppj_recruit_start,ppj_recruit_end,ppj_send_num,ppj_status,mr_marketer_id"
		. " FROM t_mp_pjt"
		. " JOIN t_marketer ON mr_marketer_id=ppj_marketer_id"
		. " WHERE ppj_status=" . MPS_REC_SHINSEI . " OR ppj_status=" . MPS_REC_CHECK
		. " $order_by";
$result = db_exec($sql);
$nrow = pg_numrows($result);
?>
<table border=1 cellspacing=0 cellpadding=1 bordercolorlight="#f0f0f0" frame="box" width="100%" class="small">
	<tr class="tch">
<?
sort_header2(1, 'ID', 0);
sort_header2(2, '���̾', 0);
sort_header2(3, '�ޡ�������̾', 0);
sort_header2(4, '������', 0);
sort_header2(5, '���ڤ�', 0);
sort_header2(6, '����ȯ����', 0);
sort_header2(0, '��ǧ', 0);
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$pjt_id = $fetch->ppj_pjt_id;
?>
	<tr class="tc<?=$i?>">
		<td align="center"><a href="../pjt/pjt_detail.php?pjt_id=<?=$pjt_id?>" title="�ץ��������⥸��ְ�����ɽ�����ޤ�"><?=$fetch->ppj_pjt_id?></a></td>
		<td align="center"><a href="javascript:onclick_job(<?=$pjt_id?>,<?=$fetch->ppj_status?>)" title="��������Ƥ�ɽ�����ѹ����ޤ�"><?=htmlspecialchars($fetch->ppj_room_name)?></a></td>
		<td align="center"><a href="../pjt/marketer_info.php?marketer_id=<?=$fetch->mr_marketer_id?>" title="�ޡ��������ξ����ɽ�����ޤ�" target="_blank"><?=htmlspecialchars("$fetch->mr_name1 $fetch->mr_name2")?></a></td>
		<td align="center"><?=format_date($fetch->ppj_recruit_start)?></td>
		<td align="center"><?=format_date($fetch->ppj_recruit_end)?></td>
		<td align="right"><?=number_format($fetch->ppj_send_num)?></td>
		<td align="center">
<?
	if ($fetch->ppj_status == MPS_REC_SHINSEI) {
?>
			��̳�ɥ����å��Ԥ�
<?
	} else {
?>
			<input type="button" value="��ǧ" onclick="onclick_shounin(<?=$pjt_id?>)">
			<input type="button" value="�����᤹" onclick="onclick_return(<?=$pjt_id?>)">
<?
	}
?>
		</td>
	</tr>
<?
}
?>
</table>

<br>
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">���оݼԻ�̳�ɥ����å���Υ���ְ���</td>
	</tr>
</table>
<input type="hidden" name="sort_col[1]" <?=value($sort_col[1])?>>
<input type="hidden" name="sort_dir[1]" <?=value($sort_dir[1])?>>
<?
// �����Ⱦ��
$order_by = order_by2(1, 0, 1, 'ppj_pjt_id', 'ppj_room_name', 'mr_name1_kana,mr_name2_kana', 'ppj_decision_date', 'ppj_room_start', 'ppj_room_end');

$sql = "SELECT ppj_pjt_id,ppj_room_name,mr_name1,mr_name2,ppj_decision_date,ppj_room_start,ppj_room_end"
		. " FROM t_mp_pjt"
		. " JOIN t_marketer ON mr_marketer_id=ppj_marketer_id"
		. " WHERE ppj_status=" . MPS_MEM_CHECK
		. " $order_by";
$result = db_exec($sql);
$nrow = pg_numrows($result);
?>
<table border=1 cellspacing=0 cellpadding=1 bordercolorlight="#f0f0f0" frame="box" width="100%" class="small">
	<tr class="tch">
<?
sort_header2(1, 'ID', 1);
sort_header2(2, '��ļ�̾', 1);
sort_header2(3, '�ޡ�������̾', 1);
sort_header2(4, '������', 1);
sort_header2(5, '������', 1);
sort_header2(6, '��λ��', 1);
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$pjt_id = $fetch->ppj_pjt_id;
?>
	<tr class="tc<?=$i?>">
		<td align="center"><a href="../pjt/pjt_detail.php?pjt_id=<?=$pjt_id?>" title="�ץ��������⥸��ְ�����ɽ�����ޤ�"><?=$fetch->ppj_pjt_id?></a></td>
		<td align="center"><a href="../pjt/select_monitor.php?pjt_id=<?=$pjt_id?>" title="�����������Ԥ��ޤ�"><?=htmlspecialchars($fetch->ppj_room_name)?></a></td>
		<td align="center"><a href="../pjt/marketer_info.php?marketer_id=<?=$fetch->mr_marketer_id?>" title="�ޡ��������ξ����ɽ�����ޤ�" target="_blank"><?=htmlspecialchars("$fetch->mr_name1 $fetch->mr_name2")?></a></td>
		<td align="center"><?=format_date($fetch->ppj_decision_date)?></td>
		<td align="center"><?=format_date($fetch->ppj_room_start)?></td>
		<td align="center"><?=format_date($fetch->ppj_room_end)?></td>
	</tr>
<?
}
?>
</table>
</form>

<form method="post" name="form2" action="update.php">
<input type="hidden" name="pjt_id">
<input type="hidden" name="flag">
<input type="hidden" name="start_date_y">
<input type="hidden" name="start_date_m">
<input type="hidden" name="start_date_d">
<input type="hidden" name="start_date_h">
</form>

<? page_footer() ?>

</body>
</html>
