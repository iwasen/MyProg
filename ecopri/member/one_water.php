<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:���ݥ���ȥ��ɥХ����ʿ�ƻ�ԡ�
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/sim_db.php");

// �ޥ������ޡ���ɽ��
function disp_master_mark($ganbalist_id) {
	global $master;

	if ($master[$ganbalist_id])
		echo '<img src="img/shindan/sim_water/button_master.gif" width="47" height="44" border="0">';
	else
		echo '<img src="img/spacer.gif" width="47" height="44" border="0">';
}

// ���ڡ������å�
$_SESSION['s_one_advice'] = 'one_water.php';

// ���ͥ륮��ʬ�ॻ�å�
$_SESSION['s_eng_bunrui'] = ENG_SUIDOU;

// ����ֹ����
$seq_no = $_SESSION['ss_seq_no'];

// �ޥ������ޡ�������
$sql = "SELECT mm_ganbalist_id FROM t_master_mark WHERE mm_mb_seq_no=$seq_no";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$master[(int)$fetch->mm_ganbalist_id] = true;
}
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>������xxx��������Club������</title>
<link rel="stylesheet" type="text/css" href="css/main.css">
</head>

<body bgcolor="#FF0000" text="#000000" link="#66ccff" vlink="#66ccff" leftmargin="8" topmargin="8" alink="#66ccff">
<div align="center">

<!-- ��ݳѥơ��֥� -->
<table border="0" cellpadding="0" cellspacing="0" width="714" bgcolor="#FFFFFF">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF">
			<img src="img/kado_1.gif" width="35" height="35">
		</td>
		<td align="center" valign="top">
			<!-- �����ȥ���ʬ -->
			<table border="0">
				<tr>
					<td height=80><img src="img/shindan/sim_water/suidou_title.gif" border="0" width="417" height="64"></td>
				</tr>
			</table>
			<!-- �����ȥ���ʬ��λ -->

			<!-- ���������� -->
			<!-- �԰���ʬ��ɽ -->
			<table border="1" cellpadding=3 bordercolordark="orange" bordercolorlight="orange" width="100%">
				<!-- �����ȥ� -->
				<tr>
					<th width="50" bgcolor="gold"><br></th>
					<th align="center" bgcolor="gold">���ɥХ���</th>
					<th align="center" bgcolor="gold">CO2�︺��</th>
					<th align="center" bgcolor="gold">����</th>
					<th align="center" bgcolor="gold"><nobr>�ޥ�����</nobr></th>
				</tr>
<!-- 1���� -->
				<tr>
					<td rowspan="3" align="center" bgcolor="#FFFA55"><img src="img/character/yellow/kaji.gif"></td>
					<td bgcolor="#FFFFFF" class="tc0">�����ˤϡ���Ϥ�λĤ������Ѥ����ᤷ�ޤ��������Ȥ�������1���ܤ���Ϥ������Ѥ���Ȏ�����</td>
					<td bgcolor="#FFFFFF"><img src="img/one/meter/25.gif" border="0" width="100" height="45"></td>
<!--					<td bgcolor="#FFFFFF" align="middle"><a href="sim_sentaku.php" title="���ߥ�졼�����"><img src="img/one/one_foot01.gif" border="0"></a></td>-->
					<td bgcolor="#FFFFFF" align="middle"><a href="kaji_sentaku_furomizu.php" title="���ǡ�����Хꥹ����Ͽ"><img src="img/one/one_foot01.gif" border="0"></a></td>
					<td bgcolor="#FFFFFF" align="middle"><? disp_master_mark(GANBA_SENTAKU) ?></td>
				</tr>
				<tr>
					<td bgcolor="#FFFFFF" class="tc0">�����Τ������ϡ����ǤϤʤ�ί�᤹�����ˤ��ޤ��礦����夹��������ơ�ί�᤹�����ˤ���Ȏ�����</td>
					<td bgcolor="#FFFFFF"><img src="img/one/meter/13.gif" border="0" width="100" height="45"></td>
					<td bgcolor="#FFFFFF" align="middle"><a href="kaji_sentaku_chusui.php" title="���ǡ�����Хꥹ����Ͽ"><img src="img/one/one_foot03.gif" border="0"></a></td>
					<td bgcolor="#FFFFFF" align="middle"><? disp_master_mark(GANBA_SUSUGI) ?></td>
				</tr>
				<tr>
					<td bgcolor="#FFFFFF" class="tc0">���������ϡ����ί��ʤ��餹�����ȡ��ճ�������Ǥ��ޤ�����������Ȥ��Ȏ�����</td>
					<td bgcolor="#FFFFFF"><img src="img/one/meter/76.gif" border="0" width="100" height="45"></td>
					<td bgcolor="#FFFFFF" align="middle"><a href="kaji_syokkiarai.php" title="���ǡ�����Хꥹ����Ͽ"><img src="img/one/one_foot03.gif" border="0"></a></td>
					<td bgcolor="#FFFFFF" align="middle"><? disp_master_mark(GANBA_SHOKKIARAI) ?></td>
				</tr>
