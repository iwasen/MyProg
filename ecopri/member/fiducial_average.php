<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:平均値表示
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/regist3.php");

// 入力月取得
function get_m($i) {
	global $year, $month;
	return (int)(date("m", mktime (0,0,0,$month + $i,1,$year)));
}
// 入力年取得
function get_y($i) {
	global $year, $month;
	return (int)(date("Y", mktime (0,0,0,$month + $i,1,$year)));
}

// セッション処理
session_start();
if (!isset($_SESSION['ss_regist3']))
	redirect('fiducial_exp.php');
$reg = &$_SESSION['ss_regist3'];

$year = $reg->start_y;
$month = $reg->start_m;

$reg->wt_use = $reg->wt_use_av;
$reg->wt_use_flag = 4;
$reg->wt_sum = (int) ($reg->wt_use_av * $reg->wt_price_unit);
$reg->wt_sum_flag = 4;
$reg->gl_use = $reg->gl_use_av;
$reg->gl_use_flag = 4;
$reg->gl_sum = (int) ($reg->gl_use_av * $reg->gl_price_unit);
$reg->gl_sum_flag = 4;
$reg->gm_use = $reg->gm_use_av;
$reg->gm_use_flag = 4;
for ($i = 1; $i <= 12; $i++) {
	$energy = &$reg->energy[get_m($i)];
	$energy->el_use =$energy->el_use_av;
	$energy->el_use_flag = 4;
	$energy->el_sum = $energy->el_use * $reg->el_price_unit;
	$energy->el_sum_flag = 4;
	$energy->gs_use = $energy->gs_use_av;
	$energy->gs_use_flag = 4;
	$energy->gs_sum = $energy->gs_use * $reg->gs_price_unit;
	$energy->gs_sum_flag = 4;
	$energy->ol_use = $energy->ol_use_av;
	$energy->ol_use_flag = 4;
	$energy->ol_sum = $energy->ol_use * $reg->ol_price_unit;
	$energy->ol_sum_flag = 4;
}

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>☆★☆xxxゾウさんClub☆★☆</title>
</head>

<body bgcolor="#FF0000" leftmargin="8" topmargin="8">
<div align="center">
<table width="714" height ="90%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF">
		<img src="img/kado_1.gif" width="35" height="35"></td>
		<td align="center" valign="bottom"><br>
			<table border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td align="center"><img src="img/nyuukai/title/m_kakunin.gif" width="127" height="38" alt="確認してね"></td>
				</tr>
			</table>
		</td>
		<td width="35" align="right" valign="top">
		<img src="img/kado_2.gif" width="35" height="35"></td>
	</tr>

	<tr>
		<td><br></td>
		<td align="center" valign="top">
		<hr width="95%" size="1">

			<table width="599" border="1" cellspacing="3" cellpadding="0" bordercolor="#FFCCFF">
			<!--JSP変更箇所-->
				<tr>
					<td width ="50" bgcolor="#CCFFFF"><img src="img/nyuukai/eco_zou.gif" width="50" height="30" alt="平均値が入力されました"></td>
					<td bgcolor="#FFCCFF" width="528">
					<div align="center"><font color="#FF0066">・前年度平均値が入力されました</font></div>
					</td>
				</tr>
			</table>
			<br>
			<font size=2>・入力した数値は</font><font color="#0000FF" size=2>“青”</font><font size=2>、平均値で埋めた数値を</font><font color="#FF0000" size=2>“赤”</font><font size=2><font size=2>で表示</font>
			<br>
			<br>
			<table width="600" border="1" cellspacing="2" cellpadding="0" bordercolor="#FFCC99">
						<table border="1" width ="600" cellspacing="2" bordercolor="#FFCC99">
							<tr>
								<td valign="top" bgcolor="#FFFFCC" width="600">
								<div align="center"><font color="#0000FF" size=3>１年の変動の小さいもの</font></div></td>
							</tr>
						</table>
						<table>
							<tr>
								<td></td>
							</tr>
							<tr>
								<td width="600">
									<table border="1" bordercolor="#5cb8a6" align="center">
										<tr>
											<td width=200><div align="center"><img src="img/character/suidou.gif" alt="すいどう" width="62" height="72"></div></td>
											<td width=200><div align="center"><img src="img/character/gasoline.gif" width="62" height="72" alt="ガソリン"></div></td>
											<td width=200><div align="center"><img src="img/character/gomi.gif" width="62" height="72" alt="ゴミ"></div></td>
										</tr>
										<tr>
											<td align="center">
												<table>
													<tr>
													<td align="right" width=95><font size=2><font color="red"><?=$reg->wt_use?></font>&nbsp;m3</font></td>
													<td align="right" width=95><font size=2><font color="red"><?=number_format((int)($reg->wt_sum))?></font>&nbsp;円</font></td>
													</tr>
												</table>
											</td>
											<td align="center">
												<table>
													<tr>
														<td align="right" width=95><font size=2><font color="red"><?=$reg->gl_use_av?></font>&nbsp;L</font></td>
														<td align="right" width=95><font size=2><font color="red"><?=number_format((int)($reg->gl_sum))?></font>&nbsp;円</font></td>
													</tr>
												</table>
											</td>
											<td align="center"><font size=2><font color="red"><?=$reg->gm_use_av?></font>&nbsp;kg</font></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						<br>
						<table border="1" width ="600" cellspacing="2" bordercolor="#FFCC99">
							<tr>
								<td bgcolor="#FFFFCC" width ="600">
								<div align="center"><font color="#FF0000" size=3>１年の変動の大きいもの</font><br>
								</div>
								</td>
							</tr>
						</table>
						<table border="0" width ="600">
							<tr>
								<td></td>
							</tr>
							<tr valign="top">
								<td rowspan="2">
									<table border="1"bordercolor="#5cb8a6">
										<tr>
											<td width = "60"><div align="center"><font size=2>月＼エネ</font></div></td>
											<td width=180><div align="center"><img src="img/character/denki.gif" alt="でんき" width="62" height="72"></div></td>
											<td width=180><div align="center"><img src="img/character/gas.gif" alt="ガス" width="62" height="72"></div></td>
											<td width=180><div align="center"><img src="img/character/touyu.gif" width="62" height="72" alt="とうゆ"></div></td>
										</tr>
