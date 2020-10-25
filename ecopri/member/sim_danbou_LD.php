<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:LD暖房エコ診断
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/sim_define.php");
include("$inc/sim_sub.php");
include("$inc/sim_danbou.php");
include("$inc/sim_db.php");

// 主暖房の種類プルダウン表示
function select_main_heater($name, $default) {
	$ary = danbou_shurui_name();
	unset($ary[6], $ary[7], $ary[8]);
	sim_select_ary($name, $ary, $default, '', ' onchange="onChange_main_heater(this)"');
}

// 補助暖房の種類プルダウン表示
function select_sub_heater($name, $default, $eco = '') {
	global $sim;

	$ary = danbou_shurui_name();
	if ($sim->main_heater == 5)
		unset($ary[5], $ary[6], $ary[7]);
	else
		unset($ary[1], $ary[2], $ary[3], $ary[4], $ary[5]);
	sim_select_ary($name, $ary, $default, $eco);
}

// 設定温度プルダウン表示
function select_temperature($name, $default, $eco = '') {
	sim_select_no($name, 16, 25, $default, $eco, '', '℃');
}

// 使用時間（起床〜昼）プルダウン表示
function select_hours1($name, $default, $eco = '') {
	sim_select_no($name, 0, 4, $default, $eco);
}

// 使用時間（昼〜夕方）プルダウン表示
function select_hours2($name, $default, $eco = '') {
	sim_select_no($name, 0, 3, $default, $eco);
}

// 使用時間（夕方以降）プルダウン表示
function select_hours3($name, $default, $eco = '') {
	sim_select_no($name, 0, 4, $default, $eco);
}

// 補助暖房用配列取得（ダイナミックに変更するため）
$sub_heater_ary = danbou_shurui_name();

// エコ診断クラス生成
$sim = new sim_danbou;

// DBから診断データを読み込み
$sim->read_eco_data();
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>暖房診断</title>
<script type="text/javascript">
<!--
function openAC(str){window.open(str,"AC","width=300,height=300");}
function onChange_main_heater(c) {
	if (c.name == "main_heater") {
		s = document.form1.sub_heater;
		select_list("main_heater_e", c.value);
	} else if (c.name == "main_heater_e")
		s = document.form1.sub_heater_e;
	else
		return;
	switch (c.value) {
	case "1":
	case "2":
	case "3":
	case "4":
		s.options.length = 3;
		s.options[0].value = "6";
		s.options[0].text = "<?=$sub_heater_ary[6]?>";
		s.options[1].value = "7";
		s.options[1].text = "<?=$sub_heater_ary[7]?>";
		s.options[2].value = "8";
		s.options[2].text = "<?=$sub_heater_ary[8]?>";
		break;
	case "5":
		s.options.length = 5;
		s.options[0].value = "1";
		s.options[0].text = "<?=$sub_heater_ary[1]?>";
		s.options[1].value = "2";
		s.options[1].text = "<?=$sub_heater_ary[2]?>";
		s.options[2].value = "3";
		s.options[2].text = "<?=$sub_heater_ary[3]?>";
		s.options[3].value = "4";
		s.options[3].text = "<?=$sub_heater_ary[4]?>";
		s.options[4].value = "8";
		s.options[4].text = "<?=$sub_heater_ary[8]?>";
		break;
	}
}
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
// -->
</script>
</head>

