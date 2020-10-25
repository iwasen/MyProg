<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:めろんぱんレター配信
'******************************************************/

$title_text = $TT_letter_html;
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
  return confirm("画像ファイルをアップロードします。よろしいですか？");
}
//-->
</SCRIPT>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" style="margin:0" action="upload2.php" name="form1" enctype="multipart/form-data" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='750'>
    <tr>
      <td class="m0">■画像ファイルアップロード</td>
    </tr>
	</table>
<?
for ($i = 0; $i < 10; $i++) {
?>
	<input type="file" name="upload[]" size=80><br>
<?
}
?>
	<br>
  <input type="submit" value="アップロード">
  <input type="button" value=" 戻る " onclick="history.back()">
</form>
</cente>

<? footer_jmk(0); ?>

</body>
</html>
