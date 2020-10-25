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
include("$inc/sim_yuwakashi.php");
include("$inc/sim_db.php");

// ��ʨ����ˡ�饸���ܥ���ɽ��
function radio_yuwakashi_houhou($name, $default, $eco = '') {
	$ary = yuwakashi_houhou_name();
	sim_radio_ary($name, $ary, $default, $eco);
}

// ��ʨ������ץ������ɽ��
function select_yuwakashi_kaisuu($name, $default) {
	sim_select_no($name, 1, 5, $default);
}

// ��ʨ���̥ץ������ɽ��
function select_yuwakashi_ryou($name, $default) {
	$ary = array(
		33  => '��ʬ�ΰ�',
		50  => 'Ⱦʬ',
		80  => 'Ȭʬ��',
		100 => '���礦��'
	);
	sim_select_ary($name, $ary, $default);
}

// �ݲ���ˡ�饸���ܥ���ɽ��
function radio_hoon_houhou($name, $default, $eco = '') {
	$ary = hoon_houhou_name();
	sim_radio_ary($name, $ary, $default, $eco);
}

// �ݲ����֥ץ������ɽ��
function select_hoon_jikan($name, $default, $eco = '') {
	$ary = hoon_jikan_name();
	sim_select_ary($name, $ary, $default, $eco);
}

// ��ʨ����ˡ�ץ������ɽ��
function select_yuwakashi_houhou($name, $default) {
	$ary = yuwakashi_houhou_name();
	sim_select_ary($name, $ary, $default);
}

// �ݲ���ˡ�ץ������ɽ��
function select_hoon_houhou($name, $default) {
	$ary = hoon_houhou_name();
	sim_select_ary($name, $ary, $default);
}

// �ݲ����֥ץ������ɽ���ʡ��ݲ����ʤ��פ������
function select_hoon_jikan2($name, $default) {
	$ary = hoon_jikan_name();
	unset($ary[0]);
	sim_select_ary($name, $ary, $default);
}

// �������ǥ��饹����
$sim = new sim_yuwakashi;

// DB������ǥǡ������ɤ߹���
$sim->read_eco_data();
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>��ʨ������</title>
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
						<td><img src="img/shindan/title/shindan_yuwakashi.gif" border="0" width="384" height="68"></td>
					</tr>
				</table>
				���ʤ��βȤ���ʨ����ˡ���Ѥ��뤳�ȤǤɤ줯�餤�Ρ�Eco���̡פ����뤫�׻����ޤ�����
				<hr>
				<!-- �����ȥ�ơ��֥뽪λ -->

				<!-- �ե���������ơ��֥�Ϥ߳��� -->
				<form action="res_yuwakashi.php" name="form1" method="post">
					<table border="0" width="100%" bordercolordark="#FF0000" bordercolorlight="#FF6600">
						<tr>
							<td colspan="8" bgcolor="#FF0000" align="center">
								<img src="img/shindan/button/new_04.gif" border="0" width="125" height="27">
							</td>
						</tr>

						<!-- �����ȥ�1 -->
						<tr>
							<td rowspan="3" width="110" bgcolor="#FF9191">1.��ʨ��</td>
							<td align="left" width="145" bgcolor="#FF8800">��ʨ������ˡ��</td>
							<td rowspan=5 width="8"></td>
							<td><? radio_yuwakashi_houhou('yuwakashi_houhou', $sim->yuwakashi_houhou, 'yuwakashi_houhou_e') ?></td>
						</tr>

						<tr>
							<td align="left" bgcolor="#FF8800" bgcolor="#FF8800">��������ʨ�������</td>
							<td><? select_yuwakashi_kaisuu('yuwakashi_kaisuu', $sim->yuwakashi_kaisuu) ?>��</td>
						</tr>

						<tr>
							<td align="left" bgcolor="#FF8800" bgcolor="#FF8800">������ʨ���̡�</td>
							<td>�ݥåȤ�䤫���<? select_yuwakashi_ryou('yuwakashi_ryou', $sim->yuwakashi_ryou) ?></td>
						</tr>

						<tr>
							<td rowspan="2" width="110" bgcolor="#FF9191">2.�ݲ�</td>
							<td align="left" bgcolor="#FF8800">�ݲ�����ˡ��</td>
							<td><? radio_hoon_houhou('hoon_houhou', $sim->hoon_houhou, 'hoon_houhou_e') ?></td>
						</tr>

						<tr>
							<td align="left" bgcolor="#FF8800">�������ݲ����֡�</td>
							<td><? select_hoon_jikan('hoon_jikan', $sim->hoon_jikan, 'hoon_jikan_e') ?></td>
						</tr>
					</table>
					<!-- 1���ܥơ��֥뽪λ -->

					<!-- 2���ܥơ��֥볫�� -->
					<table border="0" cellspacing="0" cellpadding="0" width="100%" height="200">
						<tr>
							<!-- ����ԡ������������ڡ��� -->
							<td height="10">��</td>
							<td rowspan="2" width="300"><img src="img/shindan/conte/053.gif" border="0" width="300" height="200"></td>
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
									<td colspan="3">��ʨ������ˡ�ϡ�<? select_yuwakashi_houhou('yuwakashi_houhou_e', $sim->yuwakashi_houhou_e) ?>�ˤ���
									</td>
								</tr>

								<tr>
									<td colspan="3">
										<? select_hoon_houhou('hoon_houhou_e', $sim->hoon_houhou_e) ?>�ǡ�
										<? select_hoon_jikan2('hoon_jikan_e', $sim->hoon_jikan_e) ?>�ݲ����롣
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
										<a href="sim_yuwakashi.php" title="�ꥻ�å�"><img src="img/shindan/button/reset.gif" border="0" width="117" height="36"></a>
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