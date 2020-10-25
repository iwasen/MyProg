<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�������᡼���ۿ�����
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
include("$inc/list.php");

//�ᥤ�����
set_global('monitor', '��˥�������', '�������᡼���ۿ�', BACK_TOP);

// ���å������Ͽ
get_session_vars($pset, 'birthday_mail', 'displine', 'sort_col', 'sort_dir', 'page');

// �����Ⱦ��
$order_by = order_by(1, 0, 'bm_priority', 'bm_sex', 'bm_age_from', 'bm_subject', 'bm_void_flag');

// ɽ���Կ����
$limit = disp_limit();

$sql = "SELECT bm_seq_no, bm_priority, bm_sex, bm_age_from, bm_age_to, bm_subject, bm_void_flag"
		. " FROM t_birthday_mail"
		. " $order_by $limit";
$result = db_exec($sql);
$nrow = pg_numrows($result);

// ͭ��/̵��
function decode_void_flag($code) {
	switch ($code) {
	case 1:
		return 'ͭ��';
	case 2:
		return '̵��';
	}
}

// ǯ���ϰ�
function get_age_range($from, $to) {
	if($from && $to)
		return $from . '�͡�' . $to . '��';
	else {
		if ($from)
			return $from . '�Ͱʾ�';
		elseif ($to)
			return $to . '�Ͱʲ�';
		else
			return '����ʤ�';
	}
}
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
		<td class="lt">���������᡼�����</td>
		<td class="lb">
			<input type="button" value="�����ɲ�" onclick="location.href='new.php'">
			<input type="button" value="����롡" onclick="location.href='<?=$top?>/menu.php'">
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
		sort_header(1, 'ͥ����');
		sort_header(2, '����');
		sort_header(3, 'ǯ���ϰ�');
		sort_header(4, '��̾');
		sort_header(5, 'ͭ��');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><a href="edit.php?seq_no=<?=$fetch->bm_seq_no?>" title="�������᡼����ѹ����ޤ�"><?=$fetch->bm_priority?></a></td>
		<td align="center"><?=decode_sex($fetch->bm_sex)?></td>
		<td align="center"><?=get_age_range($fetch->bm_age_from, $fetch->bm_age_to)?></td>
		<td><?=htmlspecialchars($fetch->bm_subject)?></td>
		<td align="center"><?=decode_void_flag($fetch->bm_void_flag)?></td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
