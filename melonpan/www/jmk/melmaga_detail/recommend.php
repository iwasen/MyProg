<?php
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:����ʸ�ܺ�
'******************************************************/

$sql = "SELECT TR.recom_titl, TR.recom_text, LM.log_point"
	. " FROM T_RECOMEND TR, L_MELONPAI_LOG LM"
	. " WHERE TR.recomend_id=$recomend_id AND TR.recomend_id=LM.recomend_id";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$recom_titl = $fetch->recom_titl;
	$recom_text = $fetch->recom_text;
	$log_point = $fetch->log_point;
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>����ʸɽ��</title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
</head>
<body>
<center>
<form method="post" name="form1" method="post">
<table width="350" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td class="midashi14">������ʸ���Ʊ���</td>
		<td align="right">point: <?= number_format($log_point) ?></td>
	</tr>
</tbale>
<table width="350" border="1" cellspacing="0" cellpadding="0" bgcolor="#Fccffff" align="center">
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="1" cellpadding="3">
			  <tr>
 			    <td bgcolor="#99bc66">�����ȥ롧<?= htmlspecialchars($recom_titl); ?></td>
			  </tr>
			  <tr align="center">
	    		<td class="pfont" align="left"><textarea  cols="45" rows="10"><?= htmlspecialchars($recom_text); ?></textarea></td>
	 		 </tr>
			</table>
		</td>
	</tr>
</table>
<input type="button" value=" �Ĥ��� " onclick="window.close()">
</form>
</center>
</body>
</html>

