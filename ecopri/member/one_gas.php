<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:ワンポイントアドバイス（ガス編）
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/sim_db.php");

// マスターマーク表示
function disp_master_mark($ganbalist_id) {
	global $master;

	if ($master[$ganbalist_id])
		echo '<img src="img/shindan/sim_water/button_master.gif" width="47" height="44" border="0">';
	else
		echo '<img src="img/spacer.gif" width="47" height="44" border="0">';
}

// 戻りページセット
$_SESSION['s_one_advice'] = 'one_gas.php';

// エネルギー分類セット
$_SESSION['s_eng_bunrui'] = ENG_GAS;

// 会員番号取得
$seq_no = $_SESSION['ss_seq_no'];

// マスターマーク取得
$sql = "SELECT mm_ganbalist_id FROM t_master_mark WHERE mm_mb_seq_no=$seq_no";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$master[(int)$fetch->mm_ganbalist_id] = true;
}
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>☆★☆xxxゾウさんClub☆★☆</title>
<link rel="stylesheet" type="text/css" href="css/main.css">
</head>

<body bgcolor="#FF0000" text="#000000" link="#66ccff" vlink="#66ccff" leftmargin="8" topmargin="8" alink="#66ccff">
<div align="center">

<!-- 白丸角テーブル -->
<table border="0" cellpadding="0" cellspacing="0" width="714" bgcolor="#FFFFFF">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF">
			<img src="img/kado_1.gif" width="35" height="35">
		</td>
		<td align="center" valign="top">
			<!-- タイトル部分 -->
			<table border="0">
				<tr>
					<td height=80><img src="img/shindan/sim_water/gas_title.gif" border="0" width="417" height="64"></td>
				</tr>
			</table>
			<!-- タイトル部分終了 -->

			<!-- ここに入力 -->
			<!-- 行為別分類表 -->
			<table border="1" cellpadding=3 bordercolordark="orange" bordercolorlight="orange" width="100%">
				<!-- タイトル -->
				<tr>
					<th width="50" bgcolor="gold"><br></th>
					<th align="center" bgcolor="gold">アドバイス</th>
					<th align="center" bgcolor="gold">CO2削減量</th>
					<th align="center" bgcolor="gold">診断</th>
					<th align="center" bgcolor="gold"><nobr>マスター</nobr></th>
				</tr>
<!-- 1段目 -->
				<tr>
					<td rowspan="2" align="center" bgcolor="#FFFA55"><img src="img/character/yellow/kaji.gif"></td>
					<td bgcolor="#FFFFFF" class="tc0">同じガスコンロでも、高効率コンロをお薦めします。ガスコンロを高効率タイプに替えると･･･</td>
					<td bgcolor="#FFFFFF"><img src="img/one/meter/21.gif" border="0" width="100" height="45"></td>
					<td bgcolor="#FFFFFF" align="middle"><a href="kaji_konro_type.php" title="診断＆ガンバリスト登録"><img src="img/one/one_foot03.gif" border="0"></a></td>
					<td bgcolor="#FFFFFF" align="middle"><? disp_master_mark(GANBA_KONRO) ?></td>
				</tr>
				<tr>
					<td bgcolor="#FFFFFF" class="tc0">食器洗いは、湯を溜めながらすすぐと、意外に節約できます。洗い桶を使うと･･･</td>
					<td bgcolor="#FFFFFF"><img src="img/one/meter/76.gif" border="0" width="100" height="45"></td>
					<td bgcolor="#FFFFFF" align="middle"><a href="kaji_syokkiarai.php" title="診断＆ガンバリスト登録"><img src="img/one/one_foot03.gif" border="0"></a></td>
					<td bgcolor="#FFFFFF" align="middle"><? disp_master_mark(GANBA_SHOKKIARAI) ?></td>
				</tr>
<!-- 2段目 -->
				<tr>
					<td rowspan="6" align="center" bgcolor="#FFFA55"><img src="img/character/yellow/eisei.gif"></td>
					<td bgcolor="#FFFFFF" class="tc0">浴槽入浴の、湯量と設定温度の見直しをしてみませんか。浴槽温度を1℃下げ、夏はシャワーのみとすると･･･</td>
					<td bgcolor="#FFFFFF"><img src="img/one/meter/83.gif" border="0" width="100" height="45"></td>
<!--					<td bgcolor="#FFFFFF" align="middle"><a href="sim_yokusou.php" title="シミュレーション"><img src="img/one/one_foot01.gif" border="0"></a></td>-->
					<td bgcolor="#FFFFFF" align="middle"><a href="nyuyoku_yokuso.php" title="診断＆ガンバリスト登録"><img src="img/one/one_foot01.gif" border="0"></a></td>
					<td bgcolor="#FFFFFF" align="middle"><? disp_master_mark(GANBA_YOKUSOU) ?></td>
				</tr>
				<tr>
					<td bgcolor="#FFFFFF" class="tc0">シャワーの使用時間の見直しは、非常に効果的です。一人1分ずつ、シャワーや蛇口の使用時間を減らすと・・・</td>
					<td bgcolor="#FFFFFF"><img src="img/one/meter/139.gif" border="0" width="100" height="45"></td>
