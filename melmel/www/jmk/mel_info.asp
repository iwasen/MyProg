<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :�߂�߂�Enet�����Ǘp�y�[�W
' Content:���s�ҏ��Q�Ɖ��
'******************************************************

SetHeaderTitle "���s�ҏ��", TC_HAKKOUSHA
Response.CacheControl = "no-cache"
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<meta name="GENERATOR" Content="Microsoft Visual Studio 6.0">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="admin.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnClick_mag_id(id) {
  var win;
  win = window.open("mag_info.asp?id=" + id, "mag_info", "resizable=yes,scrollbars=yes,width=740");
  win.focus();
}
//-->
</SCRIPT>
</head>
<body>

<%
Dim DB, RS, SQL, mel_id

mel_id = Request.QueryString("id")
If IsEmpty(mel_id) Then
  Writeln "�w�肵�����s�҂h�c�̃f�[�^���݂���܂���B"
Else
  Set DB = OpenDB()
  Set RS = Server.CreateObject("ADODB.RecordSet")
  SQL = "SELECT *" & vbCrLf & _
        "FROM T_MELMEL" & vbCrLf & _
        "WHERE MM_mel_id='" & mel_id & "'"
  RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
%>
<center>
  <input type="hidden" name="mel_id" value="<%=RS("MM_mel_id")%>">
  <table border=0 cellspacing=0 cellpadding=0 width='700'>
    <tr>
      <td>�����s�ҏ��</td>
    </tr>
    <tr>
      <td>
        <table border=0 cellspacing=2 cellpadding=3 width="100%">
          <tr>
            <td class="m1" width="22%">���s��ID</td>
            <td class="n1"><%=RS("MM_mel_id")%></td>
          </tr>
          <tr>
            <td class="m1">�p�X���[�h</td>
            <td class="n1"><%=ReplaceTag(RS("MM_password"))%></td>
          </tr>
          <tr>
            <td class="m1">���s�Җ�</td>
            <td class="n1"><%=ReplaceTag(RS("MM_h_name"))%></td>
          </tr>
          <tr>
            <td class="m1">e-mail�A�h���X</td>
            <td class="n1"><%=ReplaceTag(RS("MM_mail_adr"))%></td>
          </tr>
          <tr>
            <td class="m1">��Ж�</td>
            <td class="n1"><%=ReplaceTag(RS("MM_c_name"))%></td>
          </tr>
          <tr>
            <td class="m1">�g�o�̃A�h���X�P</td>
            <td class="n1">
<%
If Len(RS("MM_url1")) > 7 Then
	Writeln "<a href='" & RS("MM_url1") & "' target='url1'>" & ReplaceTag(RS("MM_url1")) & "</a>"
Else
	Writeln ReplaceTag(RS("MM_url1"))
End If
%>
						</td>
          </tr>
          <tr>
            <td class="m1">�g�o�̃A�h���X�Q</td>
            <td class="n1">
<%
If Len(RS("MM_url2")) > 7 Then
	Writeln "<a href='" & RS("MM_url2") & "' target='url2'>" & ReplaceTag(RS("MM_url2")) & "</a>"
Else
	Writeln ReplaceTag(RS("MM_url2"))
End If
%>
						</td>
          </tr>
          <tr>
            <td class="m1">�g�o�̃A�h���X�R</td>
            <td class="n1">
<%
If Len(RS("MM_url3")) > 7 Then
	Writeln "<a href='" & RS("MM_url3") & "' target='url3'>" & ReplaceTag(RS("MM_url3")) & "</a>"
Else
	Writeln ReplaceTag(RS("MM_url3"))
End If
%>
						</td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td>����s���</td>
    </tr>
    <tr>
      <td>
        <table border=0 cellspacing=2 cellpadding=3 width="100%">
          <tr>
            <td class="m1" width="22%">��s��</td>
            <td class="n1"><%=ReplaceTag(RS("MM_bk_nm"))%></td>
          </tr>
          <tr>
            <td class="m1">�x�X��</td>
            <td class="n1"><%=ReplaceTag(RS("MM_bk_st"))%></td>
          </tr>
          <tr>
            <td class="m1">�������</td>
            <td class="n1">
<%
  Select Case RS("MM_bk_knd")
  Case 1
    Response.Write "����"
  Case 2
    Response.Write "����"
  Case 3
    Response.Write "���~"
  End Select
%>
            </td>
          </tr>
          <tr>
            <td class="m1">�����ԍ�</td>
            <td class="n1"><%=RS("MM_bk_acc")%></td>
          </tr>
          <tr>
            <td class="m1">�������`�l</td>
            <td class="n1"><%=ReplaceTag(RS("MM_bk_knm"))%></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td>�����̑��̏��</td>
    </tr>
    <tr>
      <td>
        <table border=0 cellspacing=2 cellpadding=3 width="100%">
          <tr>
            <td class="m1" width="22%">���s�����}�K</td>
            <td class="n1">
              <table border=0 cellspacing=0 cellpadding=2>
<%
Dim RS2
SQL = "SELECT MG_mag_id,MG_m_name FROM T_MELMAG WHERE MG_mel_id='" & mel_id & "' ORDER BY MG_mag_id"
Set RS2 = DB.Execute(SQL)
Do Until RS2.EOF
  Writeln "<tr>"
  Writeln "<td><a href='JavaScript:OnClick_mag_id(""" & RS2("MG_mag_id") & """)'>" & RS2("MG_mag_id") & "</a></td>"
  Writeln "<td>" & ReplaceTag(RS2("MG_m_name")) & "</td>"
  Writeln "</tr>"
  RS2.MoveNext
Loop
RS2.Close
%>
            </table>
          </tr>
          <tr>
            <td class="m1" width="22%">���|�C���g��</td>
            <td class="n1"><%=FormatNumber(NVL(RS("MM_point"), 0), 2, -1, 0, 0)%></td>
          </tr>
            <td class="m1" width="22%">�ŐV�|�C���g���Z��</td>
            <td class="n1">
<%
SQL = "SELECT MAX(SP_req_date)" & vbCrLf & _
			"FROM T_SPOINT" & vbCrLf & _
			"WHERE SP_mel_id='" & mel_id & "'"
Set RS2 = DB.Execute(SQL)
If Not IsNull(RS2(0)) Then
	Writeln DateFormat(RS2(0))
Else
	Writeln "�|�C���g���Z����"
End If
RS2.Close
%>
						</td>
					</tr>
					<tr>
            <td class="m1" width="22%">�ŐV�L���f�ړ�</td>
            <td class="n1">
<%
SQL = "SELECT MAX(RD_pub_date)" & vbCrLf & _
			"FROM (T_MELMAG INNER JOIN T_REPORTLOG ON MG_mag_id=RP_mag_id)" & vbCrLf & _
			"INNER JOIN T_REPORTDAT ON RP_seq_no=RD_seq_no" & vbCrLf & _
			"WHERE MG_mel_id='" & mel_id & "'"
Set RS2 = DB.Execute(SQL)
If Not IsNull(RS2(0)) Then
	Writeln DateFormat(RS2(0))
Else
	Writeln "�L���f�ږ���"
End If
RS2.Close
%>
						</td>
					</tr>
          <tr>
            <td class="m1">���</td>
            <td class="n1">
<%
Select Case RS("MM_status")
Case 0
	Response.Write "������"
Case 1
	Response.Write "�ꎞ��~"
Case 8
	Response.Write "�x�"
Case 9
	Response.Write "�މ�"
End Select
%>
            </td>
          </tr>
          <tr>
            <td class="m1">�o�^��</td>
            <td class="n1"><%=TimeFormat(RS("MM_regist_date"))%></td>
          </tr>
          <tr>
            <td class="m1">�ŏI�X�V��</td>
            <td class="n1"><%=TimeFormat(RS("MM_last_update"))%></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
<%
  RS.Close
End If
%>
</body>
</html>
