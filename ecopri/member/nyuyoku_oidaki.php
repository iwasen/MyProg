<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:追い炊きの詳細
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
$inc = "$top/inc";
include("$inc/mypage.php");

// パラメータでエネルギー分類を受け取ったらセッションに保存
if ($eng_bunrui)
	$_SESSION['s_eng_bunrui'] = $eng_bunrui;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>追い炊きの場合（詳細）</title>
<link rel="stylesheet" type="text/css" href="css/main.css">
</head>

<body bgcolor="#FF0000" text="#000000" link="#66ccff" vlink="#66ccff" leftmargin="8" topmargin="8" alink="#66ccff">
<div align="center">

<!-- 白丸角テーブル -->
<table border="0" cellpadding="0" cellspacing="0" width="714" height="90%" bgcolor="#FFFFFF">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF">
			<img src="img/kado_1.gif" width="35" height="35">
		</td>
		<td align="center" valign="top">
			<br>
			<img src="img/shindan/sim_syosai/hinto.gif"><br>
			<!-- タイトル部分 -->
			<br>
			<img src="img/shindan/title/nyuyoku_01.gif"><br>
			<!-- タイトル部分終了 -->

			<!-- ここに入力 -->
			<font color="red">家族の入る間隔 や 蓋をする/しないで追い炊きのエネルギー消費量が異なります。</font><br><br>

			家族の入浴間隔や追い炊きの方法の違いによるエネルギー消費量の比較
			<table border="1">
				<tr>
					<td><img src="img/spacer.gif"></td>
					<td align="center" valign="middle" bgcolor="#8ac3ff"><font color="black">利用方法</font></td>
					<td align="center" valign="middle" bgcolor="#ffff9c" width="100">年間<font color="black">消費<br>エネルギー</font></td>
					<td align="center" valign="middle" bgcolor="#ffff9c" width="120">年間<font color="black">料金</font></td>
					<td align="center" valign="middle" bgcolor="#ffff9c" width="135">年間<br>二酸化炭素排出量</td>
				</tr>

				<tr>
					<td align="center" valign="middle">
						<img src="img/shindan/seika/hyojun.gif" width="65" height="67" border="0">
					</td>
					<td class="tc1">入浴は1時間おきに20分間、3回。<br>入浴中以外も浴槽に蓋をしない。<br>追い炊きは2回/日。</td>
					<td align="center" valign="middle" width="100">613Mcal</td>
					<td align="center" valign="middle" width="120">
						<img src="img/shindan/energy/gas.gif" align="middle" width="44" height="44" border="0">8,280円
					</td>
					<td align="center" valign="middle" width="135">131 kg・CO<sub>2</sub></td>
				</tr>

				<tr>
					<td align="center" valign="middle">
						<img src="img/shindan/seika/hyojun.gif" width="65" height="67" border="0">
					</td>
					<td class="tc1">入浴は1時間おきに20分間、3回。<br>入浴中以外は浴槽に蓋をする。<br>追い炊きは2回/日。</td>
					<td align="center" valign="middle" width="100">287Mcal</td>
					<td align="center" valign="middle" width="120">
						<img src="img/shindan/energy/denki.gif" align="middle" width="44" height="44" border="0">3,880円</td>
					<td align="center" valign="middle" width="135">62 kg・CO<sub>2</sub></td>
				</tr>

				<tr>
					<td align="center" valign="middle" rowspan="3">
						<img src="img/shindan/seika/suisyou.gif" width="65" height="67" border="0">
					</td>
					<td rowspan="3" class="tc1">入浴は連続して20分ずつ、3回。<br>追い炊きは1回/日。</td>
					<td align="center" valign="middle" width="100" rowspan="3">115Mcal</td>
					<td align="center" valign="middle" width="120" rowspan="3">
						<img src="img/shindan/energy/denki.gif" align="middle" width="44" height="44" border="0">1,550円
					</td>
					<td align="center" valign="middle" width="135" rowspan="3">25 kg・CO<sub>2</sub></td>
				</tr>
			</table>

			<table border="0">
				<tr>
					<td class="tc0">試算条件：</td>
					<td class="tc0">夫婦と子ども二人家族対象。毎日入浴、新湯210L。給湯機は都市ガス普及型。</td>
				</tr>

				<tr height="20">
					<td height="20"></td>
					<td height="20" class="tc0">料金は、都市ガス13.5円/Mcalで計算。</td>
				</tr>

				<tr height="20">
					<td height="20"></td>
					<td height="20" class="tc0">消費エネルギーは、住宅内で発生する部分を記載。</td>
				</tr>

				<tr>
					<td></td>
					<td class="tc0">二酸化炭素の排出係数は、環境家計簿で採用されている値を使用。</td>
				</tr>

				<tr>
					<td colspan="2" class="tc0">（注）　この試算はあくまでも、弊社調査より得られた結果に基いており、<br>使用法により影響を受けうるということをご了解ください。</td>
				</tr>
			</table>
			<br><br>

			<table>
				<tr>
					<td><a href="javascript:history.back()"><img src="img/shindan/button/back3.gif" border="0" width="117" height="36"></a></td>
					<td><a href="sim_oidaki.php" title="シミュレーション"><img src="img/shindan/sim_syosai/button_ecoshindan.gif" border="0" width="145" height="36"></a></td>
				</tr>
			</table>
			<!-- ここまで -->
		</td>
		<td width="35" align="right" valign="top" bgcolor="#FFFFFF">
			<img src="img/kado_2.gif" width="35" height="35">
		</td>
	</tr>

	<tr>
		<td width="35" valign="bottom"><img src="img/kado_3.gif" width="35" height="35"></td>
		<td><br></td>
		<td width="35" valign="bottom"><img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>
<!-- 白丸角テーブル終了 -->

</div>
</body>
</html>