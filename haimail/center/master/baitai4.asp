<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�}�̃}�X�^�X�V����
'******************************************************

SetHeaderTitle "�}�̃}�X�^�X�V"
Response.CacheControl = "no-cache"

Dim ds, sql, id, baitai_id
baitai_id = Request.Form("baitai_id")

If Not IsEmpty(baitai_id) Then
	baitai_id = MakeID(baitai_id, 2)

	sql = "SELECT max(BT_baitai_id) FROM T_BAITAI WHERE BT_baitai_id<>'99'"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	id = NVL(ds(0), 0) + 1

	sql = "SELECT * FROM T_BAITAI WHERE BT_baitai_id='" & baitai_id & "'"
	Set ds = CreateDynaset(sql, ORADYN_DEFAULT)
	If ds.EOF Then
		ds.Addnew
		ds("BT_baitai_id").Value = MakeID(id, 2)
	Else
		ds.Edit
	End If
	ds("BT_name").Value = Request.Form("baitai_name")
	ds("BT_order").Value = Request.Form("order")
	ds("BT_search_flag").Value = Request.Form("search_flag")
	ds.Update
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
<p>�X�V���܂����B</p>
<input type="button" value=" �n�j " onclick="location.href='baitai.asp'">
</center>


<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
<%
Else
	Response.Redirect("baitai.asp")
End If
%>