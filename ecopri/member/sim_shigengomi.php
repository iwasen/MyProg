<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:�񸻤��ߥ�������
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/sim_define.php");
include("$inc/sim_sub.php");
include("$inc/sim_shigengomi.php");
include("$inc/sim_db.php");

// �����̥ץ������ɽ��
function select_num($name, $default) {
	$ary[0] = 0;
	$ary['0.5'] = '1/2';
	for ($i = 1; $i <= 10; $i++)
		$ary[$i] = $i;
	sim_select_ary($name, $ary, $default);
}

// �ꥵ������饸���ܥ���ɽ��
function radio_recycle($name, $default, $eco = '') {
	$ary = array(
		1 => '���Ƥ���',
		0 => '���Ƥ��ʤ�'
	);
	sim_radio_ary($name, $ary, $default, $eco, '��');
}
// �ꥵ������ץ������ɽ��
function select_recycle($name, $default) {
	$ary = array(
		1 => '����',
		0 => '���ʤ�'
	);
	sim_select_ary($name, $ary, $default);
}

// �������ǥ��饹����
$sim = new sim_shigengomi;

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
						<td><img src="img/shindan/title/shindan_gomi_shigen.gif" border="0" width="382" height="64"></td>
					</tr>
				</table>
				���ʤ��βȤλ񸻤��ߤνФ������Ѥ��뤳�ȤǤɤ줯�餤�Ρ�Eco���̡פ����뤫�׻����ޤ�����
				<hr>
				<!-- �����ȥ�ơ��֥뽪λ -->

				<!-- �ե���������ơ��֥�Ϥ߳��� -->
				<form action="res_shigengomi.php" name="form1" method="post">
					<table border="0" width="100%" bordercolordark="#FF0000" bordercolorlight="#FF6600">
						<tr>
							<td colspan="8" bgcolor="#FF0000" align="center">
								<img src="img/shindan/button/new_04.gif" border="0" width="125" height="27">
							</td>
						</tr>

						<!-- �����ȥ�1 -->
						<tr>
							<td rowspan="6" width="100" bgcolor="#FF9191">�񸻤���</td>
							<td rowspan="2" align="left" width="100" bgcolor="#99FF66">�����ѥå�</td>
							<td align="left" bgcolor="#FF8800">�콵�֤ι����̡�</td>
							<td rowspan="6" width="8"></td>
							<td width="300">�� <? select_num('milk_num', $sim->milk_num) ?>��</td>
						</tr>

						<tr>
							<td align="left" bgcolor="#FF8800">�ꥵ�����롧</td>
							<td><? radio_recycle('milk_recycle', $sim->milk_recycle, 'milk_recycle_e') ?></td>
						</tr>

						<tr>
							<td rowspan="2" align="left" bgcolor="#99FF66">�ڥåȥܥȥ�</td>
							<td align="left" bgcolor="#FF8800">�콵�֤ι����̡�</td>
							<td>�� <? select_num('pet_num', $sim->pet_num) ?>��</td>
						</tr>
						<tr>
							<td align="left" bgcolor="#FF8800">�ꥵ�����롧</td>
							<td><? radio_recycle('pet_recycle', $sim->pet_recycle, 'pet_recycle_e') ?></td>
						</tr>
						<tr>
							<td rowspan="2" align="left" bgcolor="#99FF66">����ߴ̰���</td>
							<td align="left" bgcolor="#FF8800">�콵�֤ι����̡�</td>
							<td>�� <? select_num('alminum_num', $sim->alminum_num) ?>��
							</td>
						</tr>
						<tr>
							<td align="left" bgcolor="#FF8800">�ꥵ�����롧</td>
							<td><? radio_recycle('alminum_recycle', $sim->alminum_recycle, 'alminum_recycle_e') ?></td>
						</tr>

					</table>
					<!-- 1���ܥơ��֥뽪λ -->

					<!-- 2���ܥơ��֥볫�� -->
					<table border="0" cellspacing="0" cellpadding="0" width="100%" height="200">
						<tr>
							<!-- ����ԡ������������ڡ��� -->
							<td height="10">��</td>
							<td rowspan="2" width="300"><img src="img/shindan/conte/059.gif" border="0" width="300" height="200"></td>
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
									<td colspan="3">�����ѥå��򡢥ꥵ������ <? select_recycle('milk_recycle_e', $sim->milk_recycle_e) ?>��</td>
								</tr>

								<tr>
									<td colspan="3">�ڥåȥܥȥ�򡢥ꥵ������ <? select_recycle('pet_recycle_e', $sim->pet_recycle_e) ?>��</td>
								</tr>

								<tr>
									<td colspan="3">����ߴ̤򡢥ꥵ������ <? select_recycle('alminum_recycle_e', $sim->alminum_recycle_e) ?>��</td>
								</tr>

								<tr height="50">
									<td colspan="3">��</td>
								</tr>
								<tr height="50">
									<!-- ��󥯳��� -->
									<td align="center">
										<a href="<?=$_SESSION['s_one_advice']?>" title="���ݥ���ȥ��ɥХ���������"><img src="img/shindan/button/back3.gif" border="0" width="117" height="36"></a>
									</td>
									<!-- ���ꥻ�åȵ�ǽ��Ĥ��� -->
									<td align="center">
										<a href="sim_shigengomi.php" title="�ꥻ�å�"><img src="img/shindan/button/reset.gif" border="0" width="117" height="36"></a>
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