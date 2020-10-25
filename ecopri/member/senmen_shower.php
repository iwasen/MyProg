<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:洗顔・歯磨きのシミュレーション
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
<title>洗顔・歯磨き（詳細）</title>
<link rel="stylesheet" type="text/css" href="css/main.css">
</head>

<body bgcolor="#FF0000" text="#000000" link="#66ccff" vlink="#66ccff" leftmargin="8" topmargin="8" alink="#66ccff">
<div align="center">

<!-- 決心submitデータ -->
<form method="post" name="form1" action="kesshin.php">
	<input type="hidden" name="ganbalist_id" <?=value(GANBA_SENGAN)?>>
	<input type="hidden" name="energy" <?=value(1341)?>>
	<input type="hidden" name="cost" <?=value(22880)?>>
	<input type="hidden" name="co2" <?=value(311)?>>
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
			<img src="img/shindan/title/sengan_01.gif"><br>
			<!-- タイトル部分終了 -->

			<!-- ここに入力 -->
			<font color="red">洗顔・歯磨きでの利用温度・水の使い方によってエネルギー消費量が異なります。</font><br><br>

			洗面台のシャワー・蛇口使用条件によるエネルギー消費量の比較
			<table border="1">
				<tr>
					<td><img src="img/spacer.gif"></td>
					<td align="center" valign="middle" bgcolor="#8ac3ff" colspan="2"><font color="#000010">湯温(℃)</font></td>
					<td align="center" valign="middle" bgcolor="#8ac3ff"><font color="black">利用方法</font></td>
					<td align="center" valign="middle" bgcolor="#ffff9c" width="100">年間<font color="black">消費<br>エネルギー</font></td>
					<td align="center" valign="middle" bgcolor="#ffff9c" width="120">年間<font color="black">料金</font></td>
					<td align="center" valign="middle" bgcolor="#ffff9c" width="135">年間<br>二酸化炭素排出量</div></td>
				</tr>

				<tr>
					<td align="center" valign="middle" rowspan="3">
						<img src="img/shindan/seika/hyojun.gif" width="65" height="67" border="0">
					</td>
					<td align="center" valign="middle" class="tc1">夏</td>
					<td align="center" valign="middle" bgcolor="#ffc99a" class="tc1">38℃</td>
					<td rowspan="3" style="padding:4" class="tc1">四人家族が、朝夕の一日二回、洗顔・洗面。</td>
					<td align="center" valign="middle" width="100" rowspan="3">1,924Mcal</td>
					<td align="center" valign="middle" width="120" rowspan="3">
						<img src="img/shindan/energy/gas.gif" align="middle" width="44" height="44" border="0"> 25,980円
						<img src="img/shindan/energy/suidou.gif" align="middle" width="44" height="44" border="0"> 7,490円
					</td>
					<td align="center" valign="middle" width="135" rowspan="3">449kg・CO<sub>2</sub></td>
				</tr>

				<tr>
					<td align="center" valign="middle" class="tc1">春/秋</td>
					<td align="center" valign="middle" bgcolor="#6bff84" class="tc1">38℃</td>
				</tr>

				<tr>
					<td align="center" valign="middle" class="tc1">冬</td>
					<td align="center" valign="middle" bgcolor="#82fffe" class="tc1">38℃</td>
				</tr>

				<tr>
					<td align="center" valign="middle" rowspan="3">
						<img src="img/shindan/seika/suisyou.gif" width="65" height="67" border="0">
					</td>
					<td align="center" valign="middle" class="tc1">夏</td>
					<td align="center" valign="middle" bgcolor="#ffc99a" class="tc1">24℃</td>
					<td rowspan="3" style="padding:4" class="tc1">四人家族が、朝夕の一日二回、洗顔・洗面。<br><br>洗顔は洗面ボウルに溜めて、歯磨きはコップを使用する。</td>
					<td align="center" valign="middle" width="100" rowspan="3">583Mcal</td>
					<td align="center" valign="middle" width="120" rowspan="3">
						<img src="img/shindan/energy/gas.gif" align="middle" width="44" height="44" border="0"> 7,870円
						<img src="img/shindan/energy/suidou.gif" align="middle" width="44" height="44" border="0"> 2,720円
					</td>
					<td align="center" valign="middle" width="135" rowspan="3">138kg・CO<sub>2</sub></td>
				</tr>

				<tr>
					<td align="center" valign="middle" class="tc1">春/秋</td>
					<td align="center" valign="middle" bgcolor="#6bff84" class="tc1">35℃</td>
				</tr>

				<tr>
					<td align="center" valign="middle" class="tc1">冬</td>
					<td align="center" valign="middle" bgcolor="#82fffe" class="tc1">38℃</td>
				</tr>
			</table>

			<table border="0">
				<tr>
					<td class="tc0">試算条件：</td>
					<td class="tc0">夫婦と子ども二人家族対象。</font class="tc0">給湯機は都市ガス普及型。</td>
				</tr>

				<tr height="20">
					<td height="20"></td>
					<td height="20" class="tc0">料金は、都市ガス13.5円/Mcal、水道116.6円/m<sup>3</sup>で計算。</td>
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
								<td align="center" bgcolor="#ffe397" width="260">1,341</td>
								<td align="center" bgcolor="#ffffc2" width="95">22,880</td>
								<td align="center" bgcolor="#deffff" width="195">311</td>
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
					<td align="center"><b>【洗面歯磨きは､流し洗いをしない】</b><br><br></b></td>
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