<?php
	include ( "../sub/head.inc");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html><!-- #BeginTemplate "/Templates/index.dwt" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>����Ѥ� || ��ꤹ������ޥ�������</title>
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
          <td width="21">��</td>
        </tr>
        <tr> 
          <td width="21"><!-- #BeginEditable "2" --><img src="img/melonpa2.gif" width="21" height="21"><!-- #EndEditable --></td>
          <td width="21" bgcolor="#90c84b"><!-- #BeginEditable "4" --><img src="img/melonpa4.gif" width="21" height="21"><!-- #EndEditable --></td>
          <td width="557" bgcolor="#90c84b"><!-- #BeginEditable "title2" --><img src="img/toroku.gif" width="129" height="21"><!-- #EndEditable --></td>
          <td width="21"><img src="../common/c_rihgt_top.gif" width="21" height="21"></td>
        </tr>
        <tr bgcolor="#ffffd3"> 
          <td colspan="4" bgcolor="#ffffd3" height="21"><!-- #BeginEditable "sub" -->
      <p>��</p>
      <p>��</p>
      <!-- #EndEditable --></td>
        </tr>
        <tr> 
          <td width="21" bgcolor="#ffffd3">��</td>
          <td width="21" bgcolor="#ffffd3">��</td>
          <td width="557" bgcolor="#ffffd3"><!-- #BeginEditable "%90%7D" --> 
      <table width="513" border="0" cellspacing="1" cellpadding="5">
        <tr> 
          <td>���ʤ��θĿ;���򤪤����ͤ��ޤ���<br>
            �ʤ������������Ϥ��줿�Ŀ;��������<font color="#990000"><b>�����</b></font>�Ǥ��Τǡ��¿����Ƥ���������������<br>
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
                        <td width="113" valign="top" bgcolor="#e2f0c9" class="text9pt"><font color="#336600">�᡼�륢�ɥ쥹</font></td>
                        <td width="420" bgcolor="white"><font size="2">
                          <input type="text" value="<? echo $mail_addr ?>" name="mail_addr" size="40">
                          <br>
                          <span class="text01">��ǧ�Τ��ᡢ�⤦���٥᡼�륢�ɥ쥹�����Ϥ��Ƥ���������</span><br>
                          <input type="text" <? echo $w_mail_addr ?> name="w_mail_addr" size="40">
                          <br>
                          <span class="text9pt">��Ⱦ�ѱѿ������Ϥ��Ƥ��������� </span></font></td>
                      </tr>
                      <tr> 
                        <td width="113" valign="top" bgcolor="#e2f0c9" class="text9pt"><font color="#336600">�ѥ����</font></td>
                        <td width="420" bgcolor="white"><font size="2">
                          <input type="password" name="reader_pswd" maxlength=8 size=24>
                          <br>
                          <span class="text9pt">��Ⱦ�ѱѿ���4ʸ���ʾ�8ʸ������ǵ������Ƥ��������� </span></font></td>
                      </tr>
                      <tr> 
                        <td width="113" valign="top" bgcolor="#e2f0c9" class="text9pt"><font color="#336600">��ǯ����</font></td>
                        <td width="420" bgcolor="white"><font size="2">
                          <input type="text" " name="wk_year" maxlength="4" size="4">
                          ǯ 
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
                          �� 
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
                          �� <br>
                          <span class="text9pt">�������Ⱦ�ѿ��������Ϥ��Ƥ������������㡧1975ǯ </span></font></td>
                      </tr>
                      <tr> 
                        <td width="113" bgcolor="#e2f0c9" class="text9pt"><font color="#336600">����</font></td>
                        <td width="420" bgcolor="white"><font size="2">
                          <input type="radio" name="reader_sex" value="1" checked>
                          ���� 
                          <input type="radio" name="reader_sex" value="2">
                          ���� </font></td>
                      </tr>
                      <tr> 
                        <td width="113" bgcolor="#e2f0c9" class="text9pt"><font color="#336600">̤����</font></td>
                        <td width="420" bgcolor="white"><font size="2">
                          <input type="radio" name="reader_mrg" value="1" checked>
                          �ȿ� 
                          <input type="radio" name="reader_mrg" value="2">
                          ���� </font></td>
                      </tr>
                      <tr> 
                        <td width="113" valign="top" bgcolor="#e2f0c9" class="text9pt"><font color="#336600">͹���ֹ�</font></td>
                        <td width="420" bgcolor="white"><font size="2">
                          <input type="text" name="reader_zip1" size="5" maxlength="3">
                          - 
                          <input type="text" name="reader_zip2" size="7" maxlength="4">
                          <br>
                          <span class="text9pt">��Ⱦ�ѿ��������������Ϥ��Ƥ������������㡧123-4567 
                          <br>
                          ���߳����ˤ����ޤ������ϡ�000-0000�٤����Ϥ��Ƥ�������</span></font></td>
                      </tr>
                      <tr> 
                        <td width="113" bgcolor="#e2f0c9" class="text9pt"><font color="#336600">����</font></td>
                        <td width="420" bgcolor="white">
                          <select name="reader_occup" size="1">
                            <option selected>���򤷤Ƥ������� 
                            <option value="01001">������ 
                            <option value="02001">��Ұ� 
                            <option value="03001">��̳�� 
                            <option value="04001">���Ķ� 
                            <option value="05001">���� 
                            <option value="06001">���翦���۸�Ρ���׻Ρ���դʤɡ� 
                            <option value="07001">���翦��SOHO���ǥ����ʡ������ꥨ�������ʤɡ� 
                            <option value="08001">���� 
                            <option value="09001">��ȼ��ء����סʥѡ��ȡ�DO����ޤ�� 
                            <option value="10001">̵�� 
                            <option value="11001">����Х��ȡ�����¾ 
                          </select>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
              <br>
              <span class="text01">����ǽ�λ�Ǥ����ʾ�Ǥ������С�[��Ͽ��ǧ]�ܥ���򲡤��Ƥ���������</span> 
              <hr>
              <input type="submit" name="rmbott0201" value=" ��Ͽ��ǧ ">
              <br>
              <br>
              <br>
            </form>
          </td>
        </tr>
      </table>
      <!-- #EndEditable --></td>
          <td width="21" bgcolor="#ffffd3">��</td>
        </tr>
        <tr> 
          <td width="21"><img src="../common/c_left_bottom.gif" width="21" height="21"></td>
          <td width="21" bgcolor="#90c84b">��</td>
          <td width="557" bgcolor="#90c84b">��</td>
          <td width="21"><img src="../common/c_right_bottom.gif" width="21" height="21"></td>
        </tr>
<?php draw_footer( ".." ); ?>
      </table>
    </td>
  </tr>
</table>
</body>
<!-- #EndTemplate --></html>
