<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:洗濯エコ診断
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
include("$inc/sim_sentaku.php");
include("$inc/sim_db.php");
include("$inc/sim_total.php");

// エコ診断クラス生成
$sim = new sim_sentaku;

// フォームからデータ取得
$sim->capacity = $capacity;
$sim->weekday_times = $weekday_times;
$sim->holiday_times = $holiday_times;
$sim->ratio = $ratio;
$sim->nokoriyu = $nokoriyu;
$sim->nokoriyu_e = $nokoriyu_e;

// エコ診断計算
$sim->simulation($energy, $cost, $co2, $water, $msg);

// 年間使用量取得
total_use($total_energy, $total_water, $total_cost, $total_co2);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>診断結果_洗濯</title>
</head>

<body bgcolor="#FF0000" text="#000000" link="#66ccff" vlink="#66ccff" leftmargin="8" topmargin="8" alink="#66ccff">
<div align="center">

<!-- 白丸角テーブル -->
<table border="0" cellpadding="0" cellspacing="0" width="714" bgcolor="#FFFFFF">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF">
			<img src="img/kado_1.gif" width="35" height="35">
		</td>
		<td align="center" valign="top">
			<!-- タイトル部分 -->
			<br>
			<table border="0" cellpadding="0" cellspacing="2">
				<tr>
					<td>
						<img src="img/shindan/title/kekka_sentaku.gif" border="0" width="307" height="58">
					</td>
				</tr>
			</table>
			<hr width="95%" size="1"><br>
			<!-- タイトル部分終了 -->

			<!-- ここに入力 -->
			<font size=4 color="#4842ff"><b>あなたの家の洗濯方法を変えると、次のような効果があります！</b></font>
			<br><br>
			<img src="img/shindan/sim_res/touroku.gif" border="0" width="299" height="49" align="bottom" alt="ガンバリストに登録しましょう">
			<br><br>

			<!-- 年間使用量(水道一緒) -->
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
								<td bgcolor="#ff8846" align="center" width="150">エネルギー(Mcal/年）</td>
								<td align="center" bgcolor="#ff8846" width="110">水道使用量(m3)</td>
								<td align="center" bgcolor="#ffff67" width="95">料金（￥/年）</td>
								<td bgcolor="#99ccff" align="center" width="195">二酸化炭素排出量(kg/年)</td>
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
								<td colspan="4" height="30" align="center" bgcolor="#33cc33">
									<img src="img/shindan/button/new_01.gif" border="0" width="366" height="27">
								</td>
							</tr>
							<tr>
								<td bgcolor="#ff8846" align="center" width="150">エネルギー(Mcal/年）</td>
								<td align="center" bgcolor="#ff8846" width="110">水道使用量(m3)</td>
								<td align="center" bgcolor="#ffff67" width="95">料金（￥/年）</td>
								<td bgcolor="#99ccff" align="center" width="195">二酸化炭素排出量(kg/年)</td>
							</tr>
							<tr>
								<td align="center" bgcolor="#ffe397" width="150">-</td>
								<td align="center" bgcolor="#ffe397" width="110"><?=sim_number($water)?></td>
								<td align="center" bgcolor="#ffffc2" width="95"><?=sim_number($cost)?></td>
								<td align="center" bgcolor="#deffff" width="195"><?=sim_number($co2)?></td>
							</tr>
						</table>
						<!-- 年間使用量まで -->
					</td>
				</tr>
			</table>
			<form name="form1" action="kesshin.php" method="post">
				<table border="0" width=650>
					<tr>
						<td align="center">
							<img src="img/shindan/conte/066.gif" border="0" width="300" height="200">
						</td>
					</tr>
					<tr>
						<td align="center" valign="top">
							<br>
<?
if ($water > 0) {
?>
							<?=$msg?><br><br>
							<input type="hidden" name="ganbalist_id" <?=value(GANBA_SENTAKU)?>>
							<input type="hidden" name="eco_data" <?=value($sim->get_eco_data())?>>
							<input type="hidden" name="energy" <?=value($energy)?>>
							<input type="hidden" name="water" <?=value($water)?>>
							<input type="hidden" name="cost" <?=value($cost)?>>
							<input type="hidden" name="co2" <?=value($co2)?>>
							<input type="hidden" name="advice" <?=value(strip_tags($msg))?>>
							<a href="javascript:document.form1.submit()"><img src="img/shindan/sim_res/button_ganbalist.gif" border="0" width="170" height="36"></a>
<?
} elseif ($water < 0)
	echo SIM_ALERT;
?>
							<a href="javascript:history.back()"><img src="img/shindan/button/saishindan.gif" border="0" width="117" height="36"></a></p>
							<hr width="95%" size="1">
						</td>
					</tr>
				</table>
			</form>
			<!-- ここまで -->
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
<!-- 白丸角テーブル終了 -->

</div>
</body>
</html>