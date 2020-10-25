<?
/******************************************************
' System :ICPオペレータページ
' Content:ログインページ
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");

$inc = "$top/inc";
include("$inc/ope_common.php");
include("$inc/header.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="Pragma" content="no-cache">
	<title>icp.net オペレータログインページ</title>
	<link rel="stylesheet" type="text/css" href="<?=$top?>/default.css">
	<script type="text/javascript">
<!--
function login() {
	var f = document.form1;
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
	f.action = "login_check.php";
  return true;
}
function cancel() {
	var f = document.form1;
	f.mail_addr.value = "";
	f.password.value = "";
	f.mail_addr.focus();
}
function reminder() {
	var f = document.form1;
  if (f.mail_addr.value == "") {
    alert("メールアドレスを入力してください。");
    f.mail_addr.focus();
    return false;
  }
	f.action = "reminder.php";
	f.submit();
}
//-->
	</script>
</head>

<body onload="document.form1.mail_addr.focus()">
<div align="center">

<? ope_header() ?>

<!-- main -->
<form method="post" name="form1" action="login_check.php" onSubmit="return login(this)">
<table border="0" cellspacing="0" cellpadding="0" width="800" height="400" class="main">
<tr>
	<td width="20"><img src="<?=$top?>/img/spacer.gif" width="20" height="1" border="0"></td>
	<td align="center" width="760">
		<table border="0" cellspacing="0" cellpadding="3" width="400" class="contents">
		<tr>
			<td>
<?
if (!isset($_GET['fail'])) {
?>
				■メールアドレスとパスワードを入力してください。
<?
} else {
?>
				<font color="red">入力したメールアドレスまたはパスワードが違うようです。</font><br>
				■再度、メールアドレスとパスワードを入力して、ログインしてください。
<?
}
?>
			</td>
		</tr>
		</table>
		<table border="0" cellspacing="2" cellpadding="5" width="400" class="login">
		<tr>
			<th width="150">メールアドレス</th>
			<td width="250"><input type="text" name="mail_addr" size="30" class="form" <?=value($_COOKIE['icp_login_operator'])?>></td>
		</tr>
		<tr>
			<th>パスワード</th>
			<td><input type="password" name="password" value="" size="30" class="form"></td>
		</tr>
		</table>
		<table border="0" cellspacing="0" cellpadding="5" width="400">
		<tr>
			<td align="center"><input type="submit" value="ログイン" class="btn">
			<input type="button" value="キャンセル" class="btn" onclick="cancel()"></td>
		</tr>
		</table>
		<table border="0" cellspacing="0" cellpadding="3" width="400" class="contents">
		<tr>
			<td align="right"><a href="javascript:void(reminder())" title="パスワードを忘れたら">パスワードを忘れたら</a></td>
		</tr>
		</table>
	</td>
	<td width="20"><img src="<?=$top?>/img/spacer.gif" width="20" height="1" border="0"></td>
</tr>
</table>
</form>
<!-- main -->

<!-- copyright -->
<table border="0" cellspacing="0" cellpadding="0" width="800" class="copyright">
<tr>
	<td align="center">
	本システムは Internet Explorer 5.0/Netscape 6.0 以上でご利用ください。<br>
	<?=COPYRIGHT?>
	</td>
</tr>
</table>
<!-- copyright -->

</div>
</body>

</html>
