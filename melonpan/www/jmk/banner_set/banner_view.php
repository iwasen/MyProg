<?php
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");


/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:����Хʡ�����
'******************************************************/

if ($type == "top") {
	$where = "BN_kind = '1'";
	$kind = "�ȥå�";
} elseif ($type == "side") {
	$where = "BN_kind = '2'";
	$kind = "������";
}

if (!$flag) {
	$view_year = date("Y");
	$view_month = date("m");
	$view_day = date("d");
	$view_hour = date("H");
}

$view_dt = sprintf("%04d-%02d-%02d %02d:00:00", $view_year, $view_month, $view_day, $view_hour);

$sql = "SELECT BN_id, BN_picture FROM T_BANNER WHERE BN_start_dt <= to_timestamp('$view_dt', 'YYYY-MM-DD HH24:MI:SS') AND BN_end_dt >= to_timestamp('$view_dt', 'YYYY-MM-DD HH24:MI:SS') AND " . $where;
$result = db_exec($sql);
$nrow = pg_numrows($result);

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>�Хʡ�ɽ��</title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
</head>
<body>
<center>
<form method="post" ENCTYPE="multipart/form-data" name="form1" method="post" action="banner_view.php?type=<?= $type ?>&flag=on">
<table border="0" cellspacing="0" cellpadding="5">
	<tr>
		<td align=right valign=bottom norap><small><font color=blue>��</font><A href='JavaScript:window.close()'>�Ĥ���</A></small></td>
	</tr>
  <tr>
    <td align="left" class="midashi14">��<?= $kind ?>�Хʡ�ɽ��<br>
	</tr>
	<tr>
		<td align="center">
			<select name="view_year"><? select_year(2001, '', $view_year); ?></select>ǯ
			<select name="view_month"><? select_month('', $view_month); ?></select>��
			<select name="view_day"><? select_day('', $view_day); ?></select>��
			<select name="view_hour"><? select_hour('', $view_hour); ?></select><font size=-1>���ߡ�</font>
			<input type="submit" name="banner_pict" value="ɽ��">
		</td>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$bn_id = $fetch->bn_id;
	$bn_picture = $fetch->bn_picture;
?>
	<tr align="center">
		<td>
			<img src="./banner_pict.php?bn_picture=<?= $bn_picture ?>" id="pict" alt="����">
		</td>
	</tr>
<?
}
?>
</table>
</form>
</center>
</body>
</html>

