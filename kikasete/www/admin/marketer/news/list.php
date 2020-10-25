<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�᡼��˥塼���ۿ�
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
include("$inc/select.php");
include("$inc/format.php");
include("$inc/list.php");

// ��������
function decode_news_status($code) {
	switch ($code) {
	case 0:
		return '̤����';
	case 1:
		return '������';
	}
	return '����';
}

// �ۿ��оݼԥǥ�����
function decode_target($code) {
	switch ($code) {
	case 2:
		return '����Ͽ�桼��';
	case 3:
		return 'My�ڡ�����Ͽ�桼��';
	case 4:
		return '���ޥ���Ͽ�桼��';
	}
	return '����';
}

// �ۿ��о�����
function select_target($sel) {
	echo "<option ", value_selected('', $sel), ">- ����ʤ� -</option>";
	echo "<option ", value_selected('2', $sel), ">����Ͽ�桼��</option>";
	echo "<option ", value_selected('3', $sel), ">My�ڡ�����Ͽ�桼��</option>";
	echo "<option ", value_selected('4', $sel), ">���ޥ���Ͽ�桼��</option>";
}

//�ᥤ�����
set_global('marketer', '�ޡ�����������', '�᡼��˥塼���ۿ�', BACK_TOP);

// ���å������Ͽ
get_session_vars($pset, 'marketer_news', 'displine', 'sort_col', 'sort_dir', 'page', 'target');

// ɽ�����
if ($target == '')
	$where = "WHERE ns_target IN (2,3,4)";
else
	$where = "WHERE ns_target=$target";

// �����Ⱦ��
$order_by = order_by(1, 1, 'ns_news_id', 'ns_subject', 'ns_target', 'ns_send_date', 'ns_status', 'ns_send_num');

// ɽ���Կ����
$limit = disp_limit();

$sql = "SELECT ns_news_id,ns_send_date,ns_target,ns_subject,ns_status,ns_send_num"
		. " FROM t_mail_news $where $order_by $limit";
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
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">���᡼��˥塼������</td>
		<td class="lb">
			<input type="button" value="������Ͽ" onclick="location.href='new.php'">
			<input type="button" value="����롡" onclick="location.href='<?=$top?>/menu.php'">
		</td>
	</tr>
	<tr>
		<td colspan=2 class="lc">
			<nobr>ɽ���Կ�<select name="displine" onchange="submit()"><?select_displine($displine)?></select><input type="button" value="����" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>><input type="button" value="����"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>></nobr>&nbsp;
			<nobr>�ۿ��о�<select name="target" onchange="submit()"><? select_target($target) ?></select>
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
		sort_header(1, '�ֹ�');
		sort_header(2, '��̾');
		sort_header(3, '�ۿ��о�');
		sort_header(4, '������');
		sort_header(5, '����');
		sort_header(6, '������');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=$fetch->ns_news_id?></td>
		<td><a href="edit.php?news_id=<?=$fetch->ns_news_id?>" title="�᡼��˥塼�����Ƥ�ɽ�����ѹ����ޤ�"><?=htmlspecialchars($fetch->ns_subject)?></a></td>
		<td align="center"><?=decode_target($fetch->ns_target)?></td>
		<td align="center"><?=format_datetime($fetch->ns_send_date)?></td>
		<td align="center"><?=decode_news_status($fetch->ns_status)?></td>
		<td align="right"><?=number_format($fetch->ns_send_num)?></td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
