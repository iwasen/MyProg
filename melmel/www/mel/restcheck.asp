<!--#INCLUDE FILE="../melmel_inc.asp"-->
<%
If Session("mel_id") = "" then
	Response.Redirect "./false.html"
End if

Dim DB, RS, SQL, Status

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")

SQL = "SELECT * FROM T_MELMEL WHERE MM_mel_id='" & Session("mel_id") & "'"
RS.Open SQL, DB, adOpenKeyset, adLockPessimistic
Status = RS("MM_status")
RS.Close
%>
<html>
<head>
<title>�߂�߂�Enet �x��\�����݊m�F</title>
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
          <td><a href="regedit.asp"><img src="images/b_memb1.gif" width="112" height="34" alt="�o�^���C��" border="0"></a></td>
        </tr>
        <tr align="center">
          <td><a href="mglist.asp"><img src="images/b_memb3.gif" width="112" height="34" alt="�����}�K���C��" border="0"></a></td>
        </tr>
        <tr align="center">
          <td><img src="images/b_memb4b.gif" width="112" height="34" alt="�x��\������"></td>
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
            <div align="center">
<%
If Status = 0 Then
%>
              <p><img src="images/t_rexxxhk.gif" width="210" height="22" alt="�߂�߂邳��x��\������"><br>
                <img name="chara09" src="../images/chara09.gif" width="83" height="62" border="0" alt="�L�����N�^�[�C���[�W">
              </p>
              <p><font size="3" class="kihon">�߂�߂�Enet���x��܂��B<br>
                ��낵���ł����H</font></p>
              <p>���x��Ȃ���ƁA�߂�߂�Enet�j���[�X����эL���̔z�M�i���Ȃ��̓o�^�Ȃ����Ă��邷�ׂẴ����}�K�ɂ��āj���Ȃ���Ȃ��Ȃ�܂��B����͂��ł��ł��܂��B</p>
              <form name="form1" METHOD="POST" ACTION="./restend.asp">
                <font size="3" class="kihon">
                <input type="submit" name="Submit" value=" �n�j ">
                </font>
              </form>
<%
ElseIf Status = 8 Then
%>
              <p><img src="images/t_rexxxhk.gif" width="210" height="22" alt="�߂�߂邳��x��\������"><br>
                <img name="chara09" src="../images/chara07.gif" width="83" height="62" border="0" alt="�L�����N�^�[�C���[�W">
              </p>
              <p><font size="3" class="kihon">���݁A�߂�߂�Enet���x��ł��B</font></p>
<%
End If
%>
              <p>�@ </p>
            </div>
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
</body>

</html>
