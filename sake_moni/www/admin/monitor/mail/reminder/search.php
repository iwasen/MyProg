<?
/******************************************************
' System :�������ʪ�Υ��󥱡��ȥ�˥�����̳���ѥڡ���
' Content:�ͣ����󥱡���(��¥�᡼��)�Ϳ���������
'******************************************************/

$top = '../../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/my_search.php");
include("$inc/search.php");
include("reminder.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

if (isset($_SESSION['ss_enq_rm'])) {
	$rm = &$_SESSION['ss_enq_rm'];
	$enquete_id = $rm->enquete_id;
	$enq_count = count($enquete_id);

	foreach ($enquete_id as $id) {
		$sql = "SELECT en_title FROM t_enquete WHERE en_enquete_id=$id";
		$title[] = db_fetch1($sql);

		$search_num[] = $rm->get_search_num($id);
		$noans = $rm->get_noans_num($id, $_GET['form']);
		$noans_num[] = $noans;
		$total += $noans;

	}
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>�������</title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body>

<div align="center">
<table width=200 border=1>
	<tr>
		<td class="m2" align="center" colspan="2">�оݿͿ��������</td>
	</tr>
	<tr>
		<td class="m2" align="center">���󥱡���̾</td>
		<td class="m2" align="center">�ʹ��߿Ϳ�/�оݿͿ�</td>
	</tr>
<? for($i=0; $i < $enq_count; $i++) { ?>
	<tr>
		<td class="m3" align="center"><?=htmlspecialchars($title[$i])?></td>
		<td class="n1" align="center"><?=number_format($noans_num[$i])?> / <?=number_format($search_num[$i])?> ��</td>
	</tr>
<? } ?>
	<tr>
		<td class="m3" align="center">��</td>
		<td class="n1" align="center"><?=number_format($total)?> ��</td>
	</tr>
</table>
<br>
<input type="button" value="���Ĥ��롡" onclick="window.close()">
</div>

</body>
</html>
