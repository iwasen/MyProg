<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<!--#INCLUDE FILE="../inc/permission.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:代理店情報登録処理
'******************************************************

'=== 代理店権限取得 ===
Sub GetPermission(dairiten_id)
	Dim sql, ds

	sql = "SELECT DA_pm_agent,DA_pm_client FROM T_DAIRITEN WHERE DA_dairiten_id='" & dairiten_id & "'"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		pm_own.permission = ds("DA_pm_agent").Value
		pm_client.permission = ds("DA_pm_client").Value
	End If
End Sub

'=== レップ選択肢 ===
Sub SelectRep(dairiten_id)
	Dim sql, ds

	sql = "SELECT DA_dairiten_id,DA_name FROM T_DAIRITEN WHERE DA_class='A'"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Writeln "<option value=''" & Selected(dairiten_id, "") & ">- 選択してください -</option>"
	Do Until ds.EOF
		Writeln "<option value='" & ds("DA_dairiten_id").Value & "'" & Selected(dairiten_id, ds("DA_dairiten_id").Value) & ">" & ds("DA_name").Value & "</option>"
		ds.MoveNext
	Loop
End Sub

'=== メイン処理 ===
Dim dairiten_id, rep_id, d_class
Dim pm_own, pm_agent, pm_client, pm_def

Set pm_own = New CPermission
Set pm_agent = New CPermission
Set pm_client = New CPermission
Set pm_def = New CPermission
pm_def.SetAll

rep_id = Request.QueryString("rep")
If Not IsEmpty(rep_id) Then
	GetPermission rep_id
	d_class = "B"
End If

SetHeaderTitle "代理店情報登録"
Response.CacheControl = "no-cache"
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnSubmit_form1() {
	with (document.form1) {
		if (d_class.value == "") {
			alert("代理店種別を選択してください。");
			d_class.focus();
			return false;
		}
		if (name.value == "") {
			alert("代理店名称を入力してください。");
			name.focus();
			return false;
		}
	}
	return confirm("登録します。よろしいですか？");
}
function OnChange_d_class(c) {
	with (document.all) {
		if (c.value == "B")
			dairiten_kind.style.display = "";
		else {
			document.form1.rep.selectedIndex = 0;
			dairiten_kind.style.display = "none";
		}
		if (c.value == "A") {
			regist_agent1.disabled = false;
			agent_permission1.disabled = false;
			sanka.style.display = "";
		} else {
			regist_agent1.checked = false;
			regist_agent1.disabled = true;
			agent_permission1.disabled = true;
			sanka.style.display = "none";
		}
	}
}
function OnChange_rep(c) {
	if (c.selectedIndex > 0)
		location.href = "eigyou_d1.asp?rep=" + c.value;
}
//-->
</SCRIPT>
<%
PermissionScript
%>
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" action="eigyou_d3.asp" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width="700">
		<tr>
			<td class="m0">■代理店情報</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3" width="30%">代理店種別</td>
						<td class="n3">
							<select name="d_class" onchange="OnChange_d_class(this)">
								<option<%ValueSelected "", d_class%>>- 選択してください -</option>
								<option<%ValueSelected "A", d_class%>>レップ</option>
								<option<%ValueSelected "B", d_class%>>レップ傘下の代理店</option>
								<option<%ValueSelected "C", d_class%>>その他の代理店</option>
							</select>
							<span id="dairiten_kind"<%If d_class<>"B" Then Response.Write "style='display:none'"%>>&nbsp;
								レップ	<select name="rep_id" onchange="OnChange_rep(this)"><%SelectRep rep_id%></select>
							</span>
						</td>
					</tr>
					<tr>
						<td class="m3" width="30%">代理店名称</td>
						<td class="n3">
							<input size=50 name="name" maxlength=50>
							<font class="note">（全角５０文字まで）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">代理店名称（かな）</td>
						<td class="n3">
							<input size=50 name="name_kana" maxlength=100>
							<font class="note">（半角１００文字まで）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">郵便番号</td>
						<td class="n3">
							<input size=3 name="zipcode1" maxlength=3> - <input size=4 name="zipcode2" maxlength=4>
							<font class="note">（数字３桁－４桁）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">住所１</td>
						<td class="n3">
							<input size=50 name="address1" maxlength=50>
							<font class="note">（全角５０文字まで）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">住所２</td>
						<td class="n3">
							<input size=50 name="address2" maxlength=50>
							<font class="note">（全角５０文字まで）</font>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
	<table border=0 cellspacing=0 cellpadding=0 width="700">
		<tr>
			<td class="m0">■この代理店に許可する権限</td>
		</tr>
		<tr>
			<td>
<%
PermissionForm 1, pm_own, pm_def
%>
			</td>
		</tr>
	</table>
	<br>
	<span id="sanka"<%If d_class<>"A" Then Response.Write "style='display:none'"%>>
	<table border=0 cellspacing=0 cellpadding=0 width="700">
		<tr>
			<td class="m0">■傘下の代理店に許可する権限</td>
		</tr>
		<tr>
			<td>
<%
PermissionForm 2, pm_agent, pm_def
%>
			</td>
		</tr>
	</table>
	<br>
	</span>
	<table border=0 cellspacing=0 cellpadding=0 width="700">
		<tr>
			<td class="m0">■クライアントに許可する権限</td>
		</tr>
		<tr>
			<td>
<%
PermissionForm 3, pm_client, pm_def
%>
			</td>
		</tr>
	</table>
	<br>
	<input type="submit" value=" 登録 ">
	<input type="reset" value="リセット">
	<input type="button" value=" 戻る " onclick="location.href='eigyou.asp'">
</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
