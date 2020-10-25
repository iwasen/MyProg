<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:購読禁止メールアドレス登録
'******************************************************/

$title_text = $TT_disabled_adr;
$title_color = $TC_DOKUSHA;

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnLoad_body() {
  document.form1.mail_addr.focus();
}
function isA(val) {
	var numstring ="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ{}./+-*=~!#$%^&_?@";
	for(var i = 0; i < val.length; i++) {
		if(numstring.indexOf(val.charAt(i)) == -1)
			return false;
	}
	return true;
}
function OnSubmit_form1() {
  with (document.form1) {
    if (mail_addr.value == "") {
      alert("メールアドレスを入力してください。");
      mail_addr.focus();
      return false;
    }
		if (!isA(mail_addr.value)) {
			alert("メールアドレスは半角英数で入力してください。");
			mail_addr.focus();
			return false;
		}
  }
  return confirm("登録します。よろしいですか？");
}
//-->
</SCRIPT>
</head>
<body onLoad="OnLoad_body()">

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" action="disabled_adr3.php" name="form1" onSubmit="return OnSubmit_form1();">
  <input type="hidden" name="mt_id" value="<?= $mt_id ?>">
  <input type="hidden" name="guide" value="<?= $guide ?>">
	<table border=0 cellspacing=0 cellpadding=0 width='750'>
    <tr>
      <td class="m0">■購読禁止メールアドレス登録</td>
    </tr>
	</table>
  <table border=0 cellspacing=2 cellpadding=3 width="750">
    <tr>
      <td class="m4" width="20%">メールアドレス</td>
      <td class="n4"><input type="text" name="mail_addr" size="50" maxlength=50><font class="note">（半角英数）</font></td>
    </tr>
  </table><br>
  <input type="submit" value=" 登録 ">
  <input type="reset" value="リセット">
  <input type="button" value=" 戻る " onclick="history.back()">
</form>
</center>


<? footer_jmk(0); ?>

</body>
</html>
