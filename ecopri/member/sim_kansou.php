<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:湯沸しエコ診断
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

// 乾燥機種類プルダウン表示
function select_dryer_type($name, $default, $eco = '') {
	$ary = kansou_shurui_name();
	sim_select_ary($name, $ary, $default, $eco);
}

// 乾燥機の容量プルダウン表示
function select_capacity($name, $default) {
	sim_select_no($name, 3, 5, $default);
}

// 乾燥機使用回数プルダウン表示
function select_times($name, $default) {
	$ary[0] = '使用しない';
	$ary[1] = '1';
	$ary[2] = '2';
	$ary[3] = '3';
	sim_select_ary($name, $ary, $default);
}

// 乾燥方法ラジオボタン表示
function radio_method($name, $default, $eco = '') {
	$ary = kansou_houhou_name();
	sim_radio_ary($name, $ary, $default, $eco, '<br>');
}

// 乾燥方法プルダウン表示
function select_method($name, $default) {
	$ary = kansou_houhou_name();
	sim_select_ary($name, $ary, $default);
}

// エコ診断クラス生成
$sim = new sim_kansou;

// DBから診断データを読み込み
$sim->read_eco_data();
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>乾燥（洗濯）診断</title>
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

<!-- 白丸角テーブル -->
<table border="0" cellpadding="0" cellspacing="0" width="714" height="90%" bgcolor="#FFFFFF">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF">
			<img src="img/kado_1.gif" width="35" height="35">
		</td>
		<td align="center" valign="top">

			<!-- ここに入力 -->
				<!-- タイトルテーブル -->
				<table border="0" cellpadding="0" cellspacing="2">
					<tr>
						<td><img src="img/shindan/title/shindan_kansou.gif" border="0" width="346" height="64"></td>
					</tr>
				</table>
				あなたの家で、洗濯の乾燥方法を変えることでどれくらいの「Eco効果」があるか計算します！！
				<hr>
				<!-- タイトルテーブル終了 -->

				<!-- フォーム処理テーブル囲み開始 -->
				<form action="res_kansou.php" name="form1" method="post">
					<table border="0" width="100%" bordercolordark="#FF0000" bordercolorlight="#FF6600">
						<tr>
							<td colspan="8" bgcolor="#FF0000" align="center">
								<img src="img/shindan/button/new_04.gif" border="0" width="125" height="27">
							</td>
						</tr>

						<!-- タイトル1 -->
						<tr>
							<td rowspan="5" width="110" bgcolor="#FF9191">乾燥パターン</td>
							<td align="left" width="145" bgcolor="#FF8800">乾燥機の種類：</td>
							<td rowspan="5" width="8"></td>
							<td><? select_dryer_type('dryer_type', $sim->dryer_type, 'dryer_type_e') ?></td>
						</tr>

						<tr>
							<td align="left" bgcolor="#FF8800" bgcolor="#FF8800">乾燥機の容量：</td>
							<td><? select_capacity('capacity', $sim->capacity) ?>　kg</td>
						</tr>

						<tr>
							<td align="left" bgcolor="#FF8800">一日の乾燥機使用回数：</td>
							<td><? select_times('times', $sim->times) ?>　回
							</td>
						</tr>

						<tr>
							<td align="left" bgcolor="#FF8800">乾燥方法（晴れの日）：</td>
							<td><? radio_method('method_fair', $sim->method_fair, 'method_fair_e') ?></td>
						</tr>

						<tr>
							<td align="left" bgcolor="#FF8800">乾燥方法（雨の日）：</td>
							<td><? radio_method('method_rain', $sim->method_rain, 'method_rain_e') ?></td>
						</tr>
					</table>
					<!-- 1段目テーブル終了 -->

					<!-- 2段目テーブル開始 -->
					<table border="0" cellspacing="0" cellpadding="0" width="100%" height="200">
						<tr>
							<!-- 空白行＆画像挿入スペース -->
							<td height="10">　</td>
							<td rowspan="2" width="300"><img src="img/shindan/conte/062.gif" border="0" width="300" height="200"></td>
						</tr>
						<tr>
							<td height="190">
							<!-- 診断条件テーブル開始 -->
							<table border="0" width="100%" height="180">
								<tr height="40">
									<td colspan="8" align="center" bgcolor="#67fac6">
										<img src="img/shindan/button/new_05.gif" border="0" width="114" height="27">
									</td>
								</tr>

								<tr>
									<td colspan="3">乾燥機は、<? select_dryer_type('dryer_type_e', $sim->dryer_type_e) ?>　とし、
									</td>
								</tr>

								<tr>
									<td colspan="3">晴れの日は、<br><? select_method('method_fair_e', $sim->method_fair_e) ?></select> 、
									</td>
								</tr>

								<tr>
									<td colspan="3">雨の日は、<br><? select_method('method_rain_e', $sim->method_rain_e) ?> 。
									</td>
								</tr>

								<tr height="50">
									<td colspan="3">　</td>
								</tr>
								<tr height="50">
									<!-- リンク開始 -->
									<td align="center">
										<a href="<?=$_SESSION['s_one_advice']?>" title="ワンポイントアドバイス電気編"><img src="img/shindan/button/back3.gif" border="0" width="117" height="36"></a>
									</td>
									<!-- ↓リセット機能をつける -->
									<td align="center">
										<a href="sim_kansou.php" title="リセット"><img src="img/shindan/button/reset.gif" border="0" width="117" height="36"></a>
									</td>
									<td align="center">
										<a href="javascript:document.form1.submit()" title="診断結果"><img src="img/shindan/button/shindan.gif" border="0" width="117" height="36"></a>
									</td>
									<!-- リンク終了 -->
								</tr>
							</table>
							<!-- 診断条件テーブル終了 -->
							</td>
						</tr>
					</table>
				</form>
				<!-- フォーム処理テーブル囲み終了 -->
			<!-- ここまで -->
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
<!-- 白丸角テーブル終了 -->

</div>
</body>
</html>