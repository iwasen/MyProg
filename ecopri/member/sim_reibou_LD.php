<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:LD冷房エコ診断
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/sim_define.php");
include("$inc/sim_sub.php");
include("$inc/sim_reibou_ld.php");
include("$inc/sim_db.php");

// 冷房の種類プルダウン表示
function select_cooler_type($name, $default, $eco = '') {
	$ary = reibou_shurui_name();
	sim_select_ary($name, $ary, $default, $eco);
}

// 設定温度プルダウン表示
function select_temperature($name, $default, $eco = '') {
	sim_select_no($name, 25, 29, $default, $eco, '', '℃');
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

// エコ診断クラス生成
$sim = new sim_reibou_ld;

// DBから診断データを読み込み
$sim->read_eco_data();
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>冷房(居間・食堂)診断</title>
<script type="text/javascript">
<!--
function openAC(str){window.open(str,"AC","width=300,height=300");}
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
						<td><img src="img/shindan/title/shindan_reibou_02.gif" border="0" width="399" height="64"></td>
					</tr>
				</table>
				あなたの家の居間の冷房使用方法を変えることでどれくらいの「Eco効果」があるか計算します！！
				<hr>
				<!-- タイトルテーブル終了 -->

				<!-- フォーム処理テーブル囲み開始 -->
				<form action="res_reibou_LD.php" name="form1" method="post">

					<!-- タイトル1 -->
					<table border="0" width="100%" bordercolordark="#FF0000" bordercolorlight="#FF6600">
						<tr>
							<td colspan="8" bgcolor="#FF0000" align="center">
								<img src="img/shindan/button/new_04.gif" border="0" width="125" height="27">
							</td>
						</tr>

						<tr>
							<td rowspan="2" colspan="2" align="center" bgcolor="#FF6699">場所</td>
							<td rowspan="2" align="center" bgcolor="#FFCC00">冷房の種類<br>
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
							<td align="center" bgcolor="#FF9999">居間・食堂</td>
							<td align="center" bgcolor="#FFCCCC">15畳</td>
							<td width="160" height="20" bgcolor="#FFFF99"><? select_cooler_type('cooler_type', $sim->cooler_type, 'cooler_type_e') ?></td>
							<td rowspan="2" width="20" bgcolor="#FFFF99"><? select_temperature('temperature', $sim->temperature, 'temperature_e') ?></td>
							<td width="80" height="20" bgcolor="#FFFF99"><? select_hours1('hours1', $sim->hours1, 'hours1_e') ?>時間</td>
							<td width="80" height="20" bgcolor="#FFFF99"><? select_hours2('hours2', $sim->hours2, 'hours2_e') ?>時間</td>
							<td width="80" height="20" bgcolor="#FFFF99"><? select_hours3('hours3', $sim->hours3, 'hours3_e') ?>時間</td>
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
							<td rowspan="2" align="center" bgcolor="#FFCC00">冷房の種類<br>
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
							<td width="160" height="20" bgcolor="#FFFF99"><? select_cooler_type('cooler_type_e', $sim->cooler_type_e) ?></td>
							<td rowspan="2" width="20" bgcolor="#FFFF99"><? select_temperature('temperature_e', $sim->temperature_e) ?></td>
							<td width="80" height="20" bgcolor="#FFFF99"><? select_hours1('hours1_e', $sim->hours1_e) ?>時間</td>
							<td width="80" height="20" bgcolor="#FFFF99"><? select_hours2('hours2_e', $sim->hours2_e) ?>時間</td>
							<td width="80" height="20" bgcolor="#FFFF99"><? select_hours3('hours3_e', $sim->hours3_e) ?>時間</td>
						</tr>

					</table>
					<!-- 診断条件 -->

					<!-- 3段目テーブル開始 -->
					<table border="0" cellspacing="0" cellpadding="0" width="100%" height="200">
						<tr>
							<!-- 空白行＆画像挿入スペース -->
							<td height="10">　</td>
							<td rowspan="2" width="300"><img src="img/shindan/conte/028.gif" border="0" width="300" height="200"></td>
						</tr>
						<tr>
							<td height="190">

							<!-- リンク専用テーブル開始 -->
							<table border="0" width="100%" height="180">
								<tr height="60" bgcolor="#FFFFFF">
									<td colspan="3"><br></td>
								</tr>
								<tr height="60">
									<!-- リンク開始 -->
									<td align="center">
										<a href="<?=$_SESSION['s_one_advice']?>" title="ワンポイントアドバイス電気編"><img src="img/shindan/button/back3.gif" border="0" width="117" height="36"></a>
									</td>
									<!-- ↓リセット機能をつける -->
									<td align="center">
										<a href="sim_reibou_LD.php" title="リセット"><img src="img/shindan/button/reset.gif" border="0" width="117" height="36"></a>
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