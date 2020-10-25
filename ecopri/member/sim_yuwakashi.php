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
include("$inc/sim_yuwakashi.php");
include("$inc/sim_db.php");

// 湯沸し方法ラジオボタン表示
function radio_yuwakashi_houhou($name, $default, $eco = '') {
	$ary = yuwakashi_houhou_name();
	sim_radio_ary($name, $ary, $default, $eco);
}

// 湯沸し回数プルダウン表示
function select_yuwakashi_kaisuu($name, $default) {
	sim_select_no($name, 1, 5, $default);
}

// 湯沸し量プルダウン表示
function select_yuwakashi_ryou($name, $default) {
	$ary = array(
		33  => '三分の一',
		50  => '半分',
		80  => '八分目',
		100 => 'ちょうど'
	);
	sim_select_ary($name, $ary, $default);
}

// 保温方法ラジオボタン表示
function radio_hoon_houhou($name, $default, $eco = '') {
	$ary = hoon_houhou_name();
	sim_radio_ary($name, $ary, $default, $eco);
}

// 保温時間プルダウン表示
function select_hoon_jikan($name, $default, $eco = '') {
	$ary = hoon_jikan_name();
	sim_select_ary($name, $ary, $default, $eco);
}

// 湯沸し方法プルダウン表示
function select_yuwakashi_houhou($name, $default) {
	$ary = yuwakashi_houhou_name();
	sim_select_ary($name, $ary, $default);
}

// 保温方法プルダウン表示
function select_hoon_houhou($name, $default) {
	$ary = hoon_houhou_name();
	sim_select_ary($name, $ary, $default);
}

// 保温時間プルダウン表示（「保温しない」を除く）
function select_hoon_jikan2($name, $default) {
	$ary = hoon_jikan_name();
	unset($ary[0]);
	sim_select_ary($name, $ary, $default);
}

// エコ診断クラス生成
$sim = new sim_yuwakashi;

// DBから診断データを読み込み
$sim->read_eco_data();
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>湯沸し診断</title>
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
						<td><img src="img/shindan/title/shindan_yuwakashi.gif" border="0" width="384" height="68"></td>
					</tr>
				</table>
				あなたの家の湯沸し方法を変えることでどれくらいの「Eco効果」があるか計算します！！
				<hr>
				<!-- タイトルテーブル終了 -->

				<!-- フォーム処理テーブル囲み開始 -->
				<form action="res_yuwakashi.php" name="form1" method="post">
					<table border="0" width="100%" bordercolordark="#FF0000" bordercolorlight="#FF6600">
						<tr>
							<td colspan="8" bgcolor="#FF0000" align="center">
								<img src="img/shindan/button/new_04.gif" border="0" width="125" height="27">
							</td>
						</tr>

						<!-- タイトル1 -->
						<tr>
							<td rowspan="3" width="110" bgcolor="#FF9191">1.湯沸し</td>
							<td align="left" width="145" bgcolor="#FF8800">湯沸しの方法：</td>
							<td rowspan=5 width="8"></td>
							<td><? radio_yuwakashi_houhou('yuwakashi_houhou', $sim->yuwakashi_houhou, 'yuwakashi_houhou_e') ?></td>
						</tr>

						<tr>
							<td align="left" bgcolor="#FF8800" bgcolor="#FF8800">一日の湯沸し回数：</td>
							<td><? select_yuwakashi_kaisuu('yuwakashi_kaisuu', $sim->yuwakashi_kaisuu) ?>回</td>
						</tr>

						<tr>
							<td align="left" bgcolor="#FF8800" bgcolor="#FF8800">一回の湯沸し量：</td>
							<td>ポットややかんの<? select_yuwakashi_ryou('yuwakashi_ryou', $sim->yuwakashi_ryou) ?></td>
						</tr>

						<tr>
							<td rowspan="2" width="110" bgcolor="#FF9191">2.保温</td>
							<td align="left" bgcolor="#FF8800">保温の方法：</td>
							<td><? radio_hoon_houhou('hoon_houhou', $sim->hoon_houhou, 'hoon_houhou_e') ?></td>
						</tr>

						<tr>
							<td align="left" bgcolor="#FF8800">一日の保温時間：</td>
							<td><? select_hoon_jikan('hoon_jikan', $sim->hoon_jikan, 'hoon_jikan_e') ?></td>
						</tr>
					</table>
					<!-- 1段目テーブル終了 -->

					<!-- 2段目テーブル開始 -->
					<table border="0" cellspacing="0" cellpadding="0" width="100%" height="200">
						<tr>
							<!-- 空白行＆画像挿入スペース -->
							<td height="10">　</td>
							<td rowspan="2" width="300"><img src="img/shindan/conte/053.gif" border="0" width="300" height="200"></td>
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
									<td colspan="3">湯沸しの方法は、<? select_yuwakashi_houhou('yuwakashi_houhou_e', $sim->yuwakashi_houhou_e) ?>にし、
									</td>
								</tr>

								<tr>
									<td colspan="3">
										<? select_hoon_houhou('hoon_houhou_e', $sim->hoon_houhou_e) ?>で、
										<? select_hoon_jikan2('hoon_jikan_e', $sim->hoon_jikan_e) ?>保温する。
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
										<a href="sim_yuwakashi.php" title="リセット"><img src="img/shindan/button/reset.gif" border="0" width="117" height="36"></a>
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