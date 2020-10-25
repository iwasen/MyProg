<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:��³��졼�����᡼���̾����ɽ��
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/database.php");
include("$inc/center.php");
include("$inc/list.php");

// ���ϥѥ�᡼��
$vol_no = $_GET['vol_no'];

// ���å�����ѿ�����
get_session_vars($_GET['pset'], 'master_relation_subject', 'sort_col', 'sort_dir');

// ��̾��������
$order_by = order_by(1, 0, 'crc_car_category_name', 'rls_subject');
$sql = "SELECT crc_car_category_cd,crc_car_category_name,rls_subject"
		. " FROM t_car_category"
		. " LEFT JOIN t_relation_subject ON rls_vol_no=$vol_no AND rls_car_category_cd=crc_car_category_cd"
		. " $order_by";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<? list_script() ?>
</head>
<body>

<? center_header('�ޥ������ƥʥ󥹡÷�³��졼�����᡼��÷�̾') ?>

<div align="center">
<form name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">����³��졼�����᡼�� VOL.<?=$vol_no?>����̾</td>
		<td class="lb">
			<input type="button" value=" ��� " onclick="location.href='list.php'">
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="pset" value=1>
<input type="hidden" name="vol_no" <?=value($vol_no)?>>
</form>

<table <?=LIST_TABLE?> width="100%">
	<tr class="tch">
<?
sort_header(1,'�ּ參�ƥ���');
sort_header(2,'��̾');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td><a href="subject_edit.php?vol_no=<?=$vol_no?>&car_category_cd=<?=$fetch->crc_car_category_cd?>" title="���ꤷ���ּ參�ƥ���η�̾����Ͽ���ޤ���"><?=$fetch->crc_car_category_name?></a></td>
		<td><?=$fetch->rls_subject != '' ? htmlspecialchars($fetch->rls_subject) : '<font color="red">̤��Ͽ</font>'?></td>
	</tr>
<?
}
?>
</table>
</div>

<? center_footer() ?>

</body>
</html>
