<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:発行者マスタ修正画面
'******************************************************

SetHeaderTitle "発行者マスタ修正", TC_MASTER

Dim mel_id

mel_id = Request.QueryString("id")
If IsEmpty(mel_id) Then
  Response.Redirect("index.asp")
End If
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
function OnSubmit_form1() {
  return confirm("更新します。よろしいですか？");
}
function OnClick_sakujo() {
  if (confirm("この発行者を削除してよろしいですか？")) {
    location.href = "mel_master4.asp?id=<%=mel_id%>";
  }
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="admin_header.asp"-->

<%
Dim DB, RS, SQL

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")
SQL = "SELECT * FROM T_MELMEL WHERE MM_mel_id='" & mel_id & "'"
RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
%>
<center>
<form method="post" action="mel_master3.asp" name="form1" onSubmit="return OnSubmit_form1();">
  <input type="hidden" name="mel_id" value="<%=RS("MM_mel_id")%>">
  <table border=0 cellspacing=0 cellpadding=0 width='700'>
    <tr>
      <td>■発行者情報</td>
    </tr>
    <tr>
      <td>
        <table border=0 cellspacing=2 cellpadding=3 width="100%">
          <tr>
            <td class="m3" width="22%">発行者ID</td>
            <td class="n3"><%=RS("MM_mel_id")%></td>
          </tr>
          <tr>
            <td class="m3">パスワード</td>
            <td class="n3"><input type="text" name="password" size=50 value="<%=RS("MM_password")%>"></td>
          </tr>
          <tr>
            <td class="m3">発行者名</td>
            <td class="n3"><input type="text" name="h_name" size=50 value="<%=RS("MM_h_name")%>"></td>
          </tr>
          <tr>
            <td class="m3">e-mailアドレス</td>
            <td class="n3"><input type="text" name="mail_adr" size=50 value="<%=RS("MM_mail_adr")%>"></td>
          </tr>
          <tr>
            <td class="m3">会社名</td>
            <td class="n3"><input type="text" name="c_name" size=50 value="<%=RS("MM_c_name")%>"></td>
          </tr>
          <tr>
            <td class="m3">ＨＰのアドレス１</td>
            <td class="n3"><input type="text" name="url1" size=60 value="<%=RS("MM_url1")%>">
<%
If Len(RS("MM_url1")) > 0 Then
  Writeln "<input type='button' value='参照' onClick='window.open(document.form1.url1.value, ""url1"")'>"
End If
%>
						</td>
          </tr>
          <tr>
            <td class="m3">ＨＰのアドレス２</td>
            <td class="n3"><input type="text" name="url2" size=60 value="<%=RS("MM_url2")%>">
<%
If Len(RS("MM_url2")) > 0 Then
  Writeln "<input type='button' value='参照' onClick='window.open(document.form1.url2.value, ""url2"")'>"
End If
%>
						</td>
          </tr>
          <tr>
            <td class="m3">ＨＰのアドレス３</td>
            <td class="n3"><input type="text" name="url3" size=60 value="<%=RS("MM_url3")%>">
<%
If Len(RS("MM_url3")) > 0 Then
  Writeln "<input type='button' value='参照' onClick='window.open(document.form1.url3.value, ""url3"")'>"
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
            <td class="m3" width="22%">銀行名</td>
            <td class="n3"><input type="text" name="bk_nm" size=50 value="<%=RS("MM_bk_nm")%>" maxlength="100"></td>
          </tr>
          <tr>
            <td class="m3">支店名</td>
            <td class="n3"><input type="text" name="bk_st" size=50 value="<%=RS("MM_bk_st")%>" maxlength="100"></td>
          </tr>
          <tr>
            <td class="m3">口座種類</td>
            <td class="n3">
              <select name="bk_knd">
                <option value="1" <%=Selected(RS("MM_bk_knd"), 1)%>>普通</option>
                <option value="2" <%=Selected(RS("MM_bk_knd"), 2)%>>当座</option>
                <option value="3" <%=Selected(RS("MM_bk_knd"), 3)%>>貯蓄</option>
              </select>
            </td>
          </tr>
          <tr>
            <td class="m3">口座番号</td>
            <td class="n3"><input type="text" name="bk_acc" size="50" value="<%=RS("MM_bk_acc")%>" maxlength="100"></td>
          </tr>
          <tr>
            <td class="m3">口座名義人</td>
            <td class="n3"><input type="text" name="bk_knm" size="50" value="<%=RS("MM_bk_knm")%>" maxlength="100"></td>
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
            <td class="m3" width="22%">発行メルマガ</td>
            <td class="n3">
              <table border=0 cellspacing=0 cellpadding=2>
<%
Dim RS2
SQL = "SELECT MG_mag_id,MG_m_name FROM T_MELMAG WHERE MG_mel_id='" & mel_id & "' ORDER BY MG_mag_id"
Set RS2 = DB.Execute(SQL)
Do Until RS2.EOF
  Writeln "<tr>"
  Writeln "<td><a href='mag_master2.asp?id=" & RS2("MG_mag_id") & "'>" & RS2("MG_mag_id") & "</a></td>"
  Writeln "<td>" & ReplaceTag(RS2("MG_m_name")) & "</td>"
  Writeln "</tr>"
  RS2.MoveNext
Loop
RS2.Close
%>
            </table>
          </tr>
          <tr>
            <td class="m3" width="22%">現ポイント数</td>
            <td class="n3"><input type="text" name="point" value="<%=FormatNumber(NVL(RS("MM_point"), 0), 2, -1, 0, 0)%>"></td>
          </tr>
            <td class="m3" width="22%">最新ポイント精算日</td>
            <td class="n3">
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
            <td class="m3" width="22%">最新広告掲載日</td>
            <td class="n3">
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
            <td class="m3">状態</td>
            <td class="n3">
              <select name="status">
                <option value="0" <%=Selected(RS("MM_status"), 0)%>>活動中</option>
                <option value="1" <%=Selected(RS("MM_status"), 1)%>>一時停止</option>
                <option value="8" <%=Selected(RS("MM_status"), 8)%>>休会中</option>
                <option value="9" <%=Selected(RS("MM_status"), 9)%>>退会</option>
              </select>
            </td>
          </tr>
          <tr>
            <td class="m3">登録日</td>
            <td class="n3"><%=TimeFormat(RS("MM_regist_date"))%></td>
          </tr>
          <tr>
            <td class="m3">最終更新日</td>
            <td class="n3"><%=TimeFormat(RS("MM_last_update"))%></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <br>
  <input type="submit" value=" 更新 ">
  <input type="reset" value="リセット">
  <input type="button" value=" 削除 " onClick="OnClick_sakujo()">
  <input type="button" value=" 戻る " onClick="history.back()">

</form>
</center>

<%
RS.Close
%>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
