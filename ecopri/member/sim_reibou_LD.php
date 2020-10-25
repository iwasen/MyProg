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
include("$inc/sim_reibou_ld.php");
include("$inc/sim_db.php");

// ��˼�μ���ץ������ɽ��
function select_cooler_type($name, $default, $eco = '') {
	$ary = reibou_shurui_name();
	sim_select_ary($name, $ary, $default, $eco);
}

// ���겹�٥ץ������ɽ��
function select_temperature($name, $default, $eco = '') {
	sim_select_no($name, 25, 29, $default, $eco, '', '��');
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

// �������ǥ��饹����
$sim = new sim_reibou_ld;

// DB������ǥǡ������ɤ߹���
$sim->read_eco_data();
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>��˼(��֡���Ʋ)����</title>
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
						<td><img src="img/shindan/title/shindan_reibou_02.gif" border="0" width="399" height="64"></td>
					</tr>
				</table>
				���ʤ��βȤε�֤���˼������ˡ���Ѥ��뤳�ȤǤɤ줯�餤�Ρ�Eco���̡פ����뤫�׻����ޤ�����
				<hr>
				<!-- �����ȥ�ơ��֥뽪λ -->

				<!-- �ե���������ơ��֥�Ϥ߳��� -->
				<form action="res_reibou_LD.php" name="form1" method="post">

					<!-- �����ȥ�1 -->
					<table border="0" width="100%" bordercolordark="#FF0000" bordercolorlight="#FF6600">
						<tr>
							<td colspan="8" bgcolor="#FF0000" align="center">
								<img src="img/shindan/button/new_04.gif" border="0" width="125" height="27">
							</td>
						</tr>

						<tr>
							<td rowspan="2" colspan="2" align="center" bgcolor="#FF6699">���</td>
							<td rowspan="2" align="center" bgcolor="#FFCC00">��˼�μ���<br>
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
							<td align="center" bgcolor="#FF9999">��֡���Ʋ</td>
							<td align="center" bgcolor="#FFCCCC">15��</td>
							<td width="160" height="20" bgcolor="#FFFF99"><? select_cooler_type('cooler_type', $sim->cooler_type, 'cooler_type_e') ?></td>
							<td rowspan="2" width="20" bgcolor="#FFFF99"><? select_temperature('temperature', $sim->temperature, 'temperature_e') ?></td>
							<td width="80" height="20" bgcolor="#FFFF99"><? select_hours1('hours1', $sim->hours1, 'hours1_e') ?>����</td>
							<td width="80" height="20" bgcolor="#FFFF99"><? select_hours2('hours2', $sim->hours2, 'hours2_e') ?>����</td>
							<td width="80" height="20" bgcolor="#FFFF99"><? select_hours3('hours3', $sim->hours3, 'hours3_e') ?>����</td>
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
							<td rowspan="2" align="center" bgcolor="#FFCC00">��˼�μ���<br>
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
							<td width="160" height="20" bgcolor="#FFFF99"><? select_cooler_type('cooler_type_e', $sim->cooler_type_e) ?></td>
							<td rowspan="2" width="20" bgcolor="#FFFF99"><? select_temperature('temperature_e', $sim->temperature_e) ?></td>
							<td width="80" height="20" bgcolor="#FFFF99"><? select_hours1('hours1_e', $sim->hours1_e) ?>����</td>
							<td width="80" height="20" bgcolor="#FFFF99"><? select_hours2('hours2_e', $sim->hours2_e) ?>����</td>
							<td width="80" height="20" bgcolor="#FFFF99"><? select_hours3('hours3_e', $sim->hours3_e) ?>����</td>
						</tr>

					</table>
					<!-- ���Ǿ�� -->

					<!-- 3���ܥơ��֥볫�� -->
					<table border="0" cellspacing="0" cellpadding="0" width="100%" height="200">
						<tr>
							<!-- ����ԡ������������ڡ��� -->
							<td height="10">��</td>
							<td rowspan="2" width="300"><img src="img/shindan/conte/028.gif" border="0" width="300" height="200"></td>
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
										<a href="sim_reibou_LD.php" title="�ꥻ�å�"><img src="img/shindan/button/reset.gif" border="0" width="117" height="36"></a>
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