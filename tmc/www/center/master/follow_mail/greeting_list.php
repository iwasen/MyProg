<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:����ե����᡼�밧��ʸ����ɽ��
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
get_session_vars($_GET['pset'], 'master_follow_greeting', 'sort_col', 'sort_dir');

// ��Ͽ�Ѥ߰���ʸ����
$order_by = order_by(1, 0, 'sch_sales_channel_cd', 'flg_contents');
$sql = "SELECT sch_sales_channel_cd,sch_sales_channel_name,flg_contents"
		. " FROM t_sales_channel"
		. " LEFT JOIN t_follow_greeting ON flg_vol_no=$vol_no AND flg_sales_channel_cd=sch_sales_channel_cd"
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

<? center_header('�ޥ������ƥʥ󥹡ý���ե����᡼��ð���ʸ') ?>

<div align="center">
<form name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">������ե����᡼�� VOL.<?=$vol_no?>������ʸ</td>
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
sort_header(1,'�������ͥ�');
sort_header(2,'����');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td><a href="greeting_edit.php?vol_no=<?=$vol_no?>&sales_channel_cd=<?=$fetch->sch_sales_channel_cd?>" title="���ꤷ���������ͥ�ΰ���ʸ����Ͽ���ޤ���"><?=htmlspecialchars($fetch->sch_sales_channel_name)?></a></td>
		<td><?=$fetch->flg_contents != '' ? '��Ͽ�Ѥ�' : '<font color="red">̤��Ͽ</font>'?></td>
	</tr>
<?
}
?>
</table>
</div>

<? center_footer() ?>

</body>
</html>
