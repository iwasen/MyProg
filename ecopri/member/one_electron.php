<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:���ݥ���ȥ��ɥХ������ŵ��ԡ�
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
$_SESSION['s_one_advice'] = 'one_electron.php';

// ���ͥ륮��ʬ�ॻ�å�
$_SESSION['s_eng_bunrui'] = ENG_DENKI;

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
					<td height=80><img src="img/shindan/sim_water/denki_title.gif" border="0" width="417" height="64"></td>
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
					<th align="center" bgcolor="gold">�ޥ�����</th>
				</tr>
<!-- 1���� -->
				<tr>
					<td rowspan="2" align="center" bgcolor="#FFFA55"><img src="img/character/yellow/kaji.gif"></td>
					<td bgcolor="#FFFFFF" class="tc0">����ϥ�����ʨ�������ŵ��ݥåȤǤ��ݲ��ϡ����Ƥߤޤ��󤫡�������ʨƭ�������塢��ˡ�Ӥ��ݲ�����Ȏ�����</td>
					<td bgcolor="#FFFFFF"><img src="img/one/meter/43.gif" border="0" width="100" height="45"></td>
					<td bgcolor="#FFFFFF" align="center"><a href="kaji_yuwakashi.php" title="���ǡ�����Хꥹ����Ͽ"><img src="img/one/one_foot01.gif" border="0"></a></td>
					<td bgcolor="#FFFFFF" align="center"><? disp_master_mark(GANBA_YUWAKASHI) ?></td>
				</tr>
				<tr>
					<td bgcolor="#FFFFFF" class="tc0">�������絡�ϡ��������λž夲�����������٤�������Ǥ����������˼���˴������塢�ž夲��������Ѥ���Ȏ�����</td>
					<td bgcolor="#FFFFFF"><img src="img/one/meter/49.gif" border="0" width="100" height="45"></td>
					<td bgcolor="#FFFFFF" align="center"><a href="kaji_kansou.php" title="���ǡ�����Хꥹ����Ͽ"><img src="img/one/one_foot01.gif" border="0"></a></td>
					<td bgcolor="#FFFFFF" align="center"><? disp_master_mark(GANBA_KANSOU) ?></td>
				</tr>
<!-- 2���� -->
				<tr>
					<td rowspan="2" align="center" bgcolor="#FFFA55"><img src="img/character/yellow/goraku.gif"></td>
					<td bgcolor="#FFFFFF" class="tc0">�ԣ֤ΤĤ��äѤʤ����ʤ����İ�Ϲ����ޤ��礦���ԣ֤Τʤ����İ����������ֹ�����Ȏ�����</td>
					<td bgcolor="#FFFFFF"><img src="img/one/meter/27.gif" border="0" width="100" height="45"></td>
					<td bgcolor="#FFFFFF" align="center"><a href="goraku_nagaraTV.php" title="���ǡ�����Хꥹ����Ͽ"><img src="img/one/one_foot03.gif" border="0"></a></td>
					<td bgcolor="#FFFFFF" align="center"><? disp_master_mark(GANBA_TV_KOUDOU) ?></td>
				</tr>
				<tr>
					<td bgcolor="#FFFFFF" class="tc0">�֥饦��ɣԣ֤ϡ��վ��ԣ֤��󣱡����ܤ��ŵ�����񤷤ޤ����֥饦��ɣԣ֤򡢱վ��ԣ֤��ؤ���Ȏ�����</td>
					<td bgcolor="#FFFFFF"><img src="img/one/meter/39.gif" border="0" width="100" height="45"></td>
					<td bgcolor="#FFFFFF" align="center"><a href="goraku_ekisyoTV.php" title="���ǡ�����Хꥹ����Ͽ"><img src="img/one/one_foot03.gif" border="0"></a></td>
					<td bgcolor="#FFFFFF" align="center"><? disp_master_mark(GANBA_TV_KIKI) ?></td>
				</tr>
<!-- 3���� -->
				<tr>
					<td align="center" bgcolor="#FFFA55"><img src="img/character/yellow/eisei.gif"></td>
					<td bgcolor="#FFFFFF" class="tc0">�ȥ���β��������غ¤ϡ������ޡ��ʤɤǤ��ޤ��ON/OFF���ޤ��礦���������������Τߤ����ŤȤ���Ȏ�����</td>
					<td bgcolor="#FFFFFF"><img src="img/one/meter/43.gif" border="0" width="100" height="45"></td>
					<td bgcolor="#FFFFFF" align="center"><a href="toilet_senjo.php?f=1" title="���ǡ�����Хꥹ����Ͽ"><img src="img/one/one_foot03.gif" border="0"></a></td>
					<td bgcolor="#FFFFFF" align="center"><? disp_master_mark(GANBA_TOILET_TSUUDEN) ?></td>
				</tr>