<body bgcolor="#ff0000" leftmargin="8" topmargin="8">

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
						<td><img src="img/shindan/title/shindan_danbou.gif" border="0" width="399" height="64"></td>
					</tr>
				</table>
				あなたの家の暖房の使用方法を変えることでどれくらいの「Eco効果」があるか計算します！！
				<hr>
				<!-- タイトルテーブル終了 -->

				<!-- フォーム処理テーブル囲み開始 -->
				<form action="res_danbou_LD.php" name="form1" method="post">

					<!-- タイトル1 -->
					<table border="0" width="100%" bordercolordark="#FF0000" bordercolorlight="#FF6600">
						<tr>
							<td colspan="8" bgcolor="#FF0000" align="center">
								<img src="img/shindan/button/new_04.gif" border="0" width="125" height="27">
							</td>
						</tr>

						<tr>
							<td rowspan="2" colspan="2" align="center" bgcolor="#FF6699">場所</td>
							<td rowspan="2" colspan="2" align="center" bgcolor="#FFCC00">暖房の種類<br>
								<a href="#" onClick="openAC('AC.php')">エアコン説明</a>
							</td>
							<td rowspan="2" align="center" bgcolor="#CCFF00">設定<br>温度</td>
							<td colspan="3" align="center" bgcolor="#99FFFF">一日の使用時間帯</td>
						</tr>

						<tr>
							<td width="80" height="20" align="center" bgcolor="#CCFFFF">起床〜昼</td>
							<td width="80" height="20" align="center" bgcolor="#CCFFFF">昼〜夕方</td>
							<td width="80" height="20" align="center" bgcolor="#CCFFFF">夕方以降</td>
						</tr>

						<tr>
							<td rowspan="2" align="center" bgcolor="#FF9999">居間・食堂</td>
							<td rowspan="2" align="center" bgcolor="#FFCCCC">15畳</td>
							<td width="40" align="center" bgcolor="#FFFF99">主</td>
							<td width="120" height="20" bgcolor="#FFFF99"><? select_main_heater('main_heater', $sim->main_heater) ?></td>
							<td rowspan="2" width="20" bgcolor="#FFFF99"><? select_temperature('temperature', $sim->temperature, 'temperature_e') ?></td>
							<td width="80" height="20" bgcolor="#FFFF99"><? select_hours1('main_hours1', $sim->main_hours1, 'main_hours1_e') ?>時間</td>
							<td width="80" height="20" bgcolor="#FFFF99"><? select_hours2('main_hours2', $sim->main_hours2, 'main_hours2_e') ?>時間</td>
							<td width="80" height="20" bgcolor="#FFFF99"><? select_hours3('main_hours3', $sim->main_hours3, 'main_hours3_e') ?>時間</td>
						</tr>

						<tr>
							<td width="40" align="center" bgcolor="#FFFF99">補助</td>
							<td width="120" height="20" bgcolor="#FFFF99"><? select_sub_heater('sub_heater', $sim->sub_heater, 'sub_heater_e') ?></td>
							<td width="80" height="20" bgcolor="#FFFF99"><? select_hours1('sub_hours1', $sim->sub_hours1, 'sub_hours1_e') ?>時間</td>
							<td width="80" height="20" bgcolor="#FFFF99"><? select_hours2('sub_hours2', $sim->sub_hours2, 'sub_hours2_e') ?>時間</td>
							<td width="80" height="20" bgcolor="#FFFF99"><? select_hours3('sub_hours3', $sim->sub_hours3, 'sub_hours3_e') ?>時間</td>
						</tr>
					</table>
					<!-- 1段目テーブル終了 -->
					<br><br><br>

					<!-- 診断条件 -->
					<table border="0" width="100%" bordercolordark="#FF0000" bordercolorlight="#FF6600">
						<tr>
							<td colspan="8" align="center" bgcolor="#67fac6">
								<img src="img/shindan/button/new_05.gif" border="0" width="114" height="27">
							</td>
						</tr>

						<tr>
							<td rowspan="2" colspan="2" align="center" bgcolor="#FF6699">場所</td>
							<td rowspan="2" colspan="2" align="center" bgcolor="#FFCC00">暖房の種類<br>
								<a href="#" onClick="openAC('AC.php')">エアコン説明</a>
							</td>
							<td rowspan="2" align="center" bgcolor="#CCFF00">設定<br>温度</td>
							<td colspan="3" align="center" bgcolor="#99FFFF">一日の使用時間帯</td>
						</tr>

						<tr>
							<td width="80" height="20" align="center" bgcolor="#CCFFFF">起床〜昼</td>
							<td width="80" height="20" align="center" bgcolor="#CCFFFF">昼〜夕方</td>
							<td width="80" height="20" align="center" bgcolor="#CCFFFF">夕方以降</td>
						</tr>

						<tr>
							<td rowspan="2" align="center" bgcolor="#FF9999">居間・食堂</td>
							<td rowspan="2" align="center" bgcolor="#FFCCCC">15畳</td>
							<td width="40" align="center" bgcolor="#FFFF99">主</td>
							<td width="120" height="20" bgcolor="#FFFF99"><? select_main_heater('main_heater_e', $sim->main_heater_e) ?></td>
							<td rowspan="2" width="20" bgcolor="#FFFF99"><? select_temperature('temperature_e', $sim->temperature_e) ?></td>
							<td width="80" height="20" bgcolor="#FFFF99"><? select_hours1('main_hours1_e', $sim->main_hours1_e) ?>時間</td>
							<td width="80" height="20" bgcolor="#FFFF99"><? select_hours2('main_hours2_e', $sim->main_hours2_e) ?>時間</td>
							<td width="80" height="20" bgcolor="#FFFF99"><? select_hours3('main_hours3_e', $sim->main_hours3_e) ?>時間</td>
						</tr>

						<tr>
							<td width="40" align="center" bgcolor="#FFFF99">補助</td>
							<td width="120" height="20" bgcolor="#FFFF99"><? select_sub_heater('sub_heater_e', $sim->sub_heater_e) ?></td>
							<td width="80" height="20" bgcolor="#FFFF99"><? select_hours1('sub_hours1_e', $sim->sub_hours1_e) ?>時間</td>
							<td width="80" height="20" bgcolor="#FFFF99"><? select_hours2('sub_hours2_e', $sim->sub_hours2_e) ?>時間</td>
							<td width="80" height="20" bgcolor="#FFFF99"><? select_hours3('sub_hours3_e', $sim->sub_hours3_e) ?>時間</td>
						</tr>
					</table>
					<!-- 診断条件 -->

					<!-- 3段目テーブル開始 -->
					<table border="0" cellspacing="0" cellpadding="0" width="100%" height="200">
						<tr>
							<!-- 空白行＆画像挿入スペース -->
							<td height="10">　</td>
							<td rowspan="2" width="300"><img src="img/shindan/conte/031.gif" border="0" width="234" height="167"></td>
						</tr>
						<tr>
							<td height="190">

							<!-- リンク専用テーブル開始 -->
							<table border="0" width="100%" height="180" cellspacing="0" cellpadding="0">
								<tr height="60" bgcolor="#FFFFFF">
									<td colspan="3"><br></td>
								</tr>
								<tr height="60">
									<!-- リンク開始 -->
									<!-- リンク元の判定をしてどちらかの戻るボタンを表示 -->
									<td align="center">
										<a href="<?=$_SESSION['s_one_advice']?>"><img src="img/shindan/button/back3.gif" border="0" width="117" height="36"></a>
									</td>

									<!-- ↓リセット機能をつける -->
									<td align="center">
										<a href="sim_danbou_LD.php" title="リセット"><img src="img/shindan/button/reset.gif" border="0" width="117" height="36"></a>
									</td>
									<td align="center">
										<a href="javascript:document.form1.submit()" title="診断結果"><img src="img/shindan/button/shindan.gif" border="0" width="117" height="36"></a>
									</td>
									<!-- リンク終了 -->
								</tr>
								<tr height="60">
									<td colspan="3"><br></td>
								</tr>
							</table>
							<!-- リンク専用テーブル終了 -->
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



</body>
</html>