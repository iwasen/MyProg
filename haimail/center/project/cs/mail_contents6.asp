<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/sub.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/login.asp"-->
<!--#INCLUDE FILE="../../inc/html.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局・営業共通ページ
' Content:画像ファイル画面処理
'******************************************************

'=== メールコンテンツ取得 ===
Private Function ReadContents(job_id)
	Dim sql, ds

	sql = "SELECT SM_mail_id,SM_body,SM_header,SM_footer,SM_impression" & vbCrLf & _
				"FROM T_JOB,T_SEND_MAIL" & vbCrLf & _
				"WHERE JB_job_id=" & job_id & " AND JB_mail_id=SM_mail_id"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		mail_id = ds("SM_mail_id").Value
		ReadContents = ds("SM_header").Value & ReadCLOB(ds("SM_body").Value) & ds("SM_impression").Value & ds("SM_footer").Value
	End If
End Function

'=== <IMG>タグ処理 ===
Private Sub CheckImgTag(s, img)
	Dim n1, n2, element_name, element_val, tag_name, src
	Dim sql, ds

	n1 = 1
	Do While True
		n2 = InStr(n1, s, "<")
		If n2 > 0 Then
			tag_name = ""
			src = ""
			n2 = n2 + 1
			Do While True
				element_name = HtmlElementName(s, n2)
				If element_name = "" Then
					Exit Do
				End If

				If tag_name = "" Then
					tag_name = element_name
				Else
					element_val = HtmlElementValue(s, n2)
					If (tag_name = "img" And element_name = "src") Or (tag_name = "body" And element_name = "background") Or (tag_name = "table" And element_name = "background") Then
						src = element_val
					End If
				End If
			Loop

			If src <> "" And Left(src, 5) <> "http:" Then
				sql = "SELECT * FROM T_ATTACHMENT WHERE AT_mail_id='" & mail_id & "' AND AT_file_name='" & src & "'"
				Set ds = CreateDynaset(sql, ORADYN_READONLY)
				img(src) = Not ds.EOF
			End If

			n1 = n2 + 1
		Else
			Exit Do
		End If
	Loop
End Sub

'=== メイン処理 ===
Dim job_id, line, url, body, img, mail_id

job_id = Request.QueryString("job_id")
If job_id = "" Then
	SystemError "job_id is empty"
End If

body = ReadContents(job_id)

Set img = Server.CreateObject("Scripting.Dictionary")
CheckImgTag body, img

If img.Count = 0 Then
	Response.Redirect "mail_contents7.asp?job_id=" & job_id
End If

SetHeaderTitle "画像ファイル登録"
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnSubmit_form1(fm) {
	var pict_file, pict_reg;
	for (var i = 0; i < <%=img.Count%>; i++) {
		pict_reg = fm("pict_reg" + (i + 1));
		pict_file = fm("pict_file" + (i + 1));
		if (pict_reg.value == "0" && pict_file.value == "") {
			alert("画像ファイル名を入力してください。");
			pict_file.focus();
			return false;
		}
	}
	return true;
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../../inc/header.asp"-->

<center>
<form method="post" action="mail_contents7.asp" name="form1" ENCtype="multipart/form-data" onsubmit="return OnSubmit_form1(this)">
<input type="hidden" name="job_id" value="<%=job_id%>">
<table border=0 cellpadding=0 cellspacing=0 width="80%">
	<tr>
		<td class="m0">■画像ファイルを入力してください</td>
	</tr>
	<tr>
		<td>
			<table border=1 cellpadding=0 cellspacing=1 width="100%">
				<tr bgcolor="#E0FFE0">
					<th>画像ファイルURL</th>
					<th>登録</th>
					<th>画像ファイル入力</th>
				</tr>
<%
line = 1
For Each url In img.Keys
Writeln "<tr class='tc" & line Mod 2 & "'>"
Writeln "<td><input type='hidden' name='pict_url" & line & "' value='" & url & "'>" & url & "</td>"
If img(url) Then
	Writeln "<td align='center'><input type='hidden' name='pict_reg" & line & "' value='1'>登録済</td>"
Else
	Writeln "<td align='center'><input type='hidden' name='pict_reg" & line & "' value='0'>未登録</td>"
End If
Writeln "<td><input type='file' name='pict_file" & line & "' size=60></td>"
Writeln "</tr>"

line = line + 1
Next
%>
			</table>
		</td>
	</tr>
</table>
<br>
<input type="submit" value=" 登録 ">
<input type="button" value=" 戻る " onclick="history.back()">
</form>

<!--#INCLUDE FILE="../../inc/footer.asp"-->

</body>
</html>
