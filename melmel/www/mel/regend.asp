<!--#INCLUDE FILE="../melmel_inc.asp"-->
<%
If Session("mel_id") = "" then
	Response.Redirect "./false.html"
Else
	Dim DB, RS, SQL, MailSubject, MailBody, knd, MailTo
	Dim ErrFlag

	Set DB = OpenDB()
	SQL = "SELECT * FROM T_MELMEL WHERE MM_mel_id='" & Session("mel_id") & "'"
	Set RS = Server.CreateObject("ADODB.RecordSet")
	RS.Open SQL, DB, adOpenKeyset, adLockPessimistic

	ErrFlag = 0

	If RS.EOF Then
		'--- No Entry ---
		Response.Write ("�G���[���������܂����B<BR>")
		Response.Write (OFFICE_ADDRESS)
		Response.Write ("<BR> �܂ł��A���������B")
		ErrFlag = -1

	Else

		RS("MM_h_name") = Session("h_name")
		RS("MM_mail_adr") = Session("madd")
		RS("MM_c_name") = Session("c_name")

		if Session("passchk") <> "" then
			RS("MM_password") = Session("pwd1")
		End if

'		if Session("bankchk") <> "" then
'			RS("MM_bk_acc") = Session("bk_acc")
'			RS("MM_bk_nm") = Session("bk_nm")
'			RS("MM_bk_st") = Session("bk_st")
'			RS("MM_bk_knm") = Session("bk_knm")
'			RS("MM_bk_knd") = Session("bk_knd")
'		End if
		RS.Update

	End if

	if ErrFlag = 0 then

%>
<html>
<head>
<title>�߂�߂�Enet ���C������</title>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<link rel="stylesheet" href="../melmel.css">
<script language="JavaScript">
<!--
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v3.0
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//-->
</script>
</head>
<body bgcolor="#ff9999" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="MM_preloadImages('../images/tab_koukoku1b.gif','../images/tab_top1.gif')">
<table width="600" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td colspan="3">
      <table bgcolor="#ff9999" border="0" cellpadding="0" cellspacing="0" width="600" align="center">
        <tr> <!-- Shim row, height 1. -->
          <td><img src="../images/shim.gif" width="252" height="1" border="0"></td>
          <td><img src="../images/shim.gif" width="348" height="1" border="0"></td>
          <td><img src="../images/shim.gif" width="1" height="1" border="0"></td>
        </tr>
        <tr valign="top"><!-- row 1 -->
          <td rowspan="3"><a HREF="../index.html"><img name="head_logo" src="../images/head_logo.gif" width="252" height="96" border="0" alt="�߂�߂�Enet ���S"></a></td>
          <td><img src="../images/shim.gif" width="348" height="26" border="0"></td>
          <td><img src="../images/shim.gif" width="1" height="26" border="0"></td>
        </tr>
        <tr valign="top"><!-- row 2 -->
          <td><img name="head_t1" src="../images/head_t1.gif" width="348" height="52" border="0" alt="���s�Ҍ����y�[�W"></td>
          <td><img src="../images/shim.gif" width="1" height="52" border="0"></td>
        </tr>
        <tr valign="top"><!-- row 3 -->
          <td><img src="../images/shim.gif" width="348" height="18" border="0"></td>
          <td><img src="../images/shim.gif" width="1" height="18" border="0"></td>
        </tr>
      </table>
    </td>
  </tr>
