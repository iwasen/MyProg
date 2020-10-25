<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:京都議定書グラフ表示
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/graph03.php");

// Flashパラメータ編集
/* 自分のノルマ本数 */
$fv[] = "treeNormNum=$norm_num";
/* 会員目標本数 */
$fv[] = "treeGoalNum=0";
/* 自分の累積本数（プラス・マイナス） */
$fv[] = "totaltreeNum=$total_tree_num";
/* 自分の累積（Flash内で計算される） */
$fv[] = "totaltreePer=0";
/* 入会月 */
$fv[] = "addmissMonth=$start_month";
/* 月毎の木の本数（39本以下） */
for($i = 0; $i < 12; $i++) {
	$fv[] = "treeM" . ($i + 1) . "Num=$tree_month[$i]";
	$fv[] = "input" . ($i + 1) . "=$input[$i]";
}
/* 以前のトータル本数 */
$fv[] = "totaltreeNumPast=$past_tree_num";
$fv = join('&', $fv);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>☆★☆xxxゾウさんClub☆★☆</title>
<link rel="stylesheet" type="text/css" href="css/main.css">
</head>

<body bgcolor="#FF0000" text="#000000" link="#66ccff" vlink="#66ccff" leftmargin="5" topmargin="8" alink="#66ccff">
<div align="center">

<!-- 白丸角テーブル -->
<table border="0" cellpadding="0" cellspacing="0" width="720" height="90%" bgcolor="#ff0000">
	<tr>
		<td align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="720" bgcolor="#ff0000">
				<tr>
					<td width=35 height=31></td>
					<td width="134"><img src="img/graph/graph03.gif" border="0"></td>
					<td width=551></td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#ffffff"><img src="img/kado_1.gif" width="35" height="35"></td>
					<td height=90 align="center" valign="bottom" bgcolor="#FFFFFF" colspan=5>
						<!-- タイトル -->
						<img src="img/graph/giteisyo.gif" border="0" width="329" height="78">
					</td>
					<td valign="top" bgcolor="#ffffff"><img src="img/kado_2.gif" width="35" height="35"></td>
				</tr>
			</table>

			<!-- Flashアニメ・画像領域 -->
			<table border="0" cellspacing="0" cellpadding="0" width="720" height="550" bgcolor="#ffffff">
				<tr>
					<td align="center" class="tc0">
					<br>
					環境省が提案するCO2排出削減目標に「どれくらい貢献できたか」を確認するグラフです
					<br><br>
					”1本の木を植えると、1年間に13.9kgのCO2を吸収してくれる”と言われています。
					<br>
					あなたの家のCO2排出量を植樹本数に例えてグラフで表示しています。<br>
					CO2が削減できているほど植樹本数が増え、増加していると枯れ木本数が増えてしまいます。<br><br>
					目標が達成できたら、今度はせっかく植えた木が減らないようにがんばってくださいね。
					</td>
				</tr>
				<tr>
					<td align="center" valign="center" bgcolor="ffffff">
						<!-- Flashタグ領域 -->

						<OBJECT ID=SWFlash classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
						 codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0"
						 WIDTH="700" HEIGHT="500">
							<PARAM NAME=movie VALUE="flash/co2_wood.swf">
							<PARAM NAME=quality VALUE=high>
							<PARAM NAME=bgcolor VALUE=#FFFFFF>
							<PARAM NAME=play VALUE=TRUE>
							<PARAM NAME=loop VALUE=FALSE>
							<PARAM NAME=FlashVars VALUE="<?=$fv?>">
							<EMBED src="flash/co2_wood.swf" play=TRUE loop=FALSE quality=high bgcolor=#FFFFFF  WIDTH="700" HEIGHT="500" NAME="SWFlash"
							 TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/go/getflashplayer" FlashVars="<?=$fv?>"></EMBED>
						</OBJECT>
					</td>
				</tr>
			</table>
			<table border="0" cellspacing="0" cellpadding="0" width="720" bgcolor="#ffffff">
				<tr>
					<td height=50 valign="bottom"><img src="img/kado_3.gif" width="35" height="35"></td>
					<td width=650 align="center" valign="top">
						<a href="point.php"><img src="img/button/next02.gif" border="0" width="54" height="30"></a>
					</td>
					<td valign="bottom"><img src="img/kado_4.gif" width="35" height="35"></td>
				</tr>
			</table>
		</td>
	</tr>

	<tr>
		<td></td>
	</tr>
</table>
<!-- 白丸角練習テーブル終了 -->

</div>
</body>
</html>