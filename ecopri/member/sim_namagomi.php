<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:�����ߥ�������
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/sim_define.php");
include("$inc/sim_sub.php");
include("$inc/sim_namagomi.php");
include("$inc/sim_db.php");

// �������̥ץ������ɽ��
function select_dust($name, $default) {
	$ary = array(
		'0.2' => '2��',
		'0.5' => '5��',
		'0.7' => '7��',
		'1'   => '1��',
		'2'   => '2��',
		'3'   => '3��',
		'4'   => '4��',
		'5'   => '5��'
	);
	sim_select_ary($name, $ary, $default);
}

// �����߽������䥳��ݥ��Ȥλ��ѥ饸���ܥ���ɽ��
function radio_compost($name, $default, $eco = '') {
	$ary = array(
		1 => '���Ѥ��Ƥ���',
		0 => '���Ѥ��Ƥ��ʤ�'
	);
	sim_radio_ary($name, $ary, $default, $eco);
}
// �����߽������䥳��ݥ��Ȥλ��ѥץ������ɽ��
function select_compost($name, $default) {
	$ary = array(
		0 => '���Ѥ��ʤ�',
		1 => '���Ѥ���'
	);
	sim_select_ary($name, $ary, $default);
}

// �������ǥ��饹����
$sim = new sim_namagomi;

// DB������ǥǡ������ɤ߹���
$sim->read_eco_data();
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>���߿���</title>
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
						<td><img src="img/shindan/title/shindan_gomi_nama.gif" border="0" width="365" height="64"></td>
					</tr>
				</table>
				���ʤ��βȤ������ߤνФ������Ѥ��뤳�ȤǤɤ줯�餤�Ρ�Eco���̡פ����뤫�׻����ޤ�����
				<hr>
				<!-- �����ȥ�ơ��֥뽪λ -->

				<!-- �ե���������ơ��֥�Ϥ߳��� -->
				<form action="res_namagomi.php" name="form1" method="post">
					<table border="0" width="100%" bordercolordark="#FF0000" bordercolorlight="#FF6600">
						<tr>
							<td colspan="8" bgcolor="#FF0000" align="center">
								<img src="img/shindan/button/new_04.gif" border="0" width="125" height="27">
							</td>
						</tr>

						<!-- �����ȥ�1 -->
						<tr>
							<td rowspan="3" width="110" bgcolor="#FF9191">������</td>
							<td align="left" bgcolor="#FF8800">��������������(ʿ��)��</td>
							<td rowspan="3" width="8"></td>
							<td>���ѥ����ʡ��ˡ��� <? select_dust('weekday_dust', $sim->weekday_dust) ?>ʬ</td>
						</tr>

						<tr>
							<td align="left" bgcolor="#FF8800">��������������(����)��</td>
							<td>���ѥ����ʡ��ˡ��� <? select_dust('holiday_dust', $sim->holiday_dust) ?>ʬ</td>
						</tr>

						<tr>
							<td align="left" bgcolor="#FF8800">�����߽������䥳��ݥ��Ȥλ��ѡ�</td>
							<td><? radio_compost('compost', $sim->compost, 'compost_e') ?></td>
						</tr>

					</table>
					<!-- 1���ܥơ��֥뽪λ -->
					<br>
					<!-- 2���ܥơ��֥볫�� -->
					<table border="0" cellspacing="0" cellpadding="0" width="100%" height="200">
						<tr>
							<!-- ����ԡ������������ڡ��� -->
							<td height="10">��</td>
							<td rowspan="2" width="300"><img src="img/shindan/conte/056.gif" border="0" width="300" height="200"></td>
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
									<td colspan="3">�����߽������䥳��ݥ��Ȥ� <? select_compost('compost_e', $sim->compost_e) ?></td>
								</tr>
								<tr height="50">
									<td colspan="3">��</td>
								</tr>
								<tr height="50">
									<!-- ��󥯳��� -->
									<td align="center">
										<a href="<?=$_SESSION['s_one_advice']?>" title="���ݥ���ȥ��ɥХ���������"><img src="img/shindan/button/back3.gif" border="0" width="117" height="36"></a><br>
									</td>
									<!-- ���ꥻ�åȵ�ǽ��Ĥ��� -->
									<td align="center">
										<a href="sim_namagomi.php" title="�ꥻ�å�"><img src="img/shindan/button/reset.gif" border="0" width="117" height="36"></a>
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