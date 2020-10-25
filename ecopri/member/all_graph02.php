<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:わが家の結果グラフ表示
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/graph02.php");

// Flashパラメータ編集
/* 	それぞれのバーの高さ */
for($i = 0; $i < 50; $i++)
	$fv[] = "bar" . ($i + 1) . "Per=$bar_ary[$i]";
/* X, Y軸のMAX値 */
$fv[] = "axisXMax=$total_ttl_num";
$fv[] = "axisYMax=$max_co2";
/* 地域と全体の総人数 */
$fv[] = "localPersonMaxNum=$local_ttl_num";
$fv[] = "totalPersonMaxNum=$total_ttl_num";
/* 地域・全体・カテゴリー毎に見た順位 */
$fv[] = "localRank=$local_ttl_rank";
$fv[] = "totalRank=$total_ttl_rank";
$fv[] = "categoryRank=$total_ttl_rank";
/* co2の平均値 */
$fv[] = "co2Average=$ave_rank";

/* 家族順位（追加） */
$fv[] = "familyRank=$categ_ttl_rank";
$fv[] = "familyMaxNum=$categ_ttl_num";

//$fv[] = "addmissMonth=3";

$fv = join('&', $fv);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>☆★☆xxxゾウさんClub☆★☆</title>
<link rel="stylesheet" type="text/css" href="css/main.css">
</head>

<body bgcolor="#FF0000" text="#000000" link="#66ccff" vlink="#66ccff" leftmargin="8" topmargin="8" alink="#66ccff" class="b0">
<div align="center">

<!-- 白丸角テーブル -->
<table border="0" cellpadding="0" cellspacing="0" width="720" height="90%" bgcolor="#ff0000">
	<tr>
		<td align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="720" bgcolor="#ff0000">
				<tr>
					<td width=35 height=31></td>
					<td width="134"><a href="all_graph.php"><img src="img/graph/tagu_01.gif" border="0"></a></td>
					<td width="134"><img src="img/graph/tagu_02.gif" border="0"></td>
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
					<td align="center" colspan=3>
						<table border=0 cellspacing=0 cellpadding=0>
							<tr>
								<td align="center">
									<img src="img/graph/rank_month/<?=sprintf('%02d', $this_month)?>.gif" width=59 height=47 alt="月">
									<img src="img/graph/title_rank.gif" width=243 height=47 alt="ランキング">
								</td>
							</tr>
							<tr>
								<td align="center"><img src="img/graph/title_co2.gif" width=346 height=31 alt="わが家のco2排出量"></td>
							</tr>
							<tr>
								<td class="tc0"><br>
								このランキングは、電気、ガス、水道、灯油の合計CO2排出量より算出されています。<br>
								上記4項目が未入力の場合には順位が表示できません。<br>
								（未使用を設定した場合は未入力でもランキングが表示されます。）<br><br>
								</td>
							</tr>
						</table>
							<!-- Flashアニメ・画像領域 -->
						<table border="0" cellspacing="0" cellpadding="0" width="720" height="550">
							<tr>
								<td align="center" valign="center" bgcolor="#FFFFFF">
									<!-- Flashタグ領域 -->
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

									<!-- 順位発表テーブルここから -->
									<br><br><br>
									<img src="img/graph/eng_month/<?=sprintf('%02d', $this_month)?>.gif" width=330 height=36>
									<br><br>
									<table border="1" width="600" height="300" align="center" bgcolor="#FFFA55" bordercolordark="orange" bordercolorlight="orange">
										<tr align="center">
											<td width="50"><img src="img/spacer.gif"></td>
											<td><img src="img/graph/title_wan.gif" alt="全体" width="35" height="20"></td>
											<td><img src="img/graph/title_lan.gif" alt="地域" width="35" height="20"></td>
											<td valign="bottom" class="tc0">
												<img src="img/graph/title_kategori.gif" alt="カテゴリ" width="64" height="18"><br>
												（<?=$famiry_text?>）
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
											※ この順位は、各エネルギーの入力があった場合に算出しています。<br>
											※ "未使用"を設定したエネルギーは表示されません。
											</td>
										<tr>
									</table>
									<br>
									<!-- ここまで -->
								</td>
							</tr>
						</table>
						<!-- ここまで -->
					</td>
				</tr>
				<tr>
					<td valign="bottom"><img src="img/kado_3.gif" width="35" height="35"></td>
					<td height=50 bgcolor="#FFFFFF" align="center" valign="top">
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