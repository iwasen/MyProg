<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:LD��˼��������
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/sim_define.php");
include("$inc/sim_sub.php");
include("$inc/sim_danbou.php");
include("$inc/sim_db.php");

// ����˼�μ���ץ������ɽ��
function select_main_heater($name, $default) {
	$ary = danbou_shurui_name();
	unset($ary[6], $ary[7], $ary[8]);
	sim_select_ary($name, $ary, $default, '', ' onchange="onChange_main_heater(this)"');
}

// �����˼�μ���ץ������ɽ��
function select_sub_heater($name, $default, $eco = '') {
	global $sim;

	$ary = danbou_shurui_name();
	if ($sim->main_heater == 5)
		unset($ary[5], $ary[6], $ary[7]);
	else
		unset($ary[1], $ary[2], $ary[3], $ary[4], $ary[5]);
	sim_select_ary($name, $ary, $default, $eco);
}

// ���겹�٥ץ������ɽ��
function select_temperature($name, $default, $eco = '') {
	sim_select_no($name, 16, 25, $default, $eco, '', '��');
}

// ���ѻ��֡ʵ�������˥ץ������ɽ��
function select_hours1($name, $default, $eco = '') {
	sim_select_no($name, 0, 4, $default, $eco);
}

// ���ѻ��֡����ͼ���˥ץ������ɽ��
function select_hours2($name, $default, $eco = '') {
	sim_select_no($name, 0, 3, $default, $eco);
}

// ���ѻ��֡�ͼ���ʹߡ˥ץ������ɽ��
function select_hours3($name, $default, $eco = '') {
	sim_select_no($name, 0, 4, $default, $eco);
}

// �����˼����������ʥ����ʥߥå����ѹ����뤿���
$sub_heater_ary = danbou_shurui_name();

// �������ǥ��饹����
$sim = new sim_danbou;

// DB������ǥǡ������ɤ߹���
$sim->read_eco_data();
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>��˼����</title>
<script type="text/javascript">
<!--
function openAC(str){window.open(str,"AC","width=300,height=300");}
function onChange_main_heater(c) {
	if (c.name == "main_heater") {
		s = document.form1.sub_heater;
		select_list("main_heater_e", c.value);
	} else if (c.name == "main_heater_e")
		s = document.form1.sub_heater_e;
	else
		return;
	switch (c.value) {
	case "1":
	case "2":
	case "3":
	case "4":
		s.options.length = 3;
		s.options[0].value = "6";
		s.options[0].text = "<?=$sub_heater_ary[6]?>";
		s.options[1].value = "7";
		s.options[1].text = "<?=$sub_heater_ary[7]?>";
		s.options[2].value = "8";
		s.options[2].text = "<?=$sub_heater_ary[8]?>";
		break;
	case "5":
		s.options.length = 5;
		s.options[0].value = "1";
		s.options[0].text = "<?=$sub_heater_ary[1]?>";
		s.options[1].value = "2";
		s.options[1].text = "<?=$sub_heater_ary[2]?>";
		s.options[2].value = "3";
		s.options[2].text = "<?=$sub_heater_ary[3]?>";
		s.options[3].value = "4";
		s.options[3].text = "<?=$sub_heater_ary[4]?>";
		s.options[4].value = "8";
		s.options[4].text = "<?=$sub_heater_ary[8]?>";
		break;
	}
}
function select_list(name, val) {
	var f = document.form1;
	var n = f[name].options.length;
	for (i = 0; i < n; i++) {
		if (f[name].options[i].value == val) {
			f[name].selectedIndex = i;
			break;
		}
	}
}
// -->
</script>
</head>

