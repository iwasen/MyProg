<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<!--#INCLUDE FILE="kaiyaku_inc.asp"-->

<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:自己申告部数チェックの処置
'******************************************************

Dim DB, SQL, rc, i, mag_id, shochi

rc = Request.Form("rc")
If Not IsEmpty(rc) Then
  rc = CLng(rc)

  Set DB = OpenDB()

  For i = 1 To rc
    mag_id = Request.Form("mag_id" & i)
    shochi = Request.Form("shochi" & i)

    If shochi = "ok" Then
      SQL = "UPDATE T_MELMAG" & vbCrLf & _
            "SET MG_magmag_keisuu=(SELECT MB_busuu FROM T_MAG2BUSUU WHERE MB_mag_id=MG_mag_id) / CAST(MG_new_busuu AS REAL)" & vbCrLf & _
            "WHERE MG_mag_id='" & mag_id & "'"
      DB.Execute SQL
    ElseIf shochi = "ng" Then
      Call kaiyaku(mag_id)
    End If
  Next
End If

Response.Redirect("busuu_check2.asp")
%>
