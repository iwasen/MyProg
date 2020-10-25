<?
/******************************************************
' System :Eco-footprint �����ڡ���
' Content:���ԥꥹ��
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
//include("$inc/decode.php");
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

// decode�ۿ��о�
function decode_target($code) {
	switch ($code) {
	case 4:
		return '����';
	case 1:
		return '����Ͽ��';
	case 2:
		return '����Ͽ��';
	case 3:
		return '����Ͽ';
	}
	return '����';
}


//�ᥤ�����
set_global('member', '����������', '���ԥꥹ��', BACK_TOP);

// ���å������Ͽ
get_session_vars($pset, 'monitor_news', 'displine', 'target', 'sort_col', 'sort_dir', 'page');

// where���
if ($target != '')
	and_join($where, "am_target=$target");

if ($where != '')
	$where = "WHERE $where";

// �����Ⱦ��
$order_by = order_by(4, 1, 'mb_id', 'mb_name1_kana||mb_name2_kana', 'tk_opinion_cd', 'tk_regist_date');

// ɽ���Կ����
$limit = disp_limit();

$sql = "SELECT mb_seq_no,mb_id,mb_name1,mb_name2,mb_name1_kana,mb_name2_kana,tk_seq_no,tk_opinion_cd,tk_regist_date"
		. " FROM t_taikai LEFT JOIN t_member ON tk_mb_seq_no=mb_seq_no $where $order_by $limit";
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

function open_detail(seq_no) {
	window.open("../../inc/detail.php?seq_no=" + seq_no, "_blank", "width=450,scrollbars=yes,resizable=yes,status=no,menubar=no,toolbar=no");
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
		<td class="lt">�����԰���</td>
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
		sort_header(1, 'ID');
		sort_header(2, '��̾');
		sort_header(3, 'ɾ��');
		sort_header(4, '�������');
		sort_header(0, '���Ͼ���');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="left"><a href="detail.php?seq_no=<?=$fetch->tk_seq_no?>" title="�����ͳ��ɽ�����ޤ�"><?=$fetch->mb_id?></td>
		<td align="left"><a href="javascript:open_detail(<?=$fetch->mb_seq_no?>)" title="�������Ͽ�����ɽ�����ޤ�"><?="$fetch->mb_name1 $fetch->mb_name2"?></a></td>
		<td align="center"><?=$fetch->tk_opinion_cd?></td>
		<td align="center"><?=format_datetime($fetch->tk_regist_date)?></td>
		<td align="center"><a href='<?=$top?>/member/input/detail.php?seq_no=<?=$fetch->mb_seq_no?>'>ɽ��</td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
