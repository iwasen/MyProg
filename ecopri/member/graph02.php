<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:�郎�Ȥη�̥����ɽ��
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/graph02.php");

// Flash�ѥ�᡼���Խ�
/* 	���줾��ΥС��ι⤵ */
for($i = 0; $i < 50; $i++)
	$fv[] = "bar" . ($i + 1) . "Per=$bar_ary[$i]";
/* X, Y����MAX�� */
$fv[] = "axisXMax=$total_ttl_num";
$fv[] = "axisYMax=$max_co2";
/* �ϰ�����Τ���Ϳ� */
$fv[] = "localPersonMaxNum=$local_ttl_num";
$fv[] = "totalPersonMaxNum=$total_ttl_num";
/* �ϰ衦���Ρ����ƥ��꡼��˸������ */
$fv[] = "localRank=$local_ttl_rank";
$fv[] = "totalRank=$total_ttl_rank";
$fv[] = "categoryRank=$total_ttl_rank";
/* co2��ʿ���� */
$fv[] = "co2Average=$ave_rank";

/* ��²��̡��ɲá� */
$fv[] = "familyRank=$categ_ttl_rank";
$fv[] = "familyMaxNum=$categ_ttl_num";

//$fv[] = "addmissMonth=3";

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
					<td width="134"><a href="graph.php"><img src="img/graph/tagu_01.gif" border="0"></a></td>
					<td width="134"><img src="img/graph/tagu_02.gif" border="0"></td>
					<td width="134"><a href="graph04.php"><img src="img/graph/tagu_04.gif" border="0"></a></td>
					<td width=283></td>
				</tr>
			</table>
			<table border="0" cellspacing="0" cellpadding="0" width="720" bgcolor="#ffffff">
				<tr>
					<td valign="top" bgcolor="#ffffff"><img src="img/kado_1.gif" width="35" height="35"></td>
					<td width=650 bgcolor="#FFFFFF"></td>
					<td valign="top" bgcolor="#ffffff"><img src="img/kado_2.gif" width="35" height="35"></td>
				</tr>
				<tr>
					<td align="center" colspan=3>
						<table border=0 cellspacing=0 cellpadding=0>
							<tr>
								<td align="center">
									<img src="img/graph/rank_month/<?=sprintf('%02d', $this_month)?>.gif" width=59 height=47 alt="��">
									<img src="img/graph/title_rank.gif" width=243 height=47 alt="��󥭥�">
								</td>
							</tr>
							<tr>
								<td align="center"><img src="img/graph/title_co2.gif" width=346 height=31 alt="�郎�Ȥ�co2�ӽ���"></td>
							</tr>
							<tr>
								<td class="tc0"><br>
								���Υ�󥭥󥰤ϡ��ŵ�����������ƻ�������ι��CO2�ӽ��̤�껻�Ф���Ƥ��ޤ���<br>
								�嵭4���ܤ�̤���Ϥξ��ˤϽ�̤�ɽ���Ǥ��ޤ���<br>
								��̤���Ѥ����ꤷ������̤���ϤǤ��󥭥󥰤�ɽ������ޤ�����<br><br>
								</td>
							</tr>
						</table>
							<!-- Flash���˥ᡦ�����ΰ� -->
						<table border="0" cellspacing="0" cellpadding="0" width="720" height="550">
							<tr>
								<td align="center" valign="center" bgcolor="#FFFFFF">
									<!-- Flash�����ΰ� -->
									<OBJECT ID=SWFlash classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
									 codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0"
									 WIDTH="700" HEIGHT="300">
										<PARAM NAME=movie VALUE="flash/rank_co2.swf">
										<PARAM NAME=quality VALUE=high>
										<PARAM NAME=bgcolor VALUE=#FFFFFF>
										<PARAM NAME=play VALUE=TRUE>
										<PARAM NAME=loop VALUE=FALSE>
										<PARAM NAME=FlashVars VALUE="<?=$fv?>">
										<EMBED src="flash/rank_co2.swf" play=TRUE loop=FALSE quality=high bgcolor=#FFFFFF  WIDTH="700" HEIGHT="300" NAME="SWFlash"
										 TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/go/getflashplayer" FlashVars="<?=$fv?>"></EMBED>
									</OBJECT>

									<!-- ���ȯɽ�ơ��֥뤳������ -->
									<br><br><br>
									<img src="img/graph/eng_month/<?=sprintf('%02d', $this_month)?>.gif" width=330 height=36>
									<br><br>
									<table border="1" width="600" height="300" align="center" bgcolor="#FFFA55" bordercolordark="orange" bordercolorlight="orange">
										<tr align="center">
											<td width="50"><img src="img/spacer.gif"></td>
											<td><img src="img/graph/title_wan.gif" alt="����" width="35" height="20"></td>
											<td><img src="img/graph/title_lan.gif" alt="�ϰ�" width="35" height="20"></td>
											<td valign="bottom" class="tc0">
												<img src="img/graph/title_kategori.gif" alt="���ƥ���" width="64" height="18"><br>
												��<?=$famiry_text?>��
											</td>
										</tr>
										<tr align="center">
											<td width="50"><img src="img/character/denki.gif"></td>
											<td bgcolor="#ffffff"><? disp_rank($total_ele_rank, $total_ele_num, $ele_co2) ?></td>
											<td bgcolor="#ffffff"><? disp_rank($local_ele_rank, $local_ele_num, $ele_co2) ?></td>
											<td bgcolor="#ffffff"><? disp_rank($categ_ele_rank, $categ_ele_num, $ele_co2) ?></td>
										</tr>
										<tr align="center">
											<td ><img src="img/character/gas.gif"></td>
											<td bgcolor="#ffffff"><? disp_rank($total_gas_rank, $total_gas_num, $gas_co2) ?></td>
											<td bgcolor="#ffffff"><? disp_rank($local_gas_rank, $local_gas_num, $gas_co2) ?></td>
											<td bgcolor="#ffffff"><? disp_rank($categ_gas_rank, $categ_gas_num, $gas_co2) ?></td>
										</tr>
										<tr align="center">
											<td><img src="img/character/suidou.gif"></td>
											<td bgcolor="#ffffff"><? disp_rank($total_wtr_rank, $total_wtr_num, $wtr_co2) ?></td>
											<td bgcolor="#ffffff"><? disp_rank($local_wtr_rank, $local_wtr_num, $wtr_co2) ?></td>
											<td bgcolor="#ffffff"><? disp_rank($categ_wtr_rank, $categ_wtr_num, $wtr_co2) ?></td>
										</tr>
										<tr align="center">
											<td ><img src="img/character/touyu.gif"></td>
											<td bgcolor="#ffffff"><? disp_rank($total_oil_rank, $total_oil_num, $oil_co2) ?></td>
											<td bgcolor="#ffffff"><? disp_rank($local_oil_rank, $local_oil_num, $oil_co2) ?></td>
											<td bgcolor="#ffffff"><? disp_rank($categ_oil_rank, $categ_oil_num, $oil_co2) ?></td>
										</tr>
										<tr align="center">
											<td><img src="img/character/gomi.gif"></td>
											<td bgcolor="#ffffff"><? disp_rank($total_dst_rank, $total_dst_num, $dst_co2) ?></td>
											<td bgcolor="#ffffff"><? disp_rank($local_dst_rank, $local_dst_num, $dst_co2) ?></td>
											<td bgcolor="#ffffff"><? disp_rank($categ_dst_rank, $categ_dst_num, $dst_co2) ?></td>
										</tr>
										<tr align="center">
											<td><img src="img/character/gasoline.gif"></td>
											<td bgcolor="#ffffff"><? disp_rank($total_gso_rank, $total_gso_num, $gso_co2) ?></td>
											<td bgcolor="#ffffff"><? disp_rank($local_gso_rank, $local_gso_num, $gso_co2) ?></td>
											<td bgcolor="#ffffff"><? disp_rank($categ_gso_rank, $categ_gso_num, $gso_co2) ?></td>
										</tr>
									</table>
									<table border="0" width="580" cellspacing=3 cellpadding=0>
										<tr>
											<td class="tc0">
											�� ���ν�̤ϡ��ƥ��ͥ륮�������Ϥ����ä����˻��Ф��Ƥ��ޤ���<br>
											�� "̤����"�����ꤷ�����ͥ륮����ɽ������ޤ���
											</td>
										<tr>
									</table>
									<br>
									<!-- �����ޤ� -->
								</td>
							</tr>
						</table>
						<!-- �����ޤ� -->
					</td>
				</tr>
				<tr>
					<td valign="bottom"><img src="img/kado_3.gif" width="35" height="35"></td>
					<td height=50 bgcolor="#FFFFFF" align="center" valign="top">
						<a href="appre.php"><img src="img/graph/button_hitokoto.gif" width="120" height="30" border="0" alt="��������ΰ��"></a>	
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