<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:承認済みメルマガ一覧
'******************************************************/

$title_text = "承認済みメルマガ一覧";
$title_color = $TC_HAKKOUSHA;

//表示選択
switch ($kind) {
	case "":
		$where = " MAG.mag_pub_status_flg = '03' and MAG.publisher_id <> 0 and MAG.publisher_id = PUB.publisher_id";
		break;
	case "1":
		$where = " MAG.mag_pub_status_flg = '01' and MAG.publisher_id <> 0 and MAG.publisher_id = PUB.publisher_id";
		break;
	case "2":
		$where = " MAG.melmel_id <> '00000' and MAG.mag_pub_status_flg <> '00' and MAG.publisher_id <> 0 and MAG.publisher_id = PUB.publisher_id";
		break;
	case "3":
		$where = " MAG.melmel_id = '00000' and MAG.mag_pub_status_flg <> '00' and MAG.publisher_id <> 0 and MAG.publisher_id = PUB.publisher_id";
		break;
	case "4":
		$where = " MAG.mag_pub_status_flg <> '00' and MAG.publisher_id <> 0 and MAG.publisher_id = PUB.publisher_id";
		break;
}

//=== Main ===
if ($header_insert_flg != '') {
	$sql = "UPDATE m_magazine SET header_insert_flg='$header_insert_flg' WHERE mag_id='$mag_id'";
	db_exec($sql);
}

$order_by = order_by(2, 1, 'mag_pub_status_flg', 'mag_pub_status_dt', 'mag_id', 'mag_nm', 'owner_nm_kana', 'header_insert_flg');
$sql = "SELECT MAG.mag_id, MAG.mag_nm, MAG.publisher_id, MAG.mag_pub_status_flg"
	. ", to_char(MAG.MAG_AGR_DT, 'yyyy/mm/dd') as MAG_AGR_DT"
	. ", to_char(MAG.MAG_PUB_STATUS_DT, 'yyyy/mm/dd') as MAG_PUB_STATUS_DT"
	. ", PUB.owner_nm_kanj,PUB.owner_nm_kana,MAG.mag_pub_stop_flg,MAG.osusume_flg,MAG.mag_riba_status_flg,MAG.header_insert_flg"
	. " FROM M_MAGAZINE MAG ,M_PUBLISHER PUB "
	. " WHERE" . $where . " " . $order_by;
$result = db_exec($sql);
$nrow = pg_numrows($result);

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
<SCRIPT LANGUAGE=javascript>
<!--
function sort_list(sort, dir) {
	var f = document.form1;
	f.sort_col.value = sort;
	f.sort_dir.value = dir;
	f.submit();
}
function OnClick_kind(kind) {
	var f = document.form1;
	f.kind.value = kind;
	f.submit();
}
function OnClick_mag(mag_id) {
  var win;
  win = window.open("../melmaga_detail/mag_detail1.php?mag_id=" + mag_id, "info_mag", "scrollbars=yes,resizable=yes,width=600,height=700");
  win.focus();
}
function OnClick_pub(pub_id) {
  var win;
  win = window.open("../melmaga_detail/publisher.php?pub_id=" + pub_id, "info_pub", "scrollbars=yes,resizable=yes,width=600,height=500");
  win.focus();
}
function onclick_header_insert_flg(mag_id,mag_nm,flg) {
	var f = document.form1;
	if (flg == "t") {
		if (confirm("「" + mag_nm + "」\nのヘッダ／フッタ挿入を無しに設定します。よろしいですか？")) {
			f.header_insert_flg.value = "f";
			f.mag_id.value = mag_id;
			f.submit();
		}
	} else {
		if (confirm("「" + mag_nm + "」\nのヘッダ／フッタ挿入を有りに設定します。よろしいですか？")) {
			f.header_insert_flg.value = "t";
			f.mag_id.value = mag_id;
			f.submit();
		}
	}
}
//-->
</SCRIPT>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" name="form1" style="margin:0">
	<table border=0 cellspacing=0 cellpadding=0 width='100%'>
		<tr>
			<td>■承認済みメルマガ一覧</td>
		</tr>
	</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="kind" <?=value($kind)?>>
