<?
/******************************************************
' System :�������ơ�net��̳�ɡ�������������ѥڡ���
' Content:My�ѡ��ȥʡ�����ļ��������
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/list.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

//�ᥤ�����
set_global2('mypartner', 'My�ѡ��ȥʡ�����', '��ļ��������', BACK_CLOSE);

// �����Ⱦ��
$order_by = order_by(1, 0, 'ppj_pjt_id', 'ppj_room_name', 'mr_kinmu_name', 'mr_name1_kana,mr_name2_kana');

// �ꥹ�����
$sql = "SELECT ppj_pjt_id,ppj_room_name,mr_kinmu_name,mr_name1,mr_name2,mr_marketer_id"
		. " FROM t_mp_pjt"
		. " JOIN t_marketer ON mr_marketer_id=ppj_marketer_id"
		. " WHERE ppj_room_name LIKE '%" . addslashes($room_name) . "%'"
		. " $order_by";
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

<form method="post" name="form1">
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="pset" value=1>
<input type="hidden" name="room_name" <?=value($room_name)?>>
</form>


<table border=1 cellspacing=0 cellpadding=1 bordercolorlight="#f0f0f0" frame="box" width="100%" class="small">
	<tr class="tch">
<?
sort_header(1, '��ļ�ID');
sort_header(2, '��ļ�̾');
sort_header(3, '���̾');
sort_header(4, '�ώ�������̾');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$pjt_id = $fetch->ppj_pjt_id;
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=$pjt_id?></td>
		<td align="center"><a href="memberlist_gaitou.php?pjt_id=<?=$pjt_id?>" target="_blank"><?=htmlspecialchars($fetch->ppj_room_name)?></a></td>
		<td align="center"><?=htmlspecialchars($fetch->mr_kinmu_name)?></td>
		<td align="center"><a href="marketer_info.php?marketer_id=<?=$fetch->mr_marketer_id?>" target="_blank"><?=htmlspecialchars("$fetch->mr_name1 $fetch->mr_name2")?></a></td>
	</tr>
<?
}
?>
</table>
<br>
<div align="center">
<input type="button" value="���Ĥ��롡" onclick="javascript:window.close()">��
</div>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
