<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:My�ѡ��ȥʡ����ɲ�ȯ��Ƚ�ǥޥ�������
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/list.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

//�ᥤ�����
set_global('mypartner', 'My�ѡ��ȥʡ�����', '�ɲ�ȯ��Ƚ�ǥޥ���', BACK_TOP);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body>

<? page_header() ?>

<div align="center">
<form name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">���ɲ�ȯ��Ƚ�ǥޥ�������</td>
		<td class="lb">
			<input type="button" value="��������" onclick="location.href='edit.php'">
			<input type="button" value="����롡" onclick="location.href='../../menu.php'">
		</td>
	</tr>
</form>

<table border=1 cellspacing=0 cellpadding=1 bordercolorlight="#f0f0f0" frame="box" width="100%">
	<tr class="tch">
<?
sort_header(0, '����');
sort_header(0, '����');
?>
	</tr>
<?
// �����ꥹ�ȼ���
$sql = "SELECT mas_hour,mas_factor FROM m_addition_send ORDER BY mas_hour";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$hour_ary[$i] = $fetch->mas_hour;
	$factor_ary[$i] = $fetch->mas_factor;
}

for ($i = 0; $i < $nrow; $i++) {
	if ($factor_ary[$i] < 100) {
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=$hour_ary[$i]?>��00��<?=$hour_ary[$i + 1] - 1?>��59</td>
<?
	} else {
?>
	<tr class="tc2">
		<td align="center"><?=$hour_ary[$i]?>��00��</td>
<?
	}
?>
		<td align="center"><?=$factor_ary[$i]?>%</td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>

</body>
</html>
