<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:��ۥ����ɽ��
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/graph04.php");

$fv[] = "thisMonth=$this_month";			// ����η�
$fv[] = "thisMonthCost=$cost_cy";			// ����Υȡ����������
$fv[] = "pastMonthCost=$cost_ly";			// ��ǯƱ����Υȡ����������
$fv[] = "pastTotalCost=$total_cost";	// ���񤫤������ʥ����С��˳ۤΥȡ�����
$fv[] = "tryMonthCost=$cost_ly2";			// ������ɸ���
$fv = join('&', $fv);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>������xxx��������Club������</title>
<link rel="stylesheet" type="text/css" href="css/main.css">
</head>

<body bgcolor="#FF0000" text="#000000" link="#66ccff" vlink="#66ccff" leftmargin="5" topmargin="8" alink="#66ccff" class="b0">
<div align="center">

<!-- ��ݳѥơ��֥� -->
<div align="center">
<table border="0" cellpadding="0" cellspacing="0" width="720" height="90%" bgcolor="#ff0000">
	<tr>
		<td align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="720" bgcolor="#ff0000">
				<tr>
					<td width=35 height=31></td>
					<td width="134"><a href="all_graph.php"><img src="img/graph/tagu_01.gif" border="0"></a></td>
					<td width="134"><a href="all_graph02.php"><img src="img/graph/tagu_02.gif" border="0"></a></td>
					<td width="134"><img src="img/graph/tagu_03.gif" border="0"></td>
					<td width="134"><a href="all_graph03.php"><img src="img/graph/graph03.gif" border="0"></a></td>
					<td width=149></td>
				</tr>
			</table>
			<table border="0" cellspacing="0" cellpadding="0" width="720" bgcolor="#ffffff">
				<tr>
					<td valign="top" bgcolor="#ffffff"><img src="img/kado_1.gif" width="35" height="35"></td>
					<td bgcolor="#FFFFFF" width=650></td>
					<td valign="top" bgcolor="#ffffff"><img src="img/kado_2.gif" width="35" height="35"></td>
				</tr>
			</table>
			<!-- Flash���˥ᡦ�����ΰ� -->
			<table border="0" cellspacing="0" cellpadding="0" width="720" height="550" bgcolor="#ffffff">
				<tr>
					<td bgcolor="#ffffff" align="center">
						<!-- Flash�����ΰ� -->
						<OBJECT ID=SWFlash classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
						 codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0"
						 WIDTH="600" HEIGHT="500">
							<PARAM NAME=movie VALUE="flash/graph/graph_money.swf">
							<PARAM NAME=quality VALUE=high>
							<PARAM NAME=bgcolor VALUE=#FFFFFF>
							<PARAM NAME=play VALUE=TRUE>
							<PARAM NAME=loop VALUE=FALSE>
							<PARAM NAME=FlashVars VALUE="<?=$fv?>">
							<EMBED src="flash/graph/graph_money.swf" play=TRUE loop=FALSE quality=high bgcolor=#FFFFFF  WIDTH="600" HEIGHT="500" NAME="SWFlash"
							 TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/go/getflashplayer" FlashVars="<?=$fv?>"></EMBED>
						</OBJECT>
					</td>
				</tr>
			</table>
			<table border="0" cellspacing="0" cellpadding="0" width="720" bgcolor="#ffffff">
				<tr>
					<td height=50 valign="bottom"><img src="img/kado_3.gif" width="35" height="35"></td>
					<td width=650 align="center" valign="top">
						<a href="javascript:window.close()"><img src="img/button/win_close.gif" border="0" width="162" height="30" alt="���Υ�����ɥ����Ĥ���"></a>
					</td>
					<td valign="bottom"><img src="img/kado_4.gif" width="35" height="35"></td>
				</tr>
			</table>
		</td>
	</tr>

</table>
<!-- ��ݳ������ơ��֥뽪λ -->

</div>
</body>
</html>