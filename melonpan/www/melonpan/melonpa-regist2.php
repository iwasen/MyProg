<?php
	include ( "../sub/head.inc");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html><!-- #BeginTemplate "/Templates/index.dwt" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>めろんぱん || よりすぐりメルマガサイト</title>
</head>
<?php	draw_head( ".." );	?>
<table width="753" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"> 
      <table width="620" border="0" cellspacing="0" cellpadding="0" align="center">
        <tr> 
          <td width="21"><!-- #BeginEditable "1" --><img src="img/melonpa1.gif" width="21" height="21"><!-- #EndEditable --></td>
          <td width="21"><!-- #BeginEditable "3" --><img src="img/melonpa3.gif" width="21" height="21"><!-- #EndEditable --></td>
          <td width="557"><!-- #BeginEditable "title1" --><!-- #EndEditable --></td>
          <td width="21">　</td>
        </tr>
        <tr> 
          <td width="21"><!-- #BeginEditable "2" --><img src="img/melonpa2.gif" width="21" height="21"><!-- #EndEditable --></td>
          <td width="21" bgcolor="#90c84b"><!-- #BeginEditable "4" --><img src="img/melonpa4.gif" width="21" height="21"><!-- #EndEditable --></td>
          <td width="557" bgcolor="#90c84b"><!-- #BeginEditable "title2" --><img src="img/toroku.gif" width="129" height="21"><!-- #EndEditable --></td>
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
      <table width="600" border="0" cellspacing="1" cellpadding="5">
        <tr > 
          <td><span class="text01">以下の内容で間違いありませんか？<br>
            登録していただいた内容については責任をもって管理させて頂きます。</span> 
            <p> 
            <form name="rmform31" action="melonpa-regctl.php" method="post">
              <table width="550" border="0" cellspacing="0" cellpadding="0" bgcolor="#91c741">
                <tr> 
                  <td>
                    <table width="550" border="0" cellpadding="2" cellspacing="1">
                      <tr> 
                        <td width="113" valign="top" bgcolor="#e2f0c9" class="text9pt"><font color="#336600">メールアドレス</font></td>
                        <td width="420" bgcolor="white"><?php echo $mail_addr ?></td>
                      </tr>
                      <tr> 
                        <td width="113" valign="top" bgcolor="#e2f0c9" class="text9pt"><font color="#336600">パスワード</font></td>
                        <td width="420" bgcolor="white"><?php echo $reader_pswd ?></td>
                      </tr>
                      <tr> 
                        <td width="113" valign="top" bgcolor="#e2f0c9" class="text9pt"><font color="#336600">生年月日</font></td>
                        <td width="420" bgcolor="white"><?php echo  $wk_year . "年" . $wk_month . "月" . $wk_day . "日" ?></td>
                      </tr>
                      <tr> 
                        <td width="113" bgcolor="#e2f0c9" class="text9pt"><font color="#336600">性別</font></td>
                        <td width="420" bgcolor="white"><? echo pub_mm_sex ("$reader_sex"); ?> </td>
                      </tr>
                      <tr> 
                        <td width="113" bgcolor="#e2f0c9" class="text9pt"><font color="#336600">未既婚</font></td>
                        <td width="420" bgcolor="white"><? echo pub_mm_mrg("$reader_mrg"); ?></td>
                      </tr>
                      <tr> 
                        <td width="113" valign="top" bgcolor="#e2f0c9" class="text9pt"><font color="#336600">郵便番号</font></td>
                        <td width="420" bgcolor="white"><?php echo $reader_zip1 . "-" . $reader_zip2 ?></td>
                      </tr>
                      <tr> 
                        <td width="113" bgcolor="#e2f0c9" class="text9pt"><font color="#336600">職業</font></td>
                        <td width="420" bgcolor="white"><? check_occup_box($reader_occup); ?> 
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
              <br>
              <table width="100%" border="0" cellspacing="0">
                <tr> 
                  <td> 
                    <hr width="550" align="left">
                    <input type="submit" name="rmbott0301" value="　登　録　">
                    　 
                    <input type="button" name="rmbott0302" value="　戻　る　" onClick="history.back()">
                    <p>　 
                  </td>
                </tr>
              </table>

<input type="hidden" value="<? echo $reader_id ?>" name="reader_id">
<input type="hidden" value="<? echo $mail_addr ?>" name="mail_addr">
<input type="hidden" value="<? echo $reader_pswd ?>" name="reader_pswd">
<input type="hidden" value="<? echo $reader_birth ?>" name="reader_birth">
<input type="hidden" value="<? echo $reader_sex ?>" name="reader_sex">
<input type="hidden" value="<? echo $reader_mrg ?>" name="reader_mrg">
<input type="hidden" value="<? echo $reader_zip1 ?>" name="reader_zip1">
<input type="hidden" value="<? echo $reader_zip2 ?>" name="reader_zip2">
<input type="hidden" value="<? echo $reader_occup ?>" name="reader_occup">
<!--
              <input type="hidden" name="reader_id">
              <input type="hidden" value="hidaka@portbellow.co.jp" name="mail_addr">
              <input type="hidden" value="7010" name="reader_pswd">
              <input type="hidden" value="1974/7/2" name="reader_birth">
              <input type="hidden" value="1" name="reader_sex">
              <input type="hidden" value="1" name="reader_mrg">
              <input type="hidden" value="150" name="reader_zip1">
              <input type="hidden" value="0044" name="reader_zip2">
              <input type="hidden" value="07001" name="reader_occup">
-->
            </form>
          </td>
        </tr>
      </table>
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
