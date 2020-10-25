<?
include ("../inc/common.php");
include ("../inc/database_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:メルマガ詳細表示画面
'******************************************************/

$title_text = "メルマガ詳細表示";
$title_color = $TC_MASTER;

$mag_id = sprintf("%06d", $mag_id);

$sql = "SELECT mag_id FROM M_MAGAZINE WHERE mag_id='$mag_id'";
$result = db_exec($sql);
$nrow = pg_numrows($result);

if ($nrow <= 0) {
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
</head>
<body>
<center>
<?php
	print "メルマガが見つかりません。<br><br>";
?>
<input type="button" value="　閉じる　" onclick="window.close()"></form>
</center>
</body>
</html>
<?
	exit;
} else {
	$sql = "SELECT PUB.mail_add_main, MAG.melmel_id, MAG.mail_add_publish, MAG.mag_nm, MAG.mag_intro_long, MAG.mag_intro_short, MAG.mag_hp"
	. ", TYPE.type, MAG.mag_cycle_day, CATG1.catg as catg1, CATG2.catg as catg2, BG1.bg as bg1, BG2.bg as bg2, MAG.mag_reader_sex"
	. ", MAG.mag_reader_age, AREA.area, OCCUP.occup, MAG.mag_bn_pub"
	. " FROM M_MAGAZINE MAG, M_PUBLISHER PUB, M_MAG_TYPE TYPE, M_MAG_CATG CATG1, M_MAG_CATG CATG2, M_MAG_BG BG1, M_MAG_BG BG2, M_MAG_AREA AREA"
	. ", M_MAG_OCCUP OCCUP"
	. " WHERE MAG.mag_id = '$mag_id' AND MAG.publisher_id=PUB.publisher_id AND MAG.mag_type=TYPE.type_id"
	. " AND MAG.mag_catg_1=CATG1.catg_id AND MAG.mag_catg_2=CATG2.catg_id AND MAG.mag_bg_1=BG1.bg_id AND MAG.mag_bg_2=BG2.bg_id"
	. " AND MAG.mag_reader_area=AREA.area_id AND MAG.mag_reader_occup=OCCUP.occup_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$mail_add_main = $fetch->mail_add_main;
		$melmel_id = $fetch->melmel_id;
		$mail_add_publish = $fetch->mail_add_publish;
		$mag_nm = $fetch->mag_nm;
		$mag_intro_long = $fetch->mag_intro_long;
		$mag_intro_short = $fetch->mag_intro_short;
		$mag_hp = $fetch->mag_hp;
		$type = $fetch->type;
		$mag_cycle_day = $fetch->mag_cycle_day;
		$catg1 = $fetch->catg1;
		$catg2 = $fetch->catg2;
		$mag_bg_1 = $fetch->bg1;
		$mag_bg_2 = $fetch->bg2;
		$mag_reader_sex = $fetch->mag_reader_sex;
		$mag_reader_age = $fetch->mag_reader_age;
		$mag_reader_area = $fetch->area;
		$mag_reader_occup = $fetch->occup;
		$mag_bn_pub = $fetch->mag_bn_pub;
		if ($mag_reader_sex == 1) {
			$mag_reader_sex = "男性";
		} elseif ($mag_reader_sex == 2) {
			$mag_reader_sex = "女性";
		} else {
			$mag_reader_sex = "-";
		}
		if ($mag_bn_pub == 1) {
			$mag_bn_pub = "公開";
		} elseif ($mag_bn_pub == 2) {
			$mag_bn_pub = "非公開";
		} elseif ($mag_bn_pub == 3) {
			$mag_bn_pub = "最新号のみ公開";
		} else {
			$mag_bn_pub = "-";
		}
	}
}

//最新バックナンバーid取得
$sql = "SELECT MAX(index_id) as index_id FROM L_BACKNUMBER WHERE mag_id='$mag_id'";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$max_index_id = $fetch->index_id;
}
/*
//最新バックナンバーrowid取得
$conn = db_conxxxt();
$sql = "select rowidtochar(rowid) from L_BACKNUMBER	where index_id=(SELECT MAX(index_id) FROM L_BACKNUMBER WHERE mag_id='$mag_id')";
$stmt_back = ociparse($gConn,$sql);
$ret = ociexecute($stmt_back);

if ( ocifetchinto($stmt_back,&$resarray_back) ) {
	$back_num = urlencode($resarray_back[0]);
}
ocifreestatement($stmt_back);
*/
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title>メルマガ情報</title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnClick_hp(mag_hp) {
  var win;
  win = window.open(mag_hp,"pub_hp","location=yes,menubar=yes,scrollbars=yes,resizable=yes");
  win.focus();
}
function OnClick_backnumber(index_id, mag_id) {
  var win;
  win = window.open("../melmaga_detail/mag_backnumber.php?index_id=" + index_id + "&mag_id=" + mag_id, "ino_backnumber", "scrollbars=yes,resizable=yes,width=700,height=800");
  win.focus();
}//-->
</SCRIPT>

