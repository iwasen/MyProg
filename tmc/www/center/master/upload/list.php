<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:���åץ���ô���ޥ��������ꥹ��ɽ��
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/database.php");
include("$inc/center.php");
include("$inc/list.php");

// ���å�����ѿ�����
get_session_vars($_GET['pset'], 'master_upload', 'sort_col', 'sort_dir');

// ���åץ���ô����������
$order_by = order_by(1, 0, 'upt_upload_cd', 'upt_name', 'upt_active');
$sql = "SELECT upt_upload_cd,upt_name,upt_active"
		. " FROM t_upload_tantou"
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

<? center_header('�ޥ������ƥʥ󥹡å��åץ���ô���ޥ���') ?>

<div align="center">
<form name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">����Ͽ�Ѥߥ��åץ���ô������</td>
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
sort_header(1,'���åץ���ô��������');
sort_header(2,'����̾��');
sort_header(3,'��ư����');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><a href="edit.php?upload_cd=<?=$fetch->upt_upload_cd?>" title="���åץ���ô�������ɽ�����ѹ����ޤ�"><?=$fetch->upt_upload_cd?></a></td>
		<td><?=htmlspecialchars($fetch->upt_name)?></td>
		<td align="center"><?=$fetch->upt_active == DBTRUE ? '��ư' : '���ư'?></td>
	</tr>
<?
}
?>
</table>
</div>

<? center_footer() ?>

</body>
</html>
