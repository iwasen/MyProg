<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:��������ɽ������
'******************************************************/

$title_text = $TT_kanri_suuji;
$title_color = $TC_MASTER;

//-���������դ����-
$yesterday = mktime (0,0,0,date(m),date(d)-1,date(Y));
$y = strftime ("%Y", $yesterday);
$m = strftime ("%m", $yesterday);
$d = strftime ("%d", $yesterday);
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
<form method="post" name="form1" action="kanri_suuji2.php">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<tr>
			<td class="m0">����������ɽ��<font size="2">���������ޤǤδ���������ɽ�����ޤ�����</font></td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width='100%'>
					<tr>
						<td class="m4" width="28%">���ϻ�����</td>
						<td class="n4" width="60%">
							<select name="year"><? select_year(2000, '', $y) ?></select>ǯ
							<select name="month"><? select_month('', $m); ?></select>��
							<select name="day"><? select_day('', $d); ?></select>��
					</tr>
				</table>
			</td>
		</tr>
	</table> <br>
<input type="submit" value=" ���� ">
<input type="button" value=" ��� " onclick="location.href='../menu.php'">
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
