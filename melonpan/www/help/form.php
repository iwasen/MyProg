<?
include('./inc/help.inc');
include('../sub/head.inc');

$cd = $_GET['cd'];
$name = $_POST['name'];
$mailadr = $_POST['mailadr'];
$body = $_POST['body'];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>めろんぱん || よりすぐりメルマガサイト</title>
</head>
<body bgcolor="#FFFFFF" text="#666666" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" vlink="#666600" link="#336600">
<? draw_head('..'); ?>
<table width="753" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td align="center"> 
      <table width="620" border="0" cellspacing="0" cellpadding="0" align="center">
        <tr> 
          <td width="21"><!-- #BeginEditable "1" --><img src="img/g1.gif" width="21" height="21"><!-- #EndEditable --></td>
          <td width="21"><!-- #BeginEditable "3" --><img src="img/g3.gif" width="21" height="21"><!-- #EndEditable --></td>
          <td width="557"><!-- #BeginEditable "title1" --><img src="img/g5.gif" width="60" height="21"><!-- #EndEditable --></td>
          <td width="21">　</td>
        </tr>
        <tr> 
          <td width="21"><!-- #BeginEditable "2" --><img src="img/g2.gif" width="21" height="21"><!-- #EndEditable --></td>
          <td width="21" bgcolor="#90c84b"><!-- #BeginEditable "4" --><img src="img/g4.gif" width="21" height="21"><!-- #EndEditable --></td>
          <td width="557" bgcolor="#90c84b"><!-- #BeginEditable "title2" --><img src="img/helptitle.gif" width="60" height="21"><!-- #EndEditable --></td>
          <td width="21"><img src="../common/c_rihgt_top.gif" width="21" height="21"></td>
        </tr>
        <tr bgcolor="#ffffd3"> 
          <td colspan="4" bgcolor="#ffffd3" height="21"><!-- #BeginEditable "sub" --><br>
            <!-- #EndEditable --></td>
        </tr>
        <tr> 
          <td width="21" bgcolor="#ffffd3">　</td>
          <td width="21" bgcolor="#ffffd3">　</td>
          <td width="557" bgcolor="#ffffd3">
            <form method="post" action="send.php">
						<table width="557" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td width="20" height="20"><img src="img/r1.gif" width="20" height="20"></td>
                <td height="20" bgcolor="#ffffff">　</td>
                <td height="20" width="20"><img src="img/r2.gif" width="20" height="20"></td>
              </tr>
              <tr> 
                <td width="20" bgcolor="#ffffff">　</td>
                <td bgcolor="#ffffff" align="center"> 
                  <table width="517" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="251" height="33"><font color="#90c84b">●</font><b><?= get_title($cd) ?></b></td>
                    </tr>
										<tr>
											<td align="center" height="40">
			                  <table width="517" border="1" cellspacing="0" cellpadding="0">
													<tr>
														<td width="120">　名前</td>
														<td width="397">　<input type="text" size="40" name="name" value="<?= $name ?>"></td>
													</tr>
													<tr>
														<td>　メールアドレス</td>
														<td>　<input type="text" size="40" name="mailadr" value="<?= $mailadr ?>"></td>
													</tr>
													<tr>
														<td>　本文</td>
														<td>　<textarea rows="12" cols="50" name="body"><?= $body ?></textarea></td>
													</tr>
												</table>
											</td>
										</tr>
										<tr>
											<td><br></td>
										</tr>
										<tr>
											<input type="hidden" name="cd" value="<?= $cd ?>">
											<td align="center">
												<input type="button" value="戻る" onclick="location.href='index.html'">　<input type="submit" value="送信">
											</td>
										</tr>
                  </table>
                </td>
                <td width="20" bgcolor="#ffffff">　</td>
              </tr>
              <tr> 
                <td width="20" height="20"><img src="img/r3.gif" width="20" height="20"></td>
                <td height="20" bgcolor="#ffffff">　</td>
                <td height="20" width="20"><img src="img/r4.gif" width="20" height="20"></td>
              </tr>
            </table>
						</form>
            <br>
            <br>
            <!-- #EndEditable --></td>
          <td width="21" bgcolor="#ffffd3">　</td>
        </tr>
        <tr> 
          <td width="21"><img src="../common/c_left_bottom.gif" width="21" height="21"></td>
          <td width="21" bgcolor="#90c84b">　</td>
          <td width="557" bgcolor="#90c84b">　</td>
          <td width="21"><img src="../common/c_right_bottom.gif" width="21" height="21"></td>
        </tr>
<? draw_footer('..'); ?>
      </table>
    </td>
  </tr>
</table>
</body>
</html>
