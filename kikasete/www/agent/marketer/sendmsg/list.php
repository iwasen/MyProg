<?
/******************************************************
' System :�������ơ�net������������ѥڡ���
' Content:��å���������������α��������
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
include("$inc/format.php");
include("$inc/select.php");
include("$inc/list.php");

//����ɽ��
function decode_status($code) {
	if ($code == 0) {
		return 'ͭ��';
	} else {
		return '<font color="red">̵��</font>';
	}
}

//�ᥤ�����

set_global('�ޡ��������ؤΥ�å���������������α��������', BACK_TOP, 1);

// ɽ���Կ�����ղ�
$limit = disp_limit(20);

if ($del != '1')
	$where = "AND co_status=0";

$order_by = order_by(1, 1, 'co_regist_date', 'co_title', 'mr_name1_kana||mr_name2_kana');
$sql = "SELECT co_seq_no,co_status,co_regist_date,co_title,mr_name1,mr_name2,mr_name1_kana||mr_name2_kana,mr_mail_addr,mr_kinmu_name"
	." FROM t_communication LEFT JOIN t_marketer ON co_marketer_id=mr_marketer_id"
	. " WHERE co_agent_id=$agent_id AND co_direction=2 AND mr_status=0 $where $order_by $limit";
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
//-->
</script>
<? menu_script() ?>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<form name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="95%">
	<tr>
		<td colspan=2>��<?=$g_title?></td>
		<td class="lb">
			<input type="button" value=" ������Ͽ " onclick="location.href='new.php'">
		</td>
	</tr>
	<tr>
		<td colspan=2 class="lc"><nobr>ɽ���Կ�<select name="displine" onchange="submit()"><?select_displine($displine)?></select>
			<input type="button" value="����" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>>
			<input type="button" value="����"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>></nobr>
		</td>
		<td align="right" class="lc"><nobr><input type="checkbox" name="del" <?=value_checked('1', $del)?> onclick="submit()">̵����ɽ��</nobr></td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="page" value=0>
<input type="hidden" name="pset" value=1>
</form>
<table <?=LIST_TABLE?> width=95% class="small">
	<tr class="tch">
<?
		sort_header(1, '��������');
		sort_header(2, '�����ȥ�');
		sort_header(3, '������');
		sort_header(0, '����');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=format_datetime($fetch->co_regist_date)?></td>
		<td align="left"><a href="edit.php?co_no=<?=$fetch->co_seq_no?>" title="�����᡼������Ƥ�ɽ���������ѹ����ޤ�"><?=htmlspecialchars($fetch->co_title)?></td>
		<td align="left"><a href="mailto:<?=$fetch->mr_mail_addr?>" title="�ޡ��������˥᡼����������ޤ�"><?=htmlspecialchars("$fetch->mr_name1 $fetch->mr_name2")?>��<?=htmlspecialchars($fetch->mr_kinmu_name)?>��</a></td>
		<td align="center"><?=decode_status($fetch->co_status)?></td>
	</tr>
<?
}
?>
</table>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