</head>
<body>
<form method="post" name="form1" action="kanri_suuji2.php">
	<center>
		<table width='550'>
			<tr>
				<td >■メルマガ詳細</td>
				<td align="right"><input type="button" value="バックナンバー表示" onclick='JavaScript:OnClick_backnumber("<?= $max_index_id ?>", "<?= $mag_id ?>")'></td>
			</tr>
		</table>
		<table width='550' border="1" CELLSPACING="0" CELLPADDING="3">
			<tr>
				<td class="h7" align="right">1</td>
				<td class="h6">メルマガID</td>
				<td><?= $mag_id ?></td>
			</tr>
			<tr>
				<td class="h7" align="right">2</td>
				<td class="h6">基本メールアドレス</td>
				<td><?= $mail_add_main ?></td>
			</tr>
			<tr>
				<td class="h7" align="right">3</td>
				<td class="h6">めるめる.net ＩＤ</td>
				<td><?= $melmel_id ?></td>
			</tr>
			<tr>
				<td class="h7" align="right">4</td>
				<td class="h6"><nobr>メルマガ発行元メールアドレス</nobr></td>
				<td><?= $mail_add_publish ?></td>
			</tr>
			<tr>
				<td class="h7" align="right">5</td>
				<td class="h6">メルマガ/ＭＬ名</td>
				<td><?= htmlspecialchars($mag_nm) ?></td>
			</tr>
			<tr>
				<td class="h7" align="right">6</td>
				<td class="h6">メルマガ紹介<br><nobr>（ロング 250文字以内）</nobr></td>
				<td ><?= htmlspecialchars($mag_intro_long) ?></td>
			</tr>
			<tr>
				<td class="h7" align="right">7</td>
				<td class="h6">メルマガ紹介<br>（ショート 50文字以内）</td>
				<td><?= htmlspecialchars($mag_intro_short) ?></td>
			</tr>
			<tr>
				<td class="h7" align="right">8</td>
				<td class="h6">ホームページURL</td>
				<td><a href='JavaScript:OnClick_hp("<?= $mag_hp ?>")'><?= htmlspecialchars($mag_hp) ?></a></td>
			</tr>
			<tr>
				<td class="h7" align="right">9</td>
				<td class="h6">タイプ</td>
				<td><?= $type ?></td>
			</tr>
			<tr>
				<td class="h7" align="right">10</td>
				<td class="h6">発行周期</td>
				<td><?= $mag_cycle_day ?></td>
			</tr>
			<tr>
				<td class="h7" align="right">11</td>
				<td class="h6">カテゴリ</td>
				<td><?= $catg1 ?>／<?= $catg2 ?></td>
			</tr>
			<tr>
				<td class="h7" align="right">12</td>
				<td class="h6">読む背景（気分で選ぶ）</td>
				<td><?= $mag_bg_1 ?>／<?= $mag_bg_2 ?></td>
			</tr>
			<tr>
				<td class="h7" align="right">13</td>
				<td class="h6"><nobr>一番読んでもらいたい＜性別＞</nobr></td>
				<td><?= $mag_reader_sex ?></td>
			</tr>
			<tr>
				<td class="h7" align="right">14</td>
				<td class="h6">一番読んでもらいたい＜年代＞</td>
				<td><?= $mag_reader_age ?>代</td>
			</tr>
			<tr>
				<td class="h7" align="right">15</td>
				<td class="h6"><nobr>一番読んでもらいたい＜エリア＞</nobr></td>
				<td><?= $mag_reader_area ?></td>
			</tr>
			<tr>
				<td class="h7" align="right">16</td>
				<td class="h6">一番読んでもらいたい＜職業＞</td>
				<td><?= $mag_reader_occup ?></td>
			</tr>
			<tr>
				<td class="h7" align="right">17</td>
				<td class="h6">バックナンバー 公開/非公開</td>
				<td><?= $mag_bn_pub ?></td>
			</tr>
		</table>
	<input type="button" value="　閉じる　" onclick="window.close()"></form>
	</center>
</form>
</body>
</html>
