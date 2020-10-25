<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:���顼�ȥ᡼��
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
function decode_alert_kind($code) {
	switch ($code) {
	case 1:
		return '��ư����';
	case 2:
		return '��ư����';
	}
return $code;
}

// ��������
function decode_send_status($code) {
	switch ($code) {
	case 0:
		return '̤����';
	case 1:
		return '������';
	}
	return '����';
}

//�ᥤ�����
set_global('agent', '����������ȴ���', '���顼�ȥ᡼��', BACK_TOP);

// ���å������Ͽ
get_session_vars($pset, 'alert_mail', 'displine', 'sort_col', 'sort_dir', 'page');

// �������
$where = "WHERE am_status<>0 OR am_kind=2";

// �����Ⱦ��
$order_by = order_by(1, 1, 'am_seq_no', 'am_subject', 'am_kind', 'am_send_date', 'am_status', 'am_send_num');

// ɽ���Կ����
$limit = disp_limit();

$sql = "SELECT am_seq_no,am_subject,am_send_date,am_kind,am_status,am_send_num"
		. " FROM t_alert_mail $where $order_by $limit";
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
		<td class="lt">�����顼�ȥ᡼��᡼�����</td>
		<td class="lb">
			<input type="button" value="��ư����" onclick="location.href='auto.php'">
			<input type="button" value="��ư����" onclick="location.href='new.php'">
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
		sort_header(1, '�ֹ�');
		sort_header(2, '��̾');
		sort_header(3, '����');
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
		<td align="center"><?=$fetch->am_seq_no?></td>
		<td><a href="edit.php?seq_no=<?=$fetch->am_seq_no?>" title="���顼�ȥ᡼�����Ƥ�ɽ�����ѹ����ޤ�"><?=htmlspecialchars($fetch->am_subject)?></a></td>
		<td align="center"><?=decode_alert_kind($fetch->am_kind)?></td>
		<td align="center"><?=format_datetime($fetch->am_send_date)?></td>
		<td align="center"><?=decode_send_status($fetch->am_status)?></td>
		<td align="right"><?=number_format($fetch->am_send_num)?></td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
