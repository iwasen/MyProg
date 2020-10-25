<?
/******************************************************
' System :ICP���ڥ졼���ڡ���
' Content:�ޡ����󥰥ޥ��������ꥹ��ɽ��
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/list.php");

$inc = "$top/inc";
include("$inc/ope_common.php");
include("$inc/login_check.php");
include("$inc/header.php");

//�ᥤ�����
$order_by = order_by(1, 0, 'mk_marking_id', 'mk_name', 'mk_color');
$sql = "SELECT mk_marking_id,mk_name,mk_color FROM m_marking $order_by";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>icp.net ���ڥ졼�����ڡ���</title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/default.css">
<? list_script() ?>
</head>
<body>

<div align="center">

<? ope_header() ?>

<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>

<? ope_menu() ?>

	<!-- ������ƥ�� -->
	<td valign="top">
		<table border="0" cellspacing="0" cellpadding="10" width="100%">
		<tr>
			<td>

<div align="center">
<form name="form1">
<table border="0" cellspacing="0" cellpadding="3" width="100%" class="pagetitle">
	<tr>
		<td>���ޡ����󥰥ޥ�������</td>
		<td align="right">
			<input type="button" value="�����ɲ�" onclick="location.href='new.php'">
			<input type="button" value=" ��� " onclick="location.href='../menu.php'">
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
</form>

<table border="1" cellspacing="0" cellpadding="2" width="100%" class="list">
	<tr>
<?
sort_header(1, 'ID');
sort_header(2, '̾��');
sort_header(3, 'ɽ����');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="line<?=$i % 2?>">
		<td align="center"><?=$fetch->mk_marking_id?></td>
		<td><a href="edit.php?marking_id=<?=$fetch->mk_marking_id?>" title="�ޡ����󥰾����ɽ�����ѹ����ޤ�"><?=htmlspecialchars($fetch->mk_name)?></a></td>
		<td><span style="background-color:<?=$fetch->mk_color?>"><?=htmlspecialchars($fetch->mk_color)?></span></td>
	</tr>
<?
}
?>
</table>
</div>
			</td>
		</tr>
		</table>

	</td>
	<!-- ������ƥ�� -->
</tr>
</table>

<? ope_footer() ?>

</div>
</body>
</html>
