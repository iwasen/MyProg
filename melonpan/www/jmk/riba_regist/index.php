<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:りばっチャ登録
'******************************************************/

$title_text = $TT_riba_regist;
$title_color = $TC_HAKKOUSHA;
$sub = "SELECT count(mag_id) as mag_count, publisher_id AS mm_publisher_id FROM M_MAGAZINE GROUP BY publisher_id";
$sql = "SELECT publisher_id, owner_nm_kanj, mail_add_main, to_char(regist_dt, 'YYYY/MM/DD') as regist_dt, to_char(modefy_dt, 'YYYY/MM/DD') as modefy_dt"
	. ", mag_count"
	. " FROM M_PUBLISHER LEFT JOIN (" . $sub . ") MC ON publisher_id=mm_publisher_id WHERE owner_riba_flg=1 ORDER BY publisher_id DESC";
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
function OnClick_pub(pub_id) {
  var win;
  win = window.open("../melmaga_detail/publisher.php?pub_id=" + pub_id, "info_pub", "scrollbars=yes,resizable=yes,width=600,height=500");
  win.focus();
}
//-->
</SCRIPT>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" name="form1" style="margin:0">
<table border=0 cellspacing=0 cellpadding=0 width='90%'>
	<tr>
		<td>■りばっチャ登録一覧</td>
		<td align="right">
			<input type="button" value="新規登録" onclick="location.href='riba_regist1.php'">
			<input type="button" value=" 戻る " onclick="location.href='../menu.php'">
		</td>
	</tr>
</table>
<table border=1 cellspacing=0 cellpadding=2 width='90%'>
	<tr bgcolor="#ffcc99">
		<th align="center"><nobr>ＩＤ</nobr></th>
		<th align="center"><nobr>発行者名</nobr></th>
		<th align="center"><nobr>メールアドレス</nobr></th>
		<th align="center"><nobr>発行誌数</nobr></th>
		<th align="center"><nobr>登録日</nobr></th>
	</tr>
<?php
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$publisher_id = $fetch->publisher_id;
	$owner_nm_kanj = $fetch->owner_nm_kanj;
	$mail_add_main = $fetch->mail_add_main;
	$regist_dt = $fetch->regist_dt;
	$modefy_dt = $fetch->modefy_dt;
	$mag_count = $fetch->mag_count;
	if ($regist_dt == "") {
		$regist_dt = $modefy_dt;
	}
?>
	<tr class="tc<?= $line % 2; ?>">
		<td align="right" title="メルマガ発行者情報を修正します"><a href='riba_regist2.php?publisher_id=<?= $publisher_id ?>'><nobr><?= $publisher_id ?></nobr></a></td>
		<td align="left" title="メルマガ発行者情報を表示します"><a href='JavaScript:OnClick_pub("<?= $publisher_id ?>")'><nobr><?= replace_br(htmlspecialchars($owner_nm_kanj)) ?></nobr></a></td>
		<td align="left"><?= $mail_add_main ?></td>
		<td align="right"><?= number_format($mag_count) ?></td>
		<td align="center"><?= $regist_dt ?></td>
	</tr>
<?php
$line++;
}
?>
	</tr>
</table>
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
