<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:�ļ���˼��������
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/sim_define.php");
include("$inc/sim_sub.php");
include("$inc/sim_reibou_ko.php");
include("$inc/sim_db.php");

// ��˼�μ���ץ������ɽ��
function select_cooler_type($name, $default, $eco = '') {
	global $cooler_type_ary;
	sim_select_ary($name, $cooler_type_ary, $default, $eco);
}

// ���겹�٥ץ������ɽ��
function select_temperature($name, $default, $eco = '') {
	sim_select_no($name, 25, 29, $default, $eco, '', '��');
}

// ���ѻ��֥ץ������ɽ��
function select_hours($name, $default, $eco = '') {
	sim_select_no($name, 0, 11, $default, $eco);
}

// ��˼�μ������
$cooler_type_ary = reibou_shurui_name();

// �������ǥ��饹����
$sim = new sim_reibou_ko;

// DB������ǥǡ������ɤ߹���
$sim->read_eco_data();
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>��˼(�ļ�)����</title>
<script type="text/javascript">
<!--
function openAC(str){window.open(str,"AC","width=300,height=300");}
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
//-->
</script>
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
						<td><img src="img/shindan/title/shindan_reibou_01.gif" border="0" width="346" height="64"></td>
					</tr>
				</table>
				���ʤ��βȤν��������˼������ˡ���Ѥ��뤳�ȤǤɤ줯�餤�Ρ�Eco���̡פ����뤫�׻����ޤ�����
				<hr>
				<!-- �����ȥ�ơ��֥뽪λ -->

				<!-- �ե���������ơ��֥�Ϥ߳��� -->
				<form action="res_reibou_ko.php" name="form1" method="post">
					<table border="0" width="100%" bordercolordark="#FF0000" bordercolorlight="#FF6600">
						<tr>
							<td colspan="8" bgcolor="#FF0000" align="center">
								<img src="img/shindan/button/new_04.gif" border="0" width="125" height="27">
							</td>
						</tr>

						<!-- �����ȥ�1 -->
						<tr>
							<td colspan="2" bgcolor="#FF8800">���</td>
							<td bgcolor="#FFFF99">��˼�μ���<br>
								<a href="#" onClick="openAC('AC.php')">������������</a>
							</td>
							<td bgcolor="#FFFF99">���겹��</td>
							<td bgcolor="#FFFF99">������λ��ѻ���</td>
						</tr>

						<tr>
							<td align="left" bgcolor="#FF8800">1.�翲����</td>
							<td>8��</td>
							<td>��<? select_cooler_type('cooler_type_ary[1]', $sim->cooler_type_ary[1], 'cooler_type_ary_e[1]') ?></td>
							<td><? select_temperature('temperature_ary[1]', $sim->temperature_ary[1], 'temperature_ary_e[1]') ?></td>
							<td><? select_hours('hours_ary[1]', $sim->hours_ary[1], 'hours_ary_e[1]') ?>����</td>
						</tr>

						<tr>
							<td align="left" bgcolor="#FF8800">2.�Ҷ���(���)��</td>
							<td>6��</td>
							<td>��<? select_cooler_type('cooler_type_ary[2]', $sim->cooler_type_ary[2], 'cooler_type_ary_e[2]') ?></td>
							<td><? select_temperature('temperature_ary[2]', $sim->temperature_ary[2], 'temperature_ary_e[2]') ?></td>
							<td><? select_hours('hours_ary[2]', $sim->hours_ary[2], 'hours_ary_e[2]') ?>����</td>
						</tr>

						<tr>
							<td align="left" bgcolor="#FF8800">3.�Ҷ���(��¦)��</td>
							<td>6��</td>
							<td>��<? select_cooler_type('cooler_type_ary[3]', $sim->cooler_type_ary[3], 'cooler_type_ary_e[3]') ?></td>
							<td><? select_temperature('temperature_ary[3]', $sim->temperature_ary[3], 'temperature_ary_e[3]') ?></td>
							<td><? select_hours('hours_ary[3]', $sim->hours_ary[3], 'hours_ary_e[3]') ?>����</td>
						</tr>

					</table>
					<!-- 1���ܥơ��֥뽪λ -->
					<br><br><br>
					<!-- ���Ǿ�ﳫ�� -->
					<table border="0" width="100%" bordercolordark="#FF0000" bordercolorlight="#FF6600">
						<tr>
							<td colspan="8" align="center" bgcolor="#67fac6">
								<img src="img/shindan/button/new_05.gif" border="0" width="114" height="27">
							</td>
						</tr>

						<!-- �����ȥ�1 -->
						<tr>
							<td colspan="2" bgcolor="#FF8800">���</td>
							<td bgcolor="#FFFF99">��˼�μ���<br>
								<a href="#" onClick="openAC('AC.php')">������������</a>
							</td>
							<td bgcolor="#FFFF99">���겹��</td>
							<td bgcolor="#FFFF99">������λ��ѻ���</td>
						</tr>

						<tr>
							<td align="left" bgcolor="#FF8800">1.�翲����</td>
							<td>8��</td>
							<td>��<? select_cooler_type('cooler_type_ary_e[1]', $sim->cooler_type_ary_e[1]) ?></td>
							<td><? select_temperature('temperature_ary_e[1]', $sim->temperature_ary_e[1]) ?></td>
							<td><? select_hours('hours_ary_e[1]', $sim->hours_ary_e[1]) ?>����</td>
						</tr>

						<tr>
							<td align="left" bgcolor="#FF8800">2.�Ҷ���(���)��</td>
							<td>6��</td>
							<td>��<? select_cooler_type('cooler_type_ary_e[2]', $sim->cooler_type_ary_e[2]) ?></td>
							<td><? select_temperature('temperature_ary_e[2]', $sim->temperature_ary_e[2]) ?></td>
							<td><? select_hours('hours_ary_e[2]', $sim->hours_ary_e[2]) ?>����</td>
						</tr>

						<tr>
							<td align="left" bgcolor="#FF8800">3.�Ҷ���(��¦)��</td>
							<td>6��</td>
							<td>��<? select_cooler_type('cooler_type_ary_e[3]', $sim->cooler_type_ary_e[3]) ?></td>
							<td><? select_temperature('temperature_ary_e[3]', $sim->temperature_ary_e[3]) ?></td>
							<td><? select_hours('hours_ary_e[3]', $sim->hours_ary_e[3]) ?>����</td>
						</tr>

					</table>
					<!-- ���Ǿ�ｪλ -->
					<br>
					<!-- 3���ܥơ��֥볫�� -->
					<table border="0" cellspacing="0" cellpadding="0" width="100%" height="200">
						<tr>
							<!-- ����ԡ������������ڡ��� -->
							<td height="10">��</td>
							<td rowspan="2" width="300"><img src="img/shindan/conte/025.gif" border="0" width="300" height="200"></td>
						</tr>
						<tr>
							<td height="190">

							<!-- ������ѥơ��֥볫�� -->
							<table border="0" width="100%" height="180">
								<tr height="60" bgcolor="#FFFFFF">
									<td colspan="3"><br></td>
								</tr>
								<tr height="60">
									<!-- ��󥯳��� -->
									<td align="center">
										<a href="<?=$_SESSION['s_one_advice']?>" title="���ݥ���ȥ��ɥХ����ŵ���"><img src="img/shindan/button/back3.gif" border="0" width="117" height="36"></a>
									</td>
									<!-- ���ꥻ�åȵ�ǽ��Ĥ��� -->
									<td align="center">
										<a href="sim_reibou_ko.php" title="�ꥻ�å�"><img src="img/shindan/button/reset.gif" border="0" width="117" height="36"></a>
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



</div>
</body>
</html>