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
      <table width="513" border="0" cellspacing="1" cellpadding="5">
        <tr> 
          <td>あなたの個人情報をおたずねします。<br>
            なお、ここに入力された個人情報は全て<font color="#990000"><b>非公開</b></font>ですので、安心してご記入ください。<br>
            <br>
<!--
<form name="rmform21" action="../melonpa/MelonpanDef.php" method="post">
-->
<form name="rmform21" action="melonpa-regctl.php" method="post">
              <table border="0" cellspacing="0" cellpadding="0" bgcolor="#91c741">
                <tr> 
                  <td>
                    <table width="545" border="0" cellpadding="2" cellspacing="1">
                      <tr> 
                        <td width="113" valign="top" bgcolor="#e2f0c9" class="text9pt"><font color="#336600">メールアドレス</font></td>
                        <td width="420" bgcolor="white"><font size="2">
                          <input type="text" value="<? echo $mail_addr ?>" name="mail_addr" size="40">
                          <br>
                          <span class="text01">確認のため、もう一度メールアドレスを入力してください。</span><br>
                          <input type="text" <? echo $w_mail_addr ?> name="w_mail_addr" size="40">
                          <br>
                          <span class="text9pt">※半角英数で入力してください。 </span></font></td>
                      </tr>
                      <tr> 
                        <td width="113" valign="top" bgcolor="#e2f0c9" class="text9pt"><font color="#336600">パスワード</font></td>
                        <td width="420" bgcolor="white"><font size="2">
                          <input type="password" name="reader_pswd" maxlength=8 size=24>
                          <br>
                          <span class="text9pt">※半角英数、4文字以上8文字以内で記入してください。 </span></font></td>
                      </tr>
                      <tr> 
                        <td width="113" valign="top" bgcolor="#e2f0c9" class="text9pt"><font color="#336600">生年月日</font></td>
                        <td width="420" bgcolor="white"><font size="2">
                          <input type="text" " name="wk_year" maxlength="4" size="4">
                          年 
                          <select name="wk_month" size="1">
                            <option value="--">-- 
                            <option value="1">1 
                            <option value="2">2 
                            <option value="3">3 
                            <option value="4">4 
                            <option value="5">5 
                            <option value="6">6 
                            <option value="7">7 
                            <option value="8">8 
                            <option value="9">9 
                            <option value="10">10 
                            <option value="11">11 
                            <option value="12">12 
                          </select>
                          月 
                          <select name="wk_day" size="1">
                            <option value="--">-- 
                            <option value="1">1 
                            <option value="2">2 
                            <option value="3">3 
                            <option value="4">4 
                            <option value="5">5 
                            <option value="6">6 
                            <option value="7">7 
                            <option value="8">8 
                            <option value="9">9 
                            <option value="10">10 
                            <option value="11">11 
                            <option value="12">12 
                            <option value="13">13 
                            <option value="14">14 
                            <option value="15">15 
                            <option value="16">16 
                            <option value="17">17 
                            <option value="18">18 
                            <option value="19">19 
                            <option value="20">20 
                            <option value="21">21 
                            <option value="22">22 
                            <option value="23">23 
                            <option value="24">24 
                            <option value="25">25 
                            <option value="26">26 
                            <option value="27">27 
                            <option value="28">28 
                            <option value="29">29 
                            <option value="30">30 
                            <option value="31">31 
                          </select>
                          日 <br>
                          <span class="text9pt">※西暦は半角数字で入力してください。　例：1975年 </span></font></td>
                      </tr>
                      <tr> 
                        <td width="113" bgcolor="#e2f0c9" class="text9pt"><font color="#336600">性別</font></td>
                        <td width="420" bgcolor="white"><font size="2">
                          <input type="radio" name="reader_sex" value="1" checked>
                          男性 
                          <input type="radio" name="reader_sex" value="2">
                          女性 </font></td>
                      </tr>
                      <tr> 
                        <td width="113" bgcolor="#e2f0c9" class="text9pt"><font color="#336600">未既婚</font></td>
                        <td width="420" bgcolor="white"><font size="2">
                          <input type="radio" name="reader_mrg" value="1" checked>
                          独身 
                          <input type="radio" name="reader_mrg" value="2">
                          既婚 </font></td>
                      </tr>
                      <tr> 
                        <td width="113" valign="top" bgcolor="#e2f0c9" class="text9pt"><font color="#336600">郵便番号</font></td>
                        <td width="420" bgcolor="white"><font size="2">
                          <input type="text" name="reader_zip1" size="5" maxlength="3">
                          - 
                          <input type="text" name="reader_zip2" size="7" maxlength="4">
                          <br>
                          <span class="text9pt">※半角数字７ケタで入力してください。　例：123-4567 
                          <br>
                          現在海外にお住まいの方は『000-0000』と入力してください</span></font></td>
                      </tr>
                      <tr> 
                        <td width="113" bgcolor="#e2f0c9" class="text9pt"><font color="#336600">職業</font></td>
                        <td width="420" bgcolor="white">
                          <select name="reader_occup" size="1">
                            <option selected>選択してください 
                            <option value="01001">会社役員 
                            <option value="02001">会社員 
                            <option value="03001">公務員 
                            <option value="04001">自営業 
                            <option value="05001">教員 
                            <option value="06001">専門職（弁護士・会計士・医師など） 
                            <option value="07001">専門職（SOHO・デザイナー・クリエイターなど） 
                            <option value="08001">学生 
                            <option value="09001">専業主婦・主夫（パート・DOさん含む） 
                            <option value="10001">無職 
                            <option value="11001">アルバイト・その他 
                          </select>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
              <br>
              <span class="text01">これで終了です。以上でよろしければ、[登録確認]ボタンを押してください。</span> 
              <hr>
              <input type="submit" name="rmbott0201" value=" 登録確認 ">
              <br>
              <br>
              <br>
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
