<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:�椬�Ȥ���餷�äפ�Ƹ����˥�ɽ��
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");

// ����ֹ����
$seq_no = $_SESSION['ss_seq_no'];

// ���å���󤫤�ǯ�����
if (!$ym)
	$ym = $_SESSION['s_anime_ym'];

// YYYY-MM-DD�������Ѵ�
$date = sprintf('%s-%s-01', substr($ym, 0, 4), substr($ym, -2));

// ��󥯤����
$sql = "SELECT cr_ele_rank,cr_gas_rank,cr_wtr_rank FROM t_co2_rank WHERE cr_seq_no=$seq_no AND cr_ym='$date'";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$ele_rank = $fetch->cr_ele_rank;
	$gas_rank = $fetch->cr_gas_rank;
	$wtr_rank = $fetch->cr_wtr_rank;
}

// Flash�ѥ�᡼���Խ�
$fv[] = "elc_co2Rank=" . ($ele_rank ? $ele_rank : 4);
$fv[] = "wtr_co2Rank=" . ($wtr_rank ? $wtr_rank : 4);
$fv[] = "gas_co2Rank=" . ($gas_rank ? $gas_rank : 4);
$fv = join('&', $fv);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>������xxx��������Club������</title>
</head>

<body bgcolor="#FF0000" text="#000000" link="#66ccff" vlink="#66ccff" leftmargin="8" topmargin="8" alink="#66ccff">
<div align="center">

<!-- ��ݳѥơ��֥� -->
<table border="0" cellpadding="0" cellspacing="0" width="714" height="100%" bgcolor="#FF0000">
	<tr>
		<td align="center" valign="middle">
		<!-- ���������� -->
		<!-- URL's used in the movie-->
		<!-- text used in the movie-->
		<!-- Flash�����ΰ� -->
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
		<!-- Flash�����ΰ轪λ -->
		<!-- �����ޤ� -->
		</td>
	</tr>
</table>
<!-- ��ݳѥơ��֥뽪λ -->

</div>
</body>
</html>