<!--  <TR>     <TD valign="top" colspan="3" align="left">       <TABLE width="405" border="0" cellspacing="0" cellpadding="0">        <TR>           <TD><A href="../index.html" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image45','','../images/tab_top1.gif',1)"><IMG name="Image45" border="0" src="../images/tab_top1b.gif" width="130" height="25" alt="�g�b�v�y�[�W"></A></TD>          <TD><A href="pubtop.asp"><IMG src="../images/tab_hakko1b.gif" width="130" height="25" alt="���s�Ҍ����g�b�v�y�[�W" border="0"></A></TD>          <TD><A href="../agenttop.html" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image44','','../images/tab_koukoku1b.gif',1)"><IMG name="Image44" border="0" src="../images/tab_koukoku1.gif" width="130" height="25" alt="�L���㗝�X�����g�b�v�y�[�W"></A></TD>          <TD><IMG src="../images/tab_tale1.gif" width="15" height="25"></TD>        </TR>      </TABLE>    </TD>  </TR>-->
  <tr>
    <td valign="top" bgcolor="#FFFF00"><img src="../images/shim.gif" width="130" height="10"></td>
    <td valign="top" bgcolor="#FFFF00"><img src="../images/shim.gif" width="461" height="10"></td>
    <td valign="top"><img src="../images/shadow5.gif" width="9" height="10"></td>
  </tr>
  <tr>
    <td valign="top" bgcolor="#FFFF00" width="130">
      <table width="130" border="0" cellspacing="0" cellpadding="0">
        <tr align="center">
          <td><img src="images/menu.gif" width="130" height="20" alt="MENU"></td>
        </tr>
        <tr align="center">
          <td><a href="pubtop.asp"><img src="images/b_memb2.gif" width="112" height="34" alt="�|�C���g���Z�m�F" border="0"></a></td>
        </tr>
        <tr align="center">
          <td><a href="pointhst.asp"><img src="images/b_memb10.gif" width="112" height="34" alt="�|�C���g����" border="0"></a></td>
        </tr>
        <tr align="center">
          <td><img src="images/b_memb1b.gif" width="112" height="34" alt="�o�^���C��"></td>
        </tr>
        <tr align="center">
          <td><a href="mglist.asp"><img src="images/b_memb3.gif" width="112" height="34" alt="�����}�K���C��" border="0"></a></td>
        </tr>
        <tr align="center">
          <td><a href="rexxxheck.asp"><img src="images/b_memb4.gif" width="112" height="34" alt="�x��\������" border="0"></a></td>
        </tr>
        <tr align="center">
          <td><a href="backcheck.asp"><img src="images/b_memb5.gif" width="112" height="34" alt="����\������" border="0"></a></td>
        </tr>
        <tr align="center">
          <td><a href="resign.asp"><img src="images/b_memb6.gif" width="112" height="34" alt="�މ�\������" border="0"></a></td>
        </tr>
        <tr align="center">
          <td><a href="../mpub/provreg_1.asp?id=<%=Session("mel_id")%>"><img src="images/b_memb7.gif" width="112" height="34" alt="���o�^�\������" border="0"></a></td>
        </tr>
        <tr align="center">
          <td height="34"><a href="index.asp"><img src="images/b_memb8.gif" width="112" height="34" alt="���O�C���y�[�W" border="0"></a></td>
        </tr>
        <tr align="center">
          <td height="34"><a href="http://www.melmel.net/"><img src="images/b_memb9.gif" width="112" height="34" alt="�g�b�v�y�[�W" border="0"></a></td>
        </tr>
      </table>
    </td>
    <td valign="top" bgcolor="#FFFFFF">
<!------------------ �{��textbox�e�[�u���E�������� ------------------------------>
      <table width="461" border="0" cellspacing="5" cellpadding="0">
        <tr>
          <td>
            <p align="center"><img src="images/t_regend.gif" width="252" height="22" alt="�߂�߂邳��o�^��񊮗�"><br>
              <img src="../images/chara05.gif" width="80" height="75" alt="�L�����N�^�[�C���[�W">
            </p>
            <p align="center"><font size="4" class="ohmidashi">�X�V���܂����B���肪�Ƃ��������܂����B</font></p>
            <p align="center"><font size="3" class="kihon"><a href="pubtop.asp">�߂�߂邳���p�g�b�v�y�[�W�ɖ߂�</a></font></p>
          </td>
        </tr>
      </table>
<!------------------ �{��textbox�e�[�u���E�����܂� ------------------------------>
      <p>�@ </p>
      </td>
    <td background="../images/shadow2.gif"><img src="../images/shim.gif" width="9" height="300"></td>
  </tr>
  <tr>
    <td bgcolor="#FFFF00"><img src="../images/shim.gif" width="130" height="10"></td>
    <td bgcolor="#FFFF00"><img src="../images/shim.gif" width="461" height="10"></td>
    <td background="../images/shadow2.gif"><img src="../images/shim.gif" width="9" height="10"></td>
  </tr>
  <tr>
    <td height="9" colspan="3"><img src="../images/shadow6.gif" width="600" height="9"></td>
  </tr>
  <tr>
    <td colspan="3">
      <table bgcolor="#ff9999" border="0" cellpadding="0" cellspacing="0" width="600" align="center">
        <tr> <!-- Shim row, height 1. -->
          <td><img src="../images/shim.gif" width="147" height="1" border="0"></td>
          <td><img src="../images/shim.gif" width="66" height="1" border="0"></td>
          <td><img src="../images/shim.gif" width="28" height="1" border="0"></td>
          <td><img src="../images/shim.gif" width="155" height="1" border="0"></td>
          <td><img src="../images/shim.gif" width="204" height="1" border="0"></td>
          <td><img src="../images/shim.gif" width="1" height="1" border="0"></td>
        </tr>
        <tr valign="top"><!-- row 1 -->
          <td colspan="3"><img src="../images/shim.gif" width="241" height="15" border="0"></td>
          <td rowspan="2"><img name="logo_s" src="../images/logo_s.gif" width="155" height="42" border="0" alt="�߂�߂�Enet ���S"></td>
          <td rowspan="2"><img src="../images/shim.gif" width="204" height="42" border="0"></td>
          <td><img src="../images/shim.gif" width="1" height="15" border="0"></td>
        </tr>
        <tr valign="top"><!-- row 2 -->
          <td rowspan="2"><img src="../images/shim.gif" width="147" height="49" border="0"></td>
          <td rowspan="2"><img name="cara_s" src="../images/cara_s.gif" width="66" height="49" border="0" alt="�߂�߂� �C���[�W"></td>
          <td><img src="../images/shim.gif" width="28" height="27" border="0"></td>
          <td><img src="../images/shim.gif" width="1" height="27" border="0"></td>
        </tr>
        <tr valign="top"><!-- row 3 -->
          <td colspan="3" height="22" valign="middle"> <font size="2" class="komoji">Copyright
            (C) <%=Year(Date)%> xx xxxxx Inc. All rights reserved.</font></td>
          <td><img src="../images/shim.gif" width="1" height="22" border="0"></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<%
	if Session("bankchk") <> "" then
'------------- sendmail --------------'
		MailSubject = "�����ύX�m�F"
		if Session("bk_knd") = "1" then
			knd = "����"
		end if
		if Session("bk_knd") = "2" then
			knd = "����"
		end if
		if Session("bk_knd") = "3" then
			knd = "���~"
		end if
		MailTo = Session("madd") & vbtab & "cc" & vbtab & OFFICE_ADDRESS

		MailBody = Session("h_name") & "����" & vbcrlf & vbcrlf & _
		"�ȉ��̓��e�ŋ�s�ύX�˗����󂯕t���܂����B" & vbcrlf & _
		"�ԈႢ�Ȃ���΁A���̃��[�������̂܂� Reply ���ĉ������B" & vbcrlf & _
		"��s���@�F " & Session("bk_nm") & vbcrlf & _
		"�x�X���@�F " & Session("bk_st") & vbcrlf & _
		"������ށF " & knd & vbcrlf & _
		"�������`�F " & Session("bk_knm") & vbcrlf & vbcrlf

		if SendMail(MailTo, OFFICE_ADDRESS, MailSubject, MailBody) <> "" then
			Response.Write "<PRE>"
			Response.Write ("<BR><font color=""#FF0000"">")
			Response.Write ("���[�����M�Ɏ��s���܂����Boffice@melmel.net �֒��ڂ��₢���킹�������B")
			Response.Write ("<br><br></font>")
			Response.Write "</PRE>"
		end if
	end if
%>			
</body>
</html>
<%
	end if
End if
%>
