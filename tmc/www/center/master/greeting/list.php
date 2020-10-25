<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:����ΰ���ʸ�ޥ��������ꥹ��ɽ��
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/database.php");
include("$inc/center.php");
include("$inc/list.php");

// ���å�����ѿ�����
get_session_vars($_GET['pset'], 'master_season_greetings', 'sort_col', 'sort_dir');

// ����ΰ���ʸ��������
$order_by = order_by(1, 0, 'sgr_from_month;sgr_from_day');
$sql = "SELECT sgr_seq_no,sgr_from_month,sgr_from_day,sgr_to_month,sgr_to_day,sgr_greeting FROM t_season_greetings $order_by";
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

<? center_header('�ޥ������ƥʥ󥹡û���ΰ���ʸ�ޥ���') ?>

<div align="center">
<form name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">����Ͽ�Ѥ߻���ΰ���ʸ����</td>
		<td class="lb">
			<input type="button" value="������Ͽ" onclick="location.href='new.php'">
			<input type="button" value=" ��� " onclick="location.href='../menu.php'">
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="pset" value=1>
</form>

<table <?=LIST_TABLE?> width="100%">
	<tr class="tch">
<?
sort_header(1,'ͭ������');
sort_header(2,'����ʸ');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td><a href="edit.php?seq_no=<?=$fetch->sgr_seq_no?>" title="����ΰ���ʸ��ɽ�����ѹ����ޤ�"><?="{$fetch->sgr_from_month}��{$fetch->sgr_from_day}����{$fetch->sgr_to_month}��{$fetch->sgr_to_day}��"?></a></td>
		<td><pre><?=$fetch->sgr_greeting?></pre></td>
	</tr>
<?
}
?>
</table>
</div>

<? center_footer() ?>

</body>
</html>
