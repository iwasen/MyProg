<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:����ե����᡼��ּ��������Ź�������ɽ��
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
get_session_vars($_GET['pset'], 'master_follow_car_dealer', 'sort_col', 'sort_dir');

// ��Ͽ�Ѥ߼ּ��������Ź�������
$order_by = order_by(1, 0, 'dlr_dealer_cd;crm_car_model_name', 'crm_car_model_name;dlr_dealer_name', 'flc_contents');
$sql = "SELECT dlr_dealer_cd,dlr_dealer_name,crm_car_model_cd,crm_car_model_name,flc_contents"
		. " FROM t_dealer_car"
		. " JOIN t_dealer ON dlr_dealer_cd=dlc_dealer_cd"
		. " JOIN t_car_model ON crm_car_model_cd=dlc_car_model_cd"
		. " LEFT JOIN t_follow_car_dealer ON flc_vol_no=$vol_no AND flc_dealer_cd=dlr_dealer_cd AND flc_car_model_cd=crm_car_model_cd"
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

<? center_header('�ޥ������ƥʥ󥹡ý���ե����᡼��üּ��������Ź����') ?>

<div align="center">
<form name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">������ե����᡼�� VOL.<?=$vol_no?>���ּ��������Ź����</td>
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
sort_header(1,'����Ź');
sort_header(2,'�ּ�̾');
sort_header(3,'����');
?>
	</tr>
<?
$same_dealer = new join_same_item;
$same_dealer->prepare($result, 'dlr_dealer_cd');

for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$row_count = $same_dealer->get_row_count();
?>
	<tr class="tc<?=$i % 2?>">
<?
	$dealer_id = $fetch->dlr_dealer_cd;
	if ($row_count > 0) {
?>
		<td rowspan=<?=$row_count?> class="tc<?=$j++ % 2?>"><?=htmlspecialchars($fetch->dlr_dealer_name)?> (<?=$fetch->dlr_dealer_cd?>)</td>
<?
}
?>
		<td><a href="car_dealer_edit.php?vol_no=<?=$vol_no?>&dealer_cd=<?=$fetch->dlr_dealer_cd?>&car_model_cd=<?=$fetch->crm_car_model_cd?>" title="���ꤷ���ּ�μּ��������Ź�������Ͽ���ޤ���"><?=htmlspecialchars($fetch->crm_car_model_name)?></a></td>
		<td><?=$fetch->flc_contents != '' ? '��Ͽ�Ѥ�' : '<font color="red">̤��Ͽ</font>'?></td>
	</tr>
<?
}
?>
</table>
</div>

<? center_footer() ?>

</body>
</html>
