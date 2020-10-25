<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:����־�ǧ����
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/list.php");

//=== ��ǧ����ɽ�� ===
function decode_enquete_status($job_id, $status) {
	global $job_id;

	switch ($status) {
	case 2:
	case 3:
		return "<input type='button' value='��ǧ' onclick='onclick_shounin($job_id)'>";
	default:
		return decode_enq_status($status);
	}
}

//�ᥤ�����
set_global2('project', '�ץ������ȴ���', '�ץ������ȡ�����־�ǧ����', BACK_TOP);

// ���å������Ͽ
get_session_vars($pset, 'approval', 'displine', 'sort_col', 'sort_dir', 'page');
$_SESSION['ss_pjt_list'] = 3;

// �����Ⱦ��
$order_by = order_by(1, 1, 'pj_project_id,jb_job_id', 'pj_project_name,jb_job_id', 'jb_job_id', 'jb_job_name', 'en_enq_type', 'jb_start_date', 'en_end_date', 'jb_send_num', 'en_status');

$sub1 = "SELECT DISTINCT jb_project_id FROM t_job JOIN t_enquete ON jb_enquete_id=en_enquete_id WHERE en_status=2 OR en_status=3";
$sql = "SELECT pj_project_id,pj_project_name,jb_job_id,jb_job_name,jb_start_date,jb_end_date,en_end_date,jb_send_num,en_status,en_enq_type"
		. " FROM t_project"
		. " LEFT JOIN t_job ON pj_project_id=jb_project_id"
		. " LEFT JOIN t_enquete ON jb_enquete_id=en_enquete_id"
		. " WHERE jb_project_id IN ($sub1) $order_by";
$result = db_exec($sql);
$nrow = pg_numrows($result);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function sort_list(sort, dir) {
	var f = document.form1;
	f.sort_col.value = sort;
	f.sort_dir.value = dir;
	f.submit();
}
function onclick_project(project_id) {
	var win;
	win = window.open("../project_info.php?project_id=" + project_id, "project_info", "resizable=yes,width=600,height=400");
	win.focus();
}
function onclick_shounin(job_id) {
	location.href = "edit.php?job_id=" + job_id;
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<form method="post" name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">��̤��ǧ�Υ���ְ���</td>
		<td class="lb">
			<input type="button" value="����롡" onclick="location.href='../../menu.php'">
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="page" value=0>
<input type="hidden" name="pset" value=1>
</form>


<table <?=LIST_TABLE?> width="100%" class="small">
	<tr class="tch">
<?
		sort_header(1, '�̎ߎێ��ގ�����ID');
		sort_header(2, '�̎ߎێ��ގ�����̾');
		sort_header(0, '��ǧ');
		sort_header(3, '���ގ��̎�ID');
		sort_header(4, '���ގ��̎�̾');
		sort_header(5, '����');
		sort_header(6, '��������');
		sort_header(7, '���ڤ�');
		sort_header(8, '����ȯ����');
		sort_header(9, '��ǧ');
?>
	</tr>
<?
$same_project = new join_same_item;
$same_project->prepare($result, 'pj_project_id');

for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$row_count = $same_project->get_row_count();
?>
	<tr class="tc<?=$i % 2?>">
<?
	if ($row_count > 0) {
		$project_id = $fetch->pj_project_id;
?>
		<td rowspan=<?=$row_count?> class="tc0" align="center"><a href="JavaScript:onclick_project(<?=$project_id?>)" title="�ץ������Ⱦ����ɽ�����ޤ�"><?=$project_id?></a></td>
		<td rowspan=<?=$row_count?> class="tc0"><a href="../job/job_list.php?project_id=<?=$project_id?>" title="�ץ��������⥸��ְ�����ɽ�����ޤ�"><?=htmlspecialchars($fetch->pj_project_name)?></a></td>
		<td rowspan=<?=$row_count?> class="tc0" align="center"><input type="button" value="��ǧ" onclick="location.href='edit.php?project_id=<?=$project_id?>'"></td>
<?
	}

	if ($fetch->jb_job_id == null) {
?>
		<td><br></td>
		<td><br></td>
		<td><br></td>
		<td><br></td>
		<td><br></td>
		<td><br></td>
		<td><br></td>
		<td><br></td>
		<td><br></td>
<?
	} else {
		$job_id = $fetch->jb_job_id;
?>
		<td align="center"><a href="../job/jb_contents.php?job_id=<?=$job_id?>" title="��������Ƥ�ɽ�����ѹ����ޤ�"><?=$job_id?></a></td>
		<td><?=htmlspecialchars($fetch->jb_job_name)?></td>
		<td align="center"><?=decode_enq_type2($fetch->en_enq_type)?></td>
		<td align="center"><?=format_date($fetch->jb_start_date)?>��<?=format_date($fetch->jb_end_date)?></td>
		<td align="center"><?=format_date($fetch->en_end_date)?></td>
		<td align="right"><?=number_format($fetch->jb_send_num)?></td>
		<td align="center"><?=decode_enquete_status($fetch->jb_job_id, $fetch->en_status)?></td>
<?
	}
?>
	</tr>
<?
}
?>
</table>

<? page_footer() ?>
</body>
</html>
