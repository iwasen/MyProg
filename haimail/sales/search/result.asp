<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<!--#INCLUDE FILE="../inc/search.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�c�Ɨp�y�[�W
' Content:�����o�[�������ʉ�ʏ���
'******************************************************

'=== ���C������ ===
SetHeaderTitle "�����o�[��������"

Dim search, result, sql, ds, find, condition

Set search = New CSearch
search.GetData
condition = search.GetConditionText

'HTML���[����M�\��I�������ꍇ��Web���[���h���C�������O����
If search.html_mail_shitei = "1" And search.html_mail = "1" Then
	search.jogai_domain = ""
	sql = "SELECT MD_domain_id FROM T_MAIL_DOMAIN"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Do Until ds.EOF
		If search.jogai_domain <> "" Then
			search.jogai_domain = search.jogai_domain & ","
		End If
		search.jogai_domain = search.jogai_domain & ds(0).Value
		ds.MoveNext
	Loop
End If

'�C���v���X�𔭐M�ΏۊO�Ƃ���i�Q�����܂ł̌��菈�u�j====
'Dim org_jogai_baitai
'org_jogai_baitai = search.jogai_baitai
'If search.jogai_baitai <> "" Then
'	search.jogai_baitai = search.jogai_baitai & ","
'End If
'search.jogai_baitai = search.jogai_baitai & "30"
'====================================================

sql = search.MakeSQL
'�C���v���X�𔭐M�ΏۊO�Ƃ���i�Q�����܂ł̌��菈�u�j====
'search.jogai_baitai = org_jogai_baitai
'====================================================
If CheckPermission(PM_GUEST_SEARCH) Then
	sql = "MM_regist_date<TRUNC(SYSDATE, 'MONTH') AND " & sql
End If

sql = "SELECT COUNT(*) FROM T_MEMBER WHERE " & sql
Set ds = CreateDynaset(sql, ORADYN_READONLY)
If ds.EOF Then
	find = 0
Else
	find = ds(0).Value
End If
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" name="form1" action="../estimate/index.asp">
<input type="hidden" name="send_num" value="<%=find%>">
<table border=0 cellspacing=0 cellpadding=0 width='700'>
	<tr>
		<td class="m0">����������</td>
	</tr>
	<tr>
		<td class="np">
			<p>���w��̏����ɂĂ����ς������]�̏ꍇ�ɂ́A�u�����ς�v�{�^���������ĉ������B<br>
				���z�M�\��������ۂɂ́A �u�����ς�v�ւ��i�݉������B<br>
			</p>
		</td>
	</tr>
	<tr>
		<td>
			<table border=0 cellspacing=2 cellpadding=3 width='100%'>
				<tr>
					<td class="m3" width="25%">���������</td>
					<td class="n3"><%=NumFormat(find)%></td>
				</tr>
				<tr>
					<td class="m3">��������</td>
					<td class="n3"><font size="-1"><%=Replace(condition, vbCrLF, "<br>")%></font></td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<p>
<%If CheckPermission(PM_MITSUMORI) Then%>
<%search.WriteCondition%>
			<input type="submit" value="�@�����ς�@">
<%End If%>
			<input type="button" value="�@�Č����@" onclick="history.back()">
</p>
</form>
<p>�@</p>
<div align="center">
	<table width="700" border="2" cellspacing="0" cellpadding="0" bordercolor="peru">
		<tr>
			<td>
				
          <table border=0 cellspacing=10 cellpadding=0 width='100%' align="center">
            <tr> 
              <td class="info" valign='TOP' width="35"> 
                <div align="right"><font color="#336699">��</font></div>
              </td>
              <td class="info"> �z�M�����ɂ��܂��ẮA���[�����e�Ƃ̕s��v������ƃZ���^�[�Ŕ��f�����Ă����������ꍇ�A�z�M�ł��Ȃ��ꍇ���������܂��B�������������B</td>
            </tr>
            <tr> 
              <td class="info" valign='TOP' width="35"> 
                <div align="right"></div>
              </td>
              <td class="info"> 
                <p>���s��v������Ɣ��f�����Ă���������</p>
                <ul>
                  <li>�������Ƃ̈ē��F�u�����v�����łȂ��u�����v�ɂ��w�肪����B<br>
                    �i����������]���Ă���͖̂����҂݂̂Ɣ��f�����Ă��������܂��j</li>
                  <li>�}���V�������̈ē��F�u�����Ȃ��ˌ��āv�Ɏw�肪����B<br>
                    �i�}���V����������]���Ă���̂́u�����Ȃ�}���V�����v�Ƀ`�F�b�N���Ă��郁���o�[�݂̂Ɣ��f�����Ă��������܂��j</li>
                </ul>
              </td>
            </tr>
            <tr>
              <td class="info" valign='TOP' width="35">
                <div align="right"><font color="#336699">��</font></div>
              </td>
              <td class="info">�i���ݏ����Ō��������l���͂����܂ł����Q�l�l�ł��B<br>
                ���M��~�A���މ�Ȃǂɂ��A�P�`�Q�����x�ʐ��͐����ϓ��������܂��B<br>
                �\�߂��������������B</td>
            </tr>
          </table>
			</td>
		</tr>
	</table>
</div>


</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
