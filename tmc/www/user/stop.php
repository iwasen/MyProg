<?
$inc = "../inc";
include("$inc/common.php");
include("$inc/database.php");

// 解除理由選択肢表示
function select_kaijo_riyuu() {
	$sql = "SELECT mcr_cancel_cd,mcr_cancel_text FROM t_mail_cancel_result ORDER BY mcr_cancel_cd";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
?>
                <tr> 
                  <td class="text12" width="10"> 
                    <input type="radio" value="<?=$fetch->mcr_cancel_cd?>" name="cancel_cd">
                  </td>
                  <td class="text12"><?=htmlspecialchars($fetch->mcr_cancel_text)?></td>
                </tr>
<?
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="content-style-type" content="text/css">
<link rel="stylesheet" href="stop/ccs/default.css">
<title>おクルマ選び　お手伝いサービス</title>

</head>

<body bgcolor="#ffffff" leftmargin="0" topmargin="0" background="stop/img/sidebg.gif" text="#666666">
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
        メール配信解除<br>
        <img src="stop/img/bar.gif" width="525" height="5" alt="bar"> 
		<form method="post" action="stop2.php">
<table border="0" cellspacing="0" cellpadding="2" align="center" width="548" class="text12">
          <tr> 
            <td width="20"><img src="stop/img/spacer.gif" width="10" height="10" alt="*" class="text12"></td>
          </tr>
          <tr align="center"> 
            <td class="text12"> メール配信を停止したい場合は、【配信を解除する】ボタンを押して解除してください。 </td>
          </tr>
          <tr align="center"> 
            <td class="text14b"><img src="stop/img/spacer.gif" width="10" height="10" alt="*" class="text12"></td>
          </tr>
          <tr align="center"> 
            <td class="text14b"><img src="stop/img/arrow_blue.gif" width="14" height="8"></td>
          </tr>
          <tr align="center"> 
            <td> 
              <table border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td colspan="2" nowrap class="textbox">▼よろしければ、解除理由を教えてください。</td>
                </tr>
<? select_kaijo_riyuu() ?>
              </table>
            </td>
          </tr>
          <tr> 
              <td align="center" class="text12"> 
                <textarea name="cancel_text" cols=40 rows=5></textarea>
              <br>
              <img src="stop/img/arrow_blue.gif" width="14" height="8"> </td>
          </tr>
          <tr> 
            <td align="center" class="text12"> 
              <input type="submit" value="配信を解除する" name="submit">
              <br>
              <img src="stop/img/spacer.gif" width="10" height="10" alt="*" class="text12"> 
            </td>
          </tr>
        </table>
<input type="hidden" name="login_id" <?=value($_GET['t'])?>>
</form>
        <br>
      </td>
    </tr>
  </table>


<table border="0" cellspacing="0" cellpadding="0" width="800">
    <tr bgcolor="#002589" valign="middle"> 
      <td align="center" height="20"> 
        <div id="copyright" class="copyright">copyright 2003 e-okuruma.info All Rights Reserved.</div>
      </td>
</tr>
</table>


</div>
</body>

</html>