<input type="hidden" name="mag_id">
<input type="hidden" name="header_insert_flg">
	<table border=0 cellspacing=0 cellpadding=0 width='100%'>
		<tr>
			<td><font size="-1">
				<input type="radio" onclick="OnClick_kind('')" <? If ($kind == "") {print " checked";} ?>>本登録のみ
				<input type="radio" onclick="OnClick_kind('1')" <? If ($kind == "1") {print " checked";} ?>>仮登録のみ
				<input type="radio" onclick="OnClick_kind('2')" <? If ($kind == "2") {print " checked";} ?>>めるめるのみ
				<input type="radio" onclick="OnClick_kind('3')" <? If ($kind == "3") {print " checked";} ?>>非めるめるのみ
				<input type="radio" onclick="OnClick_kind('4')" <? If ($kind == "4") {print " checked";} ?>>全て
				　　（※項目名をクリックするとソートできます。）</font>
			</td>
			<td align="right">該当件数：<span id="rec_count"></span>件 <input type="button" value=" 戻る " onclick="location.href='../menu.php'"><br>
			</td>
		</tr>
	</table>
	<table border=1 cellspacing=0 cellpadding=3 width='100%'>
		<tr bgcolor ="#ffcc99">
<?
		sort_header(1, 'status');
		sort_header(2, '承認日');
		sort_header(3, 'メルマガID');
		sort_header(4, 'メルマガ名');
		sort_header(5, '発行者');
		sort_header(6, 'ﾍｯﾀﾞ/ﾌｯﾀ挿入');
?>
		</tr>
	<?php
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$mag_pub_status_flg = $fetch->mag_pub_status_flg;
		$mag_pub_status_dt = $fetch->mag_pub_status_dt;
		$mag_id = $fetch->mag_id;
		$mag_nm = $fetch->mag_nm;
		$owner_nm_kanj = $fetch->owner_nm_kanj;
		$owner_nm_kana = $fetch->owner_nm_kana;
		$publisher_id = $fetch->publisher_id;
		$header_insert_flg = $fetch->header_insert_flg;

		switch ($mag_pub_status_flg) {
		case "01":
			$mag_pub_status_flg = "仮";
			$class = "f2";
			break;
		case "02":
			$mag_pub_status_flg = "拒";
			$class = "f3";
			break;
		case "03":
			$mag_pub_status_flg = "本";
			$class = "f1";
			break;
		case "99":
			$mag_pub_status_flg = "廃";
			$class = "f4";
			break;
		}

	?>
		<tr>
			<td align="center" class="<?= $class ?>"><?= $mag_pub_status_flg ?></td>
			<td align="center" class="tc<?= $line % 2; ?>"><?= $mag_pub_status_dt ?></td>
			<td align="center" class="tc<?= $line % 2; ?>"><nobr><?= $mag_id ?></nobr></td>
			<td class="tc<?= $line % 2; ?>" title="メルマガの詳細を表示します"><a href='JavaScript:OnClick_mag("<?= $mag_id ?>")'><?= htmlspecialchars($mag_nm) ?></a></td>
			<td align="left" class="tc<?= $line % 2; ?>" title="発行者の詳細を表示します"><a href='JavaScript:OnClick_pub("<?= $publisher_id ?>")'><?= htmlspecialchars($owner_nm_kanj) ?></a></td>
			<td align="center" class="tc<?= $line % 2; ?>"><a href="javascript:onclick_header_insert_flg('<?=$mag_id?>','<?=htmlspecialchars(trim($mag_nm))?>','<?=$header_insert_flg?>')"><?= $header_insert_flg == 't' ? '有り' : '無し' ?></a></td>
		</tr>
	<?php
	$line++;
	}
	?>
	</table>
</form>
</center>

<SCRIPT LANGUAGE=javascript>
<!--
document.all.rec_count.innerHTML = <?= $nrow ?>;
//-->
</SCRIPT>

<? footer_jmk(0); ?>

</body>
</html>
