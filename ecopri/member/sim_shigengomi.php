<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:資源ごみエコ診断
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

// 購入量プルダウン表示
function select_num($name, $default) {
	$ary[0] = 0;
	$ary['0.5'] = '1/2';
	for ($i = 1; $i <= 10; $i++)
		$ary[$i] = $i;
	sim_select_ary($name, $ary, $default);
}

// リサイクルラジオボタン表示
function radio_recycle($name, $default, $eco = '') {
	$ary = array(
		1 => 'している',
		0 => 'していない'
	);
	sim_radio_ary($name, $ary, $default, $eco, '　');
}
// リサイクルプルダウン表示
function select_recycle($name, $default) {
	$ary = array(
		1 => 'する',
		0 => 'しない'
	);
	sim_select_ary($name, $ary, $default);
}

// エコ診断クラス生成
$sim = new sim_shigengomi;

// DBから診断データを読み込み
$sim->read_eco_data();
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>ごみ診断</title>
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
						<td><img src="img/shindan/title/shindan_gomi_shigen.gif" border="0" width="382" height="64"></td>
					</tr>
				</table>
				あなたの家の資源ごみの出し方を変えることでどれくらいの「Eco効果」があるか計算します！！
				<hr>
				<!-- タイトルテーブル終了 -->

				<!-- フォーム処理テーブル囲み開始 -->
				<form action="res_shigengomi.php" name="form1" method="post">
					<table border="0" width="100%" bordercolordark="#FF0000" bordercolorlight="#FF6600">
						<tr>
							<td colspan="8" bgcolor="#FF0000" align="center">
								<img src="img/shindan/button/new_04.gif" border="0" width="125" height="27">
							</td>
						</tr>

						<!-- タイトル1 -->
						<tr>
							<td rowspan="6" width="100" bgcolor="#FF9191">資源ごみ</td>
							<td rowspan="2" align="left" width="100" bgcolor="#99FF66">牛乳パック</td>
							<td align="left" bgcolor="#FF8800">一週間の購入量：</td>
							<td rowspan="6" width="8"></td>
							<td width="300">約 <? select_num('milk_num', $sim->milk_num) ?>本</td>
						</tr>

						<tr>
							<td align="left" bgcolor="#FF8800">リサイクル：</td>
							<td><? radio_recycle('milk_recycle', $sim->milk_recycle, 'milk_recycle_e') ?></td>
						</tr>

						<tr>
							<td rowspan="2" align="left" bgcolor="#99FF66">ペットボトル</td>
							<td align="left" bgcolor="#FF8800">一週間の購入量：</td>
							<td>約 <? select_num('pet_num', $sim->pet_num) ?>本</td>
						</tr>
						<tr>
							<td align="left" bgcolor="#FF8800">リサイクル：</td>
							<td><? radio_recycle('pet_recycle', $sim->pet_recycle, 'pet_recycle_e') ?></td>
						</tr>
						<tr>
							<td rowspan="2" align="left" bgcolor="#99FF66">アルミ缶飲料</td>
							<td align="left" bgcolor="#FF8800">一週間の購入量：</td>
							<td>約 <? select_num('alminum_num', $sim->alminum_num) ?>本
							</td>
						</tr>
						<tr>
							<td align="left" bgcolor="#FF8800">リサイクル：</td>
							<td><? radio_recycle('alminum_recycle', $sim->alminum_recycle, 'alminum_recycle_e') ?></td>
						</tr>

					</table>
					<!-- 1段目テーブル終了 -->

					<!-- 2段目テーブル開始 -->
					<table border="0" cellspacing="0" cellpadding="0" width="100%" height="200">
						<tr>
							<!-- 空白行＆画像挿入スペース -->
							<td height="10">　</td>
							<td rowspan="2" width="300"><img src="img/shindan/conte/059.gif" border="0" width="300" height="200"></td>
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
									<td colspan="3">牛乳パックを、リサイクル <? select_recycle('milk_recycle_e', $sim->milk_recycle_e) ?>。</td>
								</tr>

								<tr>
									<td colspan="3">ペットボトルを、リサイクル <? select_recycle('pet_recycle_e', $sim->pet_recycle_e) ?>。</td>
								</tr>

								<tr>
									<td colspan="3">アルミ缶を、リサイクル <? select_recycle('alminum_recycle_e', $sim->alminum_recycle_e) ?>。</td>
								</tr>

								<tr height="50">
									<td colspan="3">　</td>
								</tr>
								<tr height="50">
									<!-- リンク開始 -->
									<td align="center">
										<a href="<?=$_SESSION['s_one_advice']?>" title="ワンポイントアドバイスゴミ編"><img src="img/shindan/button/back3.gif" border="0" width="117" height="36"></a>
									</td>
									<!-- ↓リセット機能をつける -->
									<td align="center">
										<a href="sim_shigengomi.php" title="リセット"><img src="img/shindan/button/reset.gif" border="0" width="117" height="36"></a>
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