<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:洗濯エコ診断
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/sim_define.php");
include("$inc/sim_sub.php");
include("$inc/sim_sentaku.php");
include("$inc/sim_db.php");

// 洗濯層の容量プルダウン表示
function select_capacity($name, $default) {
	sim_select_no($name, 4, 8, $default);
}

// 洗濯回数プルダウン表示
function select_times($name, $default) {
	$ary['0.5'] = '2日に1';
	for ($i = 1; $i <= 5; $i++)
		$ary[$i] = $i;
	sim_select_ary($name, $ary, $default);
}

// 洗濯物量の割合プルダウン表示
function select_ratio($name, $default) {
	$ary = array(
		50  => '半分',
		80  => '八分目',
		100 => 'ちょうど',
		120 => '押し込む'
	);
	sim_select_ary($name, $ary, $default);
}

// 残り湯の使用ラジオボタン表示
function radio_nokoriyu($name, $default, $eco = '') {
	$ary = array(
		1 => '使用しない',
		2 => '洗いのみ使用',
		3 => '洗いとすすぎ1回使用 '
	);
	sim_radio_ary($name, $ary, $default, $eco);
}

// 残り湯の使用プルダウン表示
function select_nokoriyu($name, $default) {
	$ary = nokoriyu_name();
	sim_select_ary($name, $ary, $default);
}

// エコ診断クラス生成
$sim = new sim_sentaku;

// DBから診断データを読み込み
$sim->read_eco_data();
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>洗濯診断</title>
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
			<!-- タイトル部分終了 -->

			<!-- ここに入力 -->
				<!-- タイトルテーブル -->
				<table border="0" cellpadding="0" cellspacing="2">
					<tr>
						<td><img src="img/shindan/title/shindan_sentaku.gif" border="0" width="275" height="64"></td>
					</tr>
				</table>
				あなたの家の洗濯方法を変えることでどれくらいの「Eco効果」があるか計算します！！
				<hr>
				<!-- タイトルテーブル終了 -->

				<!-- フォーム処理テーブル囲み開始 -->
				<form action="res_sentaku.php" name="form1" method="post">
					<table border="0" width="100%" bordercolordark="#FF0000" bordercolorlight="#FF6600">
						<tr>
							<td colspan="8" bgcolor="#FF0000" align="center">
								<img src="img/shindan/button/new_04.gif" border="0" width="125" height="27">
							</td>
						</tr>

						<!-- タイトル1 -->
						<tr>
							<td rowspan="5" width="110" bgcolor="#FF9191">洗濯パターン</td>
							<td align="left" width="145" bgcolor="#FF8800">洗濯層の容量：</td>
							<td rowspan="5" width="8"></td>
							<td><? select_capacity('capacity', $sim->capacity) ?>kg</td>
						</tr>

						<tr>
							<td align="left" bgcolor="#FF8800">一日の洗濯回数（平日）：</td>
							<td><? select_times('weekday_times', $sim->weekday_times) ?>回</td>
						</tr>

						<tr>
							<td align="left" bgcolor="#FF8800">一日の洗濯回数（休日）：</td>
							<td><? select_times('holiday_times', $sim->holiday_times) ?>回</td>
						</tr>

						<tr>
							<td align="left" bgcolor="#FF8800">一回の洗濯物量の割合：</td>
							<td><? select_ratio('ratio', $sim->ratio) ?>くらい</td>
						</tr>

						<tr>
							<td align="left" bgcolor="#FF8800">風呂の残り湯の使用：</td>
							<td><? radio_nokoriyu('nokoriyu', $sim->nokoriyu, 'nokoriyu_e') ?></td>
						</tr>
					</table>
					<!-- 1段目テーブル終了 -->

					<!-- 2段目テーブル開始 -->
					<table border="0" cellspacing="0" cellpadding="0" width="100%" height="200">
						<tr>
							<!-- 空白行＆画像挿入スペース -->
							<td height="10">　</td>
							<td rowspan="2" width="300"><img src="img/shindan/conte/065.gif" border="0" width="300" height="200"></td>
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
									<td colspan="3">洗濯に、風呂の残り湯を <? select_nokoriyu('nokoriyu_e', $sim->nokoriyu_e) ?>。</td>
								</tr>
								<tr height="50">
									<td colspan="3">　</td>
								</tr>
								<tr height="50">
									<!-- リンク開始 -->
									<td align="center">
										<a href="<?=$_SESSION['s_one_advice']?>" title="ワンポイントアドバイス水道編"><img src="img/shindan/button/back3.gif" border="0" width="117" height="36"></a>
									</td>
									<!-- ↓リセット機能をつける -->
									<td align="center">
										<a href="sim_sentaku.php" title="リセット"><img src="img/shindan/button/reset.gif" border="0" width="117" height="36"></a>
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