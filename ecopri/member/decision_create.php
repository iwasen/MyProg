<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:����Хꥹ�ȿ�������
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");

// ����ǯ��Υ���Хꥹ��
function data_exist_check($ym) {
	if ($ym) {
		// ����ֹ����
		$seq_no = $_SESSION['ss_seq_no'];

		// ����Хꥹ�Ȥ򸡺�
		$sql = "SELECT gn_seq_no FROM t_ganbalist WHERE gn_seq_no=$seq_no AND gn_ym<=$ym";
		$result = db_exec($sql);
		return pg_numrows($result);
	}
}

// ����ֹ����
$seq_no = $_SESSION['ss_seq_no'];

// �����ǯ�����
$ym_now = date('Ym');

// ǯ��λ��̵꤬����к���Ȥ���
if (!$ym)
	$ym = $ym_now;

// ǯ���ʬ��
$year = substr($ym, 0, 4);
$month = substr($ym, -2);

// �������
$sql = "SELECT count(*) FROM t_ganbalist WHERE gn_seq_no=$seq_no AND gn_ym<'$ym'";
if (db_fetch1($sql))
	$ym_back = date('Ym', mktime(0, 0, 0, $month - 1, 1, $year));

// �������
if ($ym < $ym_now)
	$ym_next = date('Ym', mktime(0, 0, 0, $month + 1, 1, $year));
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>������xxx��������Club������</title>
</head>

<body bgcolor="#FF0000" text="#000000" link="#66ccff" vlink="#66ccff" leftmargin="8" topmargin="8" alink="#66ccff">
<div align="center">

<!-- ��ݳѥơ��֥� -->
<table border="0" cellpadding="0" cellspacing="0" width="714" height="90%" bgcolor="#FFFFFF">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF">
			<img src="img/kado_1.gif" width="35" height="35">
		</td>
		<td align="center" valign="top">
			<!-- �����ȥ���ʬ -->
			<!-- �����ư�ܥ��󳫻� -->
			<div align="center">
			<br>
			<table border="0" align="center">
				<tr align="center" valign="middle">
					<td rowspan="2" align="right" width="33%">
<?
if ($ym_back) {
?>
						<a href="decision_scrap.php?ym=<?=$ym_back?>"><img src="img/button/sengetu.gif" border="0" align="middle" alt="���" title="���"></a>
<?
}
?>
					</td>
					<!-- �����ܥ���Ƿ����� -->
					<td align="center" width="33%"><img src="img/month/<?=$month?>.gif" border="0" width="63" height="28"></td>
					<td rowspan="2" align="left" width="33%">
<?
if ($ym_next) {
?>
						<a href="decision_scrap.php?ym=<?=$ym_next?>"><img src="img/button/jigetu.gif" border="0" align="middle" alt="����" title="����"></a>
<?
}
?>
					</td>
				</tr>

				<tr>
					<td align="center"><img src="img/ganbalist/ganbalist_02.gif" border="0" width="131" height="35"></td>
				</tr>
			</table>
			</div>
			<!-- �����ư�ܥ���λ -->
			<hr width="95%" size="1">
			<!-- �����ȥ���ʬ��λ -->

			<!-- ���������� -->
			<table border="0" align="center">
				<tr>
					<td align="center">
						<img src="img/ganbalist/decision_nothing.gif" border="0" width="600" height="300">
						<!-- �����Ʊ��ʪ��������롣���դ��Ѥ��뤳�� -->
<?
// ��������Υǡ��������뤫�����å�
if (data_exist_check($ym_back)) {
?>
						<a href="decision_copy.php?ym=<?=$ym?>"><img src="img/ganbalist/zengetu.gif" border="0" width="123" height="35"></a>
<?
}
?>
						<a href="life_style.php"><img src="img/ganbalist/kesshin.gif" border="0" width="123" height="35"></a>
					</td>
				</tr>
			</table>
			<!-- �����ޤ� -->
		</td>
		<td width="35" align="right" valign="top" bgcolor="#FFFFFF">
			<img src="img/kado_2.gif" width="35" height="35">
		</td>
	</tr>
	<tr>
		<td width="35" valign="bottom"><img src="img/kado_3.gif" width="35" height="35"></td>
		<td align="center">
			<hr width="95%" size="1"><br>
			<a href="main.php"><img src="img/button/main.gif" border="0" alt="�ᥤ���" width="61" height="30"></a>
		</td>
		<td width="35" valign="bottom"><img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>
<!-- ��ݳѥơ��֥뽪λ -->

</div>
</body>
</html>