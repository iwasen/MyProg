<?
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:発行者リスト表示
'******************************************************/

function title($title, $sort, $sort2) {
	if ($sort == $sort2) {
		print "<th><font color='brown'>" . $title  . "</font></th>";
	} else {
		print "<th class='sort' onClick='location.href=\"send_pub_list.php?sort2=" . $sort . "\"' align='center'>" . $title . "</th>";
	}
}

if (!$sort2) { $sort2 = 0; }
if ($sort2 == 0) {
	$sort_name = "publisher_id";
} elseif ($sort2 == 1){
	$sort_name = "owner_nm_kana";
} elseif ($sort2 == 2) {
	$sort_name = "mail_add_main";
} elseif ($sort2 = 3) {
	$sort_name = "mag_count DESC";
}

$sub1 = "SELECT COUNT(*) as mag_count, publisher_id as cnt_pub_id FROM M_MAGAZINE"
	. " WHERE mag_pub_stop_flg<>'02' AND mag_status_flg<>'99' GROUP BY publisher_id";
$sub2 = "SELECT COUNT(*) as mag_hon_count, publisher_id as cnt_hon_id FROM M_MAGAZINE"
	. " WHERE mag_pub_status_flg='03' GROUP BY publisher_id";
$sub3 = "SELECT COUNT(*) as mag_kari_count, publisher_id as cnt_kari_id FROM M_MAGAZINE"
	. " WHERE mag_pub_status_flg='01' GROUP BY publisher_id";
$sub4 = "SELECT COUNT(*) as mag_hakkou_count, publisher_id as cnt_hakkou_id FROM M_MAGAZINE"
	. " WHERE mag_status_flg='00' GROUP BY publisher_id";
$sub5 = "SELECT COUNT(*) as mag_kyuukan_count, publisher_id as cnt_kyuukan_id FROM M_MAGAZINE"
	. " WHERE mag_status_flg='01' GROUP BY publisher_id";

$sql = "SELECT publisher_id, owner_nm_kanj, mail_add_main, mag_count, mag_hon_count, mag_kari_count, mag_hakkou_count, mag_kyuukan_count"
	. " FROM (" . $sub1 . ") as MT"
	. ", M_PUBLISHER LEFT JOIN (" . $sub2 . ") as HC ON publisher_id=cnt_hon_id"
	. " LEFT JOIN (" . $sub3 . ") as KC ON publisher_id=cnt_kari_id"
	. " LEFT JOIN (" . $sub4 . ") as HA ON publisher_id=cnt_hakkou_id"
	. " LEFT JOIN (" . $sub5 . ") as KU ON publisher_id=cnt_kyuukan_id"
	. " WHERE publisher_id<>0 AND publisher_id=cnt_pub_id"
	.	" ORDER BY " . $sort_name;
