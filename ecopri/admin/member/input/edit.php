<?
/******************************************************
' System :Eco-footprint �����ڡ���
' Content:����ǡ������Ͼ�������
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/list.php");
include("$inc/decode.php");
include("$inc/format.php");

// ���ͥ륮��decode
function decode_eng($code) {
	if ($code == 'el')
		echo '�ŵ�';
	if ($code == 'gs')
		echo '����';
	if ($code == 'wt')
		echo '��ƻ';
	if ($code == 'gm')
		echo '����';
	if ($code == 'gl')
		echo '�������';
	if ($code == 'ol')
		echo '����';
}
// ���ͥ륮������decode
function decode_kind($code) {
	if ($code == 0)
		echo 'ξ��';
	if ($code == 1)
		echo '������';
	if ($code == 2)
		echo '������';
}
//�ᥤ�����
set_global('member', '����������', '����ǡ������Ͼ���', '');

$sql = "select mb_name1,mb_name2,ed_mb_seq_no,ed_month,ed_eng_cd,ed_use_sum,ed_old_volume,ed_new_volume,ed_reason,ed_regist_date,ed_old_sum,ed_new_sum from t_member left join t_edit_data on mb_seq_no=ed_mb_seq_no where mb_seq_no=$seq_no order by ed_regist_date desc";
$result = db_exec($sql);
$nrow = pg_numrows($result);
if ($nrow) {
	$fetch = pg_fetch_object($result, 0);
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
<form name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">��<b><?="$fetch->mb_name1 $fetch->mb_name2"?></b> �ͤν������Ͼ���</td>
		<td class="lb">
			<input type="button" value="���Ĥ��롡" onclick="window.close()">
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
</form>

<table <?=LIST_TABLE?> width="100%">
	<tr bgcolor="#FFDAB9">
		<td align="center" rowspan=2><nobr>ǯ��</nobr></td>
		<td align="center" rowspan=2><nobr>���ͥ륮��</nobr></td>
		<td align="center" rowspan=2><nobr>����</nobr></td>
		<td align="center" colspan=2><nobr>������</nobr></td>
		<td align="center" colspan=2><nobr>������</nobr></td>
		<td align="center" rowspan=2><nobr>��ͳ</nobr></td>
		<td align="center" rowspan=2><nobr>��������</nobr></td>
	</tr>
	<tr bgcolor="#FFDAB9">
		<td align="center"><nobr>������</nobr></td>
		<td align="center"><nobr>������</nobr></td>
		<td align="center"><nobr>������</nobr></td>
		<td align="center"><nobr>������</nobr></td>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	if ($fetch->ed_mb_seq_no) {
		$old_use = $fetch->ed_old_volume;
		$new_use = $fetch->ed_new_volume;
		$old_sum = $fetch->ed_old_sum;
		$new_sum = $fetch->ed_new_sum;

		if ($fetch->ed_use_sum == 2) {
			$old_use = '';
			$new_use = '';
			$old_sum = $fetch->ed_old_volume;
			$new_sum = $fetch->ed_new_volume;
		}
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><nobr><?=get_datepart('Y',$fetch->ed_month)."/".sprintf("%02d",get_datepart('M',$fetch->ed_month))?></nobr></td>
		<td align="center"><nobr><?=decode_eng($fetch->ed_eng_cd)?></nobr></td>
		<td align="center"><nobr><?=decode_kind($fetch->ed_use_sum)?></nobr></td>
		<td align="right"><nobr><?=number_format($old_use)?></nobr></td>
		<td align="right"><nobr><?=number_format($new_use)?></nobr></td>
		<td align="right"><nobr><?=number_format($old_sum)?></nobr></td>
		<td align="right"><nobr><?=number_format($new_sum)?></nobr></td>
		<td align="left"><nobr><?=htmlspecialchars($fetch->ed_reason)?></nobr></td>
		<td align="center"><nobr><?=format_datetime($fetch->ed_regist_date)?></nobr></td>
	</tr>
<?
	}
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
