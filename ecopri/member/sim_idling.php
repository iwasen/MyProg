<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:��ž��ˡ��������
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/sim_define.php");
include("$inc/sim_sub.php");
include("$inc/sim_unten.php");
include("$inc/sim_db.php");

// �ӵ��̡ʥ������֡˥ץ������ɽ��
function select_engine_vol1($name, $default) {
	$ary = array(
		1 => '660',
		2 => '1000-1500',
		3 => '1600-2000',
		4 => '2200-2500',
		5 => '2600-3000'
	);
	sim_select_ary($name, $ary, $default);
}

// �ӵ��̡ʥǥ�������֡˥ץ������ɽ��
function select_engine_vol2($name, $default) {
	$ary = array(
		4 => '2200-2500',
		5 => '2600-3000'
	);
	sim_select_ary($name, $ary, $default);
}

// �ӵ��̡ʥϥ��֥�åɼ֡˥ץ������ɽ��
function select_engine_vol3($name, $default) {
	$ary = array(
		2 => '1000-1500',
		3 => '1600-2000',
		4 => '2200-2500',
		5 => '2600-3000'
	);
	sim_select_ary($name, $ary, $default);
}

// ���Ե�Υ�ץ������ɽ��
function select_kyori($name, $default) {
	sim_select_no($name, 100, 1000, $default, '', '', '', 100);
}

// �����ɥ�󥰻��֥ץ������ɽ��
function select_idling($name, $default) {
	sim_select_no($name, 0, 60, $default, '', '', '', 10);
}

// ���Ե�Υ�︺�ץ������ɽ��
function select_kyori_e($name, $default) {
	sim_select_no($name, 0, 100, $default, '', '', '', 10);
}

// �����ɥ�󥰺︺���֥ץ������ɽ��
function select_idling_e($name, $default) {
	sim_select_no($name, 0, 100, $default, '', '', '', 10);
}

// �������ǥ��饹����
$sim = new sim_unten;

// DB������ǥǡ������ɤ߹���
$sim->read_eco_data();
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>��ž��ˡ����</title>
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
						<td><img src="img/shindan/title/shindan_gasoline.gif" border="0" width="414" height="72"></td>
					</tr>
				</table>
				���ʤ��μ֤α�ž��ˡ���Ѥ��뤳�ȤǤɤ줯�餤�Ρ�Eco���̡פ����뤫�׻����ޤ�����
				<hr>
				<!-- �����ȥ�ơ��֥뽪λ -->

				<!-- �ե���������ơ��֥�Ϥ߳��� -->
				<form action="res_idling.php" name="form1" method="post">
					<table border="0" width="100%" bordercolordark="#FF0000" bordercolorlight="#FF6600">
						<tr>
							<td colspan="8" bgcolor="#FF0000" align="center">
								<img src="img/shindan/button/new_04.gif" border="0" width="125" height="27">
							</td>
						</tr>

						<!-- �����ȥ�1 ������饸���ܥ���ˤ�걦�Υץ������ͭ���ˤʤ� -->
						<tr>
							<td rowspan="3" colspan="2" width="110" bgcolor="#FF9191">1.�֤μ���</td>
							<td><input type="radio" name="car_cd" <?=value_checked('1', $sim->car_cd)?> tabindex="1">��������</td>
							<td rowspan="3" width="8"></td>
							<td><? select_engine_vol1('engine_vol[1]', $sim->engine_vol[1]) ?>���</td>
						</tr>

						<tr>
							<td><input type="radio" name="car_cd" <?=value_checked('2', $sim->car_cd)?> tabindex="2">�ǥ��������</td>
							<td><? select_engine_vol2('engine_vol[2]', $sim->engine_vol[2]) ?>���</td>
						</tr>

						<tr>
							<td><input type="radio" name="car_cd" <?=value_checked('2', $sim->car_cd)?> tabindex="3">�ϥ��֥�åɼ�</td>
							<td><? select_engine_vol3('engine_vol[3]', $sim->engine_vol[3]) ?>���</td>
						</tr>

						<tr>
							<td rowspan="2" width="110" bgcolor="#FF9191">2.��ž��ˡ</td>
							<td align="left" bgcolor="#FF8800" bgcolor="#FF8800">������Ե�Υ��</td>
							<td>�� <? select_kyori('kyori', $sim->kyori) ?>���</td>
						</tr>

						<tr>
							<td align="left" bgcolor="#FF8800" bgcolor="#FF8800">�����������<br>�����Υ����ɥ�󥰻��֡�</td>
							<td>�� <? select_idling('idling', $sim->idling) ?>ʬ</td>
						</tr>
					</table>
					<!-- 1���ܥơ��֥뽪λ -->

					<!-- 2���ܥơ��֥볫�� -->
					<table border="0" cellspacing="0" cellpadding="0" width="100%" height="200">
						<tr>
							<!-- ����ԡ������������ڡ��� -->
							<td height="10">��</td>
							<td rowspan="2" width="300"><img src="img/shindan/conte/069.gif" border="0" width="300" height="200"></td>
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
									<td colspan="3">������Ե�Υ�� <? select_kyori_e('kyori_e', $sim->kyori_e) ?>��
										<br>�����Υ����ɥ�󥰻��֤� <? select_idling_e('idling_e', $sim->idling_e) ?>�󾯤ʤ����ޤ���
									</td>
								</tr>

								<tr height="50">
									<td colspan="3">��</td>
								</tr>
								<tr height="50">
									<!-- ��󥯳��� -->
									<td align="center">
										<a href="<?=$_SESSION['s_one_advice']?>" title="���ݥ���ȥ��ɥХ������������"><img src="img/shindan/button/back3.gif" border="0" width="117" height="36"></a>
									</td>
									<!-- ���ꥻ�åȵ�ǽ��Ĥ��� -->
									<td align="center">
										<a href="sim_idling.php" title="�ꥻ�å�"><img src="img/shindan/button/reset.gif" border="0" width="117" height="36"></a>
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