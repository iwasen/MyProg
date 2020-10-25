<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:ガンバリスト
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/sim_db.php");

// 会員番号取得
$seq_no = $_SESSION['ss_seq_no'];

// 今月の年月取得
$ym_now = date('Ym');

// 年月の指定が無ければ今月とする
if (!$ym)
	$ym = $ym_now;

// 年月に分解
$year = substr($ym, 0, 4);
$month = substr($ym, -2);

// 前月取得
$sql = "SELECT count(*) FROM t_ganbalist WHERE gn_seq_no=$seq_no AND gn_ym<'$ym'";
if (db_fetch1($sql))
	$ym_back = date('Ym', mktime(0, 0, 0, $month - 1, 1, $year));

// 次月取得
if ($ym < $ym_now)
	$ym_next = date('Ym', mktime(0, 0, 0, $month + 1, 1, $year));

// 指定月のガンバリストを取得
$sql = "SELECT gn_co2,gn_ym,gn_ganbalist_id,gn_eng_bunrui,gn_cost,COALESCE(gn_advice,gm_advice) AS advice,EXISTS (SELECT * FROM t_master_mark WHERE mm_mb_seq_no=gn_seq_no AND mm_ganbalist_id=gn_ganbalist_id) AS master_mark"
		. " FROM t_ganbalist JOIN m_ganbalist ON gm_ganbalist_id=gn_ganbalist_id AND gm_eng_bunrui=gn_eng_bunrui"
		. " WHERE gn_seq_no=$seq_no AND gn_ym='$ym'"
		. " ORDER BY gn_eng_bunrui,gn_ganbalist_id";
