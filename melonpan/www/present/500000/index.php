<?php
include("../../sub/head.inc");

/****************************************************************
 Main
****************************************************************/
//$gConn = db_conxxxt();

if ($mail_addr != "") {pass_check($gConn,trim($mail_addr),trim($reader_pswd));}
if ($mail_addr == "") {
	$mail_addr = $cookie_mail_addr;
	$reader_pswd = $cookie_reader_pswd;
	$reader_id = $cookie_reader_id;
}
?>

<html>
<head>
<title>����Ѥ����ɼԤ⤦���������������̥ץ쥼��ȴ��</title>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<style type="text/css">
<!--
.text1 {  font-size: 16px; font-style: normal; line-height: normal; font-weight: bolder}
.bg {  background-image: url(img/bgimg.gif); background-repeat: repeat-y}
-->
</style>
</head>
<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?php draw_head("../..");	?>
<table width="754" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center">
      <table width="620" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="21" height="21"><img src="img/r_left.gif" width="21" height="21"></td>
          <td height="21" bgcolor="#99CC33">��</td>
          <td width="21" height="21"><img src="img/c_rihgt_top.gif" width="21" height="21"></td>
        </tr>
        <tr bgcolor="#FFFFCC"> 
          <td width="21">��</td>
          <td align="center"> 
            <table width="620" border="0" cellspacing="0" cellpadding="0" class="bg">
              <tr> 
                <td align="center">��</td>
              </tr>
              <tr> 
                <td align="center"><img src="img/logo2.gif" width="452" height="83"></td>
              </tr>
              <tr> 
                <td align="center">��</td>
              </tr>
              <tr> 
                <td align="center" class="text1"><font color="#FF6600"><b><font color="#FF0000">�ڥ�������</font></b></font><b>�����ɼԣ�������ã�����Ϥ��ġ��� 
                  </b> </td>
              </tr>
              <tr> 
                <td>��</td>
              </tr>
              <tr> 
                <td align="center"> 
                  <table width="397" border="2" cellspacing="0" cellpadding="5" bordercolor="#99CC33">
                    <tr> 
                      <td> 
                        <table border="0" cellpadding="0" cellspacing="0" width="327">
                          <!-- fwtable fwsrc="̾��̤����" fwbase="present.gif" fwstyle="Dreamweaver" fwdocid = "742308039" fwnested="0" -->
                          <tr> 
                            <td><img src="img/spacer.gif" width="92" height="1" border="0"></td>
                            <td><img src="img/spacer.gif" width="69" height="1" border="0"></td>
                            <td><img src="img/spacer.gif" width="166" height="1" border="0"></td>
                            <td><img src="img/spacer.gif" width="1" height="1" border="0"></td>
                          </tr>
                          <tr> 
                            <td><img name="present_r1_c1" src="img/present_r1_c1.jpg" width="92" height="81" border="0"></td>
                            <td><img name="present_r1_c2" src="img/present_r1_c2.jpg" width="69" height="81" border="0"></td>
                            <td valign="top"><img src="img/coment.gif" width="222" height="72"></td>
                            <td><img src="img/spacer.gif" width="1" height="81" border="0"></td>
                          </tr>
                          <tr> 
                            <td><img name="present_r2_c1" src="img/present_r2_c1.gif" width="92" height="78" border="0"></td>
                            <td colspan="2"><img name="present_r2_c2" src="img/present_r2_c2.jpg" width="235" height="78" border="0"></td>
                            <td><img src="img/spacer.gif" width="1" height="78" border="0"></td>
                          </tr>
                          <tr> 
                            <td>��</td>
                            <td colspan="2"><img name="present_r3_c2" src="img/present_r3_c2.jpg" width="235" height="153" border="0"></td>
                            <td><img src="img/spacer.gif" width="1" height="153" border="0"></td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr> 
                <td>��</td>
              </tr>
              <tr>
                <td align="center">
                  <table width="600" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
                    <tr> 
                      <td width="84" valign="top"> 
                        <div align="center"><b>����</b></div>
                      </td>
                      <td width="516">������Ѥ����ɼԤ��������ͤ����ã����������ͽ�ۤ��Ƥ���������<br>
                        ���ҥ�Ȣ����ߤ����ɼԿ���<a href="http://www.melonpan.net/" target="_blank">�ȥåץڡ���</a>�����ɽ������Ƥ��ޤ��� 
                      </td>
                    </tr>
                    <tr> 
                      <td width="84" height="12"> 
                        <div align="center"></div>
                      </td>
                      <td width="516" height="12">��</td>
                    </tr>
                    <tr> 
                      <td width="84" height="27" valign="top"> 
                        <div align="center"><b>������ˡ</b></div>
                      </td>
                      <td width="516" height="27">����ã��ͽ�����פȡ�Ϣ����᡼�륢�ɥ쥹�פ�񤤤�<br>
                        ��<a href="mailto:present@melonpan.net">
                        present@melonpan.net</a>�إ᡼������꤯��������<br>
                        �������Ԥ����ˤϡ������᡼��Ǿ��ʤ��Ϥ����Ǥ��ޤ���</td>
                    </tr>
                    <tr> 
                      <td width="84"> 
                        <div align="center"></div>
                      </td>
                      <td width="516">��</td>
                    </tr>
                    <tr> 
                      <td width="84"> 
                        <div align="center"><b>����</b></div>
                      </td>
                      <td width="516">����������ʶ���桡<b><font color="#FF0000">��������֤Ͻ�λ�������ޤ����������礢�꤬�Ȥ��������ޤ�����</font></b></td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr> 
                <td align="center">�� </td>
              </tr>
            </table>
          </td>
          <td width="21">��</td>
        </tr>
        <tr> 
          <td width="21" height="21"><img src="img/c_left_bottom.gif" width="21" height="21"></td>
          <td height="21" bgcolor="#99CC33">��</td>
          <td width="21" height="21"><img src="img/c_right_bottom.gif" width="21" height="21"></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<p>
<table width="750" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="25"><img src="img/spacer.gif" width="25" height="30"></td>
    <td>��</td>
  </tr>
  <tr> 
    <td width="25">��</td>
    <td class="text12"> 
      <div align="center">��<a href="../../index.php">����Ѥ�</a>�ϡ��ʳ��ˡ������������λ�����ǰ��ȯ����������Ԥ�ٱ礹��פ˴�Ť��Ʊ��Ĥ���Ƥ��ޤ���</div>
    </td>
  </tr>
  <tr> 
    <td width="25">��</td>
    <td class="comment"> 
      <hr noshade width="75%">
    </td>
  </tr>
  <tr>
    <td width="25">��</td>
    <td class="text12">
      <div align="center">Copyright(c)2002 xx xxxxx Inc.  All rights reserved.<br>
        <a href="http://www.xxxxxxx.co.jp/guide/xxxxxxx.html" target="_blank"><img src="../../common/do_rogo.gif" width="40" height="40" border="0"></a></div>
    </td>
  </tr>
</table>

</body>
</html>
