<?php
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:読者検索/代理解除処理
'******************************************************/

$title_text = "読者検索/代理解除";
$title_color = $TC_DOKUSHA;

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnLoad_body() {
  document.form1.search_addr.focus();
}
function OnSubmit_form1(form) {
  if (form.search_addr.value == "") {
    alert("メールアドレスを入力してください。");
    form.search_addr.focus();
    return false;
  }
  return true;
}
//-->
</SCRIPT>
</head>
<body onLoad="OnLoad_body()">

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" name="form1" action="addr_search1_2.php" onSubmit="return OnSubmit_form1(this)" method="post">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
	  <tr>
	    <td class="m0">■メールアドレスを入力してください</td>
	  </tr>
	  <tr>
	    <td>
	      <table border=0 cellspacing=2 cellpadding=3 width='100%'>
	        <tr>
	          <td class="h3" width="25%">メールアドレス</td>
	          <td class="n4"><input type="text" size="40" maxlength="50" name="search_addr"></td>
	        </tr>
	      </table>
	    </td>
	  </tr>
	</table>
  <br>
  <input type="submit" value=" 検索 ">
	<input type="button" value=" 戻る " onclick="location.href='../menu.php'">
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