<!--
				<tr>
					<td bgcolor="#FFFFFF" class="tc0">���ٻȤ��ʤ��ش����ڷ�������巿</td>
					<td bgcolor="#FFFFFF"><img src="img/one/meter/6.gif" border="0" width="100" height="45"></td>
					<td bgcolor="#FFFFFF" align="center"><a href="toilet_senjo.php?f=2" title="���ǡ�����Хꥹ����Ͽ"><img src="img/one/one_foot03.gif" border="0"></a></td>
					<td bgcolor="#FFFFFF" align="center"><? disp_master_mark(GANBA_TOILET_SESSUI) ?></td>
				</tr>
-->
<!-- 4���� -->
				<tr>
					<td rowspan="4" align="center" bgcolor="#FFFA55"><img src="img/character/yellow/kucyo.gif"></td>
					<td bgcolor="#FFFFFF" class="tc0">���������������֤�ľ���ƤߤƤ����������ä�LDK���ݥ���ȤǤ���LDK�ξ�����ָ������ؤ������ޤ����������������Ȏ�����</td>
					<td bgcolor="#FFFFFF"><img src="img/one/meter/99.gif" border="0" width="100" height="45"></td>
					<td bgcolor="#FFFFFF" align="center"><a href="syoumei_LD_keikou.php" title="���ǡ�����Хꥹ����Ͽ"><img src="img/one/one_foot01.gif" border="0"></a></td>
					<td bgcolor="#FFFFFF" align="center"><? disp_master_mark(GANBA_SHOUMEI) ?></td>
				</tr>
				<tr>
					<td bgcolor="#FFFFFF" class="tc0">��˼�ϥ�������λ��ѻ��֤�û��ˤ��ơ��������������Ѥ��ƤߤƲ����������겹�٤򣱡�夲�ƻ��Ѥ���ͼ�������֤���򳫤�����������Ȏ�����</td>
					<td bgcolor="#FFFFFF"><img src="img/one/meter/82.gif" border="0" width="100" height="45"></td>
					<td bgcolor="#FFFFFF" align="center"><a href="kucho_ryo.php" title="���ǡ�����Хꥹ����Ͽ"><img src="img/one/one_foot01.gif" border="0"></a></td>
					<td bgcolor="#FFFFFF" align="center"><? disp_master_mark(GANBA_REIBOU_LD) ?></td>
				</tr>
				<tr>
					<td bgcolor="#FFFFFF" class="tc0">��˼�����겹�٤�夲��Τϡ����Ťˤ��ʤ����Ū�Ǥ��������塢�ļ��Υ����������겹�٤򣲡�夲��Ȏ�����</td>
					<td bgcolor="#FFFFFF"><img src="img/one/meter/93.gif" border="0" width="100" height="45"></td>
					<td bgcolor="#FFFFFF" align="center"><a href="kucho_riebou_ko.php" title="���ǡ�����Хꥹ����Ͽ"><img src="img/one/one_foot01.gif" border="0"></a></td>
					<td bgcolor="#FFFFFF" align="center"><? disp_master_mark(GANBA_REIBOU_KO) ?></td>
				</tr>
				<tr>
					<td bgcolor="#FFFFFF" class="tc0">��˼�ϡ�LDK�ε�����Ȥ߹�碌�ȡ��Ȥ����Ǥ��ʤ�����̤��㤤�ޤ��������ե���ҡ������򡢥������徲��˼�ȥ��������ʻ�Ѥˤ���Ȏ�����</td>
					<td bgcolor="#FFFFFF"><img src="img/one/meter/377.gif" border="0" width="100" height="45"></td>
					<td bgcolor="#FFFFFF" align="center"><a href="kucho_danbou.php" title="���ǡ�����Хꥹ����Ͽ"><img src="img/one/one_foot01.gif" border="0"></a></td>
					<td bgcolor="#FFFFFF" align="center"><? disp_master_mark(GANBA_DANBOU_LD) ?></td>
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
		<td align="center" height=60><br><a href="life_style.php#sim_top"><img src="img/one/back3.gif" border="0" width="117" height="36"></a></td>
		<td width="35" valign="bottom"><img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>
<!-- ��ݳѥơ��֥뽪λ -->

</div>
</body>
</html>