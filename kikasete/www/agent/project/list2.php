<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�ץ������ȿʹԴ����ꥹ��ɽ��
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");
include("$inc/format.php");
include("$inc/list.php");

//=== ������� ===
function select_client($selected) {
	echo "<option value=''>- ����ʤ� -</option>\n";

	$sql = "SELECT cl_client_id,cl_name FROM m_client ORDER BY cl_client_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->cl_client_id, $selected), '>', htmlspecialchars($fetch->cl_name), '</option>', "\n";
	}
}

//=== ���ô�������� ===
function select_tantousha($selected, $client_id) {
	echo "<option value=''>- ����ʤ� -</option>\n";

	if ($client_id != '')
		$where = "WHERE ts_client_id=$client_id";

	$sql = "SELECT ts_tantousha_id,ts_name1,ts_name2 FROM m_tantousha $where ORDER BY ts_tantousha_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->ts_tantousha_id, $selected), '>', htmlspecialchars("$fetch->ts_name1 $fetch->ts_name2"), '</option>', "\n";
	}
}

//=== ȯ��������� ===
function select_send_date($send_date) {
	echo "<option value=''>- ����ʤ� -</option>\n";

	$y = (int)Date('Y');
	$m = (int)Date('m');

	do {
		$ym = sprintf('%04d%02d', $y, $m);
		echo '<option ', value_selected($ym, $send_date), ">$y/$m</option>\n";
		$m--;
		if ($m == 0) {
			$m = 12;
			$y--;
		}
	} while ($ym >= "200201");
}

//=== ȯ������ɽ�� ===
function decode_send_status($status) {
	switch ($status) {
	case '0':
		return '̤����';
	case '1':
		return '<nobr>����<wbr>�Ե�</nobr>';
	case '2':
		return '������';
	case '9':
		return '<nobr>����<wbr>��λ</nobr>';
	}
	return '-';
}

//=== ��ǧ����ɽ�� ===
function decode_enquete_status($status, $job_id) {
	global $job_id;

	switch ($status) {
	case '0':
	case '1':
		return '<font color="red">̤</font>';
	case '2':
	case '3':
		return '<font color="green">��</font>';
	default:
		return '<a href="job/status/index.php?job_id=' . $job_id . '" title="̤��ǧ���֤��ѹ����ޤ�">��</a>';
	}
}

//=== �᡼�����̾���� ===
function decode_mail_type($mail_type) {
	switch ($mail_type) {
	case '1':
		return '�᡼��';
	case '2':
		return 'Web';
	}
	return '����';
}

//����ɽ��
function format_date2($from, $to, $def = '̤����') {
	if ($from && $to)
		return date('Y/m/d', sql_time($from)).'��'.date('m/d', sql_time($to));
	else
		return $def;
}

//�ᥤ�����
set_global2('project', '�ץ������ȴ���', '�ץ������ȿʹԾ���', BACK_TOP);

// ���å������Ͽ
get_session_vars($pset, 'pjt_go', 'client_id', 'tantousha_id', 'send_date', 'displine', 'sort_col', 'sort_dir', 'page');
$_SESSION['ss_pjt_list'] = 2;

if ($send_date != '')
	and_join($where, "pj_project_id IN (SELECT DISTINCT jb_project_id FROM t_job WHERE TO_CHAR(jb_start_date,'YYYYMM')='$send_date')");

if ($tantousha_id != '')
	and_join($where, "pj_tantousha_id='$tantousha_id'");

If ($client_id != '') {
	$from .= "LEFT JOIN m_tantousha ON pj_tantousha_id=ts_tantousha_id";
	and_join($where, "ts_client_id=$client_id");
}

if ($where)
	$where = "WHERE $where";

// �����Ⱦ��
$order_by = order_by(1, 1, 'pj_project_id,jb_job_id', 'pj_project_name,jb_job_id', 'jb_job_id', 'pj_project_id,COALESCE(jb_cp_job_id,jb_job_id),jb_job_id', 'jb_job_name', 'COALESCE(jb_point_jushin;0)', 'COALESCE(en_point;0)', 'jb_start_date', 'en_end_date', 'COALESCE(jb_send_num;0)', 'jb_real_send', 'jb_enq_count', 'COALESCE(cc_count;0)', 'jb_send_status', 'en_status');
$order_by = str_replace(';', ',', $order_by);

// ɽ���Կ����
$limit = disp_limit();

$sub1 = "SELECT cc_job_id,SUM(cc_click_num) AS cc_count FROM t_click_counter GROUP BY cc_job_id";

