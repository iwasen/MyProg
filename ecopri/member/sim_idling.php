<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:運転方法エコ診断
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/sim_define.php");
include("$inc/sim_sub.php");
include("$inc/sim_unten.php");
include("$inc/sim_db.php");

// 排気量（ガソリン車）プルダウン表示
function select_engine_vol1($name, $default) {
	$ary = array(
		1 => '660',
		2 => '1000-1500',
		3 => '1600-2000',
		4 => '2200-2500',
		5 => '2600-3000'
	);
	sim_select_ary($name, $ary, $default);
}

// 排気量（ディーゼル車）プルダウン表示
function select_engine_vol2($name, $default) {
	$ary = array(
		4 => '2200-2500',
		5 => '2600-3000'
	);
	sim_select_ary($name, $ary, $default);
}

// 排気量（ハイブリッド車）プルダウン表示
function select_engine_vol3($name, $default) {
	$ary = array(
		2 => '1000-1500',
		3 => '1600-2000',
		4 => '2200-2500',
		5 => '2600-3000'
	);
	sim_select_ary($name, $ary, $default);
}

// 走行距離プルダウン表示
function select_kyori($name, $default) {
	sim_select_no($name, 100, 1000, $default, '', '', '', 100);
}

// アイドリング時間プルダウン表示
function select_idling($name, $default) {
	sim_select_no($name, 0, 60, $default, '', '', '', 10);
}

// 走行距離削減プルダウン表示
function select_kyori_e($name, $default) {
	sim_select_no($name, 0, 100, $default, '', '', '', 10);
}

// アイドリング削減時間プルダウン表示
function select_idling_e($name, $default) {
	sim_select_no($name, 0, 100, $default, '', '', '', 10);
}

// エコ診断クラス生成
$sim = new sim_unten;

// DBから診断データを読み込み
$sim->read_eco_data();
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>運転方法診断</title>
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
						<td><img src="img/shindan/title/shindan_gasoline.gif" border="0" width="414" height="72"></td>
					</tr>
				</table>
				あなたの車の運転方法を変えることでどれくらいの「Eco効果」があるか計算します！！
				<hr>
				<!-- タイトルテーブル終了 -->

				<!-- フォーム処理テーブル囲み開始 -->
				<form action="res_idling.php" name="form1" method="post">
					<table border="0" width="100%" bordercolordark="#FF0000" bordercolorlight="#FF6600">
						<tr>
							<td colspan="8" bgcolor="#FF0000" align="center">
								<img src="img/shindan/button/new_04.gif" border="0" width="125" height="27">
							</td>
						</tr>

						<!-- タイトル1 選んだラジオボタンにより右のプルダウンが有効になる -->
						<tr>
							<td rowspan="3" colspan="2" width="110" bgcolor="#FF9191">1.車の種類</td>
							<td><input type="radio" name="car_cd" <?=value_checked('1', $sim->car_cd)?> tabindex="1">ガソリン車</td>
							<td rowspan="3" width="8"></td>
							<td><? select_engine_vol1('engine_vol[1]', $sim->engine_vol[1]) ?>ｃｃ</td>
						</tr>

						<tr>
							<td><input type="radio" name="car_cd" <?=value_checked('2', $sim->car_cd)?> tabindex="2">ディーゼル車</td>
							<td><? select_engine_vol2('engine_vol[2]', $sim->engine_vol[2]) ?>ｃｃ</td>
						</tr>

						<tr>
							<td><input type="radio" name="car_cd" <?=value_checked('2', $sim->car_cd)?> tabindex="3">ハイブリッド車</td>
							<td><? select_engine_vol3('engine_vol[3]', $sim->engine_vol[3]) ?>ｃｃ</td>
						</tr>

						<tr>
							<td rowspan="2" width="110" bgcolor="#FF9191">2.運転方法</td>
							<td align="left" bgcolor="#FF8800" bgcolor="#FF8800">月間走行距離：</td>
							<td>約 <? select_kyori('kyori', $sim->kyori) ?>ｋｍ</td>
						</tr>

						<tr>
							<td align="left" bgcolor="#FF8800" bgcolor="#FF8800">一日あたりの<br>駐車中のアイドリング時間：</td>
							<td>約 <? select_idling('idling', $sim->idling) ?>分</td>
						</tr>
					</table>
					<!-- 1段目テーブル終了 -->

					<!-- 2段目テーブル開始 -->
					<table border="0" cellspacing="0" cellpadding="0" width="100%" height="200">
						<tr>
							<!-- 空白行＆画像挿入スペース -->
							<td height="10">　</td>
							<td rowspan="2" width="300"><img src="img/shindan/conte/069.gif" border="0" width="300" height="200"></td>
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
									<td colspan="3">月間走行距離を <? select_kyori_e('kyori_e', $sim->kyori_e) ?>％、
										<br>駐車中のアイドリング時間を <? select_idling_e('idling_e', $sim->idling_e) ?>％少なくします。
									</td>
								</tr>

								<tr height="50">
									<td colspan="3">　</td>
								</tr>
								<tr height="50">
									<!-- リンク開始 -->
									<td align="center">
										<a href="<?=$_SESSION['s_one_advice']?>" title="ワンポイントアドバイスガソリン編"><img src="img/shindan/button/back3.gif" border="0" width="117" height="36"></a>
									</td>
									<!-- ↓リセット機能をつける -->
									<td align="center">
										<a href="sim_idling.php" title="リセット"><img src="img/shindan/button/reset.gif" border="0" width="117" height="36"></a>
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