<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:�񸻤��ߥ�������
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/cnv_unit.php");
include("$inc/cnv_eng_mny.php");
include("$inc/cnv_eng_co2.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/sim_define.php");
include("$inc/sim_sub.php");
include("$inc/sim_shigengomi.php");
include("$inc/sim_db.php");
include("$inc/sim_total.php");

// �������ǥ��饹����
$sim = new sim_shigengomi;

// �ե����फ��ǡ�������
$sim->milk_num = $milk_num;
$sim->milk_recycle = $milk_recycle;
$sim->pet_num = $pet_num;
$sim->pet_recycle = $pet_recycle;
$sim->alminum_num = $alminum_num;
$sim->alminum_recycle = $alminum_recycle;
$sim->milk_recycle_e = $milk_recycle_e;
$sim->pet_recycle_e = $pet_recycle_e;
$sim->alminum_recycle_e = $alminum_recycle_e;

// �������Ƿ׻�
$sim->simulation($energy, $cost, $co2, $msg);

// ǯ�ֻ����̼���
total_use($total_energy, $total_water, $total_cost, $total_co2);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>���Ƿ��_�񸻤���</title>
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
			<br>
			<table border="0" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<img src="img/shindan/title/kekka_gomi_shigen.gif" border="0" width="425" height="58">
					</td>
				</tr>
			</table>
			<hr width="95%" size="1"><br>
			<!-- �����ȥ���ʬ��λ -->

			<!-- ���������� -->
			<font size=4 color="#4842ff"><b>���ʤ��βȤλ񸻤��ߤνФ������Ѥ���ȡ����Τ褦�ʸ��̤�����ޤ���</b></font>
			<br><br>
			<img src="img/shindan/sim_res/touroku.gif" border="0" width="299" height="49" align="bottom" alt="����Хꥹ�Ȥ���Ͽ���ޤ��礦">
			<br><br>

			<!-- �������� -->
			<table border="0" cellpadding="10" cellspacing="2" width="600" bgcolor="#ccffcc">
				<tr>
					<td align="center">
						<table border="0" cellpadding="0" cellspacing="2" width="550">
							<tr height="30">
								<td height="30" align="center" bgcolor="#33cc33" colspan="4">
									<img src="img/shindan/button/new_03.gif" border="0" width="211" height="27">
								</td>
							</tr>
							<tr>
								<td bgcolor="#ff8846" align="center" width="150">���ͥ륮��(Mcal/ǯ��</td>
								<td align="center" bgcolor="#ff8846" width="110">��ƻ������(m3)</td>
								<td align="center" bgcolor="#ffff67" width="95">����ʡ�/ǯ��</td>
								<td bgcolor="#99ccff" align="center" width="195">�����ú���ӽ���(kg/ǯ)</td>
							</tr>
							<tr>
								<td align="center" bgcolor="#ffe397" width="150"><?=sim_number($total_energy)?></td>
								<td align="center" bgcolor="#ffe397" width="110"><?=sim_number($total_water)?></td>
								<td align="center" bgcolor="#ffffc2" width="95"><?=sim_number($total_cost)?></td>
								<td align="center" bgcolor="#deffff" width="195"><?=sim_number($total_co2)?></td>
							</tr>
						</table>
						<table>
							<tr>
								<td><img src="img/shindan/sim_res/yazirushi.gif" width="57" height="68"></td>
							</tr>
						</table>
						<table border="0" cellpadding="0" cellspacing="2" width="550">
							<tr height="30">
								<td colspan="3" height="30" align="center" bgcolor="#33cc33">
									<img src="img/shindan/button/new_02.gif" border="0" width="366" height="27">
								</td>
							</tr>
							<tr>
								<td bgcolor="#ff8846" align="center" width="260">���ͥ륮��(Mcal/ǯ��</td>
								<td align="center" bgcolor="#ffff67" width="95">����ʡ�/ǯ��</td>
								<td bgcolor="#99ccff" align="center" width="195">�����ú���ӽ���(kg/ǯ)</td>
							</tr>
							<tr>
								<td align="center" bgcolor="#ffe397" width="260">-</td>
								<td align="center" bgcolor="#ffffc2" width="95">-</td>
								<td align="center" bgcolor="#deffff" width="195"><?=sim_number($co2)?></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<!-- �����ޤ� -->
			<font size="2">���������ú���ӽ��̤ϡ��񸻤��ߤ��Ѵ�����ƿ���������������¤���줿���ȡ�<br>
			�ꥵ�����뤵��ƺ����Ѥ��줿���Ȥκ���ɽ���Ƥ��ޤ���<br>
			<br>

			<form name="form1" action="kesshin.php" method="post">
				<table border="0" width=650>
					<tr>
						<td align="center">
							<img src="img/shindan/conte/060.gif" border="0" width="300" height="200">
						</td>
					</tr>
					<tr>
						<td align="center" valign="top">
<?
if ($co2 > 0) {
?>
							<?=$msg?><br><br>
							<input type="hidden" name="ganbalist_id" <?=value(GANBA_SHIGENGOMI)?>>
							<input type="hidden" name="eco_data" <?=value($sim->get_eco_data())?>>
							<input type="hidden" name="energy" <?=value($energy)?>>
							<input type="hidden" name="cost" <?=value($cost)?>>
							<input type="hidden" name="co2" <?=value($co2)?>>
							<input type="hidden" name="advice" <?=value(strip_tags($msg))?>>
							<a href="javascript:document.form1.submit()"><img src="img/shindan/sim_res/button_ganbalist.gif" border="0" width="170" height="36"></a>
<?
} elseif ($co2 < 0)
	echo SIM_ALERT;
?>
							<a href="javascript:history.back()"><img src="img/shindan/button/saishindan.gif" border="0" width="117" height="36"></a></p>
							<hr width="95%" size="1">
						</td>
					</tr>
				</table>
			</form>
		</td>
		<td width="35" align="right" valign="top" bgcolor="#FFFFFF">
			<img src="img/kado_2.gif" width="35" height="35">
		</td>
	</tr>

	<tr>
		<td width="35" valign="bottom"><img src="img/kado_3.gif" width="35" height="35"></td>
		<td align="center" valign="top" height=50><a href="<?=$_SESSION['s_one_advice']?>"><img src="img/shindan/back3.gif" border="0" width="117" height="36"></a></td>
		<td width="35" valign="bottom"><img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>
<!-- ��ݳѥơ��֥뽪λ -->

</div>
</body>
</html>