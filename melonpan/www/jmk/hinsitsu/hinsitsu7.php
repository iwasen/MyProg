<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");
include ("../inc/com_mail_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:�ʼ������å�
'******************************************************/

$title_text = $TT_hinshitsu;
$title_color = $TC_HAKKOUSHA;

if ($flg == 'y') {
	$mag_pub_stop_flg_now = '01';
	$mag_pub_stop_flg = '00';
	
} elseif ($flg == 'r') {
	$mag_pub_stop_flg_now = '02';
	$mag_pub_stop_flg = '01';
}
$sql = "SELECT * FROM M_MAGAZINE WHERE mag_id='$mag_id' AND mag_pub_stop_flg='$mag_pub_stop_flg_now'";
$result = db_exec($sql);
if (!pg_numrows($result)) {
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>
<center>
<form>
�ǡ��������˹�������Ƥ���褦�Ǥ�������ǧ����������<br><br>
<input type="button" value=" ��� " onclick="history.back()">
</form>
</center><? footer_jmk(0); ?>
</body>
</html>
<?
} else {
	$sql = "UPDATE M_MAGAZINE SET mag_pub_stop_flg='$mag_pub_stop_flg', mag_pub_stop_dt='now' WHERE mag_id='$mag_id'";
	db_exec($sql);
	
	$sql = "SELECT MM.mag_id, MP.owner_nm_kanj, MP.mail_add_main, MM.mag_nm, mag_pub_stop_flg, to_char(mag_pub_stop_dt, 'YYYY/MM/DD') as mag_pub_stop_dt"
		. ", mag_pub_status_flg"
		. " FROM M_MAGAZINE MM, M_PUBLISHER MP"
		. " WHERE MM.mag_id='$mag_id' AND mag_pub_stop_flg='$mag_pub_stop_flg' AND MM.publisher_id=MP.publisher_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$mag_id = $fetch->mag_id;
		$mag_pub_stop_flg = $fetch->mag_pub_stop_flg;
		$mag_pub_stop_dt = $fetch->mag_pub_stop_dt;
		$owner_nm_kanj = $fetch->owner_nm_kanj;
		$mail_add_main = $fetch->mail_add_main;
		$mag_nm = $fetch->mag_nm;
		if ($mag_pub_stop_flg == '00') { $stop_flg="OK"; }
		if ($mag_pub_stop_flg == '01') { $stop_flg="<font color=\"orange\">Y</font>"; }
		if ($mag_pub_stop_flg == '02') { $stop_flg="<font color=\"red\">R</font>"; }
		if ($mag_pub_status_flg == '02') { $status_flg="��ǧ"; }
		if ($mag_pub_status_flg <> '02') { $status_flg=""; }
		if ($mag_pub_stop_dt == "") { $mag_pub_stop_dt="-"; }
	}
	if ($mag_pub_stop_flg == '00') {
		$msg = "�������ޥ��Υ������ե饰����ä��ޤ�����";
	} elseif ($mag_pub_stop_flg == '01') {
		$msg = "�������ޥ��Υ�åɥե饰�򥤥����ե饰���ѹ����ޤ�����";
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= ("$title_text") ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>
<center>
<form method="post" name="form1">
	<table border=0 cellspacing=0 cellpadding=0 width='90%'>
		<tr>
			<td>��<?= $msg ?></td>
		</tr>
	</table>
	<table border=1 cellspacing=0 cellpadding=3 width='90%'>
		<tr bgcolor="#ffcc99">
			<th align="center"><nobr>status</nobr></th>
			<th align="center"><nobr>status������</nobr></th>
			<th align="center"><nobr>���ޥ�ID</nobr></th>
			<th align="center"><nobr>���ޥ�̾</nobr></th>
			<th align="center"><nobr>ȯ�Լ�</nobr></th>
		</tr>
		<tr class="tc<?= $line % 2; ?>">
			<td align="center"><?= $stop_flg ?><?= $status_flg ?></td>
			<td align="center"><?= $mag_pub_stop_dt ?></td>
			<td align="center"><?= $mag_id ?></td>
			<td><?= htmlspecialchars($mag_nm) ?></td>
			<td><?= htmlspecialchars($owner_nm_kanj) ?></td>
		</tr>
	</table><br>
<input type="button" value=" ��� " onclick="location.href='../hinsitsu/index.php'"><br>
</form>
</center><? footer_jmk(0); ?>

</body>
</html>
<?
}
?>
