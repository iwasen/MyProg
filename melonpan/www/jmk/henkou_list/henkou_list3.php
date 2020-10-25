<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:登録情報変更リスト
'******************************************************/

$title_text = $TT_henkou_list;
$title_color = $TC_MASTER;

//日付設定
$before_7day = strftime ("%Y%m%d", mktime (0,0,0,date(m),date(d)-7,date(Y)));
$before_14day = strftime ("%Y%m%d", mktime (0,0,0,date(m),date(d)-14,date(Y)));
$before_21day = strftime ("%Y%m%d", mktime (0,0,0,date(m),date(d)-21,date(Y)));
$before_28day = strftime ("%Y%m%d", mktime (0,0,0,date(m),date(d)-28,date(Y)));

//'=== Main ====
if ($kind == "") {
	$where = " WHERE to_char(modify_dt, 'YYYY/MM/DD')=to_char(now(), 'YYYY/MM/DD') AND";
} elseif ($kind == "1") {
	$where = " WHERE modify_dt BETWEEN to_date($before_7day, 'YYYYMMDD') AND 'now' AND";
} elseif ($kind == "2") {
	$where = " WHERE modify_dt BETWEEN to_date($before_14day, 'YYYYMMDD') AND 'now' AND";
} elseif ($kind == "3") {
	$where = " WHERE modify_dt BETWEEN to_date($before_21day, 'YYYYMMDD') AND 'now' AND";
} elseif ($kind == "4") {
	$where = " WHERE modify_dt BETWEEN to_date($before_28day, 'YYYYMMDD') AND 'now' AND";
}

$order_by = order_by(1, 1, 'modify_dt', 'mail_addr', 'mag_count');
$sub = "SELECT COUNT(*) as mag_count, reader_id as cnt_reader_id FROM T_MAILADDR GROUP BY reader_id";
$sql = "SELECT reader_id, mail_addr, to_char(modify_dt, 'YYYY/MM/DD') as modify_dt, mag_count"
	. " FROM M_MELONPA, (" . $sub . ") as CT" . $where	. " reader_id=cnt_reader_id " . $order_by;
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

function OnClick_kind() {
  location.href = "henkou_list3.php?kind=" + document.form1.kind.value;
}

function OnClick_mymelonpan(reader_id) {
  var win;
  win = window.open("../melmaga_detail/mymelonpan.php?reader_id=" + reader_id, "info_project", "resizable=yes,width=500,height=380");
  win.focus();
}
//-->
</SCRIPT>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" name="form1" style="margin:0">
	<table border=0 cellspacing=0 cellpadding=0 width='60%'>
	  <tr>
	    <td>■Myめろんぱん個人情報変更者一覧　　　<font size=-1>表示：</font>
				<select onchange="OnClick_kind()" name="kind">
					<option value=""<? If ($kind == "") {print " selected";} ?>>本日のみ</option>
					<option value="1"<? If ($kind == "1") {print " selected";} ?>>過去１週間</option>
					<option value="2"<? If ($kind == "2") {print " selected";} ?>>過去２週間</option>
					<option value="3"<? If ($kind == "3") {print " selected";} ?>>過去３週間</option>
					<option value="4"<? If ($kind == "4") {print " selected";} ?>>過去４週間</option>
				</select>
			</td>
			<td align="right">該当件数：<span id="rec_count"></span>件 <input type="button" value=" 戻る " onclick="location.href='index.php'"><br></td>
		</tr>
	</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
  <table border=1 cellspacing=0 cellpadding=0 width='60%'>
    <tr bgcolor ="#ffcc99">
<?
		sort_header(1, '登録変更日');
		sort_header(2, 'メールアドレス');
		sort_header(3, '購読誌数');

?>
    </tr>
<?php
$line = 0;
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$reader_id = $fetch->reader_id;
	$mail_addr = $fetch->mail_addr;
	$mag_count = $fetch->mag_count;
	$modify_dt = $fetch->modify_dt;
?>
		<tr class="tc<?= $line % 2; ?>">
			<td align="center"><?= $modify_dt ?></td>
			<td align="left"><a href='JavaScript:OnClick_mymelonpan("<?= $reader_id ?>")'><?= $mail_addr ?></a></td>
			<td align="right"><?= number_format($mag_count) ?></td>
		</tr>
<?php
	$line++;
}
?>
	</table><br>
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