<!-- 2���� -->
				<tr>
					<td rowspan="4" align="center" bgcolor="#FFFA55"><img src="img/character/yellow/eisei.gif"></td>
					<td bgcolor="#FFFFFF" class="tc0">��������Ρ����̤����겹�٤θ�ľ���򤷤Ƥߤޤ��󤫡����岹�٤�1������Ƥϥ����ΤߤȤ���ȡ�������</td>
					<td bgcolor="#FFFFFF"><img src="img/one/meter/83.gif" border="0" width="100" height="45"></td>
<!--					<td bgcolor="#FFFFFF" align="middle"><a href="sim_yokusou.php" title="���ߥ�졼�����"><img src="img/one/one_foot01.gif" border="0"></a></td>-->
					<td bgcolor="#FFFFFF" align="middle"><a href="nyuyoku_yokuso.php" title="���ǡ�����Хꥹ����Ͽ"><img src="img/one/one_foot01.gif" border="0"></a></td>
					<td bgcolor="#FFFFFF" align="middle"><? disp_master_mark(GANBA_YOKUSOU) ?></td>
				</tr>
				<tr>
					<td bgcolor="#FFFFFF" class="tc0">�����λ��ѻ��֤θ�ľ���ϡ����˸���Ū�Ǥ������1ʬ���ġ�������ظ��λ��ѻ��֤򸺤餹�Ȏ�����</td>
					<td bgcolor="#FFFFFF"><img src="img/one/meter/139.gif" border="0" width="100" height="45"></td>
<!--					<td bgcolor="#FFFFFF" align="middle"><a href="sim_shower.php" title="���ߥ�졼�����"><img src="img/one/one_foot01.gif" border="0"></a></td>-->
					<td bgcolor="#FFFFFF" align="middle"><a href="nyuyoku_shower.php" title="���ǡ�����Хꥹ����Ͽ"><img src="img/one/one_foot01.gif" border="0"></a></td>
					<td bgcolor="#FFFFFF" align="middle"><? disp_master_mark(GANBA_SHOWER) ?></td>
				</tr>
<!--
				<tr>
					<td bgcolor="#FFFFFF" class="tc0">�����⡢��Ψ�Τ褤��Τ��ǤƤ��ޤ����������򵡤���Ψ�����פ��ؤ���Ȏ�����</td>
					<td bgcolor="#FFFFFF"><img src="img/one/meter/78.gif" border="0" width="100" height="45"></td>
					<td bgcolor="#FFFFFF" align="middle"><a href="nyuyoku_kyuto_type.php" title="���ǡ�����Хꥹ����Ͽ"><img src="img/one/one_foot03.gif" border="0"></a></td>
					<td bgcolor="#FFFFFF" align="middle"><? disp_master_mark(GANBA_KYUUTOUKI) ?></td>
				</tr>
-->
				<tr>
					<td bgcolor="#FFFFFF" class="tc0">�����إåɤ⡢�긵����å����ʤ餳�ޤ�˻ߤ�뤳�Ȥ��Ǥ��ޤ�������å��������إåɤǡ����1ʬ���ѻ��֤򸺤餹�Ȏ�����</td>
					<td bgcolor="#FFFFFF"><img src="img/one/meter/115.gif" border="0" width="100" height="45"></td>
					<td bgcolor="#FFFFFF" align="middle"><a href="nyuyoku_shower_type.php" title="���ǡ�����Хꥹ����Ͽ"><img src="img/one/one_foot03.gif" border="0"></a></td>
					<td bgcolor="#FFFFFF" align="middle"><? disp_master_mark(GANBA_SHOWER_HEAD) ?></td>
				</tr>
				<tr>
					<td bgcolor="#FFFFFF" class="tc0">���̤���᤭�ǡ�ή�������򤷤Ƥ��ޤ��󤫡��������᤭�ϴ�ˤ���ƻ��Ѥ���Ȏ�����</td>
					<td bgcolor="#FFFFFF"><img src="img/one/meter/311.gif" border="0" width="100" height="45"></td>
					<td bgcolor="#FFFFFF" align="middle"><a href="senmen_shower.php" title="���ǡ�����Хꥹ����Ͽ"><img src="img/one/one_foot03.gif" border="0"></a></td>
					<td bgcolor="#FFFFFF" align="middle"><? disp_master_mark(GANBA_SENGAN) ?></td>
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
		<td align="center"><br><a href="life_style.php#sim_top"><img src="img/one/back3.gif" border="0" width="117" height="36"></a></td>
		<td width="35" valign="bottom"><img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>
<!-- ��ݳѥơ��֥뽪λ -->

</div>
</body>
</html>