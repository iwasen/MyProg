<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:バ・キューン配信
'******************************************************/

$title_text = $TT_letter_bakyun;
$title_color = $TC_MASTER;

$sql = "SELECT mag_nm, mag_header_tmpl, mag_footer_tmpl FROM M_MAGAZINE WHERE mag_id='000013'";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$mag_nm = $fetch->mag_nm;
	$mag_header_tmpl = $fetch->mag_header_tmpl;
	$mag_footer_tmpl = $fetch->mag_footer_tmpl;
	print pg_numrows($result);
	
}
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
  with (document.form1) {
  return confirm("登録します。よろしいですか？");
	}
}
//-->
</SCRIPT>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" style="margin:0" action="letter_bakyun1.php" name="form1" onSubmit="return OnSubmit_form1();">
  <input type="hidden" name="mt_id" value="<?= $mt_id ?>">
  <input type="hidden" name="guide" value="<?= $guide ?>">
	<table border=0 cellspacing=0 cellpadding=0 width='750'>
    <tr>
      <td class="m0">■バ･キューン情報</td>
    </tr>
	</table>
  <table border=0 cellspacing=2 cellpadding=3 width="750">
    <tr>
      <td class="m4">メルマガ名</td>
      <td class="n6"><?= $mag_nm ?></td>
    </tr>
    <tr>
      <td class="m4">ヘッダ</td>
      <td class="n6">
				<textarea cols=80 rows=10 name="mag_header_tmpl" class="np"><?= $mag_header_tmpl ?></textarea>
			</td>
    </tr>
    <tr>
      <td class="m4">フッタ</td>
      <td class="n6">
				<textarea cols=80 rows=10 name="mag_footer_tmpl" class="np"><?= $mag_footer_tmpl ?></textarea>
			</td>
    </tr>
  </table>
  <input type="submit" name="letter_header" value=" 登録 ">
  <input type="reset" value="リセット">
  <input type="button" value=" 戻る " onclick="history.back()">
</form>
</cente>

<? footer_jmk(0); ?>

</body>
</html>