<!--					<td bgcolor="#FFFFFF" align="middle"><a href="sim_shower.php" title="シミュレーション"><img src="img/one/one_foot01.gif" border="0"></a></td>-->
					<td bgcolor="#FFFFFF" align="middle"><a href="nyuyoku_shower.php" title="診断＆ガンバリスト登録"><img src="img/one/one_foot01.gif" border="0"></a></td>
					<td bgcolor="#FFFFFF" align="middle"><? disp_master_mark(GANBA_SHOWER) ?></td>
				</tr>
				<tr>
					<td bgcolor="#FFFFFF" class="tc0">家族が連続してお風呂に入ると、追い焚きの節約になります。家族が連続して入浴すると･･･</td>
					<td bgcolor="#FFFFFF"><img src="img/one/meter/106.gif" border="0" width="100" height="45"></td>
<!--					<td bgcolor="#FFFFFF" align="middle"><a href="sim_oidaki.php" title="シミュレーション"><img src="img/one/one_foot01.gif" border="0"></a></td>-->
					<td bgcolor="#FFFFFF" align="middle"><a href="nyuyoku_oidaki.php" title="診断＆ガンバリスト登録"><img src="img/one/one_foot01.gif" border="0"></a></td>
					<td bgcolor="#FFFFFF" align="middle"><? disp_master_mark(GANBA_OIDAKI) ?></td>
				</tr>
				<tr>
					<td bgcolor="#FFFFFF" class="tc0">給湯機も、効率のよいものがでています。ガス給湯機を高効率タイプに替えると･･･</td>
					<td bgcolor="#FFFFFF"><img src="img/one/meter/78.gif" border="0" width="100" height="45"></td>
					<td bgcolor="#FFFFFF" align="middle"><a href="nyuyoku_kyuto_type.php" title="診断＆ガンバリスト登録"><img src="img/one/one_foot03.gif" border="0"></a></td>
					<td bgcolor="#FFFFFF" align="middle"><? disp_master_mark(GANBA_KYUUTOUKI) ?></td>
				</tr>
				<tr>
					<td bgcolor="#FFFFFF" class="tc0">シャワーヘッドも、手元クリック型ならこまめに止めることができます。クリック型シャワーヘッドで、一人1分使用時間を減らすと･･･</td>
					<td bgcolor="#FFFFFF"><img src="img/one/meter/115.gif" border="0" width="100" height="45"></td>
					<td bgcolor="#FFFFFF" align="middle"><a href="nyuyoku_shower_type.php" title="診断＆ガンバリスト登録"><img src="img/one/one_foot03.gif" border="0"></a></td>
					<td bgcolor="#FFFFFF" align="middle"><? disp_master_mark(GANBA_SHOWER_HEAD) ?></td>
				</tr>
				<tr>
					<td bgcolor="#FFFFFF" class="tc0">洗面や歯磨きで、流し洗いをしていませんか？洗顔や歯磨きは器にためて使用すると･･･</td>
					<td bgcolor="#FFFFFF"><img src="img/one/meter/311.gif" border="0" width="100" height="45"></td>
					<td bgcolor="#FFFFFF" align="middle"><a href="senmen_shower.php" title="診断＆ガンバリスト登録"><img src="img/one/one_foot03.gif" border="0"></a></td>
					<td bgcolor="#FFFFFF" align="middle"><? disp_master_mark(GANBA_SENGAN) ?></td>
				</tr>
<!-- 3段目 -->
				<tr>
					<td align="center" bgcolor="#FFFA55"><img src="img/character/yellow/kucyo.gif"></td>
					<td bgcolor="#FFFFFF" class="tc0">暖房は、LDKでの機器の組み合わせと、使い方でかなり消費量が違います。ガスファンヒーターを、ガス温水床暖房とエアコンの併用にすると･･･</td>
					<td bgcolor="#FFFFFF"><img src="img/one/meter/377.gif" border="0" width="100" height="45"></td>
<!--					<td bgcolor="#FFFFFF" align="middle"><a href="sim_danbou_LD.php" title="シミュレーション"><img src="img/one/one_foot01.gif" border="0"></a></td>-->
					<td bgcolor="#FFFFFF" align="middle"><a href="kucho_danbou.php" title="診断＆ガンバリスト登録"><img src="img/one/one_foot01.gif" border="0"></a></td>
					<td bgcolor="#FFFFFF" align="middle"><? disp_master_mark(GANBA_DANBOU_LD) ?></td>
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
		<td align="center"><br><a href="life_style.php#sim_top"><img src="img/one/back3.gif" border="0" width="117" height="36"></a></td>
		<td width="35" valign="bottom"><img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>
<!-- 白丸角テーブル終了 -->

</div>
</body>
</html>