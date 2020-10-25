<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:������������
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/sim_define.php");
include("$inc/sim_sub.php");
include("$inc/sim_shower.php");
include("$inc/sim_db.php");

// ή��̵ͭ�ץ������ɽ��
function select_ryuuryou($name, $default) {
	$ary = array(
		12 => '�����12L/ʬ��',
		10 => 'ɸ���10L/ʬ��',
		8  => '����8L/ʬ��'
	);
	sim_select_ary($name, $ary, $default);
}

// �򲹡ʲơ˥ץ������ɽ��
function select_yuon_summer($name, $default) {
	sim_select_yuon($name, 40, $default);
}

// �򲹡ʽս��˥ץ������ɽ��
function select_yuon_sp_aut($name, $default) {
	sim_select_yuon($name, 41, $default);
}

// �򲹡��ߡ˥ץ������ɽ��
function select_yuon_winter($name, $default) {
	sim_select_yuon($name, 42, $default);
}

// �򲹡ʶ��̡˥ץ������ɽ��
function sim_select_yuon($name, $std, $default) {
	for ($i = 37; $i <= 45; $i++)
		$ary[$i] = ($i == $std ? 'ɸ��' : '') . $i;
	sim_select_ary($name, $ary, $default);
}

// ����勵���ץץ������ɽ��
function select_boiler_type($name, $default) {
	$ary = array(
		1 => '����������',
		2 => '�ŵ�������',
		3 => '����������'
	);
	sim_select_ary($name, $ary, $default);
}

// �����︺���֥ץ������ɽ��
function select_shower_jikan($name, $default) {
	sim_select_no($name, 0, 10, $default);
}

// �������ǥ��饹����
$sim = new sim_shower;

// DB������ǥǡ������ɤ߹���
$sim->read_eco_data();
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>��������</title>
</head>

<body bgcolor="#FF0000" leftmargin="8" topmargin="8">

<div align="center">

<!-- ��ݳѥơ��֥� -->
<table border="0" cellpadding="0" cellspacing="0" width="714" height="90%" bgcolor="#FFFFFF">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF">
			<img src="img/kado_1.gif" width="35" height="35">
		</td>
		<td align="center" valign="top">

			<!-- ���������� -->
				<!-- �����ȥ�ơ��֥� -->
				<table border="0" cellpadding="0" cellspacing="2">
					<tr>
						<td><img src="img/shindan/title/shindan_nyuuyoku_02.gif" border="0" width="384" height="68"></td>
					</tr>
				</table>
				���ʤ��βȤǡ�������Υ������ظ�������ˡ���Ѥ��뤳�ȤǤɤ줯�餤�Ρ�Eco���̡פ����뤫�׻����ޤ�����
				<hr>
				<!-- �����ȥ�ơ��֥뽪λ -->

				<!-- �ե���������ơ��֥�Ϥ߳��� -->
				<form action="res_shower.php" name="form1" method="post">
					<table border="0" width="100%" bordercolordark="#FF0000" bordercolorlight="#FF6600">
						<tr>
							<td colspan="8" bgcolor="#FF0000" align="center">
								<img src="img/shindan/button/new_04.gif" border="0" width="125" height="27">
							</td>
						</tr>

						<!-- �����ȥ�1 -->
						<tr>
							<td rowspan="2" width="110" bgcolor="#FF9191">1.�������ظ�</td>
							<td align="left" width="145" bgcolor="#FF8800">ή�̡�</td>
							<td width="8" rowspan="3"></td>
							<td><? select_ryuuryou('ryuuryou', $sim->ryuuryou) ?><br>10L/ʬ�ϡ�1�ö��ǥ��åפ����äѤ��ˤʤ��</td>
						</tr>

						<tr>
							<td align="left" bgcolor="#FF8800" bgcolor="#FF8800">�򲹡�</td>
							<td><font color="#0000ff">��</font>��<? select_yuon_summer('yuon_summer', $sim->yuon_summer) ?>��
								<font color="#ff69b4">��</font>/<font color="#ffa500">��</font>��<? select_yuon_sp_aut('yuon_sp_aut', $sim->yuon_sp_aut) ?>��
								<font color="lightskyblue">��</font>��<? select_yuon_winter('yuon_winter', $sim->yuon_winter) ?>��
							</td>
						</tr>

						<!-- �����ȥ�2 -->
						<tr>
							<td colspan="2" align="left" bgcolor="#00FF88">2.����勵���ס�</td>
							<td><? select_boiler_type('boiler_type', $sim->boiler_type) ?></td>
						</tr>
					</table>
					<!-- 1���ܥơ��֥뽪λ -->

					<!-- 2���ܥơ��֥볫�� -->
					<table border="0" cellspacing="0" cellpadding="0" width="100%" height="200">
						<tr>
							<!-- ����ԡ������������ڡ��� -->
							<td height="10">��</td>
							<td rowspan="2" width="300"><img src="img/shindan/conte/044.gif" border="0" width="230" height="168"></td>
						</tr>
						<tr>
							<td height="190">
							<!-- ���Ǿ��ơ��֥볫�� -->
							<table border="0" width="100%" height="180">
								<tr height="40">
									<td colspan="8" align="center" bgcolor="#67fac6">
										<img src="img/shindan/button/new_05.gif" border="0" width="114" height="27">
									</td>
								</tr>

								<tr>
									<td colspan="3">�������ظ����ѻ��֤������� <? select_shower_jikan('shower_jikan', $sim->shower_jikan) ?> ʬû�����ޤ���
									</td>
								</tr>
								<tr height="50">
									<td colspan="3">��</td>
								</tr>
								<tr height="50">
									<!-- ��󥯳��� -->
									<!-- ��󥯸���Ƚ��򤷤Ƥɤ��餫�����ܥ����ɽ�� -->
									<td align="center">
										<a href="<?=$_SESSION['s_one_advice']?>"><img src="img/shindan/button/back3.gif" border="0" width="117" height="36"></a>
									</td>
									<!-- ���ꥻ�åȵ�ǽ��Ĥ��� -->
									<td align="center">
										<a href="sim_shower.php" title="�ꥻ�å�"><img src="img/shindan/button/reset.gif" border="0" width="117" height="36"></a>
									</td>
									<td align="center">
										<a href="javascript:document.form1.submit()" title="���Ƿ��"><img src="img/shindan/button/shindan.gif" border="0" width="117" height="36"></a>
									</td>
									<!-- ��󥯽�λ -->
								</tr>
							</table>
							<!-- ���Ǿ��ơ��֥뽪λ -->
							</td>
						</tr>
					</table>
				</form>
				<!-- �ե���������ơ��֥�Ϥ߽�λ -->
			<!-- �����ޤ� -->
		</td>
		<td width="35" align="right" valign="top" bgcolor="#FFFFFF">
			<img src="img/kado_2.gif" width="35" height="35">
		</td>
	</tr>

	<tr>
		<td width="35" valign="bottom"><img src="img/kado_3.gif" width="35" height="35"></td>
		<td><hr width="95%" size="1"></td>
		<td width="35" valign="bottom"><img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>
<!-- ��ݳѥơ��֥뽪λ -->



</div>
</body>
</html>