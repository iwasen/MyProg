<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<!--#INCLUDE FILE="../inc/permission.asp"-->
<%
'******************************************************
' System :はいめーる・net営業用ページ
' Content:代理店情報変更処理
'******************************************************

'=== 代理店情報取得 ===
Sub GetDairiten(dairiten_id)
	Dim sql, ds

	sql = "SELECT * FROM T_DAIRITEN WHERE DA_dairiten_id='" & dairiten_id & "'"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		name = ds("DA_name").Value
		name_kana = ds("DA_name_kana").Value
		zipcode = ds("DA_zipcode").Value
		address1 = ds("DA_address1").Value
		address2 = ds("DA_address2").Value
		d_class = ds("DA_class").Value
		pm_own.permission = ds("DA_permission").Value
		pm_client.permission = ds("DA_pm_client").Value
	End If
End Sub

'=== 代理店ID取得 ===
Private Function GetDairitenID(operator_id)
	Dim sql, ds

	sql = "SELECT OP_dairiten_id FROM T_OPERATOR WHERE OP_operator_id='" & operator_id & "'"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		GetDairitenID = ds(0).Value
	End If
End Function

'=== 代理店権限取得 ===
Sub GetPermission(dairiten_id)
	Dim sql, ds

	sql = "SELECT DA_pm_agent,DA_pm_client FROM T_DAIRITEN WHERE DA_dairiten_id='" & dairiten_id & "'"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		pm_rep_own.permission = ds("DA_pm_agent").Value
		pm_rep_client.permission = ds("DA_pm_client").Value
	End If
End Sub

'=== メイン処理 ===
Dim dairiten_id, name, name_kana, zipcode, address1, address2, d_class, rep_id
Dim pm_own, pm_client, pm_rep_own, pm_rep_client

dairiten_id = Request.QueryString("id")
If IsEmpty(dairiten_id) Then
	SystemError "empty dairiten_id"
End If

Set pm_own = New CPermission
Set pm_client = New CPermission
Set pm_rep_own = New CPermission
Set pm_rep_client = New CPermission

GetDairiten dairiten_id

rep_id = GetDairitenID(g_login_id)
GetPermission rep_id

SetHeaderTitle "代理店情報変更"
Response.CacheControl = "no-cache"
%>
<HTML>
<HEAD>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnSubmit_form1() {
 with (document.form1) {
    if (name.value == "") {
      alert("代理店名を入力してください。");
      name.focus();
      return false;
    }
	}
	return confirm("更新します。よろしいですか？");
}
function OnClick_sakujo() {
	if (confirm("この代理店を削除してよろしいですか？")) {
    location.href = "dairiten4.asp?id=<%=dairiten_id%>";
	}
}
//-->
</SCRIPT>
<%
PermissionScript
%>
</HEAD>
<BODY>
<!--#INCLUDE FILE="../inc/header.asp"-->
<center>
<form method="post" action="dairiten3.asp" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<tr>
			<td class="m0">■代理店情報</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3" width="30%">代理店ＩＤ　</td>
						<td class="n3"><input type="hidden" name="dairiten_id" value="<%=dairiten_id%>"><%=dairiten_id%></td>
					</tr>
					<tr>
						<td class="m3">代理店名称</td>
						<td class="n3">
							<input size=50 name="name" maxlength=50 value="<%=HTMLEncode(name)%>">
							<font class="note">（全角５０文字以内）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">代理店名称（かな）</td>
						<td class="n3">
							<input size=50 name="name_kana" maxlength=100 value="<%=HTMLEncode(name_kana)%>">
							<font class="note">（半角１００文字以内）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">郵便番号</td>
						<td class="n3">
							<input size=3 name="zipcode1" maxlength=3 value="<%=Mid(zipcode,1,3)%>"> - <input size=4 name="zipcode2" maxlength=4 value="<%=Mid(zipcode,4,4)%>">
							<font class="note">（数字３桁－４桁）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">住所１</td>
						<td class="n3">
							<input size=50 name="address1" maxlength=50 value="<%=HTMLEncode(address1)%>">
							<font class="note">（全角５０文字以内）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">住所２</td>
						<td class="n3">
							<input size=50 name="address2" maxlength=50 value="<%=HTMLEncode(address2)%>">
							<font class="note">（全角５０文字以内）</font>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<span <%If Not CheckPermission(PM_AGENT_PERMISSION) Then Response.Write "style='display:none'"%>>
	<br>
	<table border=0 cellspacing=0 cellpadding=0 width="700">
		<tr>
			<td class="m0">■この代理店に許可する権限</td>
		</tr>
		<tr>
			<td>
<%
PermissionForm 2, pm_own, pm_rep_own
%>
			</td>
		</tr>
	</table>
	</span>
	<span <%If Not CheckPermission(PM_CLIENT_PERMISSION) Then Response.Write "style='display:none'"%>>
	<br>
	<table border=0 cellspacing=0 cellpadding=0 width="700">
		<tr>
			<td class="m0">■クライアントに許可する権限</td>
		</tr>
		<tr>
			<td>
<%
PermissionForm 3, pm_client, pm_rep_client
%>
			</td>
		</tr>
	</table>
	</span>
	<br>
	<input type="submit" value=" 更新 ">
	<input type="reset" value="リセット">
	<input type="button" value=" 削除 " onclick="OnClick_sakujo()">
	<input type="button" value=" 戻る " onclick="history.back()">
</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</BODY>
</HTML>