$result = db_exec($sql);
$nrow = pg_numrows($result);
if ($nrow == 0) {
	// 指定月のデータが無い場合
	if ($ym < date('Ym')) {
		// 過去の場合
		redirect("decision_nothing.php?ym=$ym");
	} else {
		// 現在または未来の場合
		redirect("decision_create.php?ym=$ym");
	}
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>☆★☆xxxゾウさんClub☆★☆</title>
<link rel="stylesheet" type="text/css" href="css/main.css">
</head>

<body bgcolor="#FF0000" text="#000000" link="#66ccff" vlink="#66ccff" leftmargin="8" topmargin="8" alink="#66ccff">
<div align="center">

<!-- 白丸角テーブル -->
<table border="0" cellpadding="0" cellspacing="0" width="720" bgcolor="#FFFFFF">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF">
			<img src="img/kado_1.gif" width="35" height="35">
		</td>
		<td align="center" valign="top">
			<!-- タイトル部分 -->

<!-- これは、ガンバリストで決心ボタンを押したものが自動的にここに反映される画面 -->

			<!-- 今月の決心リストがあるかどうか判定する。
			もしなかったら、作成するように促し、新規で作成するか、
			前月のを繰り越すか決めさせる。決心しない場合はメニューへとぶ。
			決心したら、生活改善へJumpする。
			リストスクラップには、印刷ボタンと生活改善へボタンとメニューボタンがある。 -->

			<!-- 先月次月移動ボタン開始 -->
			<div align="center">
			<br>
			<table border="0" align="center">
				<tr align="center" valign="middle">
					<td align="right" width="33%">
<?
if ($ym_back) {
?>
						<a href="decision_scrap.php?ym=<?=$ym_back?>"><img src="img/button/sengetu.gif" border="0" align="middle" alt="先月" title="先月"></a>
<?
}
?>
					</td>
					<!-- 次先月ボタンで月がかわる -->
					<td align="center" width="33%">
						<img src="img/month/<?=$month?>.gif" border="0" width="63" height="28"><img src="img/ganbalist/ganbalist_02.gif" border="0" width="131" height="35">
					</td>
					<td align="left" width="33%">
<?
if ($ym_next) {
?>
						<a href="decision_scrap.php?ym=<?=$ym_next?>"><img src="img/button/jigetu.gif" border="0" align="middle" alt="次月" title="次月"></a>
<?
}
?>
					</td>
				</tr>
			</table>
		</td>
		<td width="35" align="right" valign="top">
			<img src="img/kado_2.gif" width="35" height="35">
		</td>
	<tr>
	</tr>
		<td align="center" colspan=3>
			<table cellspacing=0 cellpadding=0 width=700>
				<tr>
					<td class="tc0" align="center">
					目標が達成できたら”マスター”ボタンを押してください。ワンポイントアドバイスにマスターマークがつきます。<br>
					目標をやめたくなったら”やめる”ボタンを押してください。ガンバリストから削除されます。
					</td>
				</tr>
			</table>
			<!-- 先月次月移動ボタン終了 -->
			<hr width="650" size="1">
			<!-- タイトル部分終了 -->

			<!-- ここに入力 bgcolor="red" -->
			<table border="1" cellspacing=1 cellpadding=1 bordercolordark="orange" bordercolorlight="orange" width="700">
				<!-- タイトル -->
				<tr>
					<th width="62" bgcolor="#FFFA55"><br></th>
					<th align="center" bgcolor="#FFFA55"><img src="img/ganbalist/advice.gif" width="152" height="25" alt="選んだアドバイス"></th>
					<th align="center" bgcolor="#FFFA55" colspan=2><img src="img/ganbalist/sakugen.gif" width="98" height="25" alt="年間削減量"></th>
					<th align="center" bgcolor="#FFFA55" colspan=2><img src="img/ganbalist/zissen.gif" width="113" height="25" alt="実践できた？"></th>
				</tr>
<?
$chara = array(
	ENG_DENKI => 'denki.gif',
	ENG_GAS => 'gas.gif',
	ENG_SUIDOU => 'suidou.gif',
	ENG_GOMI => 'gomi.gif',
	ENG_GASOLINE => 'gasoline.gif',
);
$total_co2 = 0;
$total_cost = 0;
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$total_co2 += $fetch->gn_co2;
	$total_cost += $fetch->gn_cost;
?>
				<tr>
					<td bgcolor="#FFFA55"><img src="img/character/<?=$chara[$fetch->gn_eng_bunrui]?>" width="62" height="72"></td>
					<td bgcolor="#FFFFFF" class="tc0"><?=htmlspecialchars($fetch->advice)?></td>
					<td bgcolor="#FFFFFF" align="right" class="tc0"><nobr><?=number_format($fetch->gn_co2)?> kg-CO2</nobr></td>
					<td bgcolor="#FFFFFF" align="right" class="tc0"><nobr><?=number_format($fetch->gn_cost)?> 円</nobr></td>
					<td bgcolor="#FFFFFF" align="center" width="55">
<?
if ($fetch->master_mark != 't') {
?>
						<a href="decision_master.php?ym=<?=$fetch->gn_ym?>&ganbalist_id=<?=$fetch->gn_ganbalist_id?>"><img src="img/ganbalist/button_master.gif" width="47" height="44" border="0"></a>
<?
} else {
?>
						<img src="img/ganbalist/button_masterclear.gif" width="47" height="44" border="0">
<?
}
?>
					</td>
					<td bgcolor="#FFFFFF" align="center" width="55">
<?
if ($ym >= $ym_now) {	// 過去のデータは削除を表示しない
?>
						<a href="decision_delete.php?ym=<?=$fetch->gn_ym?>&ganbalist_id=<?=$fetch->gn_ganbalist_id?>&eng_bunrui=<?=$fetch->gn_eng_bunrui?>"><img src="img/ganbalist/button_yameru.gif" width="47" height="44" border="0"></a>
<?
} else {
?>
						<img src="img/spacer.gif" width="47" height="44" border="0">
<?
}
?>
					</td>
				</tr>
<?
}
?>
				<tr>
					<td colspan=2 align="center" height=40><img src="img/ganbalist/goukei.gif" width="152" height="25"></td>
					<td bgcolor="#FFFFFF" align="right" class="tc0"><nobr><?=number_format($total_co2)?> kg-CO2</nobr></td>
					<td bgcolor="#FFFFFF" align="right" class="tc0"><nobr><?=number_format($total_cost)?> 円</nobr></td>
					<td bgcolor="#FFFFFF" colspan=2><br></td>
				</tr>
			</table>
			<!-- ここまで -->
		</td>
	</tr>

	<tr>
		<td width="35" valign="bottom"><img src="img/kado_3.gif" width="35" height="35"></td>
		<td align="center" width=650>
			<hr width="650" size="1">
			<a href="main.php"><img src="img/button/main.gif" border="0" width="61" height="30"></a>
			<a href="life_style.php"><img src="img/ganbalist/button_setuyaku.gif" border="0" width="145" height="30"></a>
			<!-- PDFのDL↓ -->
			<br><br>
		</td>
		<td width="35" valign="bottom"><img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>
<!-- 白丸角テーブル終了 -->

</div>
</body>
</html>
