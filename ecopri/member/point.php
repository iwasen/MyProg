<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:ポイントアニメ表示
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/point.php");
$inc = "$top/inc";
include("$inc/mypage.php");

// 会員番号取得
$seq_no = $_SESSION['ss_seq_no'];

// セッションから年月取得
if (!$ym)
	$ym = $_SESSION['s_anime_ym'];

// YYYY-MM-DD形式に変換
$date = sprintf('%s-%s-01', substr($ym, 0, 4), substr($ym, -2));

// 獲得ポイントを取得
$sql = "SELECT cr_point FROM t_co2_rank WHERE cr_seq_no=$seq_no AND cr_ym='$date'";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$co2_point = $fetch->cr_point;
}

// ポイント残高取得
$total_point = get_point();

// Flashパラメータ編集
$fv[] = "Point_co2=$co2_point";
$fv[] = "Point_totalco2=$total_point";
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
<table border="0" cellpadding="0" cellspacing="0" width="714" height="99%" bgcolor="#FF0000">
	<tr>
		<td align="center" valign="middle">
		<!-- ここに入力 -->
		<!-- URL's used in the movie-->
		<!-- text used in the movie-->
		<!-- Flashタグ領域 -->
		<OBJECT ID=SWFlash classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
		 codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0"
		 WIDTH="626" HEIGHT="470">
			<PARAM NAME=movie VALUE="flash/point.swf">
			<PARAM NAME=quality VALUE=high>
			<PARAM NAME=bgcolor VALUE=#FF0000>
			<PARAM NAME=play VALUE=TRUE>
			<PARAM NAME=loop VALUE=FALSE>
			<PARAM NAME=FlashVars VALUE="<?=$fv?>">
			<EMBED src="flash/point.swf" play=TRUE loop=FALSE quality=high bgcolor=#FF0000  WIDTH="626" HEIGHT="470" NAME="SWFlash"
			 TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/go/getflashplayer" FlashVars="<?=$fv?>"></EMBED>
		</OBJECT>
		<!-- Flashタグ領域終了 -->
		<a href="life_style.php"><img src="img/button/bgred/check.gif" border="0" width="122" height="30"></a>
		<img src="img/spacer.gif" width="10" height="1">
<!--
		<a href="gardening.php"><img src="img/button/bgred/gardening.gif" border="0" width="137" height="30"></a>
		<img src="img/spacer.gif" width="10" height="1">
-->
		<a href="main.php"><img src="img/button/bgred/main.gif" border="0" width="108" height="30"></a>
		<!-- ここまで -->
		</td>
	</tr>
</table>
<!-- 白丸角テーブル終了 -->

</div>
</body>
</html>