<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:��³��졼�����᡼�밧��ʸ�ѹ�����
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// ���ϥѥ�᡼��
$vol_no = $_GET['vol_no'];

// ����ʸ��������
$sql = "SELECT rlg_contents FROM t_relation_greeting WHERE rlg_vol_no=$vol_no";
$result = db_exec($sql);
if (pg_num_rows($result))
	$fetch = pg_fetch_object($result, 0);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	if (f.body.value == "") {
		alert("����ʸ�����Ϥ��Ƥ���������");
		f.body.focus();
		return false;
	}
	return confirm("����ʸ����Ͽ���ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body onload="document.form1.body.focus()">

<? center_header('�ޥ������ƥʥ󥹡÷�³��졼�����᡼��ð���ʸ') ?>

<div align="center">
<form method="post" name="form1" action="greeting_update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>����³��졼�����᡼��ΰ���ʸ�����Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td class="m1" width="20%">VOL</td>
		<td class="n1"><input type="hidden" name="vol_no" <?=value($vol_no)?>><?=$vol_no?></td>
	</tr>
	<tr>
		<td class="m1">����ʸ</td>
		<td class="n1"><textarea class="kanji" name="body" cols=78 rows=30><?=htmlspecialchars($fetch->rlg_contents)?></textarea></td>
	</tr>
</table>
<br>
<input type="submit" value="����Ͽ��">
<input type="button" value="����󥻥�" onclick="history.back()">
</form>
</div>

<? center_footer() ?>

</body>
</html>
