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

// ����Υ�󥯤����
$date = sprintf('%s-%s-01', substr($ym, 0, 4), substr($ym, -2));
$sql = "SELECT cr_rank FROM t_co2_rank WHERE cr_seq_no=$seq_no AND cr_ym='$date'";
$rank = db_fetch1($sql);

// ���Υ�󥯤����
$date = add_date($date, 0, -1, 0);
$sql = "SELECT cr_rank FROM t_co2_rank WHERE cr_seq_no=$seq_no AND cr_ym='$date'";
$rank_lm = db_fetch1($sql);

// ���ʳ���󥯷���
if (!$rank || !$rank_lm) {
	// �ɤ��餫�Υ�󥯤�̵�����2
	$total_rank = 2;
} elseif ($rank < $rank_lm) {
	// �����1
	$total_rank = 1;
} elseif ($rank > $rank_lm) {
	// ������3
	$total_rank = 3;
} else {
	// Ʊ���ʤ�2
	$total_rank = 2;
}

// ɽ�������
$month = substr($ym, -2);
if (++$month == 13)
	$month = 1;

// Flash�ѥ�᡼���Խ�
$fv[] = "co2_totalRank=$total_rank";
$fv[] = "thisMonth=$month";
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
		<td align="center" valign="center">
		<!-- ���������� -->
		<!-- URL's used in the movie-->
		<!-- text used in the movie-->
		<!-- Flash�����ΰ� -->
		<OBJECT ID=SWFlash classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
		 codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0"
		 WIDTH="760" HEIGHT="570">
			<PARAM NAME=movie VALUE="flash/appre.swf">
			<PARAM NAME=quality VALUE=high>
			<PARAM NAME=bgcolor VALUE=#FFFFFF>
			<PARAM NAME=play VALUE=TRUE>
			<PARAM NAME=loop VALUE=FALSE>
			<PARAM NAME=FlashVars VALUE="<?=$fv?>">
			<EMBED src="flash/appre.swf" play=TRUE loop=FALSE quality=high bgcolor=#FF0000  WIDTH="760" HEIGHT="570" NAME="SWFlash"
			 TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/go/getflashplayer" FlashVars="<?=$fv?>"></EMBED>
		</OBJECT>
		<!-- Flash�����ΰ轪λ -->
		<!-- �����ޤ� -->
		</td>
	</tr>
</table>
<!-- ��ݳѥơ��֥뽪λ -->

</div>
</body>
</html>