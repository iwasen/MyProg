<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:我が家の結果グラフ表示
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

// Flashパラメータ編集
/* 	グラフに打つそれぞれの点の高さ */
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
<title>☆★☆Eco象さん一家のEco生活☆★☆</title>
<link rel="stylesheet" type="text/css" href="css/main.css">
<script type="text/javascript">
<!--
function open_graph(url) {
	window.open(url + ".php?graph=All", "_blank", "width=600,height=520,scrollbars=no,resizable=no,status=no,menubar=no,toolbar=no");
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
					<td width="134"><a href="all_graph02.php"><img src="img/graph/tagu_02.gif" border="0"></a></td>
					<td width="134"><a href="all_graph04.php"><img src="img/graph/tagu_04.gif" border="0"></a></td>
					<td width="134"><a href="all_graph03.php"><img src="img/graph/graph03.gif" border="0"></a></td>
					<td width=149></td>
				</tr>
			</table>
			<table border="0" cellspacing="0" cellpadding="0" width="720" bgcolor="#ffffff">
				<tr>
					<td valign="top" bgcolor="#ffffff"><img src="img/kado_1.gif" width="35" height="35"></td>
					<td width=650 bgcolor="#FFFFFF"></td>
					<td valign="top" bgcolor="#ffffff"><img src="img/kado_2.gif" width="35" height="35"></td>
				</tr>
				<tr>
					<td align="center" bgcolor="#FFFFFF" colspan=3><img src="img/graph/title_01.gif" width="224" alt="わが家の結果"></td>
				</tr>
				<tr>
					<td align="center" colspan=3>
						<table border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td class="tc0"><br>
								数値入力で集計したあなたの家の各エネルギー使用量の合計をCO2総排出量グラフとして表示しています。<br>
								また、あなたの家から出たCO2総排出量を「木、何本分で吸収できるか？」がわかります。<br>
								前年の同じ月に比べてあなたのお家の状態はどうですか？
								</td>
							</tr>
						</table>
						<br>
							<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0" width="600" height="250">
			          <param name=movie value="flash/graph/graphAll.swf">
			          <param name=quality value=high>
			          <embed src="flash/graph/graphAll.swf" quality="high" pluginspage="http://www.macromedia.com/jp/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="600" height="250" FlashVars="<?=$fv?>"></embed>
								<param name=FlashVars value="<?=$fv?>">
			        </object>
						<br><br><br>
						<img src="img/graph/title_02.gif" width="302" height="30" alt="エネルギーのコメント">
						<br>
						<table border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td class="tc0">
								各エネルギー隊長から、あなたのお家の月の使用量を前年と比較したデータ報告です。<br>
								隊長ごとに”心のつぶやき（グチ？！）”も載っているかも・・・。<br>
								エネルギーごとにグラフを見ることが出来るのでチェックしてみましょう。	
								</td>
							</tr>
						</table>
						<br>

						<table border="6" width="92%" height="300" cellspacing="3" cellpadding="3" bordercolor="#FFCC66" >
							<tr bgcolor="#FFFFFF" valign="middle">
								<!-- 電気 -->
								<td width="63" valign="middle">
									<a href="javascript:open_graph('graph_ele')">
									<img src="img/graph/button_denki.gif" width="63" height="78" border="0" alt="電気のグラフ表示"></a>
								</td>
								<td valign="top" class="tc0"><? eng_comment('ele', $this_month, $use_ary, $comp_ary, $rank)?>
									<img src="img/graph/comment/denki/denki_<?=$rank['ele']?>.gif" width="231" height="45">
								</td>
								<!-- ガス -->
								<td width="63" valign="middle">
									<a href="javascript:open_graph('graph_gas')">
									<img src="img/graph/button_gas.gif" width="63" height="78" alt="ガスのグラフ表示" border="0"></a>
								</td>
								<td valign="top" class="tc0"><? eng_comment('gas', $this_month, $use_ary, $comp_ary, $rank)?>
									<img src="img/graph/comment/gas/gas_<?=$rank['gas']?>.gif" width="231" height="45">
								</td>
							</tr>

							<tr bgcolor="#FFFFFF">
								<!-- 水道 -->
								<td width="63" valign="middle">
									<a href="javascript:open_graph('graph_wtr')">
									<img src="img/graph/button_suidou.gif" width="63" height="78" alt="水道のグラフ表示" border="0"></a>
								</td>
								<td valign="top" class="tc0"><? eng_comment('wtr', $this_month, $use_ary, $comp_ary, $rank)?>
									<img src="img/graph/comment/suidou/suidou_<?=$rank['wtr']?>.gif" width="231" height="45">
								</td>
								<!-- 灯油 -->
								<td width="63" valign="middle">
									<a href="javascript:open_graph('graph_oil')">
									<img src="img/graph/button_touyu.gif" width="63" height="78" alt="灯油のグラフ表示" border="0"></a>
								</td>
								<td valign="top" class="tc0"><? eng_comment('oil', $this_month, $use_ary, $comp_ary, $rank)?>
									<img src="img/graph/comment/touyu/touyu_<?=$rank['oil']?>.gif" width="231" height="45">
								</td>
							</tr>

							<tr bgcolor="#FFFFFF">
								<!-- ガソリン -->
								<td width="63" valign="middle">
									<a href="javascript:open_graph('graph_gso')">
									<img src="img/graph/button_gasolien.gif" width="63" height="78" alt="ガソリンのグラフ表示" border="0"></a>
								</td>
								<td valign="top" class="tc0"><? eng_comment('gso', $this_month, $use_ary, $comp_ary, $rank)?>
									<img src="img/graph/comment/gasoline/gasolien_<?=$rank['gso']?>.gif" width="231" height="45">
								</td>
								<!-- ゴミ -->
								<td width="63" valign="middle">
									<a href="javascript:open_graph('graph_dst')">
									<img src="img/graph/button_gomi.gif" width="63" height="78" alt="ゴミのグラフ表示" border="0"></a>
								</td>
								<td valign="top" class="tc0"><? eng_comment('dst', $this_month, $use_ary, $comp_ary, $rank)?>
									<img src="img/graph/comment/gomi/gomi_<?=$rank['dst']?>.gif" width="231" height="45">
								</td>
							</tr>
						</table>
						<br>
					</td>
				</tr>
			</table>
			<table border="0" cellspacing="0" cellpadding="0" width="720" bgcolor="#ffffff">
				<tr>
					<td height=50 valign="bottom"><img src="img/kado_3.gif" width="35" height="35"></td>
					<td width=650 align="center" valign="top">
						<a href="javascript:window.close()"><img src="img/button/win_close.gif" border="0" width="162" height="30" alt="このウィンドウを閉じる"></a>
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