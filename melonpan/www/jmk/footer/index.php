<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:ヘッダー／フッターデータ登録
'******************************************************/

$title_text = $TT_footer;
$title_color = $TC_MASTER;

//'=== 行数表示 ===
function DispLine($nline) {
	if ($nline == '0') {
		print "<option value='0' selected>全て</option>";
	} else {
		print "<option value='0'>全て</option>";
	}
	for ($i = 10; $i <= 100; $i = $i + 10) {
		if ($i == $nline) {
			print "<option value=" . $i ." selected>". $i . "行</option>\n";
		} else {
			print "<option value=" . $i .">". $i . "行</option>\n";
		}
	}
}

//'=== Main ===
 if ($nline == "") {
	$nline = 10;
}

$today = date("Ymd");

$sql = "SELECT * FROM T_MAG_FOOTER"
		. " ORDER BY footer_id DESC";
$result = db_exec($sql);
$nrow = pg_numrows($result);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" name="form1" style="margin:0">
	<table border=0 cellspacing=0 cellpadding=0 width='100%'>
		<tr>
			<td>■ヘッダー／フッターデータ一覧
				<font size="-1">　　表示行</font><select name="nline"  onchange="submit()"><? DispLine($nline); ?></select></td>
			<td align="right">
				<input type="button" value="新規登録" onclick="location.href='footer1.php'">
				<input type="button" value=" 戻る " onclick="location.href='../menu.php'">
			</td>
		</tr>
	</table>
	<table border=1 cellspacing=0 cellpadding=1 width='100%'>
		<tr bgcolor="#ffcc99">
			<th align="center" rowspan="2"><nobr>ID</nobr></th>
			<th align="center" rowspan="2"><nobr>挿入日</nobr></th>
			<th align="center" rowspan="2"><nobr>メッセージ</nobr></th>
			<th align="center" colspan="4"><nobr>対象メルマガ</nobr></th>
			<th align="center" colspan="2"><nobr>挿入位置</nobr></th>
		</tr>
		<tr bgcolor="#ffcccc">
			<th align="center" width="5%"><font size="-1"><nobr>一般</nobr></font></th>
			<th align="center" width="5%"><font size="-1"><nobr>めろんぱん<br>レター</nobr></font></th>
			<th align="center" width="5%"><font size="-1"><nobr>りばっﾁｬ</nobr></font></th>
			<th align="center" width="5%"><font size="-1"><nobr>ﾊﾞ･ｷｭｰﾝ</nobr></font></th>
			<th align="center" width="5%"><font size="-1"><nobr>ヘッダー</nobr></font></th>
			<th align="center" width="5%"><font size="-1"><nobr>フッター</nobr></font></th>
		</tr>
<?php
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$footer_id = $fetch->footer_id;
	$access_day = $fetch->access_day;
	$access_day_end = $fetch->access_day_end;
	$admin_msg = htmlspecialchars($fetch->admin_msg);
	$user_footer = $fetch->user_footer;
	$riba_footer = $fetch->riba_footer;
	$letter_footer = $fetch->letter_footer;
	$bakyun_footer = $fetch->bakyun_footer;
	$body = ereg_replace("\n","<br>",$admin_msg);
	$insert_year = substr($access_day, 0, 4);
	$insert_month = substr($access_day, 4, 2);
	$insert_day = substr($access_day, 6, 2);
	$insert_year_end = substr($access_day_end, 0, 4);
	$insert_month_end = substr($access_day_end, 4, 2);
	$insert_day_end = substr($access_day_end, 6, 2);
	if ($access_day_end >= $today) {
		$footer_id = "<a href='footer2.php?footer_id=" . $footer_id . "'>" . $footer_id . "</a>";
	}
	if ($line >= $nline && $nline <> '0') {
		break;
	}
	if ($user_footer == '1') { $user = "○"; } else { $user = "<br>"; }
	if ($letter_footer == '1') { $letter = "○"; } else { $letter = "<br>"; }
	if ($riba_footer == '1') { $riba = "○"; } else { $riba = "<br>"; }
	if ($bakyun_footer == '1') { $bakyun = "○"; } else { $bakyun = "<br>"; }
	if ($line >= $nline && $nline <> '0') {
		break;
	}
?>
		<tr class="tc<?= $line % 2; ?>">
			<td align="center"><?= $footer_id ?></td>
			<td align="center"><?= "$insert_year/$insert_month/$insert_day" ?>〜<?= "$insert_year_end/$insert_month_end/$insert_day_end" ?></td>
			<td class="np"><?= $body ?></td>
			<td align="center"><?= $user ?></td>
			<td align="center"><?= $letter ?></td>
			<td align="center"><?= $riba ?></td>
			<td align="center"><?= $bakyun ?></td>
			<td align="center"><?= $fetch->insert_position[0] == '1' ? '○' : '<br>'?></td>
			<td align="center"><?= $fetch->insert_position[1] == '1' ? '○' : '<br>' ?></td>
		</tr>
<?php
$line++;
}
?>
	</table>
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
