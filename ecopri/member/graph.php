<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:�椬�Ȥη�̥����ɽ��
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

// Flash�ѥ�᡼���Խ�
/* 	����դ��ǤĤ��줾������ι⤵ */
for($i = 0; $i < 12; $i++) {
	$fv[] = "co2M" . ($i + 1) . "spend={$use_cy_ary[$i]['co2']}";
	$fv[] = "LYco2M" . ($i + 1) . "spend={$use_ly_ary[$i]['co2']}";
	$fv[] = "input" . ($i + 1) . "={$status_ary[$i]['co2']}";
}
$fv[] = "co2_spendMetaphor={$metaphor['co2']}";
$fv[] = "thisMonth=$this_month";
$fv[] = "thisMonth_co2spend={$use_ary['co2']}";
$fv[] = "addmissMonth=" . (int)get_datepart('M', $start_ym);

$fv = join('&', $fv);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>������Eco�ݤ����Ȥ�Eco���������</title>
<link rel="stylesheet" type="text/css" href="css/main.css">
<script type="text/javascript">
<!--
function open_graph(url) {
	window.open(url + ".php", "_blank", "width=600,height=520,scrollbars=no,resizable=no,status=no,menubar=no,toolbar=no");
}
//-->
</script>
</head>

<body bgcolor="#FF0000" class="b0">

<div align="center">
<table border="0" cellpadding="0" cellspacing="0" width="720" height="90%" bgcolor="#ff0000">
	<tr>
		<td align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="720" bgcolor="#ff0000">
				<tr>
					<td width=35 height=31></td>
					<td width="134"><img src="img/graph/tagu_01.gif" border="0"></td>
					<td width="134"><a href="graph02.php"><img src="img/graph/tagu_02.gif" border="0"></a></td>
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
					<td align="center" bgcolor="#FFFFFF" colspan=3><img src="img/graph/title_01.gif" width="224" alt="�郎�Ȥη��"></td>
				</tr>
				<tr>
					<td align="center" colspan=3>
						<table border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td class="tc0"><br>
								�������Ϥǽ��פ������ʤ��βȤγƥ��ͥ륮�������̤ι�פ�CO2���ӽ��̥���դȤ���ɽ�����Ƥ��ޤ���<br>
								�ޤ������ʤ��βȤ���Ф�CO2���ӽ��̤���ڡ�����ʬ�ǵۼ��Ǥ��뤫���פ��狼��ޤ���<br>
								��ǯ��Ʊ�������٤Ƥ��ʤ��Τ��Ȥξ��֤Ϥɤ��Ǥ�����
								</td>
							</tr>
						</table>
						<br>
							<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0" width="600" height="250">
			          <param name=movie value="flash/graph/graph.swf">
			          <param name=quality value=high>
			          <embed src="flash/graph/graph.swf" quality="high" pluginspage="http://www.macromedia.com/jp/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="600" height="250" FlashVars="<?=$fv?>"></embed>
								<param name=FlashVars value="<?=$fv?>">
			        </object>
						<br><br><br>
						<img src="img/graph/title_02.gif" width="302" height="30" alt="���ͥ륮���Υ�����">
						<br>

						<table border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td class="tc0">
								�ƥ��ͥ륮����Ĺ���顢���ʤ��Τ��Ȥη�λ����̤���ǯ����Ӥ����ǡ������Ǥ���<br>
								��Ĺ���Ȥˡɿ��ΤĤ֤䤭�ʥ��������ˡɤ�ܤäƤ��뤫�⡦������<br>
								���ͥ륮�����Ȥ˥���դ򸫤뤳�Ȥ������Τǥ����å����Ƥߤޤ��礦��	
								</td>
							</tr>
						</table>
						<br>

						<table border="6" width="92%" height="300" cellspacing="3" cellpadding="3" bordercolor="#FFCC66" >
							<tr bgcolor="#FFFFFF" valign="middle">
								<!-- �ŵ� -->
								<td width="63" valign="middle">
									<a href="javascript:open_graph('graph_ele')">
									<img src="img/graph/button_denki.gif" width="63" height="78" border="0" alt="�ŵ��Υ����ɽ��"></a>
								</td>
								<td valign="top" class="tc0"><? eng_comment('ele', $this_month, $use_ary, $comp_ary, $rank)?>
									<img src="img/graph/comment/denki/denki_<?=$rank['ele']?>.gif" width="231" height="45">
								</td>
								<!-- ���� -->
								<td width="63" valign="middle">
									<a href="javascript:open_graph('graph_gas')">
									<img src="img/graph/button_gas.gif" width="63" height="78" alt="�����Υ����ɽ��" border="0"></a>
								</td>
								<td valign="top" class="tc0"><? eng_comment('gas', $this_month, $use_ary, $comp_ary, $rank)?>
									<img src="img/graph/comment/gas/gas_<?=$rank['gas']?>.gif" width="231" height="45"></td>
							</tr>

							<tr bgcolor="#FFFFFF">
								<!-- ��ƻ -->
								<td width="63" valign="middle">
									<a href="javascript:open_graph('graph_wtr')">
									<img src="img/graph/button_suidou.gif" width="63" height="78" alt="��ƻ�Υ����ɽ��" border="0"></a>
								</td>
								<td valign="top" class="tc0"><? eng_comment('wtr', $this_month, $use_ary, $comp_ary, $rank)?>
									<img src="img/graph/comment/suidou/suidou_<?=$rank['wtr']?>.gif" width="231" height="45">
								</td>
								<!-- ���� -->
								<td width="63" valign="middle">
									<a href="javascript:open_graph('graph_oil')">
									<img src="img/graph/button_touyu.gif" width="63" height="78" alt="�����Υ����ɽ��" border="0"></a>
								</td>
								<td valign="top" class="tc0"><? eng_comment('oil', $this_month, $use_ary, $comp_ary, $rank)?>
									<img src="img/graph/comment/touyu/touyu_<?=$rank['oil']?>.gif" width="231" height="45">
								</td>
							</tr>

							<tr bgcolor="#FFFFFF">
								<!-- ������� -->
								<td width="63" valign="middle">
									<a href="javascript:open_graph('graph_gso')">
									<img src="img/graph/button_gasolien.gif" width="63" height="78" alt="�������Υ����ɽ��" border="0"></a>
								</td>
								<td valign="top" class="tc0"><? eng_comment('gso', $this_month, $use_ary, $comp_ary, $rank)?>
									<img src="img/graph/comment/gasoline/gasolien_<?=$rank['gso']?>.gif" width="231" height="45">
								</td>
								<!-- ���� -->
								<td width="63" valign="middle">
									<a href="javascript:open_graph('graph_dst')">
									<img src="img/graph/button_gomi.gif" width="63" height="78" alt="���ߤΥ����ɽ��" border="0"></a>
								</td>
								<td valign="top" class="tc0"><? eng_comment('dst', $this_month, $use_ary, $comp_ary, $rank)?>
									<img src="img/graph/comment/gomi/gomi_<?=$rank['dst']?>.gif" width="231" height="45">
								</td>
							</tr>

						</table><br>
					</td>
				</tr>
			</table>
			<table border="0" cellspacing="0" cellpadding="0" width="720" bgcolor="#ffffff">
				<tr>
					<td height=50 valign="bottom"><img src="img/kado_3.gif" width="35" height="35"></td>
					<td width=650 align="center" valign="top">
						<a href="appre.php"><img src="img/graph/button_hitokoto.gif" width="120" height="30" border="0" alt="��������ΰ��"></a>	
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