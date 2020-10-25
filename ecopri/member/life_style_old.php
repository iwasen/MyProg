<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:ワンポイントアドバイス
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/sim_db.php");

// 決心済みに★を付ける
function kesshin($ganbalist_id, $eng_bunrui) {
	global $ganbarist_ary;

	if ($ganbarist_ary[$ganbalist_id][$eng_bunrui])
		echo '<font color="#ff0000">★</font>';
}

// ガンバリストを取得
$seq_no = $_SESSION['ss_seq_no'];
$ym = date('Ym');
$sql = "SELECT gm_ganbalist_id,gm_eng_bunrui"
		. " FROM t_ganbalist JOIN m_ganbalist ON gn_ganbalist_id=gm_ganbalist_id AND gn_eng_bunrui=gm_eng_bunrui"
		. " WHERE gn_seq_no=$seq_no AND gn_ym=$ym";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$ganbarist_ary[$fetch->gm_ganbalist_id][$fetch->gm_eng_bunrui] = true;
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>☆★☆xxxゾウさんClub☆★☆</title>
<script type="text/javascript">
<!--
function open_graph() {
	window.open("all_graph.php", "_blank", "width=750,scrollbars=yes,resizable=yes,status=yes,menubar=yes,toolbar=yes");
}
//-->
</script>
<style type="text/css">
<!--
div.advice {
	margin-bottom: 8px;
}
-->
</style>
</head>

<body bgcolor="#FF0000" text="#000000" link="#66ccff" vlink="#66ccff" leftmargin="8" topmargin="8" alink="#66ccff">
<div align="center">

<!-- 白丸角テーブル -->
<table border="0" cellpadding="0" cellspacing="0" width="714" height="90%" bgcolor="#FFFFFF">
	<tr>
		<td width="35" height="35" align="left" valign="top" bgcolor="#FFFFFF">
			<img src="img/kado_1.gif" width="35" height="35"></td>
		<td><br></td>
		<td width="35" height="35" align="right" valign="top" bgcolor="#FFFFFF">
			<img src="img/kado_2.gif" width="35" height="35"></td>
	</tr>

	<tr>
		<td height="90%"><br></td>
		<td align="center">
