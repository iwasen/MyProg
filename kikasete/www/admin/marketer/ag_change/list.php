<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:������������ѹ�
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/format.php");
include("$inc/select.php");
include("$inc/list.php");

//�ᥤ�����
set_global('marketer', '�ޡ�����������', '������������ѹ�', BACK_TOP);

// ���å������Ͽ
get_session_vars($pset, 'ag_change', 'displine', 'sort_col', 'sort_dir', 'page');

// �����Ⱦ��
$order_by = order_by(1, 0, 'mr_mail_addr', 'mr_name1_kana||mr_name2_kana', 'cur_ag_name1||cur_ag_name2', 'new_ag_name1||new_ag_name2', 'ac_date');

// ɽ���Կ����
$limit = disp_limit();

$sql = "SELECT ac_date,mr_marketer_id,mr_mail_addr,mr_name1,mr_name2,ag_cur.ag_name1 AS cur_ag_name1,ag_cur.ag_name2 AS cur_ag_name2,ag_new.ag_name1 AS new_ag_name1,ag_new.ag_name2 AS new_ag_name2"
		. " FROM ((t_agent_change JOIN t_marketer ON ac_marketer_id=mr_marketer_id)"
		. " LEFT JOIN t_agent AS ag_cur ON mr_agent_id=ag_cur.ag_agent_id)"
		. " LEFT JOIN t_agent AS ag_new ON ac_agent_id=ag_new.ag_agent_id"
		. " WHERE mr_status<>9 $order_by $limit";
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
function onclick_change(marketer_id) {
	if (confirm("���ꤷ���ޡ��������Υ���������Ȥ��ѹ����ޤ���������Ǥ�����"))
		location.href = "update.php?marketer_id=" + marketer_id;
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">��������������ѹ��������</td>
		<td class="lb">
			<input type="button" value="����롡" onclick="location.href='<?=$top?>/menu.php'">
		</td>
	</tr>
	<tr>
		<td colspan=2 class="lc">
			<nobr>ɽ���Կ�<select name="displine" onchange="submit()"><?select_displine($displine)?></select><input type="button" value="����" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>><input type="button" value="����"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>></nobr>
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="page" value=0>
<input type="hidden" name="pset" value=1>
</form>

<table <?=LIST_TABLE?> width="100%">
	<tr class="tch">
<?
		sort_header(1, '�᡼�륢�ɥ쥹');
		sort_header(2, '�ޡ�������̾');
		sort_header(3, '�������������');
		sort_header(4, '�������������');
		sort_header(5, '�׵���');
		sort_header(0, '�ѹ�');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td><a href="<?=$top?>/common/marketer_info.php?marketer_id=<?=$fetch->mr_marketer_id?>" target="_blank" title="�ޡ������������ɽ�����ޤ�"><?=htmlspecialchars($fetch->mr_mail_addr)?></a></td>
		<td><?=htmlspecialchars("$fetch->mr_name1 $fetch->mr_name2")?></td>
		<td><?=htmlspecialchars("$fetch->cur_ag_name1 $fetch->cur_ag_name2")?></td>
		<td><?=htmlspecialchars("$fetch->new_ag_name1 $fetch->new_ag_name2")?></td>
		<td align="center"><?=format_date($fetch->ac_date)?></td>
		<td align="center"><input type="button" value="�ѹ�" onclick="onclick_change(<?=$fetch->mr_marketer_id?>)"></td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
