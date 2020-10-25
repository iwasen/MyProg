<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:発行者情報参照画面
'******************************************************

SetHeaderTitle "発行者情報", TC_HAKKOUSHA
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
  Writeln "指定した発行者ＩＤのデータがみつかりません。"
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
      <td>■発行者情報</td>
    </tr>
    <tr>
      <td>
        <table border=0 cellspacing=2 cellpadding=3 width="100%">
          <tr>
            <td class="m1" width="22%">発行者ID</td>
            <td class="n1"><%=RS("MM_mel_id")%></td>
          </tr>
          <tr>
            <td class="m1">パスワード</td>
            <td class="n1"><%=ReplaceTag(RS("MM_password"))%></td>
          </tr>
          <tr>
            <td class="m1">発行者名</td>
            <td class="n1"><%=ReplaceTag(RS("MM_h_name"))%></td>
          </tr>
          <tr>
            <td class="m1">e-mailアドレス</td>
            <td class="n1"><%=ReplaceTag(RS("MM_mail_adr"))%></td>
          </tr>
          <tr>
            <td class="m1">会社名</td>
            <td class="n1"><%=ReplaceTag(RS("MM_c_name"))%></td>
          </tr>
          <tr>
            <td class="m1">ＨＰのアドレス１</td>
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
            <td class="m1">ＨＰのアドレス２</td>
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
            <td class="m1">ＨＰのアドレス３</td>
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
      <td>■銀行情報</td>
    </tr>
    <tr>
      <td>
        <table border=0 cellspacing=2 cellpadding=3 width="100%">
          <tr>
            <td class="m1" width="22%">銀行名</td>
            <td class="n1"><%=ReplaceTag(RS("MM_bk_nm"))%></td>
          </tr>
          <tr>
            <td class="m1">支店名</td>
            <td class="n1"><%=ReplaceTag(RS("MM_bk_st"))%></td>
          </tr>
          <tr>
            <td class="m1">口座種類</td>
            <td class="n1">
<%
  Select Case RS("MM_bk_knd")
  Case 1
    Response.Write "普通"
  Case 2
    Response.Write "当座"
  Case 3
    Response.Write "貯蓄"
  End Select
%>
            </td>
          </tr>
          <tr>
            <td class="m1">口座番号</td>
            <td class="n1"><%=RS("MM_bk_acc")%></td>
          </tr>
          <tr>
            <td class="m1">口座名義人</td>
            <td class="n1"><%=ReplaceTag(RS("MM_bk_knm"))%></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td><br></td>
    </tr>
    <tr>
      <td>■その他の情報</td>
    </tr>
    <tr>
      <td>
        <table border=0 cellspacing=2 cellpadding=3 width="100%">
          <tr>
            <td class="m1" width="22%">発行メルマガ</td>
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
            <td class="m1" width="22%">現ポイント数</td>
            <td class="n1"><%=FormatNumber(NVL(RS("MM_point"), 0), 2, -1, 0, 0)%></td>
          </tr>
            <td class="m1" width="22%">最新ポイント精算日</td>
            <td class="n1">
<%
SQL = "SELECT MAX(SP_req_date)" & vbCrLf & _
			"FROM T_SPOINT" & vbCrLf & _
			"WHERE SP_mel_id='" & mel_id & "'"
Set RS2 = DB.Execute(SQL)
If Not IsNull(RS2(0)) Then
	Writeln DateFormat(RS2(0))
Else
	Writeln "ポイント精算無し"
End If
RS2.Close
%>
						</td>
					</tr>
					<tr>
            <td class="m1" width="22%">最新広告掲載日</td>
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
	Writeln "広告掲載無し"
End If
RS2.Close
%>
						</td>
					</tr>
          <tr>
            <td class="m1">状態</td>
            <td class="n1">
<%
Select Case RS("MM_status")
Case 0
	Response.Write "活動中"
Case 1
	Response.Write "一時停止"
Case 8
	Response.Write "休会中"
Case 9
	Response.Write "退会"
End Select
%>
            </td>
          </tr>
          <tr>
            <td class="m1">登録日</td>
            <td class="n1"><%=TimeFormat(RS("MM_regist_date"))%></td>
          </tr>
          <tr>
            <td class="m1">最終更新日</td>
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