$sql = "SELECT pj_project_id,pj_project_name,jb_job_id,jb_job_name,jb_start_date,jb_end_date,en_end_date,jb_send_num,jb_point_jushin,jb_send_status,jb_real_send,jb_enq_count,jb_cp_job_id,cc_count,en_status,en_point,EXISTS (SELECT * FROM t_job AS jb2 WHERE jb1.jb_job_id=jb2.jb_cp_job_id) AS jb_parent"
		. " FROM t_project"
		. " LEFT JOIN t_job AS jb1 ON pj_project_id=jb_project_id"
		. " LEFT JOIN t_enquete ON jb_enquete_id=en_enquete_id"
		. " LEFT JOIN ($sub1) AS sub1 ON jb_job_id=CC_job_id $from"
		. " $where $order_by $limit";
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
function OnChange_client_id() {
	document.form1.tantousha_id.value = '';
	document.form1.submit();
}
function onclick_project(project_id) {
	var win;
	win = window.open("project_info.php?project_id=" + project_id, "project_info", "resizable=yes,width=600,height=400");
	win.focus();
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<form method="post" name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">���ץ������ȡ�����ְ���</td>
		<td class="lb">
			<input type="button" value="����롡" onclick="location.href='../menu.php'">
		</td>
	</tr>
	<tr>
		<td colspan=2 class="lc">
			<nobr>ɽ���Կ�<select name="displine" onchange="submit()"><?select_displine($displine)?></select><input type="button" value="����" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>><input type="button" value="����"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>>&nbsp</nobr>
			<nobr>���<select name="client_id" onchange="OnChange_client_id()"><? select_client($client_id) ?></select>&nbsp</nobr>
			<nobr>ô����<select name="tantousha_id" onchange="submit()"><? select_tantousha($tantousha_id, $client_id) ?></select>&nbsp</nobr>
			<nobr>ȯ����<select name="send_date" onchange="submit()"><? select_send_date($send_date) ?></select>&nbsp</nobr>
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="page" value=0>
<input type="hidden" name="pset" value=1>
</form>


<table <?=LIST_TABLE?> width="100%" class="small2">
	<tr class="tch">
<?
sort_header(1, '�̎ߎێ��ގ�����ID');
sort_header(2, '�̎ߎێ��ގ�����̾');
sort_header(3, '���ގ��̎�ID');
sort_header(4, '�Ǝ��ގ��̎�ID');
sort_header(5, '���ގ��̎�̾');
sort_header(6, '�����Ύߎ��ݎ�');
sort_header(7, '�ֿ��Ύߎ��ݎ�');
sort_header(8, '��������');
sort_header(9, '���ڤ�');
sort_header(10, '����ȯ����');
sort_header(11, 'ȯ����');
sort_header(12, '�ֿ���');
sort_header(13, '���؎�����');
sort_header(14, '<nobr>ȯ��<wbr>����</nobr>');
sort_header(15, '��ǧ');
?>
	</tr>
<?
$same_project = new join_same_item;
$same_project->prepare($result, 'pj_project_id');

$tcn = 2;

for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$job_id = $fetch->jb_job_id;
	$row_count = $same_project->get_row_count();

	// �ƥ���֤ǥ����Ȼ��ο�ʬ��
	$cp_job_id = $fetch->jb_cp_job_id;
	if ($sort_col == 4) {
		if (!$cp_job_id) {
			if ($fetch->jb_parent == 't') {
				$tcn = ($tcn == 0) ? 2 : 0;
				$tc = "tc$tcn";
			} else
				$tc = "tc1";
		}
	} else
		$tc = 'tc' . $i % 2;

	// �ƥ����IDɽ��
	if (!$cp_job_id) {
		if ($fetch->jb_parent == 't')
			$cp_job_id = '�ʿơ�';
		else
			$cp_job_id = '';
	}
?>
	<tr class="<?=$tc?>">
<?
	$project_id = $fetch->pj_project_id;
	if ($row_count > 0) {
?>
		<td rowspan=<?=$row_count?> class="tc0" align="center"><a href="JavaScript:onclick_project(<?=$project_id?>)" title="�ץ������Ⱦ����ɽ�����ޤ�"><?=$project_id?></a></td>
		<td rowspan=<?=$row_count?> class="tc0"><a href="job/job_list.php?project_id=<?=$project_id?>" title="�ץ��������⥸��ְ�����ɽ�����ޤ�"><?=htmlspecialchars($fetch->pj_project_name)?></a></td>
<?
	}

	if (!$job_id) {
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
		<td><br></td>
		<td><br></td>
		<td><br></td>
		<td><br></td>
<?
	} else {
?>
		<td align="center"><a href="job/jb_edit.php?pjt_id=<?=$project_id?>&job_id=<?=$job_id?>" title="����֤���Ͽ���Ƥ򹹿����ޤ�"><?=$job_id?></a></td>
		<td align="center"><?=$cp_job_id?></td>
		<td><a href="job/jb_contents.php?job_id=<?=$job_id?>" title="��������Ƥ�ɽ�����ѹ����ޤ�"><?=htmlspecialchars($fetch->jb_job_name)?></a></td>
		<td align="right"><?=number_format($fetch->jb_point_jushin)?></td>
		<td align="right"><?=number_format($fetch->en_point)?></td>
		<td align="center"><?=format_date2($fetch->jb_start_date, $fetch->jb_end_date)?></td>
		<td align="center"><?=format_date($fetch->en_end_date)?></td>
		<td align="right"><?=number_format($fetch->jb_send_num)?></td>

<?	if ($fetch->jb_real_send) { ?>
		<td align="right"><a href="job/send/index.php?job_id=<?=$fetch->jb_job_id?>" title="ȯ���Ծ����ɽ�����ޤ�"><?=number_format($fetch->jb_real_send)?></a></td>
<?	} else { ?>
			<td align="right"><?=number_format($fetch->jb_real_send)?></td>
<?	} ?>

<?	if ($fetch->jb_enq_count > 0) { ?>
		<td align="right"><a href="job/recv/index.php?job_id=<?=$fetch->jb_job_id?>" title="���󥱡��ȥ쥹�ݥ󥹤�ɽ�����ޤ�"><?=number_format($fetch->jb_enq_count)?></a></td>
<?	} else { ?>
		<td align="right"><?=number_format($fetch->jb_enq_count)?></td>
<?	} ?>

<?	if ($fetch->cc_count > 0) { ?>
		<td align="right"><a href="job/click/index.php?job_id=<?=$fetch->jb_job_id?>" title="����å����Ϥ�ɽ�����ޤ�"><?=number_format($fetch->cc_count)?></a></td>
<?	} else { ?>
		<td align="right"><?=number_format($fetch->cc_count)?></td>
<?	} ?>

		<td align="center"><?=decode_send_status($fetch->jb_send_status)?></td>
		<td align="center"><?=decode_enquete_status($fetch->en_status, $job_id)?></td>
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