<body bgcolor="#ff0000" leftmargin="8" topmargin="8">

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
						<td><img src="img/shindan/title/shindan_danbou.gif" border="0" width="399" height="64"></td>
					</tr>
				</table>
				���ʤ��βȤ���˼�λ�����ˡ���Ѥ��뤳�ȤǤɤ줯�餤�Ρ�Eco���̡פ����뤫�׻����ޤ�����
				<hr>
				<!-- �����ȥ�ơ��֥뽪λ -->

				<!-- �ե���������ơ��֥�Ϥ߳��� -->
				<form action="res_danbou_LD.php" name="form1" method="post">

					<!-- �����ȥ�1 -->
					<table border="0" width="100%" bordercolordark="#FF0000" bordercolorlight="#FF6600">
						<tr>
							<td colspan="8" bgcolor="#FF0000" align="center">
								<img src="img/shindan/button/new_04.gif" border="0" width="125" height="27">
							</td>
						</tr>

						<tr>
							<td rowspan="2" colspan="2" align="center" bgcolor="#FF6699">���</td>
							<td rowspan="2" colspan="2" align="center" bgcolor="#FFCC00">��˼�μ���<br>
								<a href="#" onClick="openAC('AC.php')">������������</a>
							</td>
							<td rowspan="2" align="center" bgcolor="#CCFF00">����<br>����</td>
							<td colspan="3" align="center" bgcolor="#99FFFF">�����λ��ѻ�����</td>
						</tr>

						<tr>
							<td width="80" height="20" align="center" bgcolor="#CCFFFF">��������</td>
							<td width="80" height="20" align="center" bgcolor="#CCFFFF">���ͼ��</td>
							<td width="80" height="20" align="center" bgcolor="#CCFFFF">ͼ���ʹ�</td>
						</tr>

						<tr>
							<td rowspan="2" align="center" bgcolor="#FF9999">��֡���Ʋ</td>
							<td rowspan="2" align="center" bgcolor="#FFCCCC">15��</td>
							<td width="40" align="center" bgcolor="#FFFF99">��</td>
							<td width="120" height="20" bgcolor="#FFFF99"><? select_main_heater('main_heater', $sim->main_heater) ?></td>
							<td rowspan="2" width="20" bgcolor="#FFFF99"><? select_temperature('temperature', $sim->temperature, 'temperature_e') ?></td>
							<td width="80" height="20" bgcolor="#FFFF99"><? select_hours1('main_hours1', $sim->main_hours1, 'main_hours1_e') ?>����</td>
							<td width="80" height="20" bgcolor="#FFFF99"><? select_hours2('main_hours2', $sim->main_hours2, 'main_hours2_e') ?>����</td>
							<td width="80" height="20" bgcolor="#FFFF99"><? select_hours3('main_hours3', $sim->main_hours3, 'main_hours3_e') ?>����</td>
						</tr>

						<tr>
							<td width="40" align="center" bgcolor="#FFFF99">���</td>
							<td width="120" height="20" bgcolor="#FFFF99"><? select_sub_heater('sub_heater', $sim->sub_heater, 'sub_heater_e') ?></td>
							<td width="80" height="20" bgcolor="#FFFF99"><? select_hours1('sub_hours1', $sim->sub_hours1, 'sub_hours1_e') ?>����</td>
							<td width="80" height="20" bgcolor="#FFFF99"><? select_hours2('sub_hours2', $sim->sub_hours2, 'sub_hours2_e') ?>����</td>
							<td width="80" height="20" bgcolor="#FFFF99"><? select_hours3('sub_hours3', $sim->sub_hours3, 'sub_hours3_e') ?>����</td>
						</tr>
					</table>
					<!-- 1���ܥơ��֥뽪λ -->
					<br><br><br>

					<!-- ���Ǿ�� -->
					<table border="0" width="100%" bordercolordark="#FF0000" bordercolorlight="#FF6600">
						<tr>
							<td colspan="8" align="center" bgcolor="#67fac6">
								<img src="img/shindan/button/new_05.gif" border="0" width="114" height="27">
							</td>
						</tr>

						<tr>
							<td rowspan="2" colspan="2" align="center" bgcolor="#FF6699">���</td>
							<td rowspan="2" colspan="2" align="center" bgcolor="#FFCC00">��˼�μ���<br>
								<a href="#" onClick="openAC('AC.php')">������������</a>
							</td>
							<td rowspan="2" align="center" bgcolor="#CCFF00">����<br>����</td>
							<td colspan="3" align="center" bgcolor="#99FFFF">�����λ��ѻ�����</td>
						</tr>

						<tr>
							<td width="80" height="20" align="center" bgcolor="#CCFFFF">��������</td>
							<td width="80" height="20" align="center" bgcolor="#CCFFFF">���ͼ��</td>
							<td width="80" height="20" align="center" bgcolor="#CCFFFF">ͼ���ʹ�</td>
						</tr>

						<tr>
							<td rowspan="2" align="center" bgcolor="#FF9999">��֡���Ʋ</td>
							<td rowspan="2" align="center" bgcolor="#FFCCCC">15��</td>
							<td width="40" align="center" bgcolor="#FFFF99">��</td>
							<td width="120" height="20" bgcolor="#FFFF99"><? select_main_heater('main_heater_e', $sim->main_heater_e) ?></td>
							<td rowspan="2" width="20" bgcolor="#FFFF99"><? select_temperature('temperature_e', $sim->temperature_e) ?></td>
							<td width="80" height="20" bgcolor="#FFFF99"><? select_hours1('main_hours1_e', $sim->main_hours1_e) ?>����</td>
							<td width="80" height="20" bgcolor="#FFFF99"><? select_hours2('main_hours2_e', $sim->main_hours2_e) ?>����</td>
							<td width="80" height="20" bgcolor="#FFFF99"><? select_hours3('main_hours3_e', $sim->main_hours3_e) ?>����</td>
						</tr>

						<tr>
							<td width="40" align="center" bgcolor="#FFFF99">���</td>
							<td width="120" height="20" bgcolor="#FFFF99"><? select_sub_heater('sub_heater_e', $sim->sub_heater_e) ?></td>
							<td width="80" height="20" bgcolor="#FFFF99"><? select_hours1('sub_hours1_e', $sim->sub_hours1_e) ?>����</td>
							<td width="80" height="20" bgcolor="#FFFF99"><? select_hours2('sub_hours2_e', $sim->sub_hours2_e) ?>����</td>
							<td width="80" height="20" bgcolor="#FFFF99"><? select_hours3('sub_hours3_e', $sim->sub_hours3_e) ?>����</td>
						</tr>
					</table>
					<!-- ���Ǿ�� -->

					<!-- 3���ܥơ��֥볫�� -->
					<table border="0" cellspacing="0" cellpadding="0" width="100%" height="200">
						<tr>
							<!-- ����ԡ������������ڡ��� -->
							<td height="10">��</td>
							<td rowspan="2" width="300"><img src="img/shindan/conte/031.gif" border="0" width="234" height="167"></td>
						</tr>
						<tr>
							<td height="190">

							<!-- ������ѥơ��֥볫�� -->
							<table border="0" width="100%" height="180" cellspacing="0" cellpadding="0">
								<tr height="60" bgcolor="#FFFFFF">
									<td colspan="3"><br></td>
								</tr>
								<tr height="60">
									<!-- ��󥯳��� -->
									<!-- ��󥯸���Ƚ��򤷤Ƥɤ��餫�����ܥ����ɽ�� -->
									<td align="center">
										<a href="<?=$_SESSION['s_one_advice']?>"><img src="img/shindan/button/back3.gif" border="0" width="117" height="36"></a>
									</td>

									<!-- ���ꥻ�åȵ�ǽ��Ĥ��� -->
									<td align="center">
										<a href="sim_danbou_LD.php" title="�ꥻ�å�"><img src="img/shindan/button/reset.gif" border="0" width="117" height="36"></a>
									</td>
									<td align="center">
										<a href="javascript:document.form1.submit()" title="���Ƿ��"><img src="img/shindan/button/shindan.gif" border="0" width="117" height="36"></a>
									</td>
									<!-- ��󥯽�λ -->
								</tr>
								<tr height="60">
									<td colspan="3"><br></td>
								</tr>
							</table>
							<!-- ������ѥơ��֥뽪λ -->
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



</body>
</html>