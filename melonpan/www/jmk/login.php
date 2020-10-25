<?php

include ("inc/header_jmk.php");
include ("inc/footer_jmk.php");
include ("inc/database_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:ログイン画面処理
'******************************************************/

$title_text = "めろんぱん事務局 インターフェース";
$title_color = "#9fc741";

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title><? echo ("$title_text") ?></title>
<link rel="stylesheet" type="text/css" href="css/melonpan.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnLoad_body() {
  document.form1.login_id.focus();
}
function num_chk(v1) {
	var p1 = v1;
	for (i=0; i<p1.length; i++){
		if (p1.substring(i,i+1)<"0" || p1.substring(i,i+1)>"9") {
			return(false);
		}
	}
	return true;
}
function OnSubmit_form1(form) {
  if (form.login_id.value == "") {
    alert("ログインＩＤを入力してください。");
    form.login_id.focus();
    return false;
  }
	if (!num_chk(form.login_id.value)){
		alert("ログインIDは半角の数値で入力してください。");
		form.login_id.focus();
		return(false);
	}
  if (form.password.value == "") {
    alert("パスワードを入力してください。");
    form.password.focus();
    return false;
  }
  return true;
}
//-->
</SCRIPT>
</head>
<body onLoad="OnLoad_body()">

<? header_jmk($title_text,$title_color,1); ?>

<center>
<form method="post" name="form1" action="login/login_check.php" onSubmit="return OnSubmit_form1(this)">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
	  <tr>
	    <td class="m0">■ログインIDとパスワードを入力してください</td>
	  </tr>
	  <tr>
	    <td>
	      <table border=0 cellspacing=2 cellpadding=3 width='100%'>
	        <tr>
	          <td bgcolor="#CCFFCC" width="25%">ログインID</td>
	          <td class="n4"><input type="text" name="login_id"></td>
	        </tr>
	        <tr>
	          <td bgcolor="#CCFFCC" width="25%">パスワード</td>
	          <td class="n4"><input type="password" name="password"></td>
	        </tr>
	      </table>
	    </td>
	  </tr>
	</table>
	<font class="note">本システムは Internet Explorer 5.0 以上でご利用ください。（Netscapeでは動作しません）</font>
  </br></br>
  <input type="submit" value=" ログイン ">
</form>
</center>

<? footer_jmk(1); ?>

</body>
</html>
