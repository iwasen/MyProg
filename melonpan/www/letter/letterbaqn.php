<?php
include("../melonpa/inc/jimu_mag_num.inc");
include("../sub/head.inc");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html><!-- #BeginTemplate "/Templates/index.dwt" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>めろんぱん || よりすぐりメルマガサイト</title>
</head>

<body bgcolor="#FFFFFF" text="#666666" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" vlink="#666600" link="#336600">

<?php	draw_head( ".." );	?>
<table width="753" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"> 
      <table width="620" border="0" cellspacing="0" cellpadding="0" align="center">
        <tr> 
          <td width="21"><!-- #BeginEditable "1" --><img src="img/letter1.gif" width="21" height="21"><!-- #EndEditable --></td>
          <td width="21"><!-- #BeginEditable "3" --><img src="img/letter3.gif" width="21" height="21"><!-- #EndEditable --></td>
          <td width="557"><!-- #BeginEditable "title1" --><!-- #EndEditable --></td>
          <td width="21">　</td>
        </tr>
        <tr> 
          <td width="21"><!-- #BeginEditable "2" --><img src="img/letter2.gif" width="21" height="21"><!-- #EndEditable --></td>
          <td width="21" bgcolor="#90c84b"><!-- #BeginEditable "4" --><img src="img/letter4.gif" width="21" height="21"><!-- #EndEditable --></td>
          <td width="557" bgcolor="#90c84b"><!-- #BeginEditable "title2" --><img src="img/yell.gif" width="420" height="21"><!-- #EndEditable --></td>
          <td width="21"><img src="../common/c_rihgt_top.gif" width="21" height="21"></td>
        </tr>
        <tr bgcolor="#ffffd3"> 
          <td colspan="4" bgcolor="#ffffd3" height="21"><!-- #BeginEditable "sub" -->
      <p>　</p>
      <p>　</p>
      <!-- #EndEditable --></td>
        </tr>
        <tr> 
          <td width="21" bgcolor="#ffffd3">　</td>
          <td width="21" bgcolor="#ffffd3">　</td>
          <td width="557" bgcolor="#ffffd3"><!-- #BeginEditable "%90%7D" --> 
      <table width="513" border="0" cellspacing="1" cellpadding="5">
        <tr > 
          <td>
            <table border="1" cellpadding="2" cellspacing="0" width="541" bgcolor="#91c741">
              <tr> 
                      <td height="23"><font color="#99ccff"><span class="text01"><font color="#ff9900">■</font><font color="#336600"><b><font color="#000000">「めろんぱらん」「めろんぱんレター」の購読解除・再購読</font></b></font></span></font></td>
              </tr>
            </table>
            <font color="#4f4f4f"><b><font color="#336600"><br>
                  </font></b>「めろんぱらん」「めろんぱんレター」は読者の皆さん全員にお届けしています。<br>
            購読の解除、再購読はこのページから行うことができます。<br>
            <br>
            <br>
            下のボックスの中にメールアドレスを入力して、購読解除／再購読のどちらかを選び、最後に［</font><font color="#336600">送信</font>］ボタンを押して下さい。<br>
            <br>
            <form action="../melonpa/mel_reg.php" method="post">
              <table border="0" cellspacing="0" cellpadding="0" bgcolor="#91c741">
                <tr> 
                  <td>
                    <table border="0" cellpadding="2" cellspacing="1">
                      <tr> 
                              <td valign="top" bgcolor="#e2f0c9" class="text9pt" colspan="3"><font color="#336600"><b>「めろんぱらん」購読解除・再購読</b></font></td>
                      </tr>
                      <tr> 
                        <td width="217" valign="top" bgcolor="white" class="text9pt"><font size="2">
<input type="text" name="email" maxlength="50" size="40">
                          </font></td>
                        <td width="230" align="center" valign="middle" bgcolor="white"><font size="2"> 
<?php
	print "<input type=\"radio\" name=\"read_chk[]\" value=\"of$html_letter_id\">\n";
	print "</font><font color=\"#4f4f4f\" size=\"2\">購読解除　　</font><font size=\"2\">";
	print "<input type=\"radio\" name=\"read_chk[]\" value=\"on$html_letter_id\">\n";
	print "</font><font color=\"#4f4f4f\" size=\"2\">再購読</font></td>";
?>
                        <td width="46" bgcolor="white" align="center" valign="middle"><font size="2">
                          <input type="submit" value="送信 ">
                        </font></td>
                      </tr>
		</table>
      </td>
    </tr> 
  </table>
</form>
<form action="../melonpa/mel_reg.php" method="post">
  <table border="0" cellspacing="0" cellpadding="0" bgcolor="#91c741">
    <tr> 
      <td>
	    <table>
                      <tr> 
                        <td valign="top" bgcolor="#e2f0c9" class="text9pt" colspan="3"><font color="#336600"><b>「めろんぱんレター」購読解除・再購読</b></font></td>
                      </tr>
                      <tr> 
                        <td width="217" bgcolor="white" class="text9pt"><font size="2">
<input type="text" name="email" maxlength="50" size="40">

<!--                      <input type="password" name="reader_pswd2" maxlength="30" size="35">  -->

                          </font></td>
                        <td width="230" align="center" valign="middle" bgcolor="white"><font size="2"> 
<?php
	print "<input type=\"radio\" name=\"read_chk[]\" value=\"of$letter_id\">\n";
	print "</font><font color=\"#4f4f4f\" size=\"2\">購読解除　　</font><font size=\"2\">";
	print "<input type=\"radio\" name=\"read_chk[]\" value=\"on$letter_id\">\n";
	print "</font><font color=\"#4f4f4f\" size=\"2\">再購読</font></td>";
?>
                        <td width="46" bgcolor="white" align="center" valign="middle"><font size="2">
                          <input type="submit" name="rmbott0201" value="送信 ">
                          </font></td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
              <br>
            </form>
          </td>
        </tr>
<tr><td>下記の場合、購読・解除できない場合がありますのでご確認ください。<br><br>
　　・余分なスペースが含まれている。<br>
　　・全角文字が含まれている。<br>
　　・大文字と小文字を間違えている。<br>
</td></tr>
        
      </table>
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
<?php draw_footer( ".." ); ?>

      </table>
    </td>
  </tr>
</table>
</body>
<!-- #EndTemplate --></html>
