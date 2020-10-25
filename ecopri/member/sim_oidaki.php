<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:追い炊きエコ診断
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/sim_define.php");
include("$inc/sim_sub.php");
include("$inc/sim_oidaki.php");
include("$inc/sim_db.php");

// 追い炊きする人数プルダウン表示
function select_ninzuu($name, $default) {
	sim_select_no($name, 1, 8, $default);
}

// 湯温（夏）プルダウン表示
function select_yuon_summer($name, $default) {
	sim_select_yuon($name, 40, $default);
}

// 湯温（春秋）プルダウン表示
function select_yuon_sp_aut($name, $default) {
	sim_select_yuon($name, 41, $default);
}

// 湯温（冬）プルダウン表示
function select_yuon_winter($name, $default) {
	sim_select_yuon($name, 42, $default);
}

// 湯温（共通）プルダウン表示
function sim_select_yuon($name, $std, $default) {
	for ($i = 37; $i <= 45; $i++)
		$ary[$i] = ($i == $std ? '標準' : '') . $i;
	sim_select_ary($name, $ary, $default);
}

// 入浴間隔プルダウン表示
function select_nyuuyoku_kankaku($name, $default, $eco = '') {
	sim_select_no($name, 0, 100, $default, $eco, '', '', 10);
}

// 浴槽の蓋プルダウン表示
function select_yokusou_futa($name, $default, $eco = '') {
	$ary = array(
		1 => 'している',
		2 => 'していない'
	);
	sim_select_ary($name, $ary, $default, $eco);
}

// 湯はり量プルダウン表示
function select_yuhari_ryou($name, $default) {
	$ary = array(
		230 => '十分目(230L)',
		210 => '八分目(210L)',
		190 => '六分目(190L)'
	);
	sim_select_ary($name, $ary, $default);
}

// 給湯器タイププルダウン表示
function select_boiler_type($name, $default) {
	$ary = array(
		1 => 'ガスタイプ',
		2 => '電気タイプ',
		3 => '灯油タイプ'
	);
	sim_select_ary($name, $ary, $default);
}

// 入浴間隔プルダウン表示
function select_nyuuyoku_kankaku_e($name, $default) {
	sim_select_no($name, 0, 100, $default, '', '', '', 5);
}

// 浴槽の蓋プルダウン表示
function select_yokusou_futa_e($name, $default) {
	$ary = array(
		1 => 'します',
		2 => 'しません'
	);
	sim_select_ary($name, $ary, $default);
}

// エコ診断クラス生成
$sim = new sim_oidaki;

// DBから診断データを読み込み
$sim->read_eco_data();
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>追い焚き診断</title>
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
						<td><img src="img/shindan/title/shindan_nyuuyoku_01.gif" border="0" width="384" height="68"></td>
					</tr>
				</table>
				あなたの家で、入浴時の浴槽追い焚き方法を変えることでどれくらいの「Eco効果」があるか計算します！！
				<hr>
				<!-- タイトルテーブル終了 -->

				<!-- フォーム処理テーブル囲み開始 -->
				<form action="res_oidaki.php" name="form1" method="post">
					<table border="0" width="100%" bordercolordark="#FF0000" bordercolorlight="#FF6600">
						<tr>
							<td colspan="8" bgcolor="#FF0000" align="center">
								<img src="img/shindan/button/new_04.gif" border="0" width="125" height="27">
							</td>
						</tr>

						<!-- タイトル1 -->
						<tr>
							<td rowspan="4" width="110" bgcolor="#FF9191">1.入浴パターン</td>
							<td align="left" width="145" bgcolor="#FF8800">追い炊きする人数：</td>
							<td width="8" rowspan="6"></td>
							<td><font color="#0000ff">夏</font>：<? select_ninzuu('ninzuu_summer', $sim->ninzuu_summer) ?>人　
								<font color="#ff69b4">春</font>/<font color="#ffa500">秋</font>：<? select_ninzuu('ninzuu_sp_aut', $sim->ninzuu_sp_aut) ?>人　
								<font color="lightskyblue">冬</font>：<? select_ninzuu('ninzuu_winter', $sim->ninzuu_winter) ?>人
							</td>
						</tr>

						<tr>
							<td align="left" bgcolor="#FF8800" bgcolor="#FF8800">浴槽の湯温：</td>
							<td><font color="#0000ff">夏</font>：<? select_yuon_summer('yuon_summer', $sim->yuon_summer) ?>℃
								<font color="#ff69b4">春</font>/<font color="#ffa500">秋</font>：<? select_yuon_sp_aut('yuon_sp_aut', $sim->yuon_sp_aut) ?>℃
								<font color="lightskyblue">冬</font>：<? select_yuon_winter('yuon_winter', $sim->yuon_winter) ?>℃
							</td>
						</tr>

						<tr>
							<td align="left" bgcolor="#FF8800">入浴間隔：</td>
							<td><? select_nyuuyoku_kankaku('nyuuyoku_kankaku', $sim->nyuuyoku_kankaku, 'nyuuyoku_kankaku_e') ?>分</td>
						</tr>

						<tr>
							<td align="left" bgcolor="#FF8800">浴槽の蓋：</td>
							<td>入浴時以外は、浴槽の蓋を<? select_yokusou_futa('yokusou_futa', $sim->yokusou_futa, 'yokusou_futa_e') ?>。</td>
						</tr>

						<!-- タイトル2 -->
						<tr>
							<td colspan="2" align="left" bgcolor="#00FF88">2.浴槽湯はり量：</td>
							<td><? select_yuhari_ryou('yuhari_ryou', $sim->yuhari_ryou) ?>。</td>
						</tr>

						<!-- タイトル3 -->
						<tr>
							<td colspan="2" align="left" bgcolor="#00FF88">3.給湯器タイプ：</td>
							<td><? select_boiler_type('boiler_type', $sim->boiler_type) ?>。</td>
						</tr>
					</table>
					<!-- 1段目テーブル終了 -->

					<!-- 2段目テーブル開始 -->
					<table border="0" cellspacing="0" cellpadding="0" width="100%" height="200">
						<tr>

							<!-- 空白行＆画像挿入スペース -->
							<td height="10">　</td>
							<td rowspan="2" width="300"><img src="img/shindan/conte/041.gif" border="0" width="230" height="164"></td>
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

								<tr height="30">
									<td colspan="3">家族の入浴間隔を
										<? select_nyuuyoku_kankaku_e('nyuuyoku_kankaku_e', $sim->nyuuyoku_kankaku_e) ?>分以内にし、<br>入浴中以外は浴槽に蓋を

										<? select_yokusou_futa_e('yokusou_futa_e', $sim->yokusou_futa_e) ?>。
									</td>
								</tr>
								<tr height="50">
									<td colspan="3">　</td>
								</tr>
								<tr height="50">
									<!-- リンク開始 -->
									<!-- リンク元の判定をしてどちらかの戻るボタンを表示 -->
									<td align="center">
										<a href="<?=$_SESSION['s_one_advice']?>"><img src="img/shindan/button/back3.gif" border="0" width="117" height="36"></a>
									</td>
									<!-- ↓リセット機能をつける -->
									<td align="center">
										<a href="sim_oidaki.php" title="リセット"><img src="img/shindan/button/reset.gif" border="0" width="117" height="36"></a>
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