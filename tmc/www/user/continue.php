<?
$inc = "../inc";
include("$inc/common.php");

// 継続がチェックされていたらthanks.phpへ飛ばす
if ($_POST['continue_flag'] == '1' && $_POST['t'] != '')
	redirect("thanks.php?t={$_POST['t']}");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="content-style-type" content="text/css">
<link rel="stylesheet" href="stop/ccs/default.css">
<title>おクルマ選び　お手伝いサービス</title>
<script type="text/javascript">
<!--
function check_continue() {
	document.form1.send.disabled = !document.form1.continue_flag.checked;
}
//-->
</script>
</head>

<body bgcolor="#ffffff" leftmargin="0" topmargin="0" background="stop/img/sidebg.gif" text="#666666" onload="check_continue()">
<div align="center" class="text12b">
  <table border="0" cellspacing="0" cellpadding="0" width="800" height="30">
    <tr bgcolor="#FFFFFF">
      <td class="text12"><img src="stop/img/ttl.gif" width="800" height="70" alt="おクルマ選びお手伝いサービス" border="0"><br>
        <img src="stop/img/spacer.gif" width="10" height="10" alt="*"> </td>
	</tr>
	</table>


  <table border="0" cellspacing="0" cellpadding="0" width="800">
    <tr>
      <td align="center" valign="top" class="text12" width="800" bgcolor="#FFFFFF">
        <img src="stop/img/spacer.gif" width="10" height="10" alt="*"> <br>
        <img src="stop/img/bar.gif" width="525" height="5" alt="bar"><br>
        情報配信継続<br>
        <img src="stop/img/bar.gif" width="525" height="5" alt="bar">
		<form method="post" name="form1" action="continue.php">
<table border="0" cellspacing="0" cellpadding="2" align="center" width="548" class="text12">
          <tr>
            <td width="20"><img src="stop/img/spacer.gif" width="10" height="10" alt="*" class="text12"></td>
          </tr>
          <tr align="center">
            <td class="text12"> 今後も情報の配信を希望する場合は、以下の【今後も情報の配信を希望する】に<br>
			チェックをいれて【送信】ボタンを押してください。 </td>
          </tr>
          <tr>
            <td width="20"><img src="stop/img/spacer.gif" width="10" height="10" alt="*" class="text12"></td>
          </tr>
          <tr align="center">
            <td class="text12"> <input type="checkbox" name="continue_flag" value="1" onclick="check_continue()"> 今後も情報の配信を希望する </td>
          </tr>
          <tr align="center">
            <td class="text14b"><img src="stop/img/spacer.gif" width="10" height="10" alt="*" class="text12"></td>
          </tr>
          <tr align="center">
            <td class="text14b"><img src="stop/img/arrow_blue.gif" width="14" height="8"></td>
          </tr>
          <tr>
            <td align="center" class="text12">
              <input type="submit" value="&nbsp;送&nbsp;信&nbsp;" name="send">
              <br>
              <img src="stop/img/spacer.gif" width="10" height="10" alt="*" class="text12">
            </td>
          </tr>
        </table>
<input type="hidden" name="t" <?=value($_REQUEST['t'])?>>
</form>
        <br>
      </td>
    </tr>
  </table>


<table border="0" cellspacing="0" cellpadding="0" width="800">
    <tr bgcolor="#002589" valign="middle">
      <td align="center" height="20">
        <div id="copyright" class="copyright">copyright 2004 e-okuruma.info All Rights Reserved.</div>
      </td>
</tr>
</table>


</div>
</body>

</html>
