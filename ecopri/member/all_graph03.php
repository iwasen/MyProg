<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:���Ե���񥰥��ɽ��
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/graph03.php");

// Flash�ѥ�᡼���Խ�
/* ��ʬ�ΥΥ���ܿ� */
$fv[] = "treeNormNum=$norm_num";
/* �����ɸ�ܿ� */
$fv[] = "treeGoalNum=0";
/* ��ʬ�������ܿ��ʥץ饹���ޥ��ʥ��� */
$fv[] = "totaltreeNum=$total_tree_num";
/* ��ʬ�����ѡ�Flash��Ƿ׻������� */
$fv[] = "totaltreePer=0";
/* ����� */
$fv[] = "addmissMonth=$start_month";
/* ������ڤ��ܿ���39�ܰʲ��� */
for($i = 0; $i < 12; $i++) {
	$fv[] = "treeM" . ($i + 1) . "Num=$tree_month[$i]";
	$fv[] = "input" . ($i + 1) . "=$input[$i]";
}
/* �����Υȡ������ܿ� */
$fv[] = "totaltreeNumPast=$past_tree_num";
$fv = join('&', $fv);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>������xxx��������Club������</title>
<link rel="stylesheet" type="text/css" href="css/main.css">
</head>

<body bgcolor="#FF0000" text="#000000" link="#66ccff" vlink="#66ccff" leftmargin="8" topmargin="8" alink="#66ccff" class="b0">
<div align="center">

<table border="0" cellpadding="0" cellspacing="0" width="720" height="90%" bgcolor="#ff0000">
	<tr>
		<td align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="720" bgcolor="#ff0000">
				<tr>
					<td width=35 height=31></td>
					<td width="134"><a href="all_graph.php"><img src="img/graph/tagu_01.gif" border="0"></a></td>
					<td width="134"><a href="all_graph02.php"><img src="img/graph/tagu_02.gif" border="0"></a></td>
					<td width="134"><a href="all_graph04.php"><img src="img/graph/tagu_04.gif" border="0"></a></td>
					<td width="134"><img src="img/graph/graph03.gif" border="0"></td>
					<td width=283></td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#ffffff"><img src="img/kado_1.gif" width="35" height="35"></td>
					<td height=90 align="center" valign="bottom" bgcolor="#FFFFFF" colspan=5>
						<!-- �����ȥ� -->
						<img src="img/graph/giteisyo.gif" border="0" width="329" height="78">
					</td>
					<td valign="top" bgcolor="#ffffff"><img src="img/kado_2.gif" width="35" height="35"></td>
				</tr>
			</table>

			<!-- Flash���˥ᡦ�����ΰ� -->
			<table border="0" cellspacing="0" cellpadding="0" width="720" height="550" bgcolor="#ffffff">
				<tr>
					<td bgcolor="#ffffff" align="center" class="tc0">
						�Ķ��ʤ���Ƥ���CO2�ӽк︺��ɸ�ˡ֤ɤ줯�餤�׸��Ǥ������פ��ǧ���륰��դǤ�
						<br><br>
						��1�ܤ��ڤ򿢤���ȡ�1ǯ�֤�13.9kg��CO2��ۼ����Ƥ����ɤȸ����Ƥ��ޤ���
						<br>
						���ʤ��βȤ�CO2�ӽ��̤򿢼��ܿ����㤨�ƥ���դ�ɽ�����Ƥ��ޤ���<br>
						CO2���︺�Ǥ��Ƥ���ۤɿ����ܿ������������ä��Ƥ���ȸϤ����ܿ��������Ƥ��ޤ��ޤ���<br><br>
						��ɸ��ã���Ǥ����顢���٤Ϥ��ä����������ڤ�����ʤ��褦�ˤ���ФäƤ��������͡�
						<!-- Flash�����ΰ� -->
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
						<a href="javascript:window.close()"><img src="img/button/win_close.gif" border="0" width="162" height="30" alt="���Υ�����ɥ����Ĥ���"></a>
					</td>
					<td valign="bottom"><img src="img/kado_4.gif" width="35" height="35"></td>
				</tr>
			</table>
		</td>
	</tr>

</table>

</div>
</body>
</html>