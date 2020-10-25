<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:我が家の暮らしっぷり再現アニメ表示
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");

// 会員番号取得
$seq_no = $_SESSION['ss_seq_no'];

// セッションから年月取得
if (!$ym)
	$ym = $_SESSION['s_anime_ym'];

// YYYY-MM-DD形式に変換
$date = sprintf('%s-%s-01', substr($ym, 0, 4), substr($ym, -2));

// ランクを取得
$sql = "SELECT cr_ele_rank,cr_gas_rank,cr_wtr_rank FROM t_co2_rank WHERE cr_seq_no=$seq_no AND cr_ym='$date'";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$ele_rank = $fetch->cr_ele_rank;
	$gas_rank = $fetch->cr_gas_rank;
	$wtr_rank = $fetch->cr_wtr_rank;
}

// Flashパラメータ編集
$fv[] = "elc_co2Rank=" . ($ele_rank ? $ele_rank : 4);
$fv[] = "wtr_co2Rank=" . ($wtr_rank ? $wtr_rank : 4);
$fv[] = "gas_co2Rank=" . ($gas_rank ? $gas_rank : 4);
$fv = join('&', $fv);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>☆★☆xxxゾウさんClub☆★☆</title>
</head>

<body bgcolor="#FF0000" text="#000000" link="#66ccff" vlink="#66ccff" leftmargin="8" topmargin="8" alink="#66ccff">
<div align="center">

<!-- 白丸角テーブル -->
<table border="0" cellpadding="0" cellspacing="0" width="714" height="100%" bgcolor="#FF0000">
	<tr>
		<td align="center" valign="middle">
		<!-- ここに入力 -->
		<!-- URL's used in the movie-->
		<!-- text used in the movie-->
		<!-- Flashタグ領域 -->
		<OBJECT ID=SWFlash classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
			codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0"
			 WIDTH="760" HEIGHT="570">
			<PARAM NAME=movie VALUE="flash/anime_rank.swf">
			<PARAM NAME=quality VALUE=high>
			<PARAM NAME=bgcolor VALUE=#FFFFFF>
			<PARAM NAME=play VALUE=TRUE>
			<PARAM NAME=loop VALUE=FALSE>
			<PARAM NAME=FlashVars VALUE="<?=$fv?>">
			<EMBED src="flash/anime_rank.swf" play=TRUE loop=FALSE quality=high bgcolor=#FFFFFF  WIDTH="760" HEIGHT="570" NAME="SWFlash"
			TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/go/getflashplayer" FlashVars="<?=$fv?>"></EMBED>
		</OBJECT>
		<a href="main.php" target="_top"><img src="img/button/bgred/main.gif" border="0"></a>
		<!-- Flashタグ領域終了 -->
		<!-- ここまで -->
		</td>
	</tr>
</table>
<!-- 白丸角テーブル終了 -->

</div>
</body>
</html>