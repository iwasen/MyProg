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
<title>����Ѥ󡡥��ɥ��å����̴��</title>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<script language="JavaScript">
<!--
	function prediction(wURL){
			window.open(wURL,'Prediction','width=370,height=500,scrollbars=yes,resizable=no');
			}
//-->
</script>
<style type="text/css">
<!--
.maincontents {  background: #B0D8FF}
.question { font-size: 12px; line-height: 135%; font-weight: bold; color: #FFFFFF; background: #0033CC}
.name {  font-size: 12px; line-height: 135%; color: #000000; background: #FFFFFF}
.answer {  font-size: 12px; line-height: 135%; font-weight: bold; color: #000000; background: #FFFFFF}
h1 {  font-size: 12px; line-height: 135%; font-weight: bold; color: #FFFFFF}
.contents {  font-size: 12px; line-height: 135%; color: #333333}
.copyright {  font-size: 10px; line-height: 125%; color: #666666}
.text12 {  font-size: 12px; line-height: 135%; color: #000000}
-->
</style>
</head>

<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?php draw_head("../..");	?>
<table width="750" border="0" cellspacing="0" cellpadding="0">
<tr> 
<td width="50"><img src="img/spacer.gif" width="50" height="25"></td>
<td width="700">��</td>
</tr>
<tr> 
<td width="50">��</td>
<td width="700"> 
<div align="center"><img src="img/head.gif" width="398" height="65" alt="���ޥ�ȯ�ԼԤ����ܤ�����ɥ��åץ��å���2002"></div>
</td>
</tr>
<tr> 
<td width="50">��</td>
<td width="700">��</td>
</tr>
<tr>
<td width="50">��</td>
<td width="700">
      <table width="556" border="0" cellspacing="0" cellpadding="0" align="center">
        <tr>
          <td class="text12"> 
            <p align="center"><b>��ǯ�Υ��ɥ��åץ��å��������ܤǳ��Ť���뤳�Ȥ⤢�ä�����夬�äƤ��ޤ��͡�<br>
              �����Ǥ���Ѥ�Ǥϡ֥��å����ϥ��ޥ��פ�ȯ�Ԥ��Ƥ�����ޥ���ȯ�ԼԤ��󤿤���<br>
              �����θ��ꡢ���ä���ʹ���Ƥߤޤ�����<br>
              ���ܤη�̤ϡ�����������</b></p>
            </td>
</tr>
</table>
</td>
</tr>
</table>
<table width="750" border="0" cellspacing="0" cellpadding="0">
<tr> 
<td width="50"><img src="img/spacer.gif" width="50" height="25"></td>
<td width="700">��</td>
</tr>
<tr> 
<td width="50">��</td>
<td width="700"> 
<table width="700" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="340" class="maincontents"> <img src="img/spacer.gif" width="1" height="20"><br>
<table width="300" border="0" cellspacing="0" cellpadding="0" align="center" class="question">
<tr>
<td width="256"> <img src="img/ball.gif" width="22" height="21" align="absmiddle" vspace="2" hspace="2"><font color="#FFFFFF"><b>���ܤ����ܿ�����ϡ�</b></font></td>
<td width="44"><a href="javascript:prediction('player_domestic.html')"><img src="img/reason.gif" width="40" height="18" border="0" alt="��ͳ���Τꤿ����" hspace="2"></a></td>
</tr>
</table>
<table width="300" border="0" cellspacing="0" cellpadding="0" align="center">
<tr> 
                <td width="125" class="name">��fp����<br>
</td>
<td width="175" class="answer" valign="middle"> 
<div align="center">�����</div>
</td>
</tr>
<tr> 
                <td width="125" class="name">��akinori����<br>
</td>
<td width="175" class="answer" valign="middle"> 
<div align="center">������ʹ</div>
</td>
</tr>
<tr> 
                <td width="125" class="name">������å�����<br>
</td>
<td width="175" class="answer" valign="middle"> 
<div align="center">�����</div>
</td>
</tr>
<tr> 
                <td width="125" class="name">�������ڽ�������Ĺ����<br>
</td>
<td width="175" class="answer" valign="middle"> 
<div align="center">����</div>
</td>
</tr>
</table>
<p align="center"><img src="img/worldcup_1.gif" width="84" height="63"></p>
<table width="300" border="0" cellspacing="0" cellpadding="0" align="center" class="question">
<tr> 
<td width="256"> <img src="img/ball.gif" width="22" height="21" align="absmiddle" vspace="2" hspace="2"><font color="#FFFFFF"><b>���ܤγ��������ϡ�</b></font></td>
<td width="44"><a href="javascript:prediction('player.html')"><img src="img/reason.gif" width="40" height="18" border="0" alt="��ͳ���Τꤿ����" hspace="2"></a></td>
</tr>
</table>
<table width="300" border="0" cellspacing="0" cellpadding="0" align="center">
<tr> 
                <td width="125" class="name">��fp����<br>
</td>
<td width="175" class="answer" valign="middle"> 
<div align="center">���ͥǥ����̡�������</div>
</td>
</tr>
<tr> 
                <td width="125" class="name">��akinori����<br>
</td>
<td width="175" class="answer" valign="middle"> 
<div align="center">�륤�����ե�����</div>
</td>
</tr>
<tr> 
                <td width="125" class="name">������å�����<br>
</td>
<td width="175" class="answer" valign="middle"> 
<div align="center">����С���������</div>
</td>
</tr>
<tr> 
                <td width="125" class="name">�������ڽ�������Ĺ����<br>
</td>
<td width="175" class="answer" valign="middle"> 
<div align="center">��٥�ȡ��Хå���</div>
</td>
</tr>
</table>
<p>��</p><table width="300" border="0" cellspacing="0" cellpadding="0" align="center" class="question">
<tr> 
<td width="256"> <img src="img/ball.gif" width="22" height="21" align="absmiddle" vspace="2" hspace="2"><font color="#FFFFFF"><b>���ܥ�����ϡ�</b></font></td>
<td width="44"><a href="javascript:prediction('team.html')"><img src="img/reason.gif" width="40" height="18" border="0" alt="��ͳ���Τꤿ����" hspace="2"></a></td>
</tr>
</table>
<table width="300" border="0" cellspacing="0" cellpadding="0" align="center">
<tr> 
                <td width="125" class="name">��fp����<br>
</td>
<td width="175" class="answer" valign="middle"> 
<div align="center">�ݥ�ȥ���</div>
</td>
</tr>
<tr> 
                <td width="125" class="name">��akinori����<br>
</td>
<td width="175" class="answer" valign="middle"> 
<div align="center">���를�����</div>
</td>
</tr>
<tr> 
                <td width="125" class="name">������å�����<br>
</td>
<td width="175" class="answer" valign="middle"> 
<div align="center">�ݥ�ȥ���</div>
</td>
</tr>
<tr> 
                <td width="125" class="name">�������ڽ�������Ĺ����<br>
</td>
<td width="175" class="answer" valign="middle"> 
<div align="center">���를�����</div>
</td>
</tr>
</table>
<p align="center"><img src="img/worldcup_2.gif" width="144" height="105"></p>
</td>
          <td width="21">��</td>
          <td width="339" class="maincontents"> 
            <p align="center"><img src="img/worldcup_3.gif" width="220" height="125"></p>
<table width="300" border="0" cellspacing="0" cellpadding="0" align="center" class="question">
<tr> 
<td width="256"> <img src="img/ball.gif" width="22" height="21" align="absmiddle" vspace="2" hspace="2"><font color="#FFFFFF"><b>�辡�˻Ĥ������ϡ�ͥ���ϡ�</b></font></td>
<td width="44"><a href="javascript:prediction('finals.html')"><img src="img/reason.gif" width="40" height="18" border="0" alt="��ͳ���Τꤿ����" hspace="2"></a></td>
</tr>
</table>
<table width="300" border="0" cellspacing="0" cellpadding="0" align="center">
<tr> 
<td width="130" class="name">��</td>
<td width="85" class="answer" valign="middle"> 
<div align="center">��ͥ����</div>
</td>
<td width="85" class="answer" valign="middle"> 
<div align="center">�ڽ�ͥ����</div>
</td>
</tr>
<tr> 
                <td width="130" class="name">��fp����<br>
</td>
<td width="85" class="answer" valign="middle" bgcolor="#FFFF33"> 
<div align="center">�֥饸��</div>
</td>
<td width="85" class="answer" valign="middle"> 
<div align="center">�����ꥢ</div>
</td>
</tr>
<tr> 
                <td width="130" class="name">��akinori����<br>
</td>
<td width="85" class="answer" valign="middle" bgcolor="#FFFF33"> 
<div align="center">���를�����</div>
</td>
<td width="85" class="answer" valign="middle"> 
<div align="center">�ݥ�ȥ���</div>
</td>
</tr>
<tr> 
                <td width="130" class="name">������å�����<br>
</td>
<td width="85" class="answer" valign="middle" bgcolor="#FFFF33"> 
<div align="center">�����ꥢ</div>
</td>
<td width="85" class="answer" valign="middle"> 
<div align="center">���를�����</div>
</td>
</tr>
<tr> 
                <td width="130" class="name">�������ڽ�������Ĺ����<br>
</td>
<td width="85" class="answer" valign="middle" bgcolor="#FFFF33"> 
<div align="center">�����ꥢ</div>
</td>
<td width="85" class="answer" valign="middle"> 
<div align="center">���를�����</div>
</td>
</tr>
</table>
<p align="center"><img src="img/worldcup_4.gif" width="221" height="125"></p>
<table width="300" border="0" cellspacing="0" cellpadding="0" align="center" class="question">
<tr> 
<td width="256"> <img src="img/ball.gif" width="22" height="21" align="absmiddle" vspace="2" hspace="2"><font color="#FFFFFF"><b>���ܤϷ辡�ȡ��ʥ��Ȥ˿ʤ�롩</b></font></td>
<td width="44"><a href="javascript:prediction('japan.html')"><img src="img/reason.gif" width="40" height="18" border="0" alt="��ͳ���Τꤿ����" hspace="2"></a></td>
</tr>
</table>
<table width="300" border="0" cellspacing="0" cellpadding="0" align="center">
<tr> 
                <td width="125" class="name">��fp����<br>
</td>
<td width="175" class="answer" valign="middle"> 
<div align="center">�ʤ��</div>
</td>
</tr>
<tr> 
                <td width="125" class="name">��akinori����<br>
</td>
<td width="175" class="answer" valign="middle"> 
<div align="center">�ʤ��</div>
</td>
</tr>
<tr> 
                <td width="125" class="name">������å�����<br>
</td>
<td width="175" class="answer" valign="middle"> 
<div align="center">�ʤ��</div>
</td>
</tr>
<tr> 
                <td width="125" class="name">�������ڽ�������Ĺ����<br>
</td>
<td width="175" class="answer" valign="middle"> 
<div align="center">YES��</div>
</td>
</tr>
</table>
<p>��</p>
</td>
</tr>
</table>
</td>
</tr>
</table>
<table width="750" border="0" cellspacing="0" cellpadding="0">
<tr> 
<td width="50"><img src="img/spacer.gif" width="50" height="25"></td>
<td width="700">��</td>
</tr>
<tr> 
    <td width="50" height="103">��</td>
    <td width="700" height="103" bordercolor="#0033CC"> 
      <table width="500" border="1" cellspacing="0" cellpadding="3" align="center" bordercolor="#0033CC">
        <tr> 
          <td colspan="2" class="question"><img src="img/ball.gif" width="22" height="21" vspace="2" hspace="2" align="absmiddle"><font color="#FFFFFF"><b>����ͽ�ۤ򤷤����ޥ�ȯ�ԼԤ���Υ��ޥ��Ϥ����顪</b></font></td>
</tr>
<tr> 
          <td width="250" class="name" height="23">����fp����<br>
            <a href="http://www.melonpan.net/mag.php?000130" target="_blank">���FOOTBALL 
            PARK MAGAZINE</a><br>
</td>
          <td width="250" class="name" height="23">����akinori����<br>
            <a href="http://www.melonpan.net/mag.php?000143" target="_blank">��䤳���饵�å��������</a></td>
</tr>
<tr> 
<td width="250" class="name">��������å�����<br>
            <a href="http://www.melonpan.net/mag.php?000526" target="_blank">�����ɥ��å�����ʹ</a><br>
            <a href="http://www.melonpan.net/mag.php?000683" target="_blank">��䥵�å���������ɽ�������˸����ä�</a><br>
</td>
<td width="250" class="name">���������ڽ�������Ĺ����<br>
            <a href="http://www.melonpan.net/mag.php?002563" target="_blank">��䤪���ڽ����ġ�toto���äȳڤ���Х⡪</a></td>
</tr>
</table>
    </td>
</tr>
<tr>
    <td width="50" height="33">��</td>
    <td width="700" height="33"> 
      <hr size="2" noshade>
</td>
</tr>
</table>
<table width="750" border="0" cellspacing="0" cellpadding="0">
<tr> 
    <td width="50" height="14"><img src="img/spacer.gif" width="50" height="25"></td>
    <td width="700" height="14">��</td>
</tr>
<tr> 
<td width="50">��</td>
    <td width="700">
      <table width="586" border="2" cellspacing="1" cellpadding="0" bordercolor="#0033FF" align="center" class="contents">
        <tr>
          <td height="134"> 
            <table width="594" border="0">
              <tr> 
                <td width="65">�� </td>
                <td width="530"> 
                  <div align="right"><a href="http://www.hitobito.net/" target="_blank"><img src="img/h_88-31.gif" width="88" height="31" border="0" alt="�ҤȤӤȡ�net"></a></div>
                </td>
              </tr>
              <tr> 
                <td colspan="2" height="22"> 
                  <table width="600" border="0" height="24" bgcolor="#99CCFF">
                    <tr> 
                      <td> 
                        <div align="center"><font size="+1"><b>���ɥ��åפˤĤ��ƥ磻�磻���ä����������ϡ�����<a href="http://www.hitobito.net/nw/worldcup.html" target="_blank">������</a></b></font></div>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr> 
                <td height="53" width="64"> 
                  <div align="right"><a href="http://www.hitobito.net/nw/worldcup.html" target="_blank"><img src="img/navi.gif" width="60" height="60" border="0" hspace="2"></a></div>
                </td>
                <td height="53" width="530"><b><font size="-1">�ڥ��ɥ��åפ��ä�������</font></b><font size="-1"><br>
                  �ԣ���Ѥǳڤ���ͤϤ����󡢼ºݥ���������Ǵ��路���ꡢ�ϸ����������Ϥˤʤä���ã����˥��ɥ��åפˤĤ����ä��ޤ��󤫡�<br>
                  ���å����ե꡼�������Ǥʤ�������Υ��ɥ��åפǶ�̣����ä��Ȥ������⡢�ߤʤ��󵤷ڤ˻��ä��Ƥ��������͡� <br>
                  </font> </td>
              </tr>
            </table>
          </td>
</tr>
</table>
</td>
</tr>
</table>
<table width="749" border="0" cellspacing="0" cellpadding="0">
<tr> 
<td width="50"><img src="img/spacer.gif" width="50" height="25"></td>
<td width="699">��</td>
</tr>
<tr> 
<td width="50">��</td>
    <td width="699"> 
      <table width="594" border="2" cellspacing="1" cellpadding="0" bordercolor="#0033FF" align="center" class="contents" height="109">
        <tr> 
          <td>
            <table width="100%" border="0" height="99">
              <tr> 
                <td>
                  <div align="right"><a href="http://www.kikasete.net/" target="_blank"><img src="img/k_88x31.gif" width="88" height="31" border="0" alt="�������ơ�net"></a></div>
                </td>
              </tr>
              <tr> 
                <td>
                    <table width="600" border="0" height="24">
                      <tr>
                        <td bgcolor="#99CCFF"> 
                          <div align="center"><font size="+1"><b>���ɥ��åפ˴ؤ��륢�󥱡��Ȥϡ�����<a href="http://www.kikasete.net/monitor/tw47.php" target="_blank">������</a></b></font></div>
                        </td>
                      </tr>
                    </table>
                </td>
              </tr>
              <tr> 
                <td height="159"> 
                  <table width="550" border="0" cellspacing="5" cellpadding="0" align="center">
                    <tr> 
                      <td colspan="2" height="14"><b><font size="-1">�ѡ������λ��򸫤�ͽ��Ǥ�����</font></b> 
                      </td>
                    </tr>
                    <tr> 
                      <td width="275" class="question"> 
                        <div align="center">�ڡ��������</div>
                      </td>
                      <td width="275" class="question"> 
                        <div align="center">�ڡ�������ʳ�����</div>
                      </td>
                    </tr>
                    <tr> 
                      <td width="275" height="104">
                        <div align="center"><img src="img/graph_1.gif" width="219" height="120" vspace="5"></div>
                      </td>
                      <td width="275" height="104">
                        <div align="center"><img src="img/graph_2.gif" width="263" height="82" vspace="5"></div>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td>
                  <div align="right"><font size="-1">2002ǯ3��»�</font></div>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      
    </td>
</tr>
</table>
<table width="750" border="0" cellspacing="0" cellpadding="0">
<tr> 
<td width="50"><img src="img/spacer.gif" width="50" height="25"></td>
<td width="700">��</td>
</tr>
<tr> 
<td width="50">��</td>
<td class="text12" width="700"> 
<div align="center" class="copyright">��<a href="../../index.php">����Ѥ�</a>�ϡ��ʳ��ˡ������������λ�����ǰ��ȯ����������Ԥ�ٱ礹��פ˴�Ť��Ʊ��Ĥ���Ƥ��ޤ���<br>
<hr noshade width="75%">
Copyright(c)2001-2002 xx xxxxx Inc.  All rights reserved.<br>
<a href="http://www.xxxxxxx.co.jp/guide/xxxxxxx.html" target="_blank"><img src="../../common/do_rogo.gif" width="40" height="40" border="0"></a></div>
</td>
</tr>
</table>
</body>
</html>