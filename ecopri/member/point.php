<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:�ݥ���ȥ��˥�ɽ��
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/point.php");
$inc = "$top/inc";
include("$inc/mypage.php");

// ����ֹ����
$seq_no = $_SESSION['ss_seq_no'];

// ���å���󤫤�ǯ�����
if (!$ym)
	$ym = $_SESSION['s_anime_ym'];

// YYYY-MM-DD�������Ѵ�
$date = sprintf('%s-%s-01', substr($ym, 0, 4), substr($ym, -2));

// �����ݥ���Ȥ����
$sql = "SELECT cr_point FROM t_co2_rank WHERE cr_seq_no=$seq_no AND cr_ym='$date'";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$co2_point = $fetch->cr_point;
}

// �ݥ���ȻĹ����
$total_point = get_point();

// Flash�ѥ�᡼���Խ�
$fv[] = "Point_co2=$co2_point";
$fv[] = "Point_totalco2=$total_point";
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
<table border="0" cellpadding="0" cellspacing="0" width="714" height="99%" bgcolor="#FF0000">
	<tr>
		<td align="center" valign="middle">
		<!-- ���������� -->
		<!-- URL's used in the movie-->
		<!-- text used in the movie-->
		<!-- Flash�����ΰ� -->
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
		<!-- Flash�����ΰ轪λ -->
		<a href="life_style.php"><img src="img/button/bgred/check.gif" border="0" width="122" height="30"></a>
		<img src="img/spacer.gif" width="10" height="1">
<!--
		<a href="gardening.php"><img src="img/button/bgred/gardening.gif" border="0" width="137" height="30"></a>
		<img src="img/spacer.gif" width="10" height="1">
-->
		<a href="main.php"><img src="img/button/bgred/main.gif" border="0" width="108" height="30"></a>
		<!-- �����ޤ� -->
		</td>
	</tr>
</table>
<!-- ��ݳѥơ��֥뽪λ -->

</div>
</body>
</html>