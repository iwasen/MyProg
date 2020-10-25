<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:ワンポイントアドバイス（ゴミ編）
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
$_SESSION['s_one_advice'] = 'one_garbage.php';

// エネルギー分類セット
$_SESSION['s_eng_bunrui'] = ENG_GOMI;

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
					<td height=80><img src="img/shindan/sim_water/gomi_title.gif" border="0" width="417" height="64"></td>
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
					<td bgcolor="#FFFFFF" class="tc0">生ごみを堆肥にすることを考えませんか？今はだいぶ楽になりました。生ごみを全て、コンポストで堆肥にすると･･･</td>
					<td bgcolor="#FFFFFF"><img src="img/one/meter/263.gif" border="0" width="100" height="45"></td>
<!--					<td bgcolor="#FFFFFF" align="middle"><a href="sim_namagomi.php" title="シミュレーション"><img src="img/one/one_foot01.gif" border="0"></a></td>-->
					<td bgcolor="#FFFFFF" align="middle"><a href="kaji_namagomi.php" title="診断＆ガンバリスト登録"><img src="img/one/one_foot01.gif" border="0"></a></td>
					<td bgcolor="#FFFFFF"><? disp_master_mark(GANBA_NAMAGOMI) ?></td>
				</tr>
				<tr>
					<td bgcolor="#FFFFFF" class="tc0">ゴミはできる限りリサイクルにまわしましょう。牛乳パックを全てリサイクルに出すと･･･</td>
					<td bgcolor="#FFFFFF"><img src="img/one/meter/45.gif" border="0" width="100" height="45"></td>
<!--					<td bgcolor="#FFFFFF" align="middle"><a href="sim_shigengomi.php" title="シミュレーション"><img src="img/one/one_foot01.gif" border="0"></a></td>-->
					<td bgcolor="#FFFFFF" align="middle"><a href="kaji_shigengomi.php" title="診断＆ガンバリスト登録"><img src="img/one/one_foot01.gif" border="0"></a></td>
					<td bgcolor="#FFFFFF"><? disp_master_mark(GANBA_SHIGENGOMI) ?></td>
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