<!--#INCLUDE FILE="melmel_inc.asp"-->
<%
Dim DB, RS, SQL
Dim param, koukoku_id, mag_id, sum, i, offset, rand, url, ip, temp

'�p�����[�^�擾
param = Request.QueryString("p")

'12�����łȂ���΃G���[
If Len(param) <> 12 Then
  Call ErrorParam(1)
End If

'�`�F�b�N�T��
sum = 0
For i = 1 To 12
  temp = Mid(param, i, 1)
  If temp < "0" Or temp > "9" Then
    Call ErrorParam(2)
  End If
  sum = (sum + CInt(temp)) Mod 10
Next
If sum <> 0 Then
  Call ErrorParam(3)
End If

'�L��ID.�����}�KID���o��
koukoku_id = Left(param, 6)
mag_id = Mid(param,7, 5)

'������
rand = "5374112481591282435131234042196122667712144723901683762966630824862004266405453034177296253053378416"

'�����}�KID����
offset = CLng(koukoku_id) Mod 100
temp = ""
For i = 1 To 5
  temp = temp & Chr((CInt(Mid(mag_id, i, 1)) - CInt(Mid(rand, offset + 1, 1)) + 10) Mod 10 + Asc("0"))
  offset = (offset + 1) Mod 100
Next
mag_id = temp

'�L��ID����
offset = CLng(mag_id) Mod 100
temp = ""
For i = 1 To 6
  temp = temp & Chr((CInt(Mid(koukoku_id, i, 1)) - CInt(Mid(rand, offset + 1, 1)) + 10) Mod 10 + Asc("0"))
  offset = (offset + 1) Mod 100
Next
koukoku_id = temp

'�L���}�X�^�����ѐ�URL���擾
Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")
SQL = "SELECT KK_koukoku_url,KK_status FROM V_KOUKOKU WHERE KK_koukoku_id='" & koukoku_id & "'"
RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
If RS.EOF Then
  RS.Close
  Call ErrorParam(4)
End If
If RS("KK_status") = 9 Then
  RS.Close
  Call ErrorParam(5)
End If
url = RS("KK_koukoku_url")
RS.Close

'��ѐ�URL���ݒ肳��Ă��Ȃ���΃G���[
If IsNull(url) Or isempty(url) Then
  Call ErrorParam(6)
End If

If mag_id <> "00000" Then
  ip = Request.ServerVariables("REMOTE_ADDR")
  '�N���b�N���O�ɋL�^
  SQL = "INSERT INTO T_CLICKLOG (CL_koukoku_id,CL_mag_id,CL_ip_adr,CL_date)" & vbCrLf & _
        "VALUES('" & koukoku_id & "','" & mag_id & "','" & ip & "',GETDATE())"
  DB.Execute SQL
End If

'URL�փW�����v
Response.Redirect(url)

Sub ErrorParam(err_code)
  Dim DB, SQL

  Set DB = OpenDB()
  SQL = "INSERT INTO T_CLICKERR (CE_err_code,CE_param,CE_ip_adr,CE_date)" & vbCrLF & _
        "VALUES(" & err_code & ",'" & Replace(param, "'", "''") & "','" & Request.ServerVariables("REMOTE_ADDR") & "',GETDATE())"
  DB.Execute SQL

  Response.Redirect "index.html"
End Sub
%>