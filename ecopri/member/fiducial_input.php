<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:会員情報入力
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/select.php");
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

?>
<html>
<head>
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>☆★☆xxxゾウさんClub☆★☆</title>
</head>

<body bgcolor="#FF0000" leftmargin="8" topmargin="8">
<form name="form1" method="post" action="fiducial_input_check.php">
<div align="center">
<table width="714" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF">
			<img src="img/kado_1.gif" width="35" height="35"></td>
		<td align="center" valign="bottom"><br>
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td><div align="center"><img src="img/nyuukai/title/f_input01.gif" width="214" height="38"　 alt="さっそく入力しよう"></div></td>
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
			<table border="0" cellspacing="0" cellpadding="0" height="42">
				<tr>
					<td><img src="img/nyuukai/cyuui.gif" width="93" height="42" alt="注意"></td>
				</tr>
			</table>

			<table width="599" border="1" cellspacing="5" cellpadding="0" bordercolor="#00CCFF">
				<tr>
					<td bgcolor="#CCFFFF"><font size=2><br>
					<ul>
					<li>変動が大きい：電気／ガス／灯油は12ヵ月分入力して下さい</li>
					<br>
					<br>
					<li>変動が小さい：水道／ガソリン／ゴミは前月分のみ入力して下さい</li>
					<br>
					<br>
					<li>使用量又は請求金額が<font color="blue">不明な場合は"空欄"</font>、<font color="red">未使用の場合は"0"</font>を入力して下さい</li>
					<br>
					(領収書（検針票）の無い月は全体の平均値を基準値とします)
					<br>
					<br>
					<li>カンマや小数点を入れずに、数字のみ入力して下さい。</li>
					<br>
					</ul>
					</font></td>
				</tr>
			</table>
			<br>
			<table border="1" width ="600" cellspacing="2" bordercolor="#FFCC99">
				<tr>
					<td valign="top" bgcolor="#FFFFCC" width="600">
					<div align="center"><font color="#0000FF" size=3>１年の変動の小さいもの</font></div></td>
				</tr>
			</table>
			<table>
				<tr>
					<td><font size=2>・前月１ヶ月分の領収書（検針票）（<font color="blue">水道は領収書（検針票）の合計から、わり算して１ヶ月分</font>）のみ入力して下さい。</font></td>
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
											<td align="center"><input type="text" name="wt_use" size="10" maxlength=4 <?=value($reg->wt_input_use)?>>&nbsp;m3</td>
											<td><input type="text" name="wt_sum" size="10" maxlength=6 <?=value($reg->wt_input_sum)?>>&nbsp;円</td>
										</tr>
									</table>
								</td>
								<td align="center">
									<table>
										<tr>
										<td align="center"><input type="text" name="gl_use" size="10" maxlength=4 <?=value($reg->gl_input_use)?>>&nbsp;L</td>
										<td><input type="text" name="gl_sum" size="10" maxlength=6 <?=value($reg->gl_input_sum)?>>&nbsp;円</td>
										</tr>
									</table>
								</td>
								<td align="center"><input type="text" name="gm_use" size="10" maxlength=4 <?=value($reg->gm_input_use)?>>&nbsp;kg</td>
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
					<td><font size=2>・過去１年分の領収書（検針票）を月ごとに入力して下さい。</font></td>
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
								<td><div align="center"><?=get_y($i)."/".sprintf("%02d",get_m($i))?></div></td>
								<td align="center">
									<table>
										<tr>
											<td align="center"><font size=2><input type="text" name="el_use[]" size="8" maxlength=4 <?=value($energy->el_input_use)?>>&nbsp;kwh&nbsp;<input type="text" name="el_sum[]" size="10" maxlength=6 <?=value($energy->el_input_sum)?>>&nbsp;円</font></td>
										</tr>
									</table>
								</td>
								<td align="center">
									<table>
										<tr>
											<td align="center"><font size=2><input type="text" name="gs_use[]" size="8" maxlength=4 <?=value($energy->gs_input_use)?>>&nbsp;m3&nbsp;<input type="text" name="gs_sum[]" size="10" maxlength=6 <?=value($energy->gs_input_sum)?>>&nbsp;円</font></td>
										</tr>
									</table>
								</td>
								<td align="center">
									<table>
										<tr>
											<td align="center"><font size=2><input type="text" name="ol_use[]" size="8" maxlength=4 <?=value($energy->ol_input_use)?>>&nbsp;L&nbsp;<input type="text" name="ol_sum[]" size="10" maxlength=6 <?=value($energy->ol_input_sum)?>>&nbsp;円</font></td>
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
				<tr valign="top">
					<td width="280" valign="bottom"><div align="right"><img src="img/nyuukai/zou_img/f_input.gif" alt="どーだった？（さっそく入力しよう）" width="200" height="213"></div></td>
				</tr>
			</table>
			<hr width="95%" size="1"></td>

		</tr>

		<td width="35" align="left" valign="bottom">
		<img src="img/kado_3.gif" width="35" height="35"></td>
		<td align="center" valign="bottom">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr align="center">


					<td><a href="fiducial_exp.php"><img src="img/nyuukai/button/back.gif" width="99" height="40" alt="戻る" border="0"></a></td>
					<td><input type="image" src="img/nyuukai/button/ok.gif" width="99" height="40" alt="これでOK" border="0"></a></td>
				</tr>
			</table></td>
		<td width="35" height="35" align="right" valign="bottom">
		<img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>

</div>
</form>
</body>
</html>