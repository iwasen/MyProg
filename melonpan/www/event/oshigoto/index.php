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
<title>�錄���Τ��Ż������á�����졼Ϣ�ܥ����ʡ�</title>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<style type="text/css">
<!--
.text12 {  font-size: 12px; line-height: 125%}
.text10 {  font-size: 10px; line-height: 125%}
.syokugyou {  font-size: 12px; line-height: 125%; background: #CEDBFD; font-weight: bold}
.mailtitle {  font-size: 12px; line-height: 125%; background: #ACACFF}
.mailex {  font-size: 12px; line-height: 125%; background: #FFFFFF}
.read {  font-size: 12px; line-height: 125%; background: #CEDBFD}
-->
</style>
<script language="javascript">
<!--
function subWin(occupation){
	window.open(occupation,'colum','width=420,height=450,resizable=yes,scrollbars=yes');
	}
//-->
</script>
</head>

<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?php draw_head("../..");	?>
<p>
<table width="750" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="50"><img src="img/spacer.gif" width="50" height="1"></td>
    <td width="700"> 
      <div align="center"><img src="img/title.gif" width="323" height="79" alt="��Τ��Ż���������"></div>
    </td>
  </tr>
</table>
<p>
<table width="750" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="118"><img src="img/spacer.gif" width="100" height="1"></td>
    <td width="531" class="text12"> 
      <div align="center"><b>�ߤʤ��󤬹��ɤ��Ƥ�����ޥ���ȯ�ԼԤ����<br>
        �¤ϡ������ʤ��顩�ˤ��Ż��Τ����ޤ�˥�ä�ȯ�Ԥ��Ƥ�������¿���ΤǤ���<br><br>
        ���Υ����ʡ��ϡ������ȯ�ԼԤ��󤿤��ˤ��<br>
        �� �錄���Τ��Ż������á��� �� �򡢥�졼Ϣ�ܤǤ��Ϥ����ޤ���</b></div>
    </td>
    <td width="101"><img src="img/spacer.gif" width="100" height="1"></td>
  </tr>
</table>
<p>
<table width="750" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="50"><img src="img/spacer.gif" width="50" height="1"></td>
    <td width="700"> 
	<form action="http://www.melonpan.net/melonpa/register.php" method="post">
        <table width="300" border="0" cellspacing="0" cellpadding="5" align="right">
          <tr>
          <td bgcolor="#669933">
              <div align="center"><font color="#ffffff"><b><span class="text12">����Ϣ�ܥ����ʡ����ڤ����<br>�ؤ���Ѥ�쥿���٤Τ����ɤϤ����餫��</span></b> 
                </font></div>
          </td>
        </tr>
        <tr>
          <td bgcolor="#fff9c2">
            <div align="center"><font color="#006600" class="text12">E-mail</font> 
              <input type="hidden" name="mag_id" value="000049">
              <input type="text" size="22" name="email">
              <input type="submit" value="����" name="regist">
            </div>
			
          </td>
        </tr>
      </table>
	  </form>
    </td>
  </tr>
</table>
<p>
<table width="750" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="50"><img src="img/spacer.gif" width="50" height="1"></td>
    <td width="21"><img src="img/round_1.gif" width="21" height="21"></td>
    <td width="658" bgcolor="#99CC66">��</td>
    <td width="21"><img src="img/round_2.gif" width="21" height="21"></td>
  </tr>
  <tr> 
    <td width="50">��</td>
    <td width="21" bgcolor="#ffffc0">��</td>
    <td width="658" bgcolor="#ffffc0">��</td>
    <td width="21" bgcolor="#ffffc0">��</td>
  </tr>
  <tr> 
    <td width="50">��</td>
    <td width="21" bgcolor="#ffffc0">��</td>
    <td width="658" bgcolor="#ffffc0"> 
      <table width="658" border="0" cellspacing="0" cellpadding="5">
        <tr> 
          <td width="208" class="syokugyou"> 
            <div align="center">���Ż�������Ϣ�ܵ���</div>
          </td>
          <td width="450" class="mailtitle"> 
            <div align="center"><b>���ε����Υ饤�������󤬽񤤤Ƥ�����ޥ�</b></div>
          </td>
        </tr>
      </table>
    </td>
    <td width="21" bgcolor="#ffffc0">��</td>
  </tr>
  <tr> 
    <td width="50">��</td>
    <td width="21" bgcolor="#ffffc0">��</td>
    <td width="658" bgcolor="#ffffc0">��</td>
    <td width="21" bgcolor="#ffffc0">��</td>
  </tr>
  <tr> 
    <td width="50">��</td>
    <td width="21" bgcolor="#ffffc0">��</td>
    <td width="658" bgcolor="#ffffc0"> 
      <table width="658" border="0" cellspacing="0" cellpadding="5">
        <tr> 
          <td width="208" class="syokugyou"> 
            <div align="center">���ʥ��󥵡�</div>
          </td>
          <td width="450" class="mailtitle"> <a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=001872" target="_blank"><img src="img/mag.gif" width="32" height="24" border="0" align="absmiddle" hspace="5" vspace="0">�������ʥ��󥵡����衡���勵���ξ�������</a></td>
        </tr>
        <tr> 
          <td width="208" class="read"> 
            <div align="center"><a href="javascript:subWin('relay01.php')">Ϣ�ܤ��ɤ�</a></div>
          </td>
          <td width="450" class="mailex">����ζɥ��ʤޤ뤬���ʥ��󥵡������ʤ������Ҳ�<br>���ʥ��󥵡����ܻؤ�����Ʊ�ȼԤ�����ɤ����� 
          </td>
        </tr>
      </table>
    </td>
    <td width="21" bgcolor="#ffffc0">��</td>
  </tr>
  <tr> 
    <td width="50" height="14">��</td>
    <td width="21" bgcolor="#ffffc0" height="14">��</td>
    <td width="658" bgcolor="#ffffc0" height="14">��</td>
    <td width="21" bgcolor="#ffffc0" height="14">��</td>
  </tr>
  <tr> 
    <td width="50">��</td>
    <td width="21" bgcolor="#ffffc0">��</td>
    <td width="658" bgcolor="#ffffc0"> 
      <table width="658" border="0" cellspacing="0" cellpadding="5">
        <tr> 
          <td width="208" class="syokugyou"> 
            <div align="center">���ԡ��饤����</div>
          </td>
          <td width="450" class="mailtitle"> <a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=000691" target="_blank"><img src="img/mag.gif" width="32" height="24" border="0" align="absmiddle" hspace="5" vspace="0"></a><a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=000691" target="_blank">���ꥨ�����������ޥ������zekt��</a></td>
        </tr>
        <tr> 
          <td width="208" class="read"> 
            <div align="center"><a href="javascript:subWin('relay03.php')">Ϣ�ܤ��ɤ�</a></div>
          </td>
          <td width="450" class="mailex">���ꥨ�����������ޥ������zekt�פϡ��ץ��Ĥ֤䤯�᡼��ޥ�����Ǥ���<br>�轵�������ۿ��Ǥ���
          </td>
        </tr>
      </table>
    </td>
    <td width="21" bgcolor="#ffffc0">��</td>
  </tr>
  <tr> 
    <td width="50">��</td>
    <td width="21" bgcolor="#ffffc0">��</td>
    <td width="658" bgcolor="#ffffc0">��</td>
    <td width="21" bgcolor="#ffffc0">��</td>
  </tr>
  <tr> 
    <td width="50">��</td>
    <td width="21" bgcolor="#ffffc0">��</td>
    <td width="658" bgcolor="#ffffc0"> 
      <table width="658" border="0" cellspacing="0" cellpadding="5">
        <tr> 
          <td width="208" class="syokugyou"> 
            <div align="center">�ɣԶȳ�</div>
          </td>
          <td width="450" class="mailtitle"> <a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=000212" target="_blank"><img src="img/mag.gif" width="32" height="24" border="0" align="absmiddle" hspace="5" vspace="0"></a><a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=000212" target="_blank">�����������θ��줫��</a></td>
        </tr>
        <tr> 
          <td width="208" class="read"> 
            <div align="center"><a href="javascript:subWin('relay10.php')">Ϣ�ܤ��ɤ�</a></div>
          </td>
          <td width="450" class="mailex">�������եȥ��󥸥˥�(SE)��������ˤ����ƴ��������Ȥ�񤤤�����ࡣ<br>�ȳ��˴ط�̵������ڤ���ޤ���</td>
        </tr>
      </table>
    </td>
    <td width="21" bgcolor="#ffffc0">��</td>
  </tr>
  <tr> 
    <td width="50">��</td>
    <td width="21" bgcolor="#ffffc0">��</td>
    <td width="658" bgcolor="#ffffc0">��</td>
    <td width="21" bgcolor="#ffffc0">��</td>
  </tr>
  <tr> 
    <td width="50">��</td>
    <td width="21" bgcolor="#ffffc0">��</td>
    <td width="658" bgcolor="#ffffc0"> 
      <table width="658" border="0" cellspacing="0" cellpadding="5">
        <tr> 
          <td width="208" class="syokugyou"> 
            <div align="center">�����󥻥顼</div>
          </td>
          <td width="450" class="mailtitle"> <a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=002087" target="_blank"><img src="img/mag.gif" width="32" height="24" border="0" align="absmiddle" hspace="5" vspace="0"></a><a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=002087" target="_blank">�����󥻥�󥰤Ȥ���̾������</a></td>
        </tr>
        <tr> 
          <td width="208" class="read"> 
            <div align="center"><a href="javascript:subWin('relay02.php')">Ϣ�ܤ��ɤ�</a></div>
          </td>
          <td width="450" class="mailex">�ȼ��Ρ�����ˡ�Ӥ��󾧤��륫���󥻥顼�����ּ��ι⤤���áפȤ����������饫���󥻥�󥰤���ޤ���</td>
        </tr>
      </table>
    </td>
    <td width="21" bgcolor="#ffffc0">��</td>
  </tr>
  <tr> 
    <td width="50">��</td>
    <td width="21" bgcolor="#ffffc0">��</td>
    <td width="658" bgcolor="#ffffc0">��</td>
    <td width="21" bgcolor="#ffffc0">��</td>
  </tr>
  <tr> 
    <td width="50">��</td>
    <td width="21" bgcolor="#ffffc0">��</td>
    <td width="658" bgcolor="#ffffc0"> 
      <table width="658" border="0" cellspacing="0" cellpadding="5">
        <tr> 
          <td width="208" class="syokugyou"> 
            <div align="center">���ձ�Уԣ����</div>
          </td>
          <td width="450" class="mailtitle"> <a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=002368" target="_blank"><img src="img/mag.gif" width="32" height="24" border="0" align="absmiddle" hspace="5" vspace="0"></a><a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=002368" target="_blank">�ޥ�'s���ȣ�</a></td>
        </tr>
        <tr> 
          <td width="208" class="read"> 
            <div align="center"><a href="javascript:subWin('relay04.php')">Ϣ�ܤ��ɤ�</a></div>
          </td>
          <td width="450" class="mailex">�ޥޤκ�äƤ���ȣФ�1�Ĥ�����ǫ�˾Ҳ𤷤Ƥ������ޥ��Ǥ���<br>���ι礦�ޥޤ򸫤Ĥ��Ƥ���������</td>
        </tr>
      </table>
    </td>
    <td width="21" bgcolor="#ffffc0">��</td>
  </tr>
  <tr> 
    <td width="50">��</td>
    <td width="21" bgcolor="#ffffc0">��</td>
    <td width="658" bgcolor="#ffffc0">��</td>
    <td width="21" bgcolor="#ffffc0">��</td>
  </tr>
  <tr> 
    <td width="50">��</td>
    <td width="21" bgcolor="#ffffc0">��</td>
    <td width="658" bgcolor="#ffffc0"> 
      <table width="658" border="0" cellspacing="0" cellpadding="5">
        <tr> 
          <td width="208" class="syokugyou"> 
            <div align="center">��������</div>
          </td>
          <td width="450" class="mailtitle"> <a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=000866" target="_blank"><img src="img/mag.gif" width="32" height="24" border="0" align="absmiddle" hspace="5" vspace="0"></a><a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=000866" target="_blank">�����콸-pc��</a></td>
        </tr>
        <tr> 
          <td width="208" class="read"> 
            <div align="center"><a href="javascript:subWin('relay05.php')">Ϣ�ܤ��ɤ�</a></div>
           </td>
          <td width="450" class="mailex">�ǰ������ͤϡ��錄���Τ��Ȥ�˺��Ƥ�����������������˨�¤ϡ�����ï�����˺����Ƥ��ޤäơ�!?</td>
        </tr>
      </table>
    </td>
    <td width="21" bgcolor="#ffffc0">��</td>
  </tr>
  <tr> 
    <td width="50">��</td>
    <td width="21" bgcolor="#ffffc0">��</td>
    <td width="658" bgcolor="#ffffc0">��</td>
    <td width="21" bgcolor="#ffffc0">��</td>
  </tr>
  <tr> 
    <td width="50">��</td>
    <td width="21" bgcolor="#ffffc0">��</td>
    <td width="658" bgcolor="#ffffc0"> 
      <table width="658" border="0" cellspacing="0" cellpadding="5">
        <tr> 
          <td width="208" class="syokugyou"> 
            <div align="center">��ͻ�ȳ�</div>
          </td>
          <td width="450" class="mailtitle"> <a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=000933" target="_blank"><img src="img/mag.gif" width="32" height="24" border="0" align="absmiddle" hspace="5" vspace="0"></a><a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=000933" target="_blank">�ġš�Heaven's 
            Place���š�</a></td>
        </tr>
        <tr> 
          <td width="208" class="read"> 
            <div align="center"><a href="javascript:subWin('relay07.php')">Ϣ�ܤ��ɤ�</a></div>
          </td>
          <td width="450" class="mailex">�������å������ΤҤȤꤴ�Ȥ�������û��ʪ����ï���˰��Ƥ������ä��ꡣ<br>�ˤ��Ϥ��䤢�ꡩ 
          </td>
        </tr>
      </table>
    </td>
    <td width="21" bgcolor="#ffffc0">��</td>
  </tr>
  <tr> 
    <td width="50">��</td>
    <td width="21" bgcolor="#ffffc0">��</td>
    <td width="658" bgcolor="#ffffc0">��</td>
    <td width="21" bgcolor="#ffffc0">��</td>
  </tr>
  <tr> 
    <td width="50">��</td>
    <td width="21" bgcolor="#ffffc0">��</td>
    <td width="658" bgcolor="#ffffc0"> 
      <table width="658" border="0" cellspacing="0" cellpadding="5">
        <tr> 
          <td width="208" class="syokugyou"> 
            <div align="center">������¤�����������꡼����</div>
          </td>
          <td width="450" class="mailtitle"> <a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=002062" target="_blank"><img src="img/mag.gif" width="32" height="24" border="0" align="absmiddle" hspace="5" vspace="0"></a><a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=002062" target="_blank">��� 
            Treasure Box</a></td>
        </tr>
        <tr> 
          <td width="208" class="read"> 
            <div align="center"><a href="javascript:subWin('relay12.php')">Ϣ�ܤ��ɤ�</a></div>
          </td>
          <td width="450" class="mailex"> 
            <div align="left">��̡���ä���餹ͷ�־���᡼��ޥ��������� Treasure Box��</div>
          </td>
        </tr>
      </table>
    </td>
    <td width="21" bgcolor="#ffffc0">��</td>
  </tr>
  <tr> 
    <td width="50">��</td>
    <td width="21" bgcolor="#ffffc0">��</td>
    <td width="658" bgcolor="#ffffc0">��</td>
    <td width="21" bgcolor="#ffffc0">��</td>
  </tr>
  <tr> 
    <td width="50">��</td>
    <td width="21" bgcolor="#ffffc0">��</td>
    <td width="658" bgcolor="#ffffc0"> 
      <table width="658" border="0" cellspacing="0" cellpadding="5">
        <tr> 
          <td width="208" class="syokugyou"> 
            <div align="center">������ߤξ��ʴ��ޥ�</div>
          </td>
          <td width="450" class="mailtitle"> <a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=002199" target="_blank"><img src="img/mag.gif" width="32" height="24" border="0" align="absmiddle" hspace="5" vspace="0"></a><a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=002199" target="_blank">VIC's 
            D.I.Y.�ǿ�����</a></td>
        </tr>
        <tr> 
          <td width="208" class="read">
            <div align="center"><a href="javascript:subWin('relay06.php')">Ϣ�ܤ��ɤ�</a></div>
          </td>
          <td width="450" class="mailex">ï�ˤ����������繩���������ޤ��󾧤��Ƥ���VIC'��DIY�κǿ�����ȡ�DIY�ҥ�Ȥ��Ϥ����ޤ���</td>
        </tr>
      </table>
    </td>
    <td width="21" bgcolor="#ffffc0">��</td>
  </tr>
  <tr> 
    <td width="50">��</td>
    <td width="21" bgcolor="#ffffc0">��</td>
    <td width="658" bgcolor="#ffffc0">�� </td>
    <td width="21" bgcolor="#ffffc0">��</td>
  </tr>
  <tr> 
    <td width="50">��</td>
    <td width="21" bgcolor="#ffffc0">��</td>
    <td width="658" bgcolor="#ffffc0"> 
      <table width="658" border="0" cellspacing="0" cellpadding="5">
        <tr> 
          <td width="208" class="syokugyou"> 
            <div align="center">������в�</div>
          </td>
          <td width="450" class="mailtitle"> <a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=000246" target="_blank"><img src="img/mag.gif" width="32" height="24" border="0" align="absmiddle" hspace="5" vspace="0"></a><a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=000246" target="_blank">�֤Ĥ��פ�Ƥ֥᡼��ޥ�����</a></td>
        </tr>
        <tr> 
          <td width="208" class="read"> 
            <div align="center"><a href="javascript:subWin('relay14.php')">Ϣ�ܤ��ɤ�</a></div>
          </td>
          <td width="450" class="mailex">�Ĥ���Ƥ֤���Τ��Ĥ����߰ռ������Ѥ������᡼���ȥ졼�˥󥰤��濴�ˤ��Ϥ��������Ū������ʡˡ��</td>
        </tr>
      </table>
    </td>
    <td width="21" bgcolor="#ffffc0">��</td>
  </tr>
  <tr> 
    <td width="50">��</td>
    <td width="21" bgcolor="#ffffc0">��</td>
    <td width="658" bgcolor="#ffffc0">�� </td>
    <td width="21" bgcolor="#ffffc0">��</td>
  </tr>
  <tr> 
    <td width="50">��</td>
    <td width="21" bgcolor="#ffffc0">��</td>
    <td width="658" bgcolor="#ffffc0"> 
      <table width="658" border="0" cellspacing="0" cellpadding="5">
        <tr> 
          <td width="208" class="syokugyou"> 
            <div align="center">������</div>
          </td>
          <td width="450" class="mailtitle"> <a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=002290" target="_blank"><img src="img/mag.gif" width="32" height="24" border="0" align="absmiddle" hspace="5" vspace="0"></a><a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=002290" target="_blank">ƣ��ͺ��Ϻ�Ρ֤��ΰ���Ǵ�Ȥ���ߤ������</a></td>
        </tr>
        <tr> 
          <td width="208" class="read"> 
            <div align="center"><a href="javascript:subWin('relay11.php')">Ϣ�ܤ��ɤ�</a></div>
          </td>
          <td width="450" class="mailex">�ǥե����������Ĥ�ɬ��ˡ�Ǥ���<br>�������Ϥμ��������������ǤɤΤ褦�ˤ���٤����������������ޤ���</td>
        </tr>
      </table>
    </td>
    <td width="21" bgcolor="#ffffc0">��</td>
  </tr>
  <tr> 
    <td width="50">��</td>
    <td width="21" bgcolor="#ffffc0">��</td>
    <td width="658" bgcolor="#ffffc0">�� </td>
    <td width="21" bgcolor="#ffffc0">��</td>
  </tr>
  <tr> 
    <td width="50">��</td>
    <td width="21" bgcolor="#ffffc0">��</td>
    <td width="658" bgcolor="#ffffc0"> 
      <table width="658" border="0" cellspacing="0" cellpadding="5">
        <tr> 
          <td width="208" class="syokugyou"> 
            <div align="center">ź���</div>
          </td>
          <td width="450" class="mailtitle"> <a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=000136" target="_blank"><img src="img/mag.gif" width="32" height="24" border="0" align="absmiddle" hspace="5" vspace="0"></a><a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=000136" target="_blank">������ɥ��饹��ź���</a></td>
        </tr>
        <tr> 
          <td width="208" class="read"> 
            <div align="center"><a href="javascript:subWin('relay16.php')">Ϣ�ܤ��ɤ�</a></div>
          </td>
          <td width="450" class="mailex">��ź������и����������ͤȤι�����Ѥʼ��ۤȤγ�Ʈ���ä����ޤ��� </td>
        </tr>
      </table>
    </td>
    <td width="21" bgcolor="#ffffc0">��</td>
  </tr>
  <tr> 
    <td width="50">��</td>
    <td width="21" bgcolor="#ffffc0">��</td>
    <td width="658" bgcolor="#ffffc0">�� </td>
    <td width="21" bgcolor="#ffffc0">��</td>
  </tr>
  <tr> 
    <td width="50">��</td>
    <td width="21" bgcolor="#ffffc0">��</td>
    <td width="658" bgcolor="#ffffc0"> 
      <table width="658" border="0" cellspacing="0" cellpadding="5">
        <tr> 
          <td width="208" class="syokugyou"> 
            <div align="center">�������������Υ���Х���</div>
          </td>
          <td width="450" class="mailtitle"> <a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=002164" target="_blank"><img src="img/mag.gif" width="32" height="24" border="0" align="absmiddle" hspace="5" vspace="0"></a><a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=002164" target="_blank">̴��̴����å���������</a></td>
        </tr>
        <tr> 
          <td width="208" class="read"> 
             <div align="center"><a href="javascript:subWin('relay08.php')">Ϣ�ܤ��ɤ�</a></div>
          </td>
          <td width="450" class="mailex">�����֤Σ����С��С����������ǡ��Ƕ�פä��������դ���������ʤɤ����򤪤������񤭤ޤ���</td>
        </tr>
      </table>
    </td>
    <td width="21" bgcolor="#ffffc0">��</td>
  </tr>
  <tr> 
    <td width="50">��</td>
    <td width="21" bgcolor="#ffffc0">��</td>
    <td width="658" bgcolor="#ffffc0">�� </td>
    <td width="21" bgcolor="#ffffc0">��</td>
  </tr>
  <tr> 
    <td width="50">��</td>
    <td width="21" bgcolor="#ffffc0">��</td>
    <td width="658" bgcolor="#ffffc0"> 
      <table width="658" border="0" cellspacing="0" cellpadding="5">
        <tr> 
          <td width="208" class="syokugyou"> 
            <div align="center">���ҥޥ�</div>
          </td>
          <td width="450" class="mailtitle"> <a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=001041" target="_blank"><img src="img/mag.gif" width="32" height="24" border="0" align="absmiddle" hspace="5" vspace="0"></a><a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=001041" target="_blank">�� 
            ��ͻ���ؤ϶����ʤ� ��</a></td>
        </tr>
        <tr> 
          <td width="208" class="read"> 
            <div align="center"><a href="javascript:subWin('relay13.php')">Ϣ�ܤ��ɤ�</a></div>
          </td>
          <td width="450" class="mailex">�ض�ͻ���ؤ����Ѥ��뤿��Ρ١ض�ͻ���ؤ����Ѥ���ʤ�����Ρپ����������ޤ���</td>
        </tr>
      </table>
    </td>
    <td width="21" bgcolor="#ffffc0">��</td>
  </tr>
  <tr> 
    <td width="50">��</td>
    <td width="21" bgcolor="#ffffc0">��</td>
    <td width="658" bgcolor="#ffffc0">�� </td>
    <td width="21" bgcolor="#ffffc0">��</td>
  </tr>
  <tr> 
    <td width="50">��</td>
    <td width="21" bgcolor="#ffffc0">��</td>
    <td width="658" bgcolor="#ffffc0"> 
      <table width="658" border="0" cellspacing="0" cellpadding="5">
        <tr> 
          <td width="208" class="syokugyou"> 
            <div align="center">Web����åױ���</div>
          </td>
          <td width="450" class="mailtitle"> <a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=001932" target="_blank"><img src="img/mag.gif" width="32" height="24" border="0" align="absmiddle" hspace="5" vspace="0"></a><a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=001932" target="_blank">Ư���ʤ����������ڤˤ��������ʤ��ء����ֽ����̿�</a></td>
        </tr>
        <tr> 
          <td width="208" class="read"> 
            <div align="center"><a href="javascript:subWin('relay09.php')">Ϣ�ܤ��ɤ�</a></div>
          </td>
          <td width="450" class="mailex">����ǯ��μ�ʬ�������٤˺��μ�ʬ���Ǥ��뤳�Ȥ�ͤ��Ƥߤޤ��󤫡�<br>Ź�����Ω���ȥ����Ȳֽ��̤ξ����</td>
        </tr>
      </table>
    </td>
    <td width="21" bgcolor="#ffffc0">��</td>
  </tr>
  <tr> 
    <td width="50">��</td>
    <td width="21" bgcolor="#ffffc0">��</td>
    <td width="658" bgcolor="#ffffc0">�� </td>
    <td width="21" bgcolor="#ffffc0">��</td>
  </tr>
  <tr> 
    <td width="50" height="60">��</td>
    <td width="21" bgcolor="#ffffc0" height="60">��</td>
    <td width="658" bgcolor="#ffffc0" height="60"> 
      <table width="658" border="0" cellspacing="0" cellpadding="5">
        <tr> 
          <td width="208" class="syokugyou"> 
            <div align="center">�ŴﲰŹ��</div>
          </td>
          <td width="450" class="mailtitle"> <a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=000123" target="_blank"><img src="img/mag.gif" width="32" height="24" border="0" align="absmiddle" hspace="5" vspace="0"></a><a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=000123" target="_blank">SonyShop 
            C-TEC News</a></td>
        </tr>
        <tr> 
          <td width="208" class="read"> 
            <div align="center"><a href="javascript:subWin('relay15.php')">Ϣ�ܤ��ɤ�</a></div>
          </td>
          <td width="450" class="mailex">����åפ�Ź�����ºݤ˿��äƤξ��ʥ�ӥ塼�����Ϥ����ޤ������ˡ��ե���ɬ���Ǥ���</td>
        </tr>
      </table>
    </td>
    <td width="21" bgcolor="#ffffc0" height="60">��</td>
  </tr>
  <tr> 
    <td width="50">��</td>
    <td width="21" bgcolor="#ffffc0">��</td>
    <td width="658" bgcolor="#ffffc0">�� </td>
    <td width="21" bgcolor="#ffffc0">��</td>
  </tr>
  <tr> 
    <td width="50">��</td>
    <td width="21"><img src="img/round_3.gif" width="21" height="21"></td>
    <td width="658" bgcolor="#99CC66">��</td>
    <td width="21"><img src="img/round_4.gif" width="21" height="21"></td>
  </tr>
</table>
<p>
<table width="750" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="50" height="97"><img src="img/spacer.gif" width="50" height="1"></td>
    <td width="700" height="97"> 
      <form action="http://www.melonpan.net/melonpa/register.php" method="post">
        <table width="300" border="0" cellspacing="0" cellpadding="5" align="right">
          <tr> 
          <td bgcolor="#669933"> 
              <div align="center"><font color="#ffffff"><b><span class="text12">����Ϣ�ܥ����ʡ����ڤ����<br>�ؤ���Ѥ�쥿���٤Τ����ɤϤ����餫��</span></b><span class="text12"> 
                </span> </font></div>
          </td>
        </tr>
        <tr> 
          <td bgcolor="#fff9c2">
            <div align="center"><font color="#006600" class="text12">E-mail</font> 
              <input type="hidden" name="mag_id" value="000049">
              <input type="text" size="22" name="email">
              <input type="submit" value="����" name="regist">
            </div>
			</td>
        </tr>
      </table>
	  </form>
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
      <div align="center">��<a href="../index.php">����Ѥ�</a>�ϡ��ʳ��ˡ������������λ�����ǰ��ȯ����������Ԥ�ٱ礹��פ˴�Ť��Ʊ��Ĥ���Ƥ��ޤ���</div>
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
      <div align="center">Copyright(c)2001 xxxxxxx, Co., Ltd. All rights reserved.<br>
        <a href="http://www.xxxxxxx.co.jp/guide/xxxxxxx.html" target="_blank"><img src="../../common/do_rogo.gif" width="40" height="40" border="0"></a></div>
    </td>
  </tr>
</table>
</body>
</html>