<?
if (!$_SESSION['ss_yet_flag']) {
	$sql = "SELECT cr_ele_rank,cr_gas_rank,cr_oil_rank,cr_wtr_rank,cr_gso_rank,cr_dst_rank"
			. " FROM t_co2_rank"
			. " WHERE cr_seq_no=$seq_no"
			. " ORDER BY cr_ym DESC";
	$result = db_exec($sql);
	if (pg_numrows($result))
		$fetch = pg_fetch_object($result, 0);
?>
		<!-- ここに入力 -->
		<!-- 隊長コメント領域(全て画像) -->
		<!-- 画像部分はユーザの状態でいろいろ変わる(予定) -->

		<img src="img/one/one_comment.gif">
		<table border="1" width="644" height="300" cellspacing="1" cellpadding="1" bgcolor="#FFFA55" bordercolordark="orange" bordercolorlight="orange">
			<tr>
				<td width="15" height="15"></td>
			</tr>
			<tr>
				<td width="15"></td>
				<td align="center" bgcolor="FFFFFF"><img src="img/comment/electron_0<?=(int)$fetch->cr_ele_rank?>.gif"></td>
				<td align="center" bgcolor="FFFFFF"><img src="img/comment/gas_0<?=(int)$fetch->cr_gas_rank?>.gif"></td>
				<td width="15"></td>
			</tr>
			<tr>
				<td width="15"></td>
				<td align="center" bgcolor="FFFFFF"><img src="img/comment/water_0<?=(int)$fetch->cr_wtr_rank?>.gif"></td>
				<td align="center" bgcolor="FFFFFF"><img src="img/comment/touyu_0<?=(int)$fetch->cr_oil_rank?>.gif"></td>
				<td width="15"></td>
			</tr>
			<tr>
				<td width="15"></td>
				<td align="center" bgcolor="FFFFFF"><img src="img/comment/gasoline_0<?=(int)$fetch->cr_gso_rank?>.gif"></td>
				<td align="center" bgcolor="FFFFFF"><img src="img/comment/garbage_0<?=(int)$fetch->cr_dst_rank?>.gif"></td>
				<td width="15"></td>
			</tr>

			<tr>
				<td width="15" height="15"></td>
			</tr>
		</table>
		<br>
		<a href="javascript:open_graph()"><img src="img/button/graph.gif" border="0" width="102" height="30"></a>
		<!-- ドラムロールからグラフ表示へ -->
		<!-- 隊長コメント領域ここまで -->

		<br>
		<br>
		<br>

<?
}
?>
		<!-- シミュレーション領域テーブルここから -->
		<a name="sim_top">
		<img src="img/one/one_advice.gif">
		<table border="1" width="644" height="300" bgcolor="#FFFA55" bordercolordark="orange" bordercolorlight="orange">
			<tr>
				<td width="50">　<br>　</td>
				<td width="150" align="middle"><img src="img/character/yellow/kaji.gif"></td>
				<td width="150" align="middle"><img src="img/character/yellow/goraku.gif"></td>
				<td width="150" align="middle"><img src="img/character/yellow/eisei.gif"></td>
				<td width="150" align="middle"><img src="img/character/yellow/kucyo.gif"></td>
				<td width="150" align="middle"><img src="img/character/yellow/kotsu.gif"></td>
			</tr>
			<tr>
				<td align="middle"><a href="one_electron.php" title="ワンポイント電気"><img src="img/character/yellow/denki.gif" border="0"></a></td>
				<td width="150" bgcolor="FFFFFF">　<br><div class="advice"><? kesshin(GANBA_YUWAKASHI, ENG_DENKI) ?>湯沸しについて</div><div class="advice"><? kesshin(GANBA_KANSOU, ENG_DENKI) ?>乾燥機(洗濯)は…</div></td>
				<td width="150" bgcolor="FFFFFF">　<br><div class="advice"><? kesshin(GANBA_TV_KOUDOU, ENG_DENKI) ?>TVは…</div><div class="advice"><? kesshin(GANBA_TV_KIKI, ENG_DENKI) ?>買い換えるなら…</div></td>
				<td width="150" bgcolor="FFFFFF">　<br><div class="advice"><? kesshin(GANBA_TOILET_TSUUDEN, ENG_DENKI) ?>温水洗浄便座は…</div><div class="advice"><? kesshin(GANBA_TOILET_SESSUI, ENG_DENKI) ?>買い換えるなら…</div></td>
				<td width="150" bgcolor="FFFFFF">　<br><div class="advice"><? kesshin(GANBA_SHOUMEI, ENG_DENKI) ?>点灯時間は…</div><div class="advice"><? kesshin(GANBA_REIBOU_LD, ENG_DENKI) ?>エアコンの設定温度</div><div class="advice"><? kesshin(GANBA_REIBOU_KO, ENG_DENKI) ?>就寝後の冷房</div><div class="advice"><? kesshin(GANBA_DANBOU_LD, ENG_DENKI) ?>暖房機器の組み合わせ</div>　<br></td>
				<td align="center" width="150" bgcolor="FFFFFF">　<br><img src="img/comment/sleep/sleep_denki.gif" border="0" width="73" height="53"></td>
			</tr>
			<tr>
				<td align="middle"><a href="one_gas.php" title="ワンポイントガス"><img src="img/character/yellow/gas.gif" border="0"><br><img src="img/character/yellow/touyu.gif" border="0"></a></td>
				<td width="150" bgcolor="FFFFFF">　<br><div class="advice"><? kesshin(GANBA_KONRO, ENG_GAS) ?>高効率ガスコンロ！</div><div class="advice"><? kesshin(GANBA_SHOKKIARAI, ENG_GAS) ?>食器洗いは…</div></td>
				<td align="center" width="150" bgcolor="FFFFFF">　<br><img src="img/comment/sleep/sleep_gas.gif" border="0" width="73" height="53"></td>
				<td width="150" bgcolor="FFFFFF">　<br><div class="advice"><? kesshin(GANBA_YOKUSOU, ENG_GAS) ?>湯量と温度設定！</div><div class="advice"><? kesshin(GANBA_SHOWER, ENG_GAS) ?>シャワーの使い方！</div><div class="advice"><? kesshin(GANBA_OIDAKI, ENG_GAS) ?>入浴間隔は…</div><div class="advice"><? kesshin(GANBA_KYUUTOUKI, ENG_GAS) ?>今度買うなら…</div><div class="advice"><? kesshin(GANBA_SHOWER_HEAD, ENG_GAS) ?>シャワーヘッドは…</div><div class="advice"><? kesshin(GANBA_SENGAN, ENG_GAS) ?>歯磨きの時！</div>　<br></td>
				<td width="150" bgcolor="FFFFFF">　<div class="advice"><? kesshin(GANBA_DANBOU_LD, ENG_GAS) ?>暖房の組み合わせと設定は…</div></td>
				<td align="center" width="150" bgcolor="FFFFFF">　<br><img src="img/comment/sleep/sleep_gas.gif" border="0" width="73" height="53"></td>
			</tr>
			<tr>
				<td align="middle"><a href="one_water.php" title="ワンポイント水道"><img src="img/character/yellow/suidou.gif" border="0"></a></td>
				<td width="150" bgcolor="FFFFFF">　<div class="advice"><? kesshin(GANBA_SENTAKU, ENG_SUIDOU) ?>風呂の残り湯を…</div><div class="advice"><? kesshin(GANBA_SUSUGI, ENG_SUIDOU) ?>溜めすすぎに！</div><div class="advice"><? kesshin(GANBA_SHOKKIARAI, ENG_SUIDOU) ?>食器洗いは…</div></td>
				<td align="center" width="150" bgcolor="FFFFFF">　<br><img src="img/comment/sleep/sleep_suidou.gif" border="0" width="73" height="53"></td>
				<td width="150" bgcolor="FFFFFF">　<div class="advice"><? kesshin(GANBA_YOKUSOU, ENG_SUIDOU) ?>湯量と温度設定！</div><div class="advice"><? kesshin(GANBA_SHOWER, ENG_SUIDOU) ?>シャワーの使い方！</div><div class="advice"><? kesshin(GANBA_OIDAKI, ENG_SUIDOU) ?>入浴間隔について</div><div class="advice"><? kesshin(GANBA_KYUUTOUKI, ENG_SUIDOU) ?>効率のよい給湯器！</div><div class="advice"><? kesshin(GANBA_SHOWER_HEAD, ENG_SUIDOU) ?>シャワーヘッドは…</div><div class="advice"><? kesshin(GANBA_SENGAN, ENG_SUIDOU) ?>歯磨きは…</div>　<br></td>
				<td align="center" width="150" bgcolor="FFFFFF">　<br><img src="img/comment/sleep/sleep_suidou.gif" border="0" width="73" height="53"></td>
				<td align="center" width="150" bgcolor="FFFFFF">　<br><img src="img/comment/sleep/sleep_suidou.gif" border="0" width="73" height="53"></td>
			</tr>
			<tr>
				<td align="middle"><a href="one_garbage.php" title="ワンポイントゴミ"><img src="img/character/yellow/gomi.gif" border="0"></a></td>
				<td width="150" bgcolor="FFFFFF">　<div class="advice"><? kesshin(GANBA_NAMAGOMI, ENG_GOMI) ?>生ゴミを堆肥に！</div><div class="advice"><? kesshin(GANBA_SHIGENGOMI, ENG_GOMI) ?>資源ゴミ！</div>　<br></td>
				<td align="center" width="150" bgcolor="FFFFFF">　<br><img src="img/comment/sleep/sleep_gomi.gif" border="0" width="73" height="53"></td>
				<td align="center" width="150" bgcolor="FFFFFF">　<br><img src="img/comment/sleep/sleep_gomi.gif" border="0" width="73" height="53"></td>
				<td align="center" width="150" bgcolor="FFFFFF">　<br><img src="img/comment/sleep/sleep_gomi.gif" border="0" width="73" height="53"></td>
				<td align="center" width="150" bgcolor="FFFFFF">　<br><img src="img/comment/sleep/sleep_gomi.gif" border="0" width="73" height="53"></td>
			</tr>
			<tr>
				<td align="middle"><a href="one_gasoline.php" title="ワンポイントガソリン"><img src="img/character/yellow/gasoline.gif" border="0"></a></td>
				<td align="center" width="150" bgcolor="FFFFFF">　<br><img src="img/comment/sleep/sleep_gasoline.gif" border="0" width="73" height="53"></td>
				<td align="center" width="150" bgcolor="FFFFFF">　<br><img src="img/comment/sleep/sleep_gasoline.gif" border="0" width="73" height="53"></td>
				<td align="center" width="150" bgcolor="FFFFFF">　<br><img src="img/comment/sleep/sleep_gasoline.gif" border="0" width="73" height="53"></td>
				<td align="center" width="150" bgcolor="FFFFFF">　<br><img src="img/comment/sleep/sleep_gasoline.gif" border="0" width="73" height="53"></td>
				<td width="150" bgcolor="FFFFFF">　<div class="advice"><? kesshin(GANBA_TEIKOUGAISHA, ENG_GASOLINE) ?>ハイブリット車って…</div><div class="advice"><? kesshin(GANBA_HAIKIRYOU, ENG_GASOLINE) ?>排気量は少なめ！</div><div class="advice"><? kesshin(GANBA_UNTENHOUHOU, ENG_GASOLINE) ?>アイドリング…</div>　<br></td>
			</tr>
		</table>
		<br>
		<a href="main.php"><img src="img/button/main.gif" border="0" width="61" height="30"></a>
		<a href="decision_scrap.php"><img src="img/button/ganbalist.gif" border="0" width="94" height="30"></a>
		<!-- ここまで -->
		</td>
		<td height="90%"><br></td>
	</tr>

	<tr>
		<td width="35" height="35" valign="bottom"><img src="img/kado_3.gif" width="35" height="35"></td>
		<td><br></td>
		<td width="35" height="35" valign="bottom"><img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>
<!-- 白丸角テーブル終了 -->

</div>
</body>
</html>