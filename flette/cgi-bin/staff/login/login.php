#!/usr/local/bin/php4
<?
/******************************************************
' System :fletteスタッフページ
' Content:ログイン画面処理
'******************************************************/

$top = '..';
$inc = "$top/../inc";
include("$inc/common.php");

$inc = "$top/inc";
include("$inc/header.php");

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta http-equiv="Content-Style-Type" content="text/css">
<title>flette</title>
<link rel="stylesheet" type="text/css" href="/css/staff.css">
<script type="text/javascript">
<!--
function onload_body() {
  document.form1.mail_addr.focus();
}
function onsubmit_form1(form) {
  if (form.mail_addr.value == "") {
    alert("メールアドレスを入力してください。");
    form.mail_addr.focus();
    return false;
  }
  if (form.password.value == "") {
    alert("パスワードを入力してください。");
    form.password.focus();
    return false;
  }
  return true;
}
//-->
</script>
</head>
<body onload="onload_body()">

<? page_header('スタッフログイン', false, false) ?>

<div align="center">
<form method="post" name="form1" action="login_check.php" onSubmit="return onsubmit_form1(this)">
	<table border=0 cellspacing=1 cellpadding=0 width='700'>
	  <tr>
	    <td class="m0">■メールアドレスとパスワードを入力してください</td>
	  </tr>
	  <tr>
	    <td>
	      <table border=0 cellspacing=2 cellpadding=3 width="100%">
	        <tr>
	          <td class="m1" width="25%">メールアドレス</td>
	          <td class="n1"><input class="alpha" type="text" name="mail_addr" value="" size=30></td>
	        </tr>
	        <tr>
	          <td class="m1" width="25%">パスワード</td>
	          <td class="n1"><input type="password" name="password" size=30 maxlength=20></td>
	        </tr>
	      </table>
	    </td>
	  </tr>
	</table>
	<span class="note">本システムは Internet Explorer 5.0 以上でご利用ください。（Netscapeでは動作しません）</span>
  <br><br>
  <input type="submit" value=" ログイン ">
</form>
</div>

<? page_footer() ?>

</body>
</html>
