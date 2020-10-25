<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:ワンポイントアドバイス（電気編）
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
$_SESSION['s_one_advice'] = 'one_electron.php';

// エネルギー分類セット
$_SESSION['s_eng_bunrui'] = ENG_DENKI;

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
					<td height=80><img src="img/shindan/sim_water/denki_title.gif" border="0" width="417" height="64"></td>
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
					<th align="center" bgcolor="gold">マスター</th>
				</tr>
<!-- 1段目 -->
				<tr>
					<td rowspan="2" align="center" bgcolor="#FFFA55"><img src="img/character/yellow/kaji.gif"></td>
					<td bgcolor="#FFFFFF" class="tc0">お湯はガスで沸かし、電気ポットでの保温は、やめてみませんか？ガスで沸騰させた後、魔法瓶で保温すると･･･</td>
					<td bgcolor="#FFFFFF"><img src="img/one/meter/43.gif" border="0" width="100" height="45"></td>
					<td bgcolor="#FFFFFF" align="center"><a href="kaji_yuwakashi.php" title="診断＆ガンバリスト登録"><img src="img/one/one_foot01.gif" border="0"></a></td>
					<td bgcolor="#FFFFFF" align="center"><? disp_master_mark(GANBA_YUWAKASHI) ?></td>
				</tr>
				<tr>
					<td bgcolor="#FFFFFF" class="tc0">洗濯乾燥機は、雨の日の仕上げ乾燥利用程度がお薦めです。雨の日に室内に干した後、仕上げ乾燥に利用すると･･･</td>
					<td bgcolor="#FFFFFF"><img src="img/one/meter/49.gif" border="0" width="100" height="45"></td>
					<td bgcolor="#FFFFFF" align="center"><a href="kaji_kansou.php" title="診断＆ガンバリスト登録"><img src="img/one/one_foot01.gif" border="0"></a></td>
					<td bgcolor="#FFFFFF" align="center"><? disp_master_mark(GANBA_KANSOU) ?></td>
				</tr>
<!-- 2段目 -->
				<tr>
					<td rowspan="2" align="center" bgcolor="#FFFA55"><img src="img/character/yellow/goraku.gif"></td>
					<td bgcolor="#FFFFFF" class="tc0">ＴＶのつけっぱなし、ながら視聴は控えましょう。ＴＶのながら視聴を一日１時間控えると･･･</td>
					<td bgcolor="#FFFFFF"><img src="img/one/meter/27.gif" border="0" width="100" height="45"></td>
					<td bgcolor="#FFFFFF" align="center"><a href="goraku_nagaraTV.php" title="診断＆ガンバリスト登録"><img src="img/one/one_foot03.gif" border="0"></a></td>
					<td bgcolor="#FFFFFF" align="center"><? disp_master_mark(GANBA_TV_KOUDOU) ?></td>
				</tr>
				<tr>
					<td bgcolor="#FFFFFF" class="tc0">ブラウン管ＴＶは、液晶ＴＶの約１．５倍の電気を消費します。ブラウン管ＴＶを、液晶ＴＶに替えると･･･</td>
					<td bgcolor="#FFFFFF"><img src="img/one/meter/39.gif" border="0" width="100" height="45"></td>
					<td bgcolor="#FFFFFF" align="center"><a href="goraku_ekisyoTV.php" title="診断＆ガンバリスト登録"><img src="img/one/one_foot03.gif" border="0"></a></td>
					<td bgcolor="#FFFFFF" align="center"><? disp_master_mark(GANBA_TV_KIKI) ?></td>
				</tr>
<!-- 3段目 -->
				<tr>
					<td align="center" bgcolor="#FFFA55"><img src="img/character/yellow/eisei.gif"></td>
					<td bgcolor="#FFFFFF" class="tc0">トイレの温水洗浄便座は、タイマーなどでこまめにON/OFFしましょう。起床から午前中のみの通電とすると･･･</td>
					<td bgcolor="#FFFFFF"><img src="img/one/meter/43.gif" border="0" width="100" height="45"></td>
					<td bgcolor="#FFFFFF" align="center"><a href="toilet_senjo.php?f=1" title="診断＆ガンバリスト登録"><img src="img/one/one_foot03.gif" border="0"></a></td>
					<td bgcolor="#FFFFFF" align="center"><? disp_master_mark(GANBA_TOILET_TSUUDEN) ?></td>
				</tr>
