<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:ログイン画面処理
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/center.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta http-equiv="Content-Style-Type" content="text/css">
<title><?=SYSTEM_NAME?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<script type="text/javascript">
<!--
function onLoad_body() {
  document.form1.mail_addr.focus();
}
function onSubmit_form1(f) {
  if (f.mail_addr.value == "") {
    alert("メールアドレスを入力してください。");
    f.mail_addr.focus();
    return false;
  }
  if (f.password.value == "") {
    alert("パスワードを入力してください。");
    f.password.focus();
    return false;
  }
  return true;
}
//-->
</script>
</head>
<body onload="onLoad_body()">

<? center_header('<div align="center">' . SYSTEM_NAME . '　ログイン</div>') ?>

<div align="center">
<form method="post" name="form1" action="login_check.php" onSubmit="return onSubmit_form1(this)">
	<table border=0 cellspacing=1 cellpadding=0 width='700'>
	  <tr>
	    <td class="m0">■メールアドレスとパスワードを入力してください</td>
	  </tr>
	  <tr>
	    <td>
	      <table border=0 cellspacing=2 cellpadding=3 width="100%">
	        <tr>
	          <td class="m1" width="25%">メールアドレス</td>
	          <td class="n1"><input class="alpha" type="text" name="mail_addr" size=40 <?=value($_COOKIE['login_mail_addr'])?>></td>
	        </tr>
	        <tr>
	          <td class="m1" width="25%">パスワード</td>
	          <td class="n1"><input type="password" name="password"></td>
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

<? center_footer() ?>

</body>
</html>
