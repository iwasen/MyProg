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

// 指定がなければ最新のデータ
if (!$ym) {
	$sql = "SELECT to_char(max(cr_ym), 'YYYYMM') FROM t_co2_rank WHERE cr_seq_no=$seq_no";
	$ym = db_fetch1($sql);
	if (!$ym) {
		$sql = "SELECT to_char(max(bd_month), 'YYYYMM') FROM t_base_data WHERE bd_mb_seq_no=$seq_no AND bd_commit_flag=1";
		$ym = db_fetch1($sql);
	}
}

// 表示月取得
$month = substr($ym, -2);
if (++$month == 13)
	$month = 1;

// セッションに保存
$_SESSION['s_anime_ym'] = $ym;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>☆★☆xxxゾウさんClub☆★☆</title>
</head>

<body bgcolor="#FF0000" text="#000000" link="#66ccff" vlink="#66ccff" alink="#66ccff" leftmargin="8" topmargin="8">
<div align="center">

<!-- 白丸角テーブル -->
<table border="0" cellpadding="0" cellspacing="0" width="100％" height="100%" bgcolor="#FF0000">
	<tr>
		<td align="center" valign="center">
		<!-- ここに入力 -->
		<!-- URL's used in the movie-->
		<!-- text used in the movie-->
		<!-- Flashタグ領域 -->
		<OBJECT ID=SWFlash classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
			codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0"
			 WIDTH="760" HEIGHT="570">
			<PARAM NAME=movie VALUE="flash/anime.swf">
			<PARAM NAME=quality VALUE=high>
			<PARAM NAME=bgcolor VALUE=#FFFFFF>
			<PARAM NAME=play VALUE=TRUE>
			<PARAM NAME=loop VALUE=FALSE>
			<PARAM NAME=FlashVars VALUE="thisMonth=<?=$month?>">
			<EMBED src="flash/anime.swf" play=TRUE loop=FALSE quality=high bgcolor=#FFFFFF  WIDTH="760" HEIGHT="570" NAME="SWFlash" 
			FlashVars="thisMonth=<?=$month?>" 
			TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/go/getflashplayer"></EMBED>
		</OBJECT>
		<br>
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