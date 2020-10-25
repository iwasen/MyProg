<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�c�Ɨp�y�[�W
' Content:�c�ƃ��j���[��ʏ���
'******************************************************

'=== ���O�C�����擾 ===
Public Function GetLoginName()
	Dim sql, ds

	Select Case g_login_type
	Case LOGIN_CENTER
		sql = "SELECT CN_user_name FROM T_CENTER WHERE CN_user_id='" & g_login_id & "'"
	Case LOGIN_AGENT
		sql = "SELECT DA_name FROM T_OPERATOR,T_DAIRITEN WHERE OP_operator_id='" & g_login_id & "' AND OP_dairiten_id=DA_dairiten_id"
	Case LOGIN_CLIENT
		sql = "SELECT TS_name1,TS_name2 FROM T_TANTOUSHA WHERE TS_tantousha_id='" & g_login_id & "'"
	Case LOGIN_GUEST
		GetLoginName = "�Q�X�g"
		Exit Function
	End Select

	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		GetLoginName = ds(0).Value
	End If
End Function

'=== ���C������ ===
Dim title

Select Case g_login_type
Case LOGIN_AGENT
	title = ReplaceTag(GetLoginName) & "�l ��p�}�[�P�e�B���O�C���^�[�t�F�[�X"
Case LOGIN_CLIENT
	title = ReplaceTag(GetLoginName) & "�l ��p�}�[�P�e�B���O�C���^�[�t�F�[�X"
Case LOGIN_GUEST
	Response.Redirect "../search/index.asp"
End Select

SetHeaderTitle title
top_link = "<small><font color='blue'>��</font><A href='logoff.asp'>���O�I�t</A></small>"
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function onMouseOver(c) {
	c.style.color="yellow";
}
function onMouseOut(c) {
	c.style.color="white";
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<table align="center" border=0 cellpadding=4 cellspacing=15 width="85%">
<%
If CheckPermission(PM_REGIST_AGENT) And g_login_type = LOGIN_AGENT Then
%>
	<tr>
		<a href="../agent/index.asp">
			<td width="30%" align="center" class="menu" onMouseOver="onMouseOver(this)" onMouseOut="onMouseOut(this)">�㗝�X�^�S���ғo�^</td>
		</a>
		<td width="70%">�㗝�X����т��̒S���҂̓o�^�E�ύX���s���܂��B</td>
	</tr>
<%
End If

If CheckPermission(PM_REGIST_CLIENT) And g_login_type = LOGIN_AGENT Then
%>
	<tr>
		<a href="../client/index.asp">
			<td width="30%" align="center" class="menu" onMouseOver="onMouseOver(this)" onMouseOut="onMouseOut(this)">��Ɓ^�S���ғo�^</td>
		</a>
		<td width="70%">���M��Ƃ���т��̒S���҂̓o�^�E�ύX���s���܂��B</td>
	</tr>
<%
End If

If CheckPermission(PM_REGIST_OPERATOR) Then
%>
	<tr>
		<a href="../operator/index.asp">
			<td width="30%" align="center" class="menu" onMouseOver="onMouseOver(this)" onMouseOut="onMouseOut(this)">�I�y���[�^�o�^</td>
		</a>
		<td width="70%">�V�X�e���̃I�y���[�^��o�^���܂��B</td>
	</tr>
<%
End If
%>
	<tr>
		<a href="../project/pjt_go.asp">
			<td width="30%" align="center" class="menu" onMouseOver="onMouseOver(this)" onMouseOut="onMouseOut(this)">�z�M�Ǘ�</td>
		</a>
		<td width="70%">�v���W�F�N�g�̃N���b�N���A�ԐM�󋵂����A���^�C���ɉ{���ł��܂��B���|�[�g�̃_�E�����[�h�͂����炩��s���Ă��������B</td>
	</tr>
	<tr>
		<a href="../search/index.asp">
			<td width="30%" align="center" class="menu" onMouseOver="onMouseOver(this)" onMouseOut="onMouseOut(this)">�����o�[�����^�z�M�\��</td>
		</a>
		<td width="70%">���w��̏����ɊY�����郁���o�[�̐l����������z�M�\��܂ł��s���܂��B<br>�����[�������̂����e�̓Z���^�[�܂ŁA���⍇�����������B</td>
	</tr>
</table>

<p> </p>
<table width="75%" border="2" cellspacing="0" cellpadding="0" align="center" bordercolor="cd853f">
	<tr>
		<td>
			
      <table width="100%" border="0" cellspacing="10" cellpadding="0" align="center">
        <tr> 
          <td colspan="3" class="np"> 
            <div align="center"><b><font color="#336699">�͂��߁[��Z���^�[����̂��m�点</font></b></div>
          </td>
        </tr>
        <tr> 
          <td valign="top" class="info"> 
            <div align="right"><font color="#336699">��</font></div>
          </td>
          <td colspan="2" class="info">�Œ�L�������͂T���~�ɂȂ�܂��B<br>
            ������A�z�M�������T���~�ɖ����Ȃ��ꍇ�A�Œ�L��������K�p�����Ă��������Ă���܂��B ���炩���߂��������������܂��悤���肢�\���グ�܂��B</td>
        </tr>
        <tr> 
          <td valign="top" class="info"> 
            <div align="right"><font color="#336699">��</font></div>
          </td>
          <td colspan="2" class="info">���e���e�͌����A<font color="red"><b>�z�M�̂R�c�Ɠ��P�V:�O�O ��</b></font>�ƂȂ��Ă���܂�</td>
        </tr>
        <tr> 
          <td valign="top" class="info"> 
            <div align="right"><font color="#336699">��</font></div>
          </td>
          <td colspan="2" class="info">�͂��߁[��Enet�̓I�v�g�C�����[���ł��邽�߁A���[���R���e���c�Ƌ����W�������A�������̓��e�̐���������点�Ē����Ă���܂��B<br>
            �z�M������e�Ƃ��w�荀�ڂ̓��e�ɂ��܂��ẮA�����e������������ɃZ���^�[���m�F�����Ă����������Ƃ��������܂��B���炩���߂��������������B 
            <a href="http://manyu.haimail.net/sales/top/faq.html" target="_blank">�i�z�M�O�Ɋm�F�����Ă��������P�[�X�j</a> 
          </td>
        </tr>
        <tr> 
          <td valign="top" class="info"> 
            <div align="right"><font color="#336699">��</font></div>
          </td>
          <td colspan="2" class="info"> HTML�����o�[�ɍi���Ẵ����o�[�����Ɍ���A�\������錟�������o�[���ɔ�ׁA���ۂ̔z�M�\�������Ȃ��Ȃ�ꍇ���������܂��B<br>
			<font color="red"><b>HTML�����o�[����������]�̍ۂɂ́A���萔�ł����͂��߁[��Z���^�[�܂ŕʓr���������\���t���������B</b></font></td>
        </tr>
        <tr> 
          <td valign="top" class="info"> 
            <div align="right"><font color="#336699">��</font></div>
          </td>
          <td colspan="2" class="info">�͂��߁[��Enet�ł́A�L�����y�[�����s���Ă���܂��B�ڂ�����<a href="http://www.haimail.net/6/download.html" target="_blank">�}�̎���</a> 
            �����m�F���������B </td>
        </tr>
        <tr>
          <td valign="top" class="info">
            <div align="right"><font color="#336699">��</font></div>
          </td>
          <td colspan="2" class="info">�͂��߁[��Enet�ł́A�Ǝ�K��ɂ��z�M�ł��Ȃ��Ǝ���������܂��B�ڂ�����<a href="http://www.haimail.net/6/download.html">�}�̎���</a>�����m�F�������B</td>
        </tr>
        <tr> 
          <td valign="top" class="info"> 
            <div align="right"><font color="#336699">��</font></div>
          </td>
          <td colspan="2" class="info"><a href="manual_for_agencies.pdf">�ݒ�}�j���A���_�E�����[�h</a>�iPDF�`���j</td>
        </tr>
        <tr> 
          <td valign="top" class="info">�@</td>
          <td class="info">PDF�t�@�C���������ɂȂ�ɂ�<a href="http://www.adobe.co.jp/products/acrobat/readstep.html" target="_blank">Adobe 
            Acrobat Reader</a>���K�v�ł��B<br>
            �E�̃A�C�R�����疳���Ń_�E�����[�h�ł��܂��B</td>
          <td width="20%" class="info"> 
            <div align="left"><a href="http://www.adobe.co.jp/products/acrobat/readstep.html" target="_blank"><img src="getacro.gif" width="88" height="31" border="0" alt="Acrobat Reader"></a></div>
          </td>
        </tr>
      </table>
		</td>
	</tr>
</table>

<p>�@</p>
<table width="300" border="0" cellspacing="0" cellpadding="0" align="right">
	<tr>
		<td class="info"> ���⍇���͉��L�ɂĎ󂯕t���Ă���܂��B<br>
			������Ё@�������������@�͂��߁[��Z���^�[<br>
      mail to <a href="mailto:ad@xxxxxxx.co.jp">ad@xxxxxxx.co.jp</a> <br>
			��t���ԁF�P�O�F�O�O�`�P�W�F�O�O�i�y���j�̂����j </td>
	</tr>
</table>
<p>�@</p>
<p>�@</p>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
