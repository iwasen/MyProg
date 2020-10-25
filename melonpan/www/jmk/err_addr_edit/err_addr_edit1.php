<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:不正アドレス修正画面
'******************************************************/

$title_text = "不正アドレス修正";
$title_color = $TC_MASTER;

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnSubmit_form1() {
  return confirm("実行します。よろしいですか？");
}
//-->
</SCRIPT>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>
<center>
<form method="post" name="form1" style="margin:0" action="err_addr_edit2.php" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='80%'>
		<tr>
			<td>■下記の通り修正します。</td>
		</tr>
	</table>
	<table border=1 cellspacing=0 cellpadding=1 width='80%'>
		<tr bgcolor="#ffcc99">
			<th align="center"><nobr>メルマガID</nobr></th>
			<th align="center"><nobr>修正前</nobr></th>
			<th align="center"><nobr>修正後</nobr></th>
			<th align="center"><nobr>備考</nobr></th>
		</tr>
<?php
$line = 0; $del_mag_list = "";
if (count($delete_mag) > 0) {
	for ($i = 0; $i < count($mag_id); $i++) {
		if ($delete_mag[$i] != "") {
			$del_mag_list .= $delete_mag[$i] . ",";
		}
	}
	$del_mag_list = substr($del_mag_list, 0, strlen($del_mag_list) - 1);
	$sql = "SELECT mag_id, mail_addr FROM T_MAILADDR WHERE mail_id IN (" . $del_mag_list . ")";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
for ($j = 0; $j < $nrow; $j++) {
	$fetch = pg_fetch_object($result, $j);
		$edit_mag_id = $fetch->mag_id;
		$mail_addr = $fetch->mail_addr;
?>
		<tr class="tc<?= $line % 2; ?>">
			<td align="center"><?= $edit_mag_id ?></td>
			<td><?= $mail_addr ?></td>
			<td align="center">＜解除＞</td>
			<td><br></td>
		</tr>
<?php
	$line++;
	}
}

$line = $line - 1; $n = 0;
for ($i = 0; $i < count($mail_id); $i++) {
	if ($delete_mag[$i] != $mail_id[$i] && $old_mail_addr[$i] != $new_mail_addr[$i]) {
		if (!addr_check2($new_mail_addr[$i])) {
			$err_msg = "<font color=\"red\">メールアドレスが正しくないようです。</font>";
			$err_flg = "1";
		} else {
			$err_msg = "<br>";
			$err_flg = "0";
		}
?>
		<tr class="tc<?= $line % 2; ?>">
<?
			if ($err_flg != "1") {
?>
			<input type="hidden" name="edit_mail_id[]" value="<?= $mail_id[$i] ?>">
			<input type="hidden" name="new_mail_addr[]" value="<?= $new_mail_addr[$i] ?>">
			<input type="hidden" name="reader_id[]" value="<?= $reader_id[$i] ?>">
<?
}
?>
			<td align="center"><?= $mag_id[$i] ?></td>
			<td><?= $old_mail_addr[$i] ?></td>
			<td><?= $new_mail_addr[$i] ?></td>
			<td><?= $err_msg ?></td>
		</tr>
<?php
	}
	$line++;
}
?>
	</table><br>
<input type="hidden" name="kind" value="<?= $kind_id ?>">
<input type="hidden" name="del_mag_list" value="<?= $del_mag_list ?>">
<input type="submit" value=" 実行 ">
<input type="button" value=" 戻る " onclick="history.back()">

</form>
</center>
<? footer_jmk(0); ?>

</body>
</html>
