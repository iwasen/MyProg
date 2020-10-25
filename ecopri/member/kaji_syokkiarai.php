<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:テレビ（機器）のシミュレーション
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/sim_db.php");

// パラメータでエネルギー分類を受け取ったらセッションに保存
if ($eng_bunrui)
	$_SESSION['s_eng_bunrui'] = $eng_bunrui;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>食器洗いの場合（詳細）</title>
<link rel="stylesheet" type="text/css" href="css/main.css">
</head>

<body bgcolor="#FF0000" text="#000000" link="#66ccff" vlink="#66ccff" leftmargin="8" topmargin="8" alink="#66ccff">
<div align="center">

<!-- 決心submitデータ -->
<form method="post" name="form1" action="kesshin.php">
	<input type="hidden" name="ganbalist_id" <?=value(GANBA_SHOKKIARAI)?>>
	<input type="hidden" name="energy" <?=value(324)?>>
	<input type="hidden" name="cost" <?=value(5684)?>>
	<input type="hidden" name="co2" <?=value(76)?>>
</form>

<!-- 白丸角テーブル -->
<table border="0" cellpadding="0" cellspacing="0" width="714" height="90%" bgcolor="#FFFFFF">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF">
			<img src="img/kado_1.gif" width="35" height="35">
		</td>
		<td align="center" valign="top">
			<!-- タイトル部分 -->
			<br>
			<img src="img/shindan/title/syokki_01.gif"><br>
			<!-- タイトル部分終了 -->

			<!-- ここに入力 -->
			<font color="red">食器洗いの方法によって、エネルギー消費量が異なります。</font><br><br>

			食器洗いの方法の違いによる水使用量、電気、ガスのエネルギー消費量の比較
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
					<td style="padding:4" class="tc1">手洗いで、水(湯)を溜めずに流しながら、洗ってすすぐ。</td>
					<td align="center" valign="middle" width="100">1,289Mcal</td>
					<td align="center" valign="middle" width="120">
						<img src="img/shindan/energy/gas.gif" align="middle" width="44" height="44" border="0">17,400円
						<img src="img/shindan/energy/suidou.gif" align="middle" width="44" height="44" border="0"> 5,200円
					</td>
					<td align="center" valign="middle" width="135">302 kg・CO<sub>2</sub></td>
				</tr>

				<tr>
					<td align="center" valign="middle"><img src="img/shindan/seika/suisyou.gif" width="65" height="67" border="0"></td>
					<td style="padding:4" class="tc1">手洗いで、洗い桶を使い、水(湯)を溜めながら、洗ってすすぐ。</td>
					<td align="center" valign="middle" width="100">965Mcal</td>
					<td align="center" valign="middle" width="120">
						<img src="img/shindan/energy/gas.gif" align="middle" width="44" height="44" border="0">13,026円
						<img src="img/shindan/energy/suidou.gif" align="middle" width="44" height="44" border="0"> 3,890円
					</td>
					<td align="center" valign="middle" width="135">226 kg・CO<sub>2</sub></td>
				</tr>

				<tr>
					<td align="center" valign="middle" rowspan="3"><img src="img/shindan/seika/sankou.gif" width="65" height="67" border="0"></td>
					<td style="padding:4" class="tc1">食器洗い乾燥機を使用する。<br><font size="-1">参考：給湯接続タイプ</font></td>
					<td align="center" valign="middle" width="100" rowspan="3">662Mcal</td>
					<td align="center" valign="middle" width="120" rowspan="3">
						<img src="img/shindan/energy/gas.gif" align="middle" width="44" height="44" border="0"> 5,120円
						<img src="img/shindan/energy/denki.gif" align="middle" width="44" height="44" border="0"> 8,080円
						<img src="img/shindan/energy/suidou.gif" align="middle" width="44" height="44" border="0"> 1,360円
					</td>
					<td align="center" valign="middle" width="135" rowspan="3">231 kg・CO<sub>2</sub></td>
				</tr>
			</table>

			<table border="0">
				<tr>
					<td class="tc0">試算条件：</td>
					<td class="tc0">夫婦と子ども二人家族対象。手洗いの場合、春/秋/冬は40℃の湯を使用。</td>
				</tr>

				<tr>
					<td></td>
					<td class="tc0">給湯機はガス普及型。</td>
				</tr>

				<tr height="20">
					<td height="20"></td>
					<td height="20" class="tc0">料金は、電気28.6円/Mcal、都市ガス13.5円/Mcal、水道116.6円/m3で計算。</td>
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
			<table border=0 cellspacing=2 cellpadding=0>
				<tr>
					<td align="center"><font color="#ff6600"><b>◎　ガンバリストに登録する際の目安　（上記の標準値−推奨値≒削減効果）　◎</b></font></td>
				</tr>
				<tr>
					<td align="center" class="tc0"><font color="orange">（このアドバイス項目には現在、個別診断機能がないため、以下の概算値でガンバリストに登録されます）</font></td>
				</tr>
				<tr>
					<td align="center">
						<table border="1" bordercolor="#cccccc" cellpadding="0" cellspacing="2" width="550">
							<tr>
								<td bgcolor="#ff8846" align="center" width="260">エネルギー(Mcal/年）</td>
								<td align="center" bgcolor="#ffff67" width="95">料金（￥/年）</td>
								<td bgcolor="#99ccff" align="center" width="195">二酸化炭素排出量(kg/年)</td>
							</tr>
							<tr>
								<td align="center" bgcolor="#ffe397" width="260">324</td>
								<td align="center" bgcolor="#ffffc2" width="95">5,684</td>
								<td align="center" bgcolor="#deffff" width="195">76</td>
							</tr>
						</table><br>
					</td>
				</tr>
				<tr>
					<td align="center">ガンバリストの宣言内容</td>
				</tr>
				<tr>
					<td align="center">↓</td>
				</tr>
				<tr>
					<td align="center"><b>【食器洗いは湯を流しながらでなく溜めてすすごう！】</b><br><br></b></td>
				</tr>
			</table>

			<table>
				<tr>
					<td><a href="javascript:history.back()"><img src="img/shindan/button/back3.gif" border="0" width="117" height="36"></a></td>
					<td><a href="javascript:document.form1.submit()"><img src="img/shindan/sim_res/button_ganbalist.gif" border="0" width="170" height="36"></td>
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