<%@ page contentType="text/html; charset=Shift_JIS" %>
<%@ page import="base.*" %>
<jsp:useBean id="SearchDbBeanId" scope="request" class="xxx.TargetAccessBean" />
<jsp:useBean id="SelectCondBeanId" scope="session" class="base.SelectCondBean" />
<jsp:useBean id="db" scope="request" class="base.DataBase" />
<html>
<head>
<title>xxx Business Analyst ��������</title>
<meta name="author" content="Kuni Tojima/tojima@xxxx.co.jp">
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<meta http-equiv="Pragma" content="no-cache">
<script src='Script.js'></script>
<link rel="stylesheet" href="table.css" type="text/css">
<link rel="stylesheet" href="size.css" type="text/css">
<!---�@�X���b�v�C���[�WJS�@--->
<script language="JavaScript">
<!--
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
// -->

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}
//-->
</script>
<!---�@�X���b�v�C���[�WJS�@�����܂Ł@--->
<!--- URL�ʃA�N�Z�X�\��JS --->
<script language="JavaScript">
<!--
function urlAccess(date, category) {
  urlAccessCommon(date, "<%=SelectCondBeanId.getTargetCornerOrUrl()%>", category);
}
//-->
</script>
<!--- URL�ʃA�N�Z�X�\��JS �����܂� --->
</head>

<body bgcolor="#FFFFFF" text="#000000"  leftmargin="10" topmargin="10" onLoad="document.form1.reset();MM_preloadImages('images/btn_tmenu_kiteichiB.gif','images/btn_tmenu_gyou_co_bunpuB.gif','images/btn_tmenu_gyou_suiiB.gif','images/btn_tmenu_gyou_graphB.gif','images/btn_tmenu_bumon_gyou_suiiB.gif','images/btn_tmenu_bumon_gyou_graphB.gif','images/btn_tmenu_bumon_gyou_suco_bunpuB.gif','images/btn_tmenu_ac_moto_graphB.gif','images/btn_tmenu_ac_saki_graphB.gif','images/btn_tmenu_co_gyou_bunpuB.gif','images/btn_tmenu_co_suiiB.gif','images/btn_tmenu_co_graphB.gif','images/btn_tmenu_bumon_toku_graphB.gif','images/btn_tmenu_streamB.gif','images/btn_tmenu_kaishi_pageB.gif','images/btn_tmenu_syuuryou_pageB.gif')">
<form method="post" action="AllAccess" name="form1">
<!---�@�w�b�_�����@--->
<!---�@�i�r�Q�[�^�[�e�[�u���@��������@--->
<%=NavigatorGenerate.getNavigator(SelectCondBeanId)%>
<!---�@�i�r�Q�[�^�[�e�[�u���@�����܂Ł@--->
<table width="720" border="0" cellspacing="0" cellpadding="0" height="5">
<tr>
<td> </td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" background="images/tile_header_home.gif" height="6">
<tr>
<td> </td>
</tr>
</table>
<!---�@�w�b�_���� �����܂Ł@--->
<!---�@�\�������@--->
<table width="720" border="0" cellspacing="0" cellpadding="0">
<tr>
<td height="5"> </td>
</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0">
<tr>
<td align="left" class="size20"><b><font color="#333333" face="�l�r �o�S�V�b�N, Osaka">�w��y�[�W�E�A�N�Z�X�g�����h</font></b></td>
</tr>
</table>
<table width="720" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table width="720" border="0" cellpadding="0" cellspacing="0">
<tr>
<%
// �������̕\��
if ((SelectCondBeanId.getMode() & SelectCondBeanId.modeCondition) != 0) {
%>
<td class="size09">���̃y�[�W�ł͂��炩���ߐݒ肳��Ă���y�[�W��R�[�i�[�̃A�N�Z�X�󋵂����L�Őݒ肵����Ƒ����E���Ԃɏ]����<br>
�\�����܂��B�\���͓��P�ʁE���ԒP�ʂōs���܂��̂ŁA���ꂼ��̕\���{�^�����N���b�N���ĉ������B</td>
<%
} else {
%>
<td class="size09">���̃y�[�W�ł͂��炩���ߐݒ肳��Ă���y�[�W��R�[�i�[�̃A�N�Z�X�󋵂�����l�ɏ]���ĕ\�����܂��B<br>
����y�[�W�̑Ώۂ�I�����ĉ������B</td>
<%
} // �������̕\�� �����܂�
%>
<td width="70" align="right" class="size09">�@</td>
</tr>
</table>
<table width="720" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="100" align="left"><img src="images/txt_taisyo_sentaku.gif"></td>
<td width="50" align="left">
<%=CornerSelect.getTargetCornerSelect(db, SelectCondBeanId, "targetCorner")%>
</td>
<td width="20" align="left" class="size09">�@</td>
<td width="600" align="left" class="size09">
<%
// �ΏۃR�[�i�[(URL)�̕\������
if (SelectCondBeanId.getTargetCorner().length() != 0) {
%>
<b>���L�Ώۂ̕\��������<%=SelectCondBeanId.getTargetLimit(db)%>�܂łł��B</b>
<%
} // �ΏۃR�[�i�[(URL)�̕\������ �����܂�
%>
</td>
</tr>
</table>
<%
// �ΏۃR�[�i�[(URL)�I���ς݃`�F�b�N
if (SelectCondBeanId.getTargetCorner().length() != 0) {
%>
<%
// �����w�蕔���̕\������
if ((SelectCondBeanId.getMode() & SelectCondBeanId.modeCondition) != 0) {
%>
<%@ include file="CondAttribute.jsp" %>
<%@ include file="CondDateDH.jsp" %>
<%
} // �����w�蕔���̕\�� �����܂�
%>
<table width="720" border="0" cellspacing="0" cellpadding="0">
<tr>
<td height="5"> </td>
</tr>
</table>
<%
// ���ʃe�[�u�������̕\��
if ((SelectCondBeanId.getMode() & SelectCondBeanId.modeResult) != 0) {
%>
<table width="100%" border="0" cellspacing="0" cellpadding="0" background="images/tile_footer_home.gif" height="3">
<tr>
<td> </td>
</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0">
<tr>
<td height="5"> </td>
</tr>
</table>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="65" align="left" class="size09"><img src="images/txt_bumon.gif" width="65" height="18"></td>
<td width="5">�@</td>
<td class="size09" align="left"><b><%=SelectCondBeanId.getGroupName(db)%></b></td>
</tr>
</table>
<table width="720" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="65" align="left" class="size09"><img src="images/txt_taisyou_sentakuS.gif" width="65" height="18"></td>
<td width="5">�@</td>
<td class="size09" align="left"><b><%=SelectCondBeanId.getTargetCornerName(db)%></b></td>
</tr>
</table>
<table width="720" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="65" align="left" class="size09"><img src="images/txt_kigyou_zokuseiS.gif" width="65" height="18"></td>
<td width="5">�@</td>
<td class="size09" align="left" width="340"><%=SelectCondBeanId.getCondAttribute(db)%></td>
</tr>
</table>
<table width="720" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="65" align="left" class="size09"><img src="images/txt_kikan_shiteiS.gif" width="65" height="18"></td>
<td width="5">�@</td>
<td class="size09" align="left"><b><%=SelectCondBeanId.getDateSpec()%>�ŕ\��</b>�����ԁF<%=SelectCondBeanId.getDateSpan()%></td>
</tr>
</table>
<table width="720" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="65" align="left" class="size09"><img src="images/txt_tatejjiku.gif" width="65" height="18"></td>
<td width="5">�@</td>
<td class="size09" align="left"><b><%=SelectCondBeanId.getDateSpec2()%></b></td>
</tr>
</table>
<table width="720" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="65" align="left" class="size09"><img src="images/txt_yokojjiku.gif" width="65" height="18"></td>
<td width="5">�@</td>
<td class="size09" align="left"><b><%=SelectCondBeanId.getCondCategoryLink(db)%></b></td>
<%
if (!SelectCondBeanId.getCategory().equals("/")) {
%>
<td width="20" align="center" class="size09">��</td>
<td class="size09">�y�[�W�ւ��F</td>
<td width="70">
<input type="submit" name="Back2" value="�O��20��" style="width:70;height:18"<jsp:getProperty name="SearchDbBeanId" property="disableBackPage2" />>
</td>
<td width="70">
<input type="submit" name="Foward2" value="����20��" style="width:70;height:18"<jsp:getProperty name="SearchDbBeanId" property="disableForwardPage2" />>
</td>
<%
}
%>
</tr>
</table>
<table width="720" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table border="0" cellpadding="3" cellspacing="1" bgcolor="#00156C">
<jsp:getProperty name="SearchDbBeanId" property="dbdata" />
</table>
<table width="720" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0">
<tr>
<td class="size08" align="left">���{�[���h�����̓����N�ł��B</td>
</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0">
<tr>
<td height="5"> </td>
</tr>
</table>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="70">
<input type="submit" name="Back" value="�O��20��" style="width:70"<jsp:getProperty name="SearchDbBeanId" property="disableBackPage" />>
</td>
<td width="10">�@</td>
<td width="70">
<input type="submit" name="Foward" value="����20��" style="width:70"<jsp:getProperty name="SearchDbBeanId" property="disableForwardPage" />>
</td>
</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0">
<tr>
<td height="5"> </td>
</tr>
</table>
<!---�@�\���F�̐����@--->
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="3"></td>
<td class="size08" width="95" align="left">�e�s�ɂ����鏇�ʁF</td>
<td>
<table border="0" cellpadding="3" cellspacing="1" bgcolor="#00156C">
<tr align="center" bgcolor="#62B0FF">
<td width="45" bgcolor="#FFCCFF" class="size08">�P��</td>
<td width="45" bgcolor="#FFFFCC" class="size08">�Q��</td>
<td width="45" bgcolor="#ADE3A2" class="size08">�R��</td>
<td width="45" bgcolor="#D2E9FF" class="size08">�S�ʈȉ�</td>
</tr>
</table>
</td>
</tr>
</table>
<!---�@�\���F�̐����@�����܂Ł@--->
<%
} // ���ʃe�[�u�������̕\�� �����܂�
%>
<%
} // �ΏۃR�[�i�[(URL)�I���ς݃`�F�b�N �����܂�
%>
<table width="720" border="0" cellspacing="0" cellpadding="0">
<tr>
<td height="5"> </td>
</tr>
</table>
<!---�@�\�������@�����܂Ł@--->
<!---�@�t�b�^�����@--->
<div align="center">
<table width="100%" border="0" cellspacing="0" cellpadding="0" background="images/tile_footer_home.gif" height="6">
<tr>
<td> </td>
</tr>
</table>
</div>
<!---�@�t�b�^�����@�����܂Ł@--->
<!-- hidden���� -->
<input type="hidden" name="category" value="<jsp:getProperty name="SelectCondBeanId" property="category" />">
<input type="hidden" name="page" value="<jsp:getProperty name="SelectCondBeanId" property="page" />">
<input type="hidden" name="mode" value="<jsp:getProperty name="SelectCondBeanId" property="mode" />">
<input type="hidden" name="table" value="<jsp:getProperty name="SelectCondBeanId" property="table" />">
<input type="hidden" name="init" value="0">
<!-- hidden���� �����܂� -->
</form>
</body>
</html>