<!--
				<tr>
					<td bgcolor="#FFFFFF" class="tc0">今度使うなら便器は普及型から節水型</td>
					<td bgcolor="#FFFFFF"><img src="img/one/meter/6.gif" border="0" width="100" height="45"></td>
					<td bgcolor="#FFFFFF" align="center"><a href="toilet_senjo.php?f=2" title="診断＆ガンバリスト登録"><img src="img/one/one_foot03.gif" border="0"></a></td>
					<td bgcolor="#FFFFFF" align="center"><? disp_master_mark(GANBA_TOILET_SESSUI) ?></td>
				</tr>
-->
<!-- 4段目 -->
				<tr>
					<td rowspan="4" align="center" bgcolor="#FFFA55"><img src="img/character/yellow/kucyo.gif"></td>
					<td bgcolor="#FFFFFF" class="tc0">照明器具と点灯時間を見直してみてください。特にLDKがポイントです。LDKの照明を蛍光灯に替え、こまめに点灯・消灯すると･･･</td>
					<td bgcolor="#FFFFFF"><img src="img/one/meter/99.gif" border="0" width="100" height="45"></td>
					<td bgcolor="#FFFFFF" align="center"><a href="syoumei_LD_keikou.php" title="診断＆ガンバリスト登録"><img src="img/one/one_foot01.gif" border="0"></a></td>
					<td bgcolor="#FFFFFF" align="center"><? disp_master_mark(GANBA_SHOUMEI) ?></td>
				</tr>
				<tr>
					<td bgcolor="#FFFFFF" class="tc0">冷房はエアコンの使用時間を短めにして、自然の風を利用してみて下さい。設定温度を１℃上げて使用し、夕方３時間は窓を開けて通風すると･･･</td>
					<td bgcolor="#FFFFFF"><img src="img/one/meter/82.gif" border="0" width="100" height="45"></td>
					<td bgcolor="#FFFFFF" align="center"><a href="kucho_ryo.php" title="診断＆ガンバリスト登録"><img src="img/one/one_foot01.gif" border="0"></a></td>
					<td bgcolor="#FFFFFF" align="center"><? disp_master_mark(GANBA_REIBOU_LD) ?></td>
				</tr>
				<tr>
					<td bgcolor="#FFFFFF" class="tc0">冷房で設定温度を上げるのは、節電にかなり効果的です。就寝後、個室のエアコン設定温度を２℃上げると･･･</td>
					<td bgcolor="#FFFFFF"><img src="img/one/meter/93.gif" border="0" width="100" height="45"></td>
					<td bgcolor="#FFFFFF" align="center"><a href="kucho_riebou_ko.php" title="診断＆ガンバリスト登録"><img src="img/one/one_foot01.gif" border="0"></a></td>
					<td bgcolor="#FFFFFF" align="center"><? disp_master_mark(GANBA_REIBOU_KO) ?></td>
				</tr>
				<tr>
					<td bgcolor="#FFFFFF" class="tc0">暖房は、LDKの機器の組み合わせと、使い方でかなり消費量が違います。ガスファンヒーターを、ガス温水床暖房とエアコンの併用にすると･･･</td>
					<td bgcolor="#FFFFFF"><img src="img/one/meter/377.gif" border="0" width="100" height="45"></td>
					<td bgcolor="#FFFFFF" align="center"><a href="kucho_danbou.php" title="診断＆ガンバリスト登録"><img src="img/one/one_foot01.gif" border="0"></a></td>
					<td bgcolor="#FFFFFF" align="center"><? disp_master_mark(GANBA_DANBOU_LD) ?></td>
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
		<td align="center" height=60><br><a href="life_style.php#sim_top"><img src="img/one/back3.gif" border="0" width="117" height="36"></a></td>
		<td width="35" valign="bottom"><img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>
<!-- 白丸角テーブル終了 -->

</div>
</body>
</html>