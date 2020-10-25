<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/sub.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/login.asp"-->
<!--#INCLUDE FILE="../../inc/mail_check.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局・営業共通ページ
' Content:メールコンテンツ登録処理
'******************************************************

'=== メールコンテンツ取得 ===
Private Function ReadContents(job_id)
	Dim sql, ds

	sql = "SELECT SM_body,SM_header,SM_footer,SM_impression,SM_html_pict" & vbCrLf & _
				"FROM T_JOB,T_SEND_MAIL" & vbCrLf & _
				"WHERE JB_job_id=" & job_id & " AND JB_mail_id=SM_mail_id"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		html_pict = ds("SM_html_pict").Value
		ReadContents = ds("SM_header").Value & ReadCLOB(ds("SM_body").Value) & ds("SM_impression").Value & ds("SM_footer").Value
	End If
End Function

'=== <IMG>タグのsrc取得 ===
Private Sub CheckImgTag(s, img)
	Dim n1, n2, n3, element_name, element_val, tag_name, src

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
					n3 = n2
					element_val = HtmlElementValue(s, n2)
					If (tag_name = "img" And element_name = "src") Or (tag_name = "body" And element_name = "background") Or (tag_name = "table" And element_name = "background") Then
						src = element_val
					End If
				End If
			Loop

			If src <> "" And (Left(src, 5) <> "http:" Or html_pict = "0") Then
				img(src) = Null
			End If

			n1 = n2 + 1
		Else
			Exit Do
		End If
	Loop
End Sub

'=== 画像ファイル取得 ===
Private Function ReadPictFile(job_id, img)
	Dim objXML, url, count, pict_url, pict_file

	On Error Resume Next

	Set objXML = Server.CreateObject("Microsoft.XMLHTTP")

	CheckImgTag ReadContents(job_id), img

	For Each url In img.Keys
		If Left(url, 5) = "http:" Then
			objXML.Open "GET", url, False
			objXML.Send
			If objXML.Status = 200 Then
				img(url) = objXML.responseBody
			End If
		Else
			count = 0
			Do While True
				count = count + 1
				pict_url = basp.Form(b, "pict_url" & count)
				pict_file = basp.FormBinary(b, "pict_file" & count)
				If pict_url = url Then
					If LenB(pict_file) > 0 Then
						img(url) = pict_file
					ElseIf  basp.Form(b, "pict_reg" & count) = "1" Then
						img(url) = ""
					End If
					Exit Do
				ElseIf pict_url = "" Then
					Exit Do
				End If
			Loop
		End If
	Next

End Function

'=== 画像ファイル登録 ===
Private Sub WritePictFile(job_id, img)
	Dim sql, ds, mail_id, seq_no, url, pict, cond

	sql = "SELECT JB_mail_id FROM T_JOB WHERE JB_job_id=" & job_id
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		mail_id = ds(0).Value
	Else
		Exit Sub
	End If

	sql = "SELECT MAX(AT_seq_no) FROM T_ATTACHMENT WHERE AT_mail_id='" & mail_id & "'"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		seq_no = NVL(ds(0).Value, 0)
	Else
		seq_no = 0
	End If

	If seq_no < 10 Then
		seq_no = 10
	End If

	cond = ""
	For Each url In img.Keys
		pict = img(url)
		If LenB(pict) > 0 Then
			sql = "SELECT * FROM T_ATTACHMENT WHERE AT_mail_id='" & mail_id & "' AND AT_file_name='" & url & "'"
			Set ds = CreateDynaset(sql, ORADYN_DEFAULT)
			If ds.EOF Then
				seq_no = seq_no + 1
				ds.AddNew
				ds("AT_mail_id").Value = mail_id
				ds("AT_seq_no").Value = seq_no
				ds("AT_file_name").Value = url
				ds("AT_content_type").Value = mail_id & MakeID(seq_no, 4)
				ds("AT_file_body").Value = Empty
				ds.Update
			End If

			ds.Edit
			ds("AT_file_body").Value.Trim 0
			ds("AT_file_body").Value.Write pict, LenB(pict)
			ds.Update
		End If
		If cond <> "" Then
			cond = cond & ","
		End If
		cond = cond & "'" & SqlStr(url) & "'"
	Next

	If cond <> "" Then
		 cond = " AND AT_file_name NOT IN (" & cond & ")"
	End If

	sql = "DELETE T_ATTACHMENT WHERE AT_mail_id='" & mail_id & "' AND AT_content_type IS NOT NULL" & cond
	ExecuteSQL sql
End Sub

'=== メイン処理 ===
SetHeaderTitle "メールコンテンツ登録"

Dim job_id, msg, b, basp, img, html_pict, url, err_file

b = Request.BinaryRead(Request.TotalBytes)
Set basp = Server.CreateObject("basp21")

job_id = Request.QueryString("job_id")
If IsEMpty(job_id) Then
	job_id = basp.Form(b, "job_id")
	If job_id = "" Then
		SystemError "job_id is empty"
	End If
End If

'画像ファイル取得
Set img = Server.CreateObject("Scripting.Dictionary")
ReadPictFile job_id, img

err_file = ""
For Each url In img.Keys
	If IsNull(img(url)) Then
		err_file = err_file & url & "<br>"
	End If
Next

If err_file = "" Then
	WritePictFile job_id, img
	msg = "メールコンテンツを登録しました。"
Else
	msg = "以下の画像ファイルを取得できませんでした。<br><br>" & err_file
End If
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../../haimail.css">
</head>
<body>

<!--#INCLUDE FILE="../../inc/header.asp"-->

<center>
<p><%=msg%></p>
<%If err_file = "" Then%>
<input type="button" value=" OK " onclick="location.href='job_contents.asp?job_id=<%=job_id%>'">
<%Else%>
<input type="button" value=" 戻る " onclick="location.href='mail_contents.asp?job_id=<%=job_id%>'">
<%End If%>
</center>

<!--#INCLUDE FILE="../../inc/footer.asp"-->

</body>
</html>
