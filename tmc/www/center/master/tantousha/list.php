<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:ô���ԥޥ��������ꥹ��ɽ��
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/database.php");
include("$inc/center.php");
include("$inc/decode.php");
include("$inc/list.php");

// ���å�����ѿ�����
get_session_vars($_GET['pset'], 'master_tantousha', 'sort_col', 'sort_dir');

// ô���԰�������
$order_by = order_by(1, 0, 'tts_tantousha_name', 'tts_from_name', 'tts_from_addr');
$sql = "SELECT tts_tantousha_id,tts_tantousha_name,tts_from_name,tts_from_addr FROM t_tantousha $order_by";
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

<? center_header('�ޥ������ƥʥ󥹡�ô���ԥޥ���') ?>

<div align="center">
<form name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">����Ͽ�Ѥ�ô���԰���</td>
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
sort_header(1,'ô����̾');
sort_header(2,'�᡼��From̾��');
sort_header(3,'�᡼�륢�ɥ쥹');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td><a href="edit.php?tantousha_id=<?=$fetch->tts_tantousha_id?>" title="ô���Ծ����ɽ�����ѹ����ޤ�"><?=htmlspecialchars($fetch->tts_tantousha_name)?></a></td>
		<td><?=htmlspecialchars($fetch->tts_from_name)?></td>
		<td><?=htmlspecialchars($fetch->tts_from_addr)?></td>
	</tr>
<?
}
?>
</table>
</div>

<? center_footer() ?>

</body>
</html>
