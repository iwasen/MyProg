<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�����ͥ��󥱡��ȴ���
'******************************************************/

$top = '../..';
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
include("$inc/decode.php");

//�ᥤ�����
set_global('monitor', '��˥�������', '�����ͥ��󥱡��ȴ���', BACK_TOP);

// �����Ⱦ��
$order_by = order_by(1, 0, 'mn_monitor_id', 'mn_sex', 'mn_age', 'mn_mikikon', 'mn_shokugyou_cd', 'mn_jitaku_area', 'ep_handle_name', 'ep_episode');

// ɽ���Կ����
$limit = disp_limit();

// ���󥱡��ȳ�����������
$sql = "SELECT en_start_date FROM t_enquete JOIN t_free_enquete ON en_enquete_id=fe_enquete_id WHERE fe_seq_no=$seq_no";
$start_date = "'" . format_date(db_fetch1($sql)) . "'";

$sql = "SELECT ep_episode,ep_handle_name,mn_monitor_id,mn_sex,DATE_PART('Y', AGE($start_date,mn_birthday)) AS mn_age,mn_mikikon,sg_shokugyou_name,ar_area_name"
		. " FROM t_episode"
		. " JOIN t_monitor ON mn_monitor_id=ep_monitor_id"
		. " LEFT JOIN m_shokugyou ON sg_shokugyou_cd=mn_shokugyou_cd"
		. " LEFT JOIN m_area ON ar_area_cd=mn_jitaku_area"
		. " WHERE ep_seq_no=$seq_no"
		. " $order_by $limit";
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
		<td class="lt">�������ͥ��󥱡��ȥ��ԥ�����</td>
		<td class="lb">
			<input type="button" value="CSV����" onclick="location.href='episode_csv.php?seq_no=<?=$seq_no?>'">
			<input type="button" value="����롡" onclick="location.href='list.php'">
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

<table <?=LIST_TABLE?> width="100%" class="small">
	<tr class="tch">
<?
		sort_header(1, '�ӎƎ���ID');
		sort_header(2, '����');
		sort_header(3, 'ǯ��');
		sort_header(4, '̤����');
		sort_header(5, '����');
		sort_header(6, '�ｻ�ϰ�');
		sort_header(7, '�ϥ�ɥ�̾');
		sort_header(8, '����');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=$fetch->mn_monitor_id?></td>
		<td align="center"><?=decode_sex($fetch->mn_sex)?></td>
		<td align="right"><?=$fetch->mn_age?></td>
		<td align="center"><?=decode_mikikon($fetch->mn_mikikon)?></td>
		<td><?=htmlspecialchars($fetch->sg_shokugyou_name)?></td>
		<td><?=htmlspecialchars($fetch->ar_area_name)?></td>
		<td class="small"><?=htmlspecialchars($fetch->ep_handle_name)?></td>
		<td class="small"><?=nl2br(htmlspecialchars($fetch->ep_episode))?></td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
