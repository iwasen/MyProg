<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:����Хꥹ��
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/sim_db.php");

// gif����ɽ��
function disp_gif_num($n) {
	$s = sprintf("%3d", $n);
	for ($i = 0; $i < 3; $i++) {
		$c = $s{$i};
		if ($c == ' ')
			$f = 'spacer.gif';
		else
			$f = "ganbalist/{$c}.gif";
		echo '<td><img src="img/', $f, '" border="0" width="14" height="30"></td>';
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

// �����Υ���Хꥹ�Ȥ����
$sql = "SELECT gm_bunrui1,gm_image_file,gn_co2,gn_ym,gn_ganbalist_id,gn_eng_bunrui"
		. " FROM t_ganbalist JOIN m_ganbalist ON gn_ganbalist_id=gm_ganbalist_id AND gn_eng_bunrui=gm_eng_bunrui"
		. " WHERE gn_seq_no=$seq_no AND gn_ym='$ym'"
		. " ORDER BY gm_bunrui1,gm_ganbalist_id";
$result = db_exec($sql);
$nrow = pg_numrows($result);
if ($nrow) {
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$bunrui[$fetch->gm_bunrui1]++;
		$fetch_ary[] = $fetch;
	}
} else {
	// �����Υǡ�����̵�����
	if ($ym < date('Ym')) {
		// ���ξ��
		redirect("decision_nothing.php?ym=$ym");
	} else {
		// ���ߤޤ���̤��ξ��
		redirect("decision_create.php?ym=$ym");
	}
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>������xxx��������Club������</title>
<link rel="stylesheet" type="text/css" href="css/main.css">
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

<!-- ����ϡ�����Хꥹ�ȤǷ迴�ܥ���򲡤�����Τ���ưŪ�ˤ�����ȿ�Ǥ������� -->

			<!-- ����η迴�ꥹ�Ȥ����뤫�ɤ���Ƚ�ꤹ�롣
			�⤷�ʤ��ä��顢��������褦��¥���������Ǻ������뤫��
			����Τ򷫤�ۤ�����ᤵ���롣�迴���ʤ����ϥ�˥塼�ؤȤ֡�
			�迴�����顢���������Jump���롣
			�ꥹ�ȥ�����åפˤϡ������ܥ������������إܥ���ȥ�˥塼�ܥ��󤬤��롣 -->

			<!-- �����ư�ܥ��󳫻� -->
			<div align="center">
			<br>
			<table border="0" align="center">
				<tr align="center" valign="middle">
					<td align="right" width="33%">
<?
if ($ym_back) {
?>
						<a href="decision_scrap.php?ym=<?=$ym_back?>"><img src="img/button/sengetu.gif" border="0" align="middle" alt="���" title="���"></a>
<?
}
?>
					</td>
					<!-- �����ܥ���Ƿ����� -->
					<td align="center" width="33%">
						<img src="img/month/<?=$month?>.gif" border="0" width="63" height="28"><img src="img/ganbalist/ganbalist_02.gif" border="0" width="131" height="35">
					</td>
					<td align="left" width="33%">
<?
if ($ym_next) {
?>
						<a href="decision_scrap.php?ym=<?=$ym_next?>"><img src="img/button/jigetu.gif" border="0" align="middle" alt="����" title="����"></a>
<?
}
?>
					</td>
				</tr>
			</table>
			<table width=650>
				<tr>
					<td class="tc0" align="center">
					��ɸ��ã���Ǥ�����ɥޥ������ɥܥ���򲡤��Ƥ������������ݥ���ȥ��ɥХ����˥ޥ������ޡ������Ĥ��ޤ���<br>
					��ɸ���᤿���ʤä���ɤ���ɥܥ���򲡤��Ƥ�������������Хꥹ�Ȥ���������ޤ���
					</td>
				</tr>
			</table>
			</div>
			<!-- �����ư�ܥ���λ -->
			<hr width="95%" size="1"><br>
			<!-- �����ȥ���ʬ��λ -->

			<!-- ���������� bgcolor="red" -->
			<table border="0" cellpadding="0" cellspacing="0">
<?
$chara = array(
	BUNRUI1_EISEI => 'eisei.gif',
	BUNRUI1_GORAKU => 'goraku.gif',
	BUNRUI1_KAJI => 'kaji.gif',
	BUNRUI1_SHOUMEI => 'syoumei.gif',
	BUNRUI1_KAITEKI => 'kucyo.gif',
	BUNRUI1_KOUTSUU => 'kotsu.gif'
);
$c = 0;
for ($i = 1; $i <= 6; $i++) {
	$n = $bunrui[$i];
	if ($n) {
		if ($c) {
?>
				<tr>
					<td colspan="7"><hr></td>
				</tr>
<?
		}
		for ($j = 0; $j < $n; $j++) {
			$fetch = &$fetch_ary[$c++];
?>
				<tr height="35">
<?
			if ($j == 0) {
?>
					<td rowspan="<?=$n * 2?>"><img src="img/character/<?=$chara[$i]?>" border="0" width="62" height="72"></td>
<?
			}
?>
					<td rowspan="2"><img src="img/ganbalist/<?=$fetch->gm_image_file?>" border="0" width="400" height="60"></td>
					<td colspan="4" align="center"><img src="img/ganbalist/co2.gif" border="0" width="100" height="30"></td>
					<td rowspan="2" width="32">
<?
if ($ym >= $ym_now) {	// ���Υǡ����Ϻ����ɽ�����ʤ�
?>
						<a href="decision_delete.php?ym=<?=$fetch->gn_ym?>&ganbalist_id=<?=$fetch->gn_ganbalist_id?>&eng_bunrui=<?=$fetch->gn_eng_bunrui?>"><img src="img/ganbalist/sakujyo.gif" border="0" width="45" height="37"></a>
<?
}
?>
					</td>
				</tr>
				<tr>
					<? disp_gif_num($fetch->gn_co2) ?>
					<td><img src="img/ganbalist/kg_co2.gif" border="0" width="55" height="30"></td>
				</tr>
<?
		}
	}
}
?>
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
			<hr width="95%" size="1">
			<a href="main.php"><img src="img/button/main.gif" border="0" width="61" height="30"></a>
			<a href="life_style.php"><img src="img/ganbalist/button_setuyaku.gif" border="0" width="145" height="30"></a>
			<!-- PDF��DL�� -->
			<br><br>
		</td>
		<td width="35" valign="bottom"><img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>
<!-- ��ݳѥơ��֥뽪λ -->

</div>
</body>
</html>
