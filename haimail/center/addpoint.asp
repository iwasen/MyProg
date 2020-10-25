<!--#INCLUDE FILE="inc/common.asp"-->
<!--#INCLUDE FILE="inc/database.asp"-->
<!--#INCLUDE FILE="inc/sub.asp"-->
<!--#INCLUDE FILE="inc/login.asp"-->
<!--#INCLUDE FILE="inc/point.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:ポイント追加処理
'******************************************************

Dim sql, ds
Dim member_id, point_type, point, message

member_id = Request.Form("member_id")
point_type = Request.Form("point_type")
point = Request.Form("point")

If Not IsEmpty(member_id) Then
	member_id = Right(String(10, "0") & member_id, 10)
	sql = "SELECT * from T_MEMBER WHERE MM_member_id='" & member_id & "'"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		AddPoint member_id, point_type, CLng(point), Null
		message = "会員ID[" & member_id & "]に " & point & " ポイントを付与しました。"
	Else
		message = "会員ID[" & member_id & "]は存在しません。"
	End If
Else
	message = ""
End If

SetHeaderTitle "ポイント付与（暫定版）"
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnSubmit_form1() {
	with (document.form1) {
		if (member_id.value == "") {
			alert("会員IDを入力してください。");
			member_id.focus();
			return false;
		}
		if (point_type.value == "") {
			alert("ポイント種類を選択してください。");
			point_type.focus();
			return false;
		}
		if (point.value == "") {
			alert("ポイント数を選択してください。");
			point.focus();
			return false;
		}
	}
	return true;
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="inc/header.asp"-->

<center>
<%If message = "" Then%>
<form method="post" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<tr>
			<td class="m0">■ポイント付与情報</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3" width="22%">会員ID</td>
						<td class="n3"><input type="text" name="member_id"></td>
					</tr>
					<tr>
						<td class="m3">ポイント種類</td>
						<td class="n3">
							<select name="point_type">
								<option value="">- 選択してください -</option>
								<option value="01">被紹介ポイント(01)</option>
								<option value="02">ごめんなさいポイント(02)</option>
								<option value="03">紹介ポイント(03)</option>
								<option value="04">個人アフィリエイトポイント(04)</option>
								<option value="10">受信ポイント(10)</option>
								<option value="11">アンケートポイント(11)</option>
								<option value="12">感想文ポイント(12)</option>
								<option value="12">手動加算ポイント(13)</option>
							</select>
						</td>
					</tr>
					<tr>
						<td class="m3">ポイント数</td>
						<td class="n3"><input type="text" name="point"></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
	<input type="submit" value=" 付与 ">
	<input type="reset" value="リセット">
</form>
<%Else%>
<form>
	<p><%=message%></p>
	<input type="button" value="　戻る　" onclick="location.href='addpoint.asp'">
</form>
<%End If%>
</center>

<!--#INCLUDE FILE="inc/footer.asp"-->

</body>
</html>
