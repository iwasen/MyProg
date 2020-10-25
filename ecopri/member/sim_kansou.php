<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:��ʨ����������
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/sim_define.php");
include("$inc/sim_sub.php");
include("$inc/sim_kansou.php");
include("$inc/sim_db.php");

// ���絡����ץ������ɽ��
function select_dryer_type($name, $default, $eco = '') {
	$ary = kansou_shurui_name();
	sim_select_ary($name, $ary, $default, $eco);
}

// ���絡�����̥ץ������ɽ��
function select_capacity($name, $default) {
	sim_select_no($name, 3, 5, $default);
}

// ���絡���Ѳ���ץ������ɽ��
function select_times($name, $default) {
	$ary[0] = '���Ѥ��ʤ�';
	$ary[1] = '1';
	$ary[2] = '2';
	$ary[3] = '3';
	sim_select_ary($name, $ary, $default);
}

// ������ˡ�饸���ܥ���ɽ��
function radio_method($name, $default, $eco = '') {
	$ary = kansou_houhou_name();
	sim_radio_ary($name, $ary, $default, $eco, '<br>');
}

// ������ˡ�ץ������ɽ��
function select_method($name, $default) {
	$ary = kansou_houhou_name();
	sim_select_ary($name, $ary, $default);
}

// �������ǥ��饹����
$sim = new sim_kansou;

// DB������ǥǡ������ɤ߹���
$sim->read_eco_data();
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>����������˿���</title>
<script type="text/javascript">
<!--
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
						<td><img src="img/shindan/title/shindan_kansou.gif" border="0" width="346" height="64"></td>
					</tr>
				</table>
				���ʤ��βȤǡ������δ�����ˡ���Ѥ��뤳�ȤǤɤ줯�餤�Ρ�Eco���̡פ����뤫�׻����ޤ�����
				<hr>
				<!-- �����ȥ�ơ��֥뽪λ -->

				<!-- �ե���������ơ��֥�Ϥ߳��� -->
				<form action="res_kansou.php" name="form1" method="post">
					<table border="0" width="100%" bordercolordark="#FF0000" bordercolorlight="#FF6600">
						<tr>
							<td colspan="8" bgcolor="#FF0000" align="center">
								<img src="img/shindan/button/new_04.gif" border="0" width="125" height="27">
							</td>
						</tr>

						<!-- �����ȥ�1 -->
						<tr>
							<td rowspan="5" width="110" bgcolor="#FF9191">����ѥ�����</td>
							<td align="left" width="145" bgcolor="#FF8800">���絡�μ��ࡧ</td>
							<td rowspan="5" width="8"></td>
							<td><? select_dryer_type('dryer_type', $sim->dryer_type, 'dryer_type_e') ?></td>
						</tr>

						<tr>
							<td align="left" bgcolor="#FF8800" bgcolor="#FF8800">���絡�����̡�</td>
							<td><? select_capacity('capacity', $sim->capacity) ?>��kg</td>
						</tr>

						<tr>
							<td align="left" bgcolor="#FF8800">�����δ��絡���Ѳ����</td>
							<td><? select_times('times', $sim->times) ?>����
							</td>
						</tr>

						<tr>
							<td align="left" bgcolor="#FF8800">������ˡ����������ˡ�</td>
							<td><? radio_method('method_fair', $sim->method_fair, 'method_fair_e') ?></td>
						</tr>

						<tr>
							<td align="left" bgcolor="#FF8800">������ˡ�ʱ������ˡ�</td>
							<td><? radio_method('method_rain', $sim->method_rain, 'method_rain_e') ?></td>
						</tr>
					</table>
					<!-- 1���ܥơ��֥뽪λ -->

					<!-- 2���ܥơ��֥볫�� -->
					<table border="0" cellspacing="0" cellpadding="0" width="100%" height="200">
						<tr>
							<!-- ����ԡ������������ڡ��� -->
							<td height="10">��</td>
							<td rowspan="2" width="300"><img src="img/shindan/conte/062.gif" border="0" width="300" height="200"></td>
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
									<td colspan="3">���絡�ϡ�<? select_dryer_type('dryer_type_e', $sim->dryer_type_e) ?>���Ȥ���
									</td>
								</tr>

								<tr>
									<td colspan="3">��������ϡ�<br><? select_method('method_fair_e', $sim->method_fair_e) ?></select> ��
									</td>
								</tr>

								<tr>
									<td colspan="3">�������ϡ�<br><? select_method('method_rain_e', $sim->method_rain_e) ?> ��
									</td>
								</tr>

								<tr height="50">
									<td colspan="3">��</td>
								</tr>
								<tr height="50">
									<!-- ��󥯳��� -->
									<td align="center">
										<a href="<?=$_SESSION['s_one_advice']?>" title="���ݥ���ȥ��ɥХ����ŵ���"><img src="img/shindan/button/back3.gif" border="0" width="117" height="36"></a>
									</td>
									<!-- ���ꥻ�åȵ�ǽ��Ĥ��� -->
									<td align="center">
										<a href="sim_kansou.php" title="�ꥻ�å�"><img src="img/shindan/button/reset.gif" border="0" width="117" height="36"></a>
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