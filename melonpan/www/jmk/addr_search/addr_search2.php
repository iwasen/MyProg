<?php
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:読者検索/代理解除処理
'******************************************************/

$title_text = $TT_addr_search;
$title_color = $TC_DOKUSHA;

$mail_addr = replace_single($mail_addr);
$sql = "SELECT * FROM T_MAILADDR WHERE mail_addr = '$mail_addr'";
$result = db_exec($sql);
$nrow = pg_numrows($result);
if ($nrow == 0) {
	header ("location: ../admin/err_msg.php?id=2301");
	exit;
} else {
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnSubmit_form1() {
	return confirm("購読メルマガを解除します。よろしいですか？");
}
function OnClick_info(mail_addr) {
  var win;
  win = window.open("addr_info.php?mail_addr=" + mail_addr, "info_project", "resizable=yes,width=500,height=380");
  win.focus();
}
function OnClick_mag(mag_id) {
  var win;
  win = window.open("../melmaga_detail/mag_detail1.php?mag_id=" + mag_id, "info_mag", "scrollbars=yes,resizable=yes,width=650,height=700");
  win.focus();
}
//-->
</SCRIPT>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" name="form1" action="addr_search3.php" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='800'>
	  <tr>
	    <td>■読者メールアドレス
	      <table border=0 cellspacing=2 cellpadding=3 width='100%'>
	        <tr>
	          <td class="h3" width="200">メールアドレス</td>
	          <td class="h2"><?= $mail_addr ?></td>
<?
	$sql = "SELECT MAG_ID, READER_ID, MAIL_ADDR FROM T_MAILADDR WHERE mail_addr = '$mail_addr' AND reader_id<>0";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$mail_addr2 = $fetch->mail_addr;
		$reader_id = $fetch->reader_id;
		print "<td><input type=\"button\" value=\"Myめろんぱん情報\" onclick='JavaScript:OnClick_info(\"$mail_addr\")'></td>";
	}
?>
	        </tr>
	      </table>
	    </td>
	  </tr>
	</table><br>
	<table border=0 cellspacing=0 cellpadding=0 width='800'>
		<tr>
			<td>■購読メルマガ一覧
	      <table border=0 cellspacing=2 cellpadding=3 width='100%'>
					<tr>
	          <td class="h4" width="5%" align="center"><nobr>メルマガID</nobr></td>
	          <td class="h4" width="5%" align="center"><nobr>マーク</nobr</td>
	          <td class="h4" width="25%" align="center"><nobr>タイトル</nobr</td>
						<td class="h4" width="3%" align="center">解除</td>
					</tr>
<?php
	$sql = "SELECT T.mail_id, T.MAG_ID, M.MAG_NM, M.OSUSUME_FLG, "
		. "  to_char(M.MAG_PUB_STATUS_DT,'YY-MM-DD') as mag_pub_status_dt,"
		. "  to_char(M.MAG_AGR_DT,'YY-MM-DD') as mag_agr_dt,"
		. "  M.MAG_PUB_STATUS_FLG, M.MAG_RIBA_STATUS_FLG, MAG_ORIGINAL_FLG "
		. " FROM T_MAILADDR T, M_MAGAZINE M"
		. " WHERE T.mail_addr = '$mail_addr' AND M.mag_id = T.mag_id ORDER BY T.mag_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);

	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$mail_id = $fetch->mail_id;
		$mag_id = $fetch->mag_id;
		$mag_nm = $fetch->mag_nm;
		$osusume_flg = $fetch->osusume_flg;
		$mag_pub_status_dt = $fetch->mag_pub_status_dt;
		$mag_agr_dt = $fetch->mag_agr_dt;
		$mag_pub_status_flg = $fetch->mag_pub_status_flg;
		$mag_riba_status_flg = $fetch->mag_riba_status_flg;
		$mag_original_flg = $fetch->mag_original_flg;
		$mae = strftime("%y-%m-%d ",time() - (14*24*60*60));

		$mark = "";
		if ($osusume_flg == 1) { $mark = "<img src=\"../../img/osusume.gif\">";}
//		if ($mag_pub_status_dt > $mae) { $mark .= "<img src=\"../../img/shincyaku.gif\">";}
//		if ($mag_pub_status_flg == '01') { $mark .= "<img src=\"../../img/kari.gif\">";}
		if ($mag_pub_status_flg == '01') { 
			if ($mag_pub_status_dt > $mae) { $mark .= "<img src=\"../../img/shincyaku.gif\">"; }
			$mark .= "<img src=\"../../img/kari.gif\">";
		}else{
			if ($mag_agr_dt > $mae)	{ $mark .= "<img src=\"../../img/shincyaku.gif\">";}
		}
		if ($mag_riba_status_flg == 1) { $mark .= "<img src=\"../../img/mark-rib.gif\">";}
		if ($mag_original_flg == 1) { $mark .= "<img src=\"../../img/meron.gif\">";}
?>
					<tr>
						<td class="h2" align="center"><?= $mag_id ?></td>
						<td class="h2" align="center"><?= $mark ?></td>
						<td class="h2" title="メルマガの詳細を表示します"><nobr><a href='JavaScript:OnClick_mag("<?= $mag_id ?>")'><?= htmlspecialchars($mag_nm) ?></a></nobr></td>
						<td align="center"><input type="checkbox" name="mail_id[]" value="<?= $mail_id ?>"></td>
					</tr>
<?php
	}
?>
				</table>
			<td>
		</tr>
	</table>
  </br>
	<input type="hidden" name="reader_id" value="<?= $reader_id ?>">
	<input type="hidden" name="mail_addr" value="<?= $mail_addr ?>">
  <input type="submit" value=" 一括解除 ">
	<input type="button" value=" 戻る " onclick="history.back()">
</form>
</center>

<?
}
footer_jmk(0);
?>

</body>
</html>
