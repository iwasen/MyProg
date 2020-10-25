<?
/******************************************************
' System :�������ơ�net��̳�ɡ�������������ѥڡ���
' Content:My�ѡ��ȥʡ�����ļ����ü԰���
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/list.php");
include("$inc/decode.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

//�ᥤ�����
set_global2('mypartner', 'My�ѡ��ȥʡ�����', '��ļ����ü԰���', BACK_CLOSE);

// ��ļ�̾����
$sql = "SELECT ppj_room_name FROM t_mp_pjt WHERE ppj_pjt_id=$pjt_id";
$room_name = db_fetch1($sql);

// �����Ⱦ��
$order_by = order_by(1, 0, 'mn_monitor_id', 'mn_name1_kana,mn_name2_kana', 'mn_sex', 'mn_age', 'mn_jitaku_area');

// �ꥹ�����
$sb1 = "SELECT pmn_monitor_id FROM t_mp_monitor GROUP BY pmn_monitor_id";
$sql = "SELECT mn_monitor_id,mn_name1,mn_name2,mn_sex,DATE_PART('Y',AGE(mn_birthday)) AS mn_age,ar_area_name"
		. " FROM t_mp_monitor"
		. " JOIN t_monitor ON mn_monitor_id=pmn_monitor_id"
		. " JOIN m_area ON ar_area_cd=mn_jitaku_area"
		. " WHERE pmn_pjt_id=$pjt_id AND pmn_admin_choice IN (1,2)"
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
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">��<?=htmlspecialchars($room_name)?></td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="pset" value=1>
<input type="hidden" name="pjt_id" <?=value($pjt_id)?>>
</form>

<table border=1 cellspacing=0 cellpadding=1 bordercolorlight="#f0f0f0" frame="box" width="100%" class="small">
	<tr class="tch">
<?
sort_header(1, '�ӎƎ���ID');
sort_header(2, '̾��');
sort_header(3, '����');
sort_header(4, 'ǯ��');
sort_header(5, '�ｻ��');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$monitor_id = $fetch->mn_monitor_id;
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=$monitor_id?></td>
		<td align="center"><a href="monitor_job_list_gaitou.php?monitor_id=<?=$monitor_id?>" target="_blank"><?=htmlspecialchars("$fetch->mn_name1 $fetch->mn_name2")?></a></td>
		<td align="center"><?=decode_sex($fetch->mn_sex)?></td>
		<td align="center"><?=$fetch->mn_age?></td>
		<td align="center"><?=$fetch->ar_area_name?></td>
	</tr>
<?
}
?>
</table>
<br>
<div align="center">
<input type="button" value="���Ĥ��롡" onclick="javascript:window.close()">
</div>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
