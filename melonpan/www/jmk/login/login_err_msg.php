<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:ログインエラー画面処理
'******************************************************/

$title_text = "めろんぱん事務局 インターフェース";
$title_color = "#9fc741";

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnLoad_body() {
  document.form1.button.focus();
}
//-->
</SCRIPT>
</head>
<body onLoad="OnLoad_body()">

<? header_jmk($title_text,$title_color,1); ?>

<center>
<form name="form1">
<p>ログインに失敗しました。<br><br>戻るボタンをクリックしてもう一度ＩＤとパスワードを入力してください。</p>
<input type="button" name="button" value=" 戻る " onclick="history.back()">
<br>
</form>
</center>

<? footer_jmk(1); ?>

</body>
</html>