<?
for ($i = 1; $i <= 12; $i++) {
	$energy = &$reg->energy[get_m($i)];
?>
										<tr>
											<td><div align="center"><?=get_y($i)."/".sprintf("%02d", get_m($i))?></div></td>
											<td align="center">
												<table>
													<tr>
														<td width=85 align="right"><font size=2><font color="red"><?=number_format($energy->el_use)?></font>&nbsp;kwh</td>
														<td width=85 align="right"><font size=2><font color="red"><?=number_format((int)($energy->el_sum))?></font>&nbsp;円</font></td>
													</tr>
												</table>
											</td>
											<td align="center">
												<table>
													<tr>
														<td width=85 align="right"><font size=2><font color="red"><?=$energy->gs_use?></font>&nbsp;m3</td>
														<td width=85 align="right"><font size=2><font color="red"><?=number_format((int)($energy->gs_sum))?></font>&nbsp;円</font></td>
													</tr>
												</table>
											</td>
											<td align="center">
												<table>
													<tr>
														<td width=85 align="right"><font size=2><font color="red"><?=$energy->ol_use?></font>&nbsp;L</td>
														<td width=85 align="right"><font size=2><font color="red"><?=number_format((int)($energy->ol_sum))?></font>&nbsp;円</font></td>
													</tr>
												</table>
											</td>
										</tr>
<?
}
?>
									</table>
								</td>
							</tr>
						</table>
						<table>
					</td>
				</tr>
				<tr valign="top">
					<td><div align="right"><img src="img/nyuukai/zou_img/f_check.gif" width="200" height="210" alt="これが基準値になるよ（確認してね）"></div></td>
				</tr>
			</table>
			<hr width="95%" size="1"></td>
		<td><br></td>
	</tr>
	<tr>
		<td width="35" align="left" valign="bottom">
		<img src="img/kado_3.gif" width="35" height="35"></td>
		<td align="center" valign="bottom">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr align="center">
				<form name="form1" method="post" action="fiducial_end.php">
					<td><a href="fiducial_input.php"><img src="img/nyuukai/button/naosu.gif" width="99" height="40" alt="やり直す" border="0"></a></td>
					<td width="10"><img src="img/spacer.gif" width="10" height="40"></td>
					<td><input type="image" src="img/nyuukai/button/ok.gif" border="0" alt="これでOK" width="99" height="40"></a></td>
					</form>
				</tr>
				<tr>
					<td colspan="3"><img src="img/spacer.gif" width="208" height="7"></td>
				</tr>
			</table></td>
		<td width="35" height="35" align="right" valign="bottom">
		<img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>

</div>
</body>
</html>