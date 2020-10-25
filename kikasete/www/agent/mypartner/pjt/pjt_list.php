<?
/******************************************************
' System :�������ơ�net��̳�ɡ�������������ѥڡ���
' Content:My�ѡ��ȥʡ���������ļ��ʹԾ���
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");
include("$inc/list.php");
include("$inc/format.php");
include("$inc/my_partner.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// ��˥������������
function select_mypartner_status($sel) {
	echo "<option ", value_selected('', $sel), ">- ����ʤ� -</option>";
	echo "<option ", value_selected(MPS_REC_JUNBI, $sel), ">�ꥯ�롼�Ƚ�����</option>";
	echo "<option ", value_selected(MPS_REC_KANRYOU, $sel), ">�ꥯ�롼�Ⱥ�����λ</option>";
	echo "<option ", value_selected(MPS_REC_SHINSEI, $sel), ">�ꥯ�롼�ȿ�����</option>";
	echo "<option ", value_selected(MPS_REC_CHECK, $sel), ">�ꥯ�롼�Ȼ�̳�ɥ����å���</option>";
	echo "<option ", value_selected(MPS_REC_SHOUNIN, $sel), ">�ꥯ�롼�Ⱦ�ǧ��</option>";
	echo "<option ", value_selected(MPS_REC_JISSHI, $sel), ">�ꥯ�롼�ȼ»���</option>";
	echo "<option ", value_selected(MPS_MEM_SENTEI, $sel), ">�оݼ�������</option>";
	echo "<option ", value_selected(MPS_MEM_CHECK, $sel), ">�оݼԻ�̳�ɥ����å���</option>";
	echo "<option ", value_selected(MPS_MEM_SHOUNIN, $sel), ">�оݼ����꾵ǧ��</option>";
	echo "<option ", value_selected(MPS_ROOM_JUNBI, $sel), ">��ļ�������</option>";
	echo "<option ", value_selected(MPS_ROOM_JISSHI, $sel), ">��ļ�������</option>";
	echo "<option ", value_selected(MPS_ROOM_END, $sel), ">��ļ���λ</option>";
}

//�ᥤ�����
set_global2('mypartner', 'My�ѡ��ȥʡ�����', 'My�ѡ��ȥʡ�����ļ��ʹԾ���', BACK_TOP);

// ���������
set_return_page('edit.php');
set_return_page('update.php');
set_return_page('index.php');
set_return_page('active.php');
set_return_page('pjt_detail.php');
set_return_page('r_job_edit.php');

// ���å������Ͽ
get_session_vars($pset, 'pjt_list', 'marketer_id', 'kinmu_name', 'status', 'displine', 'sort_col', 'sort_dir', 'page');

if (SERVICE == 'agent')
	and_join($where, "mr_agent_id=$agent_id");

if ($marketer_id != '')
	and_join($where, "ppj_marketer_id=" . sql_number($marketer_id));

If ($mr_kinmu_name != '')
	and_join($where, "mr_kinmu_name=" . sql_char($kinmu_name));

if ($status != '')
	and_join($where, "ppj_status=" . sql_number($status));

if ($where)
	$where = "WHERE $where";

// �����Ⱦ��
$order_by = order_by(1, 1, 'ppj_pjt_id', 'ppj_room_name', 'ppj_room_start', 'ppj_member_num', 'mr_kinmu_name', 'mr_name1_kana,mr_name2_kana', 'ppj_status');

// ɽ���Կ����
$limit = disp_limit();

$sql = "SELECT ppj_pjt_id,ppj_room_name,ppj_recruit_start,ppj_room_end,COALESCE(ppj_icp_member_num,ppj_member_num) AS ppj_member_num,ppj_theme_num,mr_marketer_id,mr_kinmu_name,mr_name1,mr_name2,ppj_status,ppj_point_timing,ppj_icp_member_num"
		. " FROM t_mp_pjt"
		. " JOIN t_marketer ON mr_marketer_id=ppj_marketer_id"
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

function marketer_info(){
	window.open('marketer_info.php','mi','wi')
}
//-->
</script>
<? menu_script() ?>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<form method="post" name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">����ļ�����</td>
		<td class="lb">
<?
if (SERVICE == 'admin') {
?>
			<input type="button" value="�»����ļ�����" onclick="location.href='active.php'">
<?
}
?>
			<input type="button" value="��ļ�������Ͽ" onclick="location.href='pjt_new1.php'">
			<input type="button" value="����롡" onclick="location.href='../../menu.php'">
		</td>
	</tr>
	<tr>
		<td colspan=2 class="lc">
			<nobr>ɽ���Կ�<select name="displine" onchange="submit()"><? select_displine($displine) ?></select><input type="button" value="����" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>><input type="button" value="����"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>>&nbsp</nobr>
			<nobr>�ޡ�������<select name="marketer_id" onchange="submit()"><? select_marketer('- ����ʤ� -', $marketer_id, 3, $agent_id) ?></select>&nbsp</nobr>
			<nobr>���ơ�����<select name="status" onchange="submit()"><? select_mypartner_status($status) ?></select>&nbsp</nobr>
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="page" value=0>
<input type="hidden" name="pset" value=1>
</form>


<table border=1 cellspacing=0 cellpadding=1 bordercolorlight="#f0f0f0" frame="box" width="100%" class="small">
	<tr class="tch">
<?
sort_header(1, 'ID');
sort_header(2, '��ļ�̾');
sort_header(0, '�ܺ�');
sort_header(3, '����');
sort_header(4, '�Ҏݎʎގ���');
sort_header(5, '���̾');
sort_header(6, '�ώ�������̾');
sort_header(7, '���ơ�����');
if (SERVICE == 'admin') {
	sort_header(0, '�ꥯ�롼��');
	sort_header(0, '��ļ�');
	sort_header(0, '�ݥ������Ϳ');
}
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$pjt_id = $fetch->ppj_pjt_id;
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><a href="pjt_new2.php?pjt_id=<?=$pjt_id?>" title="��ļ������Ƥ�ɽ�����ѹ����ޤ�"><?=$pjt_id?></a></td>
		<td><a href="pjt_detail.php?pjt_id=<?=$pjt_id?>" title="��ļ������Ƥ�ɽ�����ѹ����ޤ�"><?=htmlspecialchars($fetch->ppj_room_name)?></a></td>
		<td align="center"><input type="button" value="�ܺ�" onclick="location.href='pjt_design.php?pjt_id=<?=$pjt_id?>'"></td>
		<td align="center"><?=format_date($fetch->ppj_recruit_start)?>��<?=format_date($fetch->ppj_room_end)?></td>
		<td align="right"><?=$fetch->ppj_member_num?>��<?=$fetch->ppj_icp_member_num == 0 ? '��ɸ' : '����'?>��</td>
		<td><?=htmlspecialchars($fetch->mr_kinmu_name)?></td>
		<td><a href="marketer_info.php?marketer_id=<?=$fetch->mr_marketer_id?>" target="_blank" title="�ޡ��������ξ����ɽ�����ޤ�"><?=htmlspecialchars("$fetch->mr_name1 $fetch->mr_name2")?></a></td>
		<td align="center"><?=decode_mypartner_status($fetch->ppj_status)?></td>
<?
	if (SERVICE == 'admin') {
		if ($fetch->ppj_status == MPS_REC_JUNBI) {
?>
		<td align="center"><input type="button" value="��Ͽ" onclick="location.href='r_job_edit.php?pjt_id=<?=$pjt_id?>'"></td>
<?
		} else {
?>
		<td align="center"><a href="r_job.php?pjt_id=<?=$pjt_id?>"><?=decode_recruit_status($fetch->ppj_status)?></a></td>
<?
		}
		if ($fetch->ppj_status < MPS_ROOM_JISSHI) {
?>
		<td align="center">̤</td>
<?
		} else {
?>
		<td align="center"><a href="pjt_job.php?pjt_id=<?=$pjt_id?>"><?=decode_room_status($fetch->ppj_status)?></a></td>
<?
		}
		if (!$fetch->ppj_point_timing) {
?>
		<td align="center"><input type="button" value="��Ͽ" onclick="location.href='../point/edit.php?pjt_id=<?=$pjt_id?>'"></td>
<?
		} else {
?>
		<td align="center"><a href="../point/index.php?pjt_id=<?=$pjt_id?>">����Ѥ�</a></td>
<?
		}
	}
?>
	</tr>
<?
}
?>
</table>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
