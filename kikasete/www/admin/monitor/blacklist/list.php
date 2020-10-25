<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�֥�å��ꥹ�ȴ���
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

//�ᥤ�����
set_global('monitor', '��˥�������', '�֥�å��ꥹ�ȴ���', BACK_TOP);

// ���å������Ͽ
get_session_vars($pset, 'blacklist', 'mail_addr', 'blacklist_flg', 'displine', 'sort_col', 'sort_dir', 'page');

// where���
$where = 'mn_blacklist_flg>0 AND mn_status<>9';
if ($mail_addr != '')
	and_join($where, "mn_mail_addr LIKE '%$mail_addr%'");

if ($blacklist_flg != '')
	and_join($where, "mn_blacklist_flg=$blacklist_flg");

$where = "WHERE $where";

// �����Ⱦ��
$order_by = order_by(1, 0, 'mn_mail_addr', 'mn_name1_kana||mn_name2_kana', 'mn_regist_date', 'mn_type', 'mn_blacklist_flg');

// ɽ���Կ����
$limit = disp_limit();

$sql = "SELECT mn_monitor_id,mn_mail_addr,mn_name1,mn_name2,mn_regist_date,mn_type,mn_blacklist_flg,bl_name"
		. " FROM t_monitor LEFT JOIN m_blacklist ON mn_blacklist_flg=bl_blacklist_flg"
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
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">���֥�å��ꥹ����Ͽ��˥�������</td>
		<td class="lb">
			<input type="button" value="���׎؎���" onclick="location.href='sum.php'">
			<input type="button" value="����롡" onclick="location.href='<?=$top?>/menu.php'">
		</td>
	</tr>
	<tr>
		<td colspan=2 class="lc">
			<nobr>ɽ���Կ�<select name="displine" onchange="submit()"><?select_displine($displine)?></select><input type="button" value="����" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>><input type="button" value="����"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>>��</nobr>
			<nobr>�᡼�륢�ɥ쥹<input type="text" name="mail_addr" <?=value($mail_addr)?>>��</nobr>
			<nobr>�ե饰<select name="blacklist_flg"><? select_blacklist('- ����ʤ� -', $blacklist_flg) ?></select>��</nobr>
			<input type="button" value="����" onclick="submit()">
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
		sort_header(2, '̾��');
		sort_header(3, '��Ͽ����');
		sort_header(4, '����');
		sort_header(5, '�ե饰');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td><a href="edit.php?monitor_id=<?=$fetch->mn_monitor_id?>" title="���֤��ѹ��ޤ�����������Ԥ��ޤ�"><?=htmlspecialchars($fetch->mn_mail_addr)?></a></td>
		<td><?=htmlspecialchars("$fetch->mn_name1 $fetch->mn_name2")?></td>
		<td align="center"><?=format_date($fetch->mn_regist_date)?></td>
		<td align="center"><?=decode_monitor_type($fetch->mn_type)?></td>
		<td align="center"><?=htmlspecialchars($fetch->bl_name)?></td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
