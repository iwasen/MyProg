<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:ログイン画面処理
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");

set_global('kikasete', '', 'きかせて・net 事務局ログイン', BACK_NONE);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta http-equiv="Content-Style-Type" content="text/css">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onLoad_body() {
  document.form1.login_id.focus();
}
function onSubmit_form1(form) {
  if (form.login_id.value == "") {
    alert("ログインＩＤを入力してください。");
    form.login_id.focus();
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
<body onload="onLoad_body()">

<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="login_check.php" onSubmit="return onSubmit_form1(this)">
	<table border=0 cellspacing=1 cellpadding=0 width='700'>
	  <tr>
	    <td class="m0">■ログインIDとパスワードを入力してください</td>
	  </tr>
	  <tr>
	    <td>
	      <table border=0 cellspacing=2 cellpadding=3 width="100%">
	        <tr>
	          <td class="m1" width="25%">ログインID</td>
	          <td class="n1"><input class="alpha" type="text" name="login_id" size=30 <?=value($_COOKIE['admin_mail_addr'])?>></td>
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
