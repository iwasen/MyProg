<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:電気の使用量推移グラフ表示
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
include("$inc/eng_comment.php");

// Flashパラメータ編集
/* 	グラフに打つそれぞれの点の高さ */
for($i = 0; $i < 12; $i++) {
	$fv[] = "elcM" . ($i + 1) . "spend=" . $use_cy_ary[$i]['ele'];
	$fv[] = "LYelcM" . ($i + 1) . "spend=" . $use_ly_ary[$i]['ele'];
	$fv[] = "input" . ($i + 1) . "=" . $status_ary[$i]['ele'];
}
$fv[] = "elc_spendMetaphor=" . $metaphor['ele'];
$fv[] = "elc_compareValue=" . $comp_ary['ele'];
$fv[] = "thisMonth=" . $this_month;
$fv[] = "addmissMonth=" . (int)get_datepart('M', $start_ym);

$fv = join('&', $fv);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>電気の使用量推移グラフ</title>
</head>

<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="10" marginwidth="0" marginheight="0">
<div align="center">
<table cellspacing=0 cellpadding=0 border=0 width=580>
	<tr>
		<td align="center">
			<img src="img/graph/title/graph_denki.gif" width="279" height="34" alt="電気の推移グラフ">
			<br>

			<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0" width="560" height="300">
			    <param name=movie value="flash/graph/graph_ele<?=$graph?>.swf">
			    <param name=quality value=high>
			    <embed src="flash/graph/graph_ele<?=$graph?>.swf" quality=high pluginspage="http://www.macromedia.com/jp/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="560" height="300" FlashVars="<?=$fv?>"></embed>
					<param name=FlashVars value="<?=$fv?>">
			  </object><br>
		</td>
	</tr>
</table>
<table width="50%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="center"><img src="img/graph/eng_comment/denki_<?=$rank['ele']?>.gif" width=434 height=82 alt="電気コメント"></td>
	</tr>
</table>
<hr width="90%">
<br>
<a href="javascript:window.close()"><img src="img/button/win_close.gif" border="0" width="162" height="30" alt="このウィンドウを閉じる"></a>
</div>
</body>
</html>