$result = db_exec($sql);
$nrow = pg_numrows($result);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title>発行者リスト</title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnClick_pub(pub_id) {
  var win;
  win = window.open("../melmaga_detail/publisher.php?pub_id=" + pub_id, "info_pub", "scrollbars=yes,resizable=yes,width=600,height=500");
  win.focus();
}
var sels = new Array(0);
function OnClick_sel(line) {
  var i;
  if (document.form1.sel[line].checked)
    sels[sels.length] = line;
  else {
    for (i = 0; i < sels.length; i++) {
      if (sels[i] == line) {
        sels[i] = -1;
        break;
      }
    }
  }
}
function OnClick_OK() {
	var i, n, s;
	s = "";
	for (i = 0; i < sels.length; i++) {
		n = sels[i];
		if (n != -1) {
			s += document.form1.sel[n].value + "\n";
		}
	}
	opener.document.form1.mailto.value += s;
	window.close();
}
function OnClick_kind1() {
  var i;
  sels = new Array(0);
  fm = document.form1;
	switch (fm.kind1.value) {
	case "":
	  for (i = 0; i < fm.sel.length; i++) {
	    fm.sel[i].checked = false;
	  }
		break;
 	case "1":
	  for (i = 0; i < fm.sel.length; i++) {
	   if (fm.mag_hakkou_count[i].value > 0) {
	      fm.sel[i].checked = true;
	      sels[sels.length] = i;
	    } else
	      fm.sel[i].checked = false;
	  }
		break;
	case "2":
	  for (i = 0; i < fm.sel.length; i++) {
	    if (fm.mag_kyuukan_count[i].value > 0) {
	      fm.sel[i].checked = true;
	      sels[sels.length] = i;
	    } else
	      fm.sel[i].checked = false;
	  }
		break;
	case "3":
	  for (i = 0; i < fm.sel.length; i++) {
	    if (fm.mag_hon_count[i].value > 0) {
	      fm.sel[i].checked = true;
	      sels[sels.length] = i;
	    } else
	      fm.sel[i].checked = false;
	  }
		break;
	case "4":
	  for (i = 0; i < fm.sel.length; i++) {
	    if (fm.mag_kari_count[i].value > 0) {
	      fm.sel[i].checked = true;
	      sels[sels.length] = i;
	    } else
	      fm.sel[i].checked = false;
	  }
		break;
	case "5":
	  for (i = 0; i < fm.sel.length; i++) {
	    fm.sel[i].checked = true;
	    sels[i] = i;
	  }
		break;
  }
}
//-->
</SCRIPT>
</head>
<body>
<form method="post" name="form1" action="../letter_hakkousha/letter_hakkousha.php">
	<center>
		<table width='750' border="0" CELLSPACING="0" CELLPADDING="0">
			<tr>
				<td>■メルマガ発行者リスト <font size=-1>選択条件：</font>
					<select onchange="OnClick_kind1()" name="kind1">
						<option value=""<? If ($kind1 == "") {print " selected";} ?>>未選択</option>
						<option value="1"<? If ($kind1 == "1") {print " selected";} ?>>発行中あり</option>
						<option value="2"<? If ($kind1 == "2") {print " selected";} ?>>休刊中あり</option>
						<option value="3"<? If ($kind2 == "1") {print " selected";} ?>>本登録あり</option>
						<option value="4"<? If ($kind2 == "2") {print " selected";} ?>>仮登録あり</option>
						<option value="5"<? If ($kind1 == "3") {print " selected";} ?>>全て</option>
					</select>
					<input type="submit" value=" ＯＫ "onclick="OnClick_OK()">
				</td>
				<td align="right"><font size=-1>総件数：<span id="rec_count"></span>件 </font><input type="button" value="閉じる" onclick="window.close()"></td>
			</tr>
		</table>
		<table width='750' border="1" CELLSPACING="0" CELLPADDING="0">
			<tr bgcolor="#e2f0dc">
<?php
title("発行者ID", 0, $sort2);
title("発行者名", 1, $sort2);
title("発行者メールアドレス", 2, $sort2);
title("発行誌数", 3, $sort2);
?>
				<th align="center"> 選択 </th>
			</tr>
<?php
$line = 0;
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$publisher_id = $fetch->publisher_id;
	$owner_nm_kanj = $fetch->owner_nm_kanj;
	$mail_add_main = $fetch->mail_add_main;
	$mag_count = $fetch->mag_count;
	$mag_hon_count = $fetch->mag_hon_count;
	$mag_kari_count = $fetch->mag_kari_count;
	$mag_hakkou_count = $fetch->mag_hakkou_count;
	$mag_kyuukan_count = $fetch->mag_kyuukan_count;
?>
			<tr class="tc<?= $line % 2; ?>">
				<input type="hidden" name="mag_hon_count" value=<?= $mag_hon_count ?>>
				<input type="hidden" name="mag_kari_count" value=<?= $mag_kari_count ?>>
				<input type="hidden" name="mag_hakkou_count" value=<?= $mag_hakkou_count ?>>
				<input type="hidden" name="mag_kyuukan_count" value=<?= $mag_kyuukan_count ?>>
				<td align="right"><?= $publisher_id ?></td>
				<td align="left"><a href='JavaScript:OnClick_pub("<?= $publisher_id ?>")'><?= htmlspecialchars($owner_nm_kanj) ?></a></td>
				<td align="left"><?= htmlspecialchars($mail_add_main) ?></td>
				<td align="right"><?= number_format($mag_count) ?></td>
				<td align="center"><input type="checkbox" name="sel" value="<?= $publisher_id . "(" . htmlspecialchars($owner_nm_kanj) . ")" ?>" onClick='OnClick_sel(<?= $line ?>)'></td>
			</tr>
<?php
$line++;
}
?>
		</table>
	<input type="button" value="　閉じる　" onclick="window.close()">
	</center>
<SCRIPT LANGUAGE=javascript>
<!--
document.all.rec_count.innerHTML = <?= $nrow ?>;
//-->
</SCRIPT>
</form>
</body>
</html>
