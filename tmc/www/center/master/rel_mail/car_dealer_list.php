<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:��³��졼�����᡼��ּ��������Ź�������ɽ��
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
get_session_vars($_GET['pset'], 'master_relation_car_dealer', 'sort_col', 'sort_dir');

// �ּ��������Ź�����������
$order_by = order_by(1, 0, 'dlr_dealer_cd', 'rlc_contents');
$sql = "SELECT dlr_dealer_cd,dlr_dealer_name,rlc_contents"
		. " FROM t_dealer"
		. " LEFT JOIN t_relation_car_dealer ON rlc_vol_no=$vol_no AND rlc_dealer_cd=dlr_dealer_cd"
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

<? center_header('�ޥ������ƥʥ󥹡÷�³��졼�����᡼��üּ��������Ź����') ?>

<div align="center">
<form name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">����³��졼�����᡼�� VOL.<?=$vol_no?>���ּ��������Ź����</td>
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
sort_header(2,'����');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td><a href="car_dealer_edit.php?vol_no=<?=$vol_no?>&dealer_cd=<?=$fetch->dlr_dealer_cd?>" title="���ꤷ���ּ參�ƥ���μּ��������Ź�������Ͽ���ޤ���"><?=$fetch->dlr_dealer_name?> (<?=$fetch->dlr_dealer_cd?>)</a></td>
		<td><?=$fetch->rlc_contents != '' ? '��Ͽ�Ѥ�' : '<font color="red">̤��Ͽ</font>'?></td>
	</tr>
<?
}
?>
</table>
</div>

<? center_footer() ?>

</body>
</html>
