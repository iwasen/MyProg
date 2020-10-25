<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:我が家の結果グラフ表示
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/cnv_eng_co2.php");
include("$inc/cnv_unit.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/graph01.php");

// Flashパラメータ編集
/* 	グラフに打つそれぞれの点の高さ */
for($i = 0; $i < 12; $i++) {
	$fv[] = "elcM" . ($i + 1) . "spend=" . $use_cy_ary['ele'][$i];
	$fv[] = "LYelcM" . ($i + 1) . "spend=" . $use_ly_ary['ele'][$i];

	$fv[] = "gasM" . ($i + 1) . "spend=" . $use_cy_ary['gas'][$i];
	$fv[] = "LYgasM" . ($i + 1) . "spend=" . $use_ly_ary['gas'][$i];

	$fv[] = "wtrM" . ($i + 1) . "spend=" . $use_cy_ary['wtr'][$i];
	$fv[] = "LYwtrM" . ($i + 1) . "spend=" . $use_ly_ary['wtr'][$i];

	$fv[] = "oilM" . ($i + 1) . "spend=" . $use_cy_ary['oil'][$i];
	$fv[] = "LYoilM" . ($i + 1) . "spend=" . $use_ly_ary['oil'][$i];

	$fv[] = "gsoM" . ($i + 1) . "spend=" . $use_cy_ary['gso'][$i];
	$fv[] = "LYgsoM" . ($i + 1) . "spend=" . $use_ly_ary['gso'][$i];

	$fv[] = "dstM" . ($i + 1) . "spend=" . $use_cy_ary['dst'][$i];
	$fv[] = "LYdstM" . ($i + 1) . "spend=" . $use_ly_ary['dst'][$i];

	$fv[] = "co2M" . ($i + 1) . "spend=" . $use_cy_ary['co2'][$i];
	$fv[] = "LYco2M" . ($i + 1) . "spend=" . $use_ly_ary['co2'][$i];
}

$fv[] = "elc_spendMetaphor=" . $metaphor['ele'];
$fv[] = "gas_spendMetaphor=" . $metaphor['gas'];
$fv[] = "wtr_spendMetaphor=" . $metaphor['wtr'];
$fv[] = "oil_spendMetaphor=" . $metaphor['oil'];
$fv[] = "gso_spendMetaphor=" . $metaphor['gso'];
$fv[] = "dst_spendMetaphor=" . $metaphor['dst'];
$fv[] = "co2_spendMetaphor=" . $metaphor['co2'];

$fv[] = "elc_compareValue=" . $comp_ary['ele'];
$fv[] = "gas_compareValue=" . $comp_ary['gas'];
$fv[] = "wtr_compareValue=" . $comp_ary['wtr'];
$fv[] = "oil_compareValue=" . $comp_ary['oil'];
$fv[] = "gso_compareValue=" . $comp_ary['gso'];
$fv[] = "dst_compareValue=" . $comp_ary['dst'];

$fv[] = "elc_compareCost=" . $cost_ary['ele'];
$fv[] = "gas_compareCost=" . $cost_ary['gas'];
$fv[] = "wtr_compareCost=" . $cost_ary['wtr'];
$fv[] = "oil_compareCost=" . $cost_ary['oil'];
$fv[] = "gso_compareCost=" . $cost_ary['gso'];
$fv[] = "dst_compareCost=0";

/* 追加変数 */
$fv[] = "thisMonth=" . (int)get_datepart('M', $data_ym);
$fv[] = "thisMonth_co2spend=$co2";

$fv[] = "addmissMonth=" . (int)get_datepart('M', $start_ym);

$fv = join('&', $fv);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>☆★☆xxxゾウさんClub☆★☆</title>
</head>

<body bgcolor="#FF0000" text="#000000" link="#66ccff" vlink="#66ccff" leftmargin="5" topmargin="8" alink="#66ccff">
<div align="center">

<!-- 白丸角テーブル -->
<table border="0" cellpadding="0" cellspacing="0" width="714" height="90%" bgcolor="#ff0000">
	<tr>
		<td align="center" valign="top">
			<!-- ここに入力 -->
				<!-- タブ用テーブル -->
				<!-- それぞれのボタンを押したとき、そのボタンに対応したFlashが表示される -->
				<!-- さらに、今黄色になっているテーブル枠の色をタブと同じ色へ変更する -->
				<table border="0" cellspacing="0" cellpadding="0" width="720" bgcolor="#ff0000">
					<tr>
						<td width="128"><img src="img/graph/graph01.gif" border="0"></td>
						<td width="128"><a href="graph02.php"><img src="img/graph/graph02.gif" border="0"></a></td>
						<td><img src="img/spacer.gif" height=10 width=10></td>
					</tr>
					<tr>
						<td colspan="3" height="10" bgcolor="#FFFF00"><img src="img/spacer.gif" height=10 width=10></td>
					</tr>
				</table>

				<!-- Flashアニメ・画像領域 -->
				<table border="0" cellspacing="0" cellpadding="0" width="720" height="550" bgcolor="#FFFF00">
					<tr>
						<td width="10"><img src="img/spacer.gif" height=10 width=10></td>
						<td colspan="3" align="center" valign="center" bgcolor="ffffff">
							<!-- Flashタグ領域 -->
							<OBJECT ID=SWFlash classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
							 codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0"
							 WIDTH="600" HEIGHT="550">
								<PARAM NAME=movie VALUE="flash/result.swf">
								<PARAM NAME=quality VALUE=high>
								<PARAM NAME=bgcolor VALUE=#FFFFFF>
								<PARAM NAME=play VALUE=TRUE>
								<PARAM NAME=loop VALUE=FALSE>
								<PARAM NAME=FlashVars VALUE="<?=$fv?>">
								<EMBED src="flash/result.swf" play=TRUE loop=FALSE quality=high bgcolor=#FFFFFF  WIDTH="700" HEIGHT="550" NAME="SWFlash"
								 TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/go/getflashplayer" FlashVars="<?=$fv?>"></EMBED>
							</OBJECT>
						</td>
						<td width="10"><img src="img/spacer.gif" height=10 width=10></td>
					</tr>
					<tr>
						<td height="10"><img src="img/spacer.gif" height=10 width=10></td>
					</tr>
				</table>
				<!-- ドラムロールからグラフ表示へ -->
			<!-- ここまで -->
		</td>
	</tr>

</table>
<!-- 白丸角練習テーブル終了 -->

</div>
</body>
</html>