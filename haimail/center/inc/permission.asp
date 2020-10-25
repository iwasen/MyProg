<%
Class CPermission
	Public permission
	Public jogai_baitai
	Public taishou_baitai

	Private Sub Class_Initialize()
		permission = String(100, "0")
		'SetPermission PM_REGIST_JOB_A, True
	End Sub

	Public Sub SetPermission(pm, flag)
		Dim s

		If flag Then
			s = "1"
		Else
			s = "0"
		End If
		permission = Left(permission, pm - 1) & s & Mid(permission, pm + 1)
	End Sub

	Public Function Check(pm)
		If Mid(permission, pm, 1) = "1" Then
			Check = True
		Else
			Check = False
		End If
	End Function

	Public Sub SetAll()
		permission = String(100, "1")
	End Sub
End Class

'=== "checked disabled" 出力 ===
Function CheckDisable(check, show)
	CheckDisable = ""
	If check Then
		CheckDisable = " checked"
	Else
		CheckDisable = ""
	End If

	If Not show Then
		CheckDisable = CheckDisable & " disabled"
	End If
End Function

Sub PermissionScript()
%>
<SCRIPT LANGUAGE=javascript>
<!--
function OnClick_regist_agent(n, c) {
	document.all("agent_permission" + n).disabled = !c.checked;
}
function OnClick_regist_client(n, c) {
	document.all("client_permission" + n).disabled = !c.checked;
}
function set_job_b(n, f) {
	with (document.all) {
		if (f) {
			item("job_bunkatsu_shoushin" + n).checked = false;
			item("job_henshin_point" + n).checked = false;
			item("job_soushin_taishou" + n).checked = false;
			item("job_jogai_taishou" + n).checked = false;
			item("job_hacchuu" + n).checked = false;
			item("job_click_counter" + n).checked = false;
		}
		f = !f;
		item("job_bunkatsu_shoushin" + n).disabled = f;
		item("job_henshin_point" + n).disabled = f;
		item("job_soushin_taishou" + n).disabled = f;
		item("job_jogai_taishou" + n).disabled = f;
		item("job_hacchuu" + n).disabled = f;
		item("job_click_counter" + n).disabled = f;
	}
}
function OnClick_job_a(n, c) {
	if (c.checked) {
		document.all("regist_job_b" + n).checked = false;
		set_job_b(n, false);
	}
}
function OnClick_job_b(n, c) {
	if (c.checked)
		document.all("regist_job_a" + n).checked = false;
	set_job_b(n, c.checked);
}
function OnClick_enq_use(n, c) {
	var f = c.checked ? false : true;
	with (document.all) {
		item("enq_log" + n).disabled = f;
		item("enq_csv" + n).disabled = f;
		item("enq_report" + n).disabled = f;
	}
}
function OnClick_imp_number(n, c) {
	if (c.checked) {
		document.all("imp_10log" + n).checked = false;
		document.all("imp_log" + n).checked = false;
	}
}
function OnClick_imp_10log(n, c) {
	if (c.checked) {
		document.all("imp_number" + n).checked = false;
		document.all("imp_log" + n).checked = false;
	}
}
function OnClick_imp_log(n, c) {
	if (c.checked) {
		document.all("imp_number" + n).checked = false;
		document.all("imp_10log" + n).checked = false;
	}
}
function BaitaiSelect(title, name) {
	var url;
	url = "<%=top_url%>common/baitai_select.asp?title=" + title + "&name=" + name + "&id=" + document.form1(name).value.split("\n").join(",");
	window.open(url, "baitai_select", "resizable=yes,scrollbars=yes,width=550");
}
function ShowHide(id, show) {
	if (show)
		document.all(id).style.display = "";
	else
		document.all(id).style.display = "none";
}
//-->
</SCRIPT>
<%
End Sub

Sub PermissionForm(n, pm, def)
%>
<table border=0 cellspacing=2 cellpadding=3 width="100%">
<%
If n = 1 Then
%>
	<tr>
		<td class="m3">代理店登録</td>
		<td class="n3">
			<input type="checkbox" name="pm<%=n%>" id="regist_agent<%=n%>" value="<%=PM_REGIST_AGENT%>"<%=CheckDisable(pm.Check(PM_REGIST_AGENT), d_class="A" And def.Check(PM_REGIST_AGENT))%> onclick="OnClick_regist_agent(<%=n%>,this)">代理店登録
			<input type="checkbox" name="pm<%=n%>" id="agent_permission<%=n%>" value="<%=PM_AGENT_PERMISSION%>"<%=CheckDisable(pm.Check(PM_AGENT_PERMISSION), d_class="A" And pm.Check(PM_REGIST_AGENT))%>>権限の設定
		</td>
	</tr>
<%
End If
If n <> 3 Then
%>
	<tr>
		<td class="m3">オペレータ登録</td>
		<td class="n3">
			<input type="checkbox" name="pm<%=n%>" value="<%=PM_REGIST_OPERATOR%>"<%=CheckDisable(pm.Check(PM_REGIST_OPERATOR), def.Check(PM_REGIST_OPERATOR))%>>オペレータ登録
		</td>
	</tr>
	<tr>
		<td class="m3">クライアント登録</td>
		<td class="n3">
			<input type="checkbox" name="pm<%=n%>" value="<%=PM_REGIST_CLIENT%>"<%=CheckDisable(pm.Check(PM_REGIST_CLIENT), def.Check(PM_REGIST_CLIENT))%> onclick="OnClick_regist_client(<%=n%>,this)">クライアント登録
			<input type="checkbox" name="pm<%=n%>" id="client_permission<%=n%>" value="<%=PM_CLIENT_PERMISSION%>"<%=CheckDisable(pm.Check(PM_CLIENT_PERMISSION), pm.Check(PM_REGIST_CLIENT))%>>権限の設定
		</td>
	</tr>
<%
End If
%>
	<tr>
		<td class="m3" width="30%">プロジェクト登録</td>
		<td class="n3">
			<nobr><input type="checkbox" name="pm<%=n%>" value="<%=PM_REGIST_PROJECT%>"<%=CheckDisable(pm.Check(PM_REGIST_PROJECT), def.Check(PM_REGIST_PROJECT))%>>プロジェクト登録</nobr>
			<nobr><input type="checkbox" name="pm<%=n%>" value="<%=PM_PROJECT_UPDATE%>"<%=CheckDisable(pm.Check(PM_PROJECT_UPDATE), def.Check(PM_PROJECT_UPDATE))%>>プロジェクト内容変更</nobr>
			<nobr><input type="checkbox" name="pm<%=n%>" value="<%=PM_PROJECT_DELETE%>"<%=CheckDisable(pm.Check(PM_PROJECT_DELETE), def.Check(PM_PROJECT_DELETE))%>>プロジェクト削除</nobr>
		</td>
	</tr>
	<tr>
		<td class="m3">ジョブ登録</td>
		<td class="n3">
			<nobr><input type="checkbox" name="pm<%=n%>" id="regist_job_a<%=n%>" value="<%=PM_REGIST_JOB_A%>"<%=CheckDisable(pm.Check(PM_REGIST_JOB_A), def.Check(PM_REGIST_JOB_A))%> onclick="OnClick_job_a(<%=n%>,this)">ジョブ登録(Ａ)</nobr>
			<nobr><input type="checkbox" name="pm<%=n%>" id="regist_job_b<%=n%>" value="<%=PM_REGIST_JOB_B%>"<%=CheckDisable(pm.Check(PM_REGIST_JOB_B), def.Check(PM_REGIST_JOB_B))%> onclick="OnClick_job_b(<%=n%>,this)">ジョブ登録(Ｂ)</nobr>
			<nobr><input type="checkbox" name="pm<%=n%>" value="<%=PM_JOB_UPDATE%>"<%=CheckDisable(pm.Check(PM_JOB_UPDATE), def.Check(PM_JOB_UPDATE))%>>ジョブ内容変更</nobr>
			<nobr><input type="checkbox" name="pm<%=n%>" value="<%=PM_JOB_DELETE%>"<%=CheckDisable(pm.Check(PM_JOB_DELETE), def.Check(PM_JOB_DELETE))%>>ジョブ削除</nobr>
		</td>
	</tr>
	<tr>
		<td class="m3">ジョブ登録(Ｂ)追加機能</td>
		<td class="n3">
			<nobr><input type="checkbox" name="pm<%=n%>" id="job_bunkatsu_shoushin<%=n%>" value="<%=PM_JOB_BUNKATSU_SOUSHIN%>"<%=CheckDisable(pm.Check(PM_JOB_BUNKATSU_SOUSHIN), pm.Check(PM_REGIST_JOB_B))%>>分割発信</nobr>
			<nobr><input type="checkbox" name="pm<%=n%>" id="job_henshin_point<%=n%>" value="<%=PM_JOB_HENSHIN_POINT%>"<%=CheckDisable(pm.Check(PM_JOB_HENSHIN_POINT), pm.Check(PM_REGIST_JOB_B))%>>返信ポイント</nobr>
			<nobr><input type="checkbox" name="pm<%=n%>" id="job_soushin_taishou<%=n%>" value="<%=PM_JOB_SOUSHIN_TAISHOU%>"<%=CheckDisable(pm.Check(PM_JOB_SOUSHIN_TAISHOU), pm.Check(PM_REGIST_JOB_B))%>>送信対象会員</nobr>
			<nobr><input type="checkbox" name="pm<%=n%>" id="job_jogai_taishou<%=n%>" value="<%=PM_JOB_JOGAI_TAISHOU%>"<%=CheckDisable(pm.Check(PM_JOB_JOGAI_TAISHOU), pm.Check(PM_REGIST_JOB_B))%>>除外／対象ジョブ指定</nobr>
			<nobr><input type="checkbox" name="pm<%=n%>" id="job_hacchuu<%=n%>" value="<%=PM_JOB_HACCHUU%>"<%=CheckDisable(pm.Check(PM_JOB_HACCHUU), pm.Check(PM_REGIST_JOB_B))%>>配信依頼</nobr>
			<nobr><input type="checkbox" name="pm<%=n%>" id="job_click_counter<%=n%>" value="<%=PM_JOB_CLICK_COUNTER%>"<%=CheckDisable(pm.Check(PM_JOB_CLICK_COUNTER), pm.Check(PM_REGIST_JOB_B))%>>クリックカウンタ</nobr>
		</td>
	</tr>
	<tr>
		<td class="m3">見積り／発注</td>
		<td class="n3">
			<nobr><input type="checkbox" name="pm<%=n%>" value="<%=PM_MITSUMORI%>"<%=CheckDisable(pm.Check(PM_MITSUMORI), def.Check(PM_MITSUMORI))%>>見積り</nobr>
			<nobr><input type="checkbox" name="pm<%=n%>" value="<%=PM_HACCHUU%>"<%=CheckDisable(pm.Check(PM_HACCHUU), def.Check(PM_HACCHUU))%>>発注</nobr>
		</td>
	</tr>
	<tr>
		<td class="m3">クリックカウンタ</td>
		<td class="n3">
			<nobr><input type="checkbox" name="pm<%=n%>" id="click_report<%=n%>" value="<%=PM_CLICK_REPORT%>"<%=CheckDisable(pm.Check(PM_CLICK_REPORT), True)%>>クリックレポート</nobr>
			<nobr><input type="checkbox" name="pm<%=n%>" id="click_url<%=n%>" value="<%=PM_CLICK_URL%>"<%=CheckDisable(pm.Check(PM_CLICK_URL), True)%>>ＵＲＬ別クリック率</nobr>
			<nobr><input type="checkbox" name="pm<%=n%>" id="click_csv<%=n%>" value="<%=PM_CLICK_CSV%>"<%=CheckDisable(pm.Check(PM_CLICK_CSV), True)%>>ＣＳＶ取得</nobr>
		</td>
	</tr>
	<tr>
		<td class="m3">アンケート</td>
		<td class="n3">
			<nobr><input type="checkbox" name="pm<%=n%>" value="<%=PM_ENQ_USE%>"<%=CheckDisable(pm.Check(PM_ENQ_USE), def.Check(PM_ENQ_USE))%> onclick="OnClick_enq_use(<%=n%>,this)">アンケート機能自体</nobr>
			<nobr><input type="checkbox" name="pm<%=n%>" id="enq_log<%=n%>" value="<%=PM_ENQ_LOG%>"<%=CheckDisable(pm.Check(PM_ENQ_LOG), pm.Check(PM_ENQ_USE))%>>返信ログ閲覧</nobr>
			<nobr><input type="checkbox" name="pm<%=n%>" id="enq_csv<%=n%>" value="<%=PM_ENQ_CSV%>"<%=CheckDisable(pm.Check(PM_ENQ_CSV), pm.Check(PM_ENQ_USE))%>>ＣＳＶ取得</nobr>
			<nobr><input type="checkbox" name="pm<%=n%>" id="enq_report<%=n%>" value="<%=PM_ENQ_REPORT%>"<%=CheckDisable(pm.Check(PM_ENQ_REPORT), pm.Check(PM_ENQ_USE))%>>レポート生成</nobr>
		</td>
	</tr>
	<tr>
		<td class="m3">感想文</td>
		<td class="n3">
			<nobr><input type="checkbox" name="pm<%=n%>" id="imp_number<%=n%>" value="<%=PM_IMP_NUMBER%>"<%=CheckDisable(pm.Check(PM_IMP_NUMBER), def.Check(PM_IMP_NUMBER))%> onclick="OnClick_imp_number(<%=n%>,this)">一覧の数値のみ</nobr>
			<nobr><input type="checkbox" name="pm<%=n%>" id="imp_10log<%=n%>" value="<%=PM_IMP_10LOG%>"<%=CheckDisable(pm.Check(PM_IMP_10LOG), True)%> onclick="OnClick_imp_10log(<%=n%>,this)">返信ログ１０行のみ</nobr>
			<nobr><input type="checkbox" name="pm<%=n%>" id="imp_log<%=n%>" value="<%=PM_IMP_LOG%>"<%=CheckDisable(pm.Check(PM_IMP_LOG), True)%> onclick="OnClick_imp_log(<%=n%>,this)">全返信ログ閲覧</nobr>
			<nobr><input type="checkbox" name="pm<%=n%>" value="<%=PM_IMP_CSV%>"<%=CheckDisable(pm.Check(PM_IMP_CSV), def.Check(PM_IMP_CSV))%>>ＣＳＶ取得</nobr>
			<nobr><input type="checkbox" name="pm<%=n%>" value="<%=PM_IMP_REPORT%>"<%=CheckDisable(pm.Check(PM_IMP_REPORT), def.Check(PM_IMP_REPORT))%>>レポート生成</nobr>
		</td>
	</tr>
<%
If n = 1 And system_id = "center" Then
%>
	<tr>
		<td class="m3">除外発信媒体</td>
		<td class="n3">
			<input type="checkbox" name="pm<%=n%>" onclick="ShowHide('jogai_baitai_btn',checked)" value="<%=PM_JOGAI_BAITAI%>"<%=CheckDisable(pm.Check(PM_JOGAI_BAITAI), def.Check(PM_JOGAI_BAITAI))%>>指定する
			<span id="jogai_baitai_btn" <%If Not pm.Check(PM_JOGAI_BAITAI) Then Response.Write "style='display:none'"%>>&nbsp;
				<font class="note">（媒体IDを入力または選択してください）</font>&nbsp;
				<input type="button" value="選択" onclick="BaitaiSelect('除外発信媒体選択', 'jogai_baitai')"><br>
				<textarea name="jogai_baitai" cols=60 rows=3><%=pm.jogai_baitai%></textarea>
			</span>
		</td>
	</tr>
	<tr>
		<td class="m3">対象発信媒体</td>
		<td class="n3">
			<input type="checkbox" name="pm<%=n%>" onclick="ShowHide('taishou_baitai_btn',checked)" value="<%=PM_TAISHOU_BAITAI%>"<%=CheckDisable(pm.Check(PM_TAISHOU_BAITAI), def.Check(PM_TAISHOU_BAITAI))%>>指定する
			<span id="taishou_baitai_btn" <%If Not pm.Check(PM_TAISHOU_BAITAI) Then Response.Write "style='display:none'"%>>&nbsp;
				<font class="note">（媒体IDを入力または選択してください）</font>&nbsp;
				<input type="button" value="選択" onclick="BaitaiSelect('対象発信媒体選択', 'taishou_baitai')"><br>
				<textarea name="taishou_baitai" cols=60 rows=3><%=pm.taishou_baitai%></textarea>
			</span>
		</td>
	</tr>
<%
End If
%>
</table>
<%
End Sub
%>