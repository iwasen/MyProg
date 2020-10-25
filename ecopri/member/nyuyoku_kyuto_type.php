<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:給湯器のシミュレーション
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
<title>給湯器の種類による違い（詳細）</title>
<link rel="stylesheet" type="text/css" href="css/main.css">
</head>

<body bgcolor="#FF0000" text="#000000" link="#66ccff" vlink="#66ccff" leftmargin="8" topmargin="8" alink="#66ccff">
<div align="center">

<!-- 決心submitデータ -->
<form method="post" name="form1" action="kesshin.php">
	<input type="hidden" name="ganbalist_id" <?=value(GANBA_KYUUTOUKI)?>>
	<input type="hidden" name="energy" <?=value(363)?>>
	<input type="hidden" name="cost" <?=value(4900)?>>
	<input type="hidden" name="co2" <?=value(78)?>>
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
			<img src="img/shindan/title/kyutoki_01.gif"><br>
			<!-- タイトル部分終了 -->

			<!-- ここに入力 -->
			<font color="red">給湯器の熱源の種類によって、同じ入浴方法でもエネルギー消費量が異なります。</font><br><br>

			給湯機の熱源の違いによるエネルギー消費量の比較（浴槽湯はり）
			<table border="1">
				<tr>
					<td><img src="img/spacer.gif"></td>
					<td align="center" valign="middle" bgcolor="#8ac3ff"><font color="black">種類</font></td>
					<td align="center" valign="middle" bgcolor="#ffff9c" width="100">年間<font color="black">消費<br>エネルギー</font></td>
					<td align="center" valign="middle" bgcolor="#ffff9c" width="120">年間<font color="black">料金</font></td>
					<td align="center" valign="middle" bgcolor="#ffff9c" width="135">年間<br>二酸化炭素排出量</td>
				</tr>

				<tr>
					<td align="center" valign="middle">
						<img src="img/shindan/seika/hyojun.gif" width="65" height="67" border="0">
					</td>
					<td valign="middle" height="40" class="tc1">普及型ガス給湯器</td>
					<td align="center" valign="middle" width="100" height="40">2,597Mcal</td>
					<td align="center" valign="middle" width="120" height="40">
						<img src="img/shindan/energy/gas.gif" align="middle" width="44" height="44" border="0">35,060円
					</td>
					<td align="center" valign="middle" width="135" height="40">556 kg・CO<sub>2</sub></td>
				</tr>

				<tr>
					<td align="center" valign="middle">
						<img src="img/shindan/seika/suisyou.gif" width="65" height="67" border="0">
					</td>
					<td style="padding:4" class="tc1">潜熱回収型ガス給湯器</td>
					<td align="center" valign="middle" width="100" height="40">2,234Mcal</td>
					<td align="center" valign="middle" width="120" height="40">
						<img src="img/shindan/energy/gas.gif" align="center" width="44" height="44" border="0">30,160円
					</td>
					<td align="center" valign="middle" width="135" height="40">478 kg・CO<sub>2</sub></td>
				</tr>

				<tr>
					<td align="center" valign="middle">
						<img src="img/shindan/seika/hyojun.gif" width="65" height="67" border="0">
					</td>
					<td height="40" style="padding:4" class="tc1">普及型深夜電力温水器</td>
					<td align="center" valign="middle" width="100" height="40">2,078Mcal</td>
					<td align="center" valign="middle" width="120" height="40">
						<img src="img/shindan/energy/denki.gif" align="middle" width="44" height="44" border="0">21,400円
					</td>
					<td align="center" valign="middle" width="135" height="40">445 kg・CO<sub>2</sub></td>
				</tr>
				<tr>
					<td align="center" valign="middle">
						<img src="img/shindan/seika/suisyou.gif" width="65" height="67" border="0">
					</td>
					<td height="40" style="padding:4" class="tc1">ヒートポンプ型深夜電力温水器</td>
					<td align="center" valign="middle" width="100" height="40">693Mcal</td>
					<td align="center" valign="middle" width="120" height="40">
						<img src="img/shindan/energy/denki.gif" align="middle" width="44" height="44" border="0"> 7,130円
					</td>
					<td align="center" valign="middle" width="135" height="40">148 kg・CO<sub>2</sub></td>
				</tr>

				<tr>
					<td align="center" valign="middle">
						<img src="img/shindan/seika/hyojun.gif" width="65" height="67" border="0">
					</td>
					<td height="40" style="padding:4" class="tc1">灯油給湯器</td>
					<td align="center" valign="middle" width="100" height="40">2,597Mcal</td>
					<td align="center" valign="middle" width="120" height="40">
						<img src="img/shindan/energy/gas.gif" align="middle" width="44" height="44" border="0">13,760円
					</td>
					<td align="center" valign="middle" width="135" height="40">782 kg・CO<sub>2</sub></td>
				</tr>
			</table>

			<table border="0">
				<tr>
					<td class="tc0">試算条件：</td>
					<td class="tc0">夫婦と子ども二人家族対象。毎日入浴、新湯210L。</td>
				</tr>

				<tr>
					<td></td>
					<td class="tc0">水道の分は計算に含みません。</td>
				</tr>

				<tr height="20">
					<td height="20"></td>
					<td height="20" class="tc0">料金は、深夜電力10.3円/Mcal、都市ガス13.5円/Mcal、灯油5.3円/Mcalで計算。</td>
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
								<td align="center" bgcolor="#ffe397" width="260">363</td>
								<td align="center" bgcolor="#ffffc2" width="95">4,900</td>
								<td align="center" bgcolor="#deffff" width="195">78</td>
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
					<td align="center"><b>【今度使うなら効率のよい給湯器を！】</b><br><br></b></td>
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