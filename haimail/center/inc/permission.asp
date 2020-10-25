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

'=== "checked disabled" �o�� ===
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
		<td class="m3">�㗝�X�o�^</td>
		<td class="n3">
			<input type="checkbox" name="pm<%=n%>" id="regist_agent<%=n%>" value="<%=PM_REGIST_AGENT%>"<%=CheckDisable(pm.Check(PM_REGIST_AGENT), d_class="A" And def.Check(PM_REGIST_AGENT))%> onclick="OnClick_regist_agent(<%=n%>,this)">�㗝�X�o�^
			<input type="checkbox" name="pm<%=n%>" id="agent_permission<%=n%>" value="<%=PM_AGENT_PERMISSION%>"<%=CheckDisable(pm.Check(PM_AGENT_PERMISSION), d_class="A" And pm.Check(PM_REGIST_AGENT))%>>�����̐ݒ�
		</td>
	</tr>
<%
End If
If n <> 3 Then
%>
	<tr>
		<td class="m3">�I�y���[�^�o�^</td>
		<td class="n3">
			<input type="checkbox" name="pm<%=n%>" value="<%=PM_REGIST_OPERATOR%>"<%=CheckDisable(pm.Check(PM_REGIST_OPERATOR), def.Check(PM_REGIST_OPERATOR))%>>�I�y���[�^�o�^
		</td>
	</tr>
	<tr>
		<td class="m3">�N���C�A���g�o�^</td>
		<td class="n3">
			<input type="checkbox" name="pm<%=n%>" value="<%=PM_REGIST_CLIENT%>"<%=CheckDisable(pm.Check(PM_REGIST_CLIENT), def.Check(PM_REGIST_CLIENT))%> onclick="OnClick_regist_client(<%=n%>,this)">�N���C�A���g�o�^
			<input type="checkbox" name="pm<%=n%>" id="client_permission<%=n%>" value="<%=PM_CLIENT_PERMISSION%>"<%=CheckDisable(pm.Check(PM_CLIENT_PERMISSION), pm.Check(PM_REGIST_CLIENT))%>>�����̐ݒ�
		</td>
	</tr>
<%
End If
%>
	<tr>
		<td class="m3" width="30%">�v���W�F�N�g�o�^</td>
		<td class="n3">
			<nobr><input type="checkbox" name="pm<%=n%>" value="<%=PM_REGIST_PROJECT%>"<%=CheckDisable(pm.Check(PM_REGIST_PROJECT), def.Check(PM_REGIST_PROJECT))%>>�v���W�F�N�g�o�^</nobr>
			<nobr><input type="checkbox" name="pm<%=n%>" value="<%=PM_PROJECT_UPDATE%>"<%=CheckDisable(pm.Check(PM_PROJECT_UPDATE), def.Check(PM_PROJECT_UPDATE))%>>�v���W�F�N�g���e�ύX</nobr>
			<nobr><input type="checkbox" name="pm<%=n%>" value="<%=PM_PROJECT_DELETE%>"<%=CheckDisable(pm.Check(PM_PROJECT_DELETE), def.Check(PM_PROJECT_DELETE))%>>�v���W�F�N�g�폜</nobr>
		</td>
	</tr>
	<tr>
		<td class="m3">�W���u�o�^</td>
		<td class="n3">
			<nobr><input type="checkbox" name="pm<%=n%>" id="regist_job_a<%=n%>" value="<%=PM_REGIST_JOB_A%>"<%=CheckDisable(pm.Check(PM_REGIST_JOB_A), def.Check(PM_REGIST_JOB_A))%> onclick="OnClick_job_a(<%=n%>,this)">�W���u�o�^(�`)</nobr>
			<nobr><input type="checkbox" name="pm<%=n%>" id="regist_job_b<%=n%>" value="<%=PM_REGIST_JOB_B%>"<%=CheckDisable(pm.Check(PM_REGIST_JOB_B), def.Check(PM_REGIST_JOB_B))%> onclick="OnClick_job_b(<%=n%>,this)">�W���u�o�^(�a)</nobr>
			<nobr><input type="checkbox" name="pm<%=n%>" value="<%=PM_JOB_UPDATE%>"<%=CheckDisable(pm.Check(PM_JOB_UPDATE), def.Check(PM_JOB_UPDATE))%>>�W���u���e�ύX</nobr>
			<nobr><input type="checkbox" name="pm<%=n%>" value="<%=PM_JOB_DELETE%>"<%=CheckDisable(pm.Check(PM_JOB_DELETE), def.Check(PM_JOB_DELETE))%>>�W���u�폜</nobr>
		</td>
	</tr>
	<tr>
		<td class="m3">�W���u�o�^(�a)�ǉ��@�\</td>
		<td class="n3">
			<nobr><input type="checkbox" name="pm<%=n%>" id="job_bunkatsu_shoushin<%=n%>" value="<%=PM_JOB_BUNKATSU_SOUSHIN%>"<%=CheckDisable(pm.Check(PM_JOB_BUNKATSU_SOUSHIN), pm.Check(PM_REGIST_JOB_B))%>>�������M</nobr>
			<nobr><input type="checkbox" name="pm<%=n%>" id="job_henshin_point<%=n%>" value="<%=PM_JOB_HENSHIN_POINT%>"<%=CheckDisable(pm.Check(PM_JOB_HENSHIN_POINT), pm.Check(PM_REGIST_JOB_B))%>>�ԐM�|�C���g</nobr>
			<nobr><input type="checkbox" name="pm<%=n%>" id="job_soushin_taishou<%=n%>" value="<%=PM_JOB_SOUSHIN_TAISHOU%>"<%=CheckDisable(pm.Check(PM_JOB_SOUSHIN_TAISHOU), pm.Check(PM_REGIST_JOB_B))%>>���M�Ώۉ��</nobr>
			<nobr><input type="checkbox" name="pm<%=n%>" id="job_jogai_taishou<%=n%>" value="<%=PM_JOB_JOGAI_TAISHOU%>"<%=CheckDisable(pm.Check(PM_JOB_JOGAI_TAISHOU), pm.Check(PM_REGIST_JOB_B))%>>���O�^�ΏۃW���u�w��</nobr>
			<nobr><input type="checkbox" name="pm<%=n%>" id="job_hacchuu<%=n%>" value="<%=PM_JOB_HACCHUU%>"<%=CheckDisable(pm.Check(PM_JOB_HACCHUU), pm.Check(PM_REGIST_JOB_B))%>>�z�M�˗�</nobr>
			<nobr><input type="checkbox" name="pm<%=n%>" id="job_click_counter<%=n%>" value="<%=PM_JOB_CLICK_COUNTER%>"<%=CheckDisable(pm.Check(PM_JOB_CLICK_COUNTER), pm.Check(PM_REGIST_JOB_B))%>>�N���b�N�J�E���^</nobr>
		</td>
	</tr>
	<tr>
		<td class="m3">���ς�^����</td>
		<td class="n3">
			<nobr><input type="checkbox" name="pm<%=n%>" value="<%=PM_MITSUMORI%>"<%=CheckDisable(pm.Check(PM_MITSUMORI), def.Check(PM_MITSUMORI))%>>���ς�</nobr>
			<nobr><input type="checkbox" name="pm<%=n%>" value="<%=PM_HACCHUU%>"<%=CheckDisable(pm.Check(PM_HACCHUU), def.Check(PM_HACCHUU))%>>����</nobr>
		</td>
	</tr>
	<tr>
		<td class="m3">�N���b�N�J�E���^</td>
		<td class="n3">
			<nobr><input type="checkbox" name="pm<%=n%>" id="click_report<%=n%>" value="<%=PM_CLICK_REPORT%>"<%=CheckDisable(pm.Check(PM_CLICK_REPORT), True)%>>�N���b�N���|�[�g</nobr>
			<nobr><input type="checkbox" name="pm<%=n%>" id="click_url<%=n%>" value="<%=PM_CLICK_URL%>"<%=CheckDisable(pm.Check(PM_CLICK_URL), True)%>>�t�q�k�ʃN���b�N��</nobr>
			<nobr><input type="checkbox" name="pm<%=n%>" id="click_csv<%=n%>" value="<%=PM_CLICK_CSV%>"<%=CheckDisable(pm.Check(PM_CLICK_CSV), True)%>>�b�r�u�擾</nobr>
		</td>
	</tr>
	<tr>
		<td class="m3">�A���P�[�g</td>
		<td class="n3">
			<nobr><input type="checkbox" name="pm<%=n%>" value="<%=PM_ENQ_USE%>"<%=CheckDisable(pm.Check(PM_ENQ_USE), def.Check(PM_ENQ_USE))%> onclick="OnClick_enq_use(<%=n%>,this)">�A���P�[�g�@�\����</nobr>
			<nobr><input type="checkbox" name="pm<%=n%>" id="enq_log<%=n%>" value="<%=PM_ENQ_LOG%>"<%=CheckDisable(pm.Check(PM_ENQ_LOG), pm.Check(PM_ENQ_USE))%>>�ԐM���O�{��</nobr>
			<nobr><input type="checkbox" name="pm<%=n%>" id="enq_csv<%=n%>" value="<%=PM_ENQ_CSV%>"<%=CheckDisable(pm.Check(PM_ENQ_CSV), pm.Check(PM_ENQ_USE))%>>�b�r�u�擾</nobr>
			<nobr><input type="checkbox" name="pm<%=n%>" id="enq_report<%=n%>" value="<%=PM_ENQ_REPORT%>"<%=CheckDisable(pm.Check(PM_ENQ_REPORT), pm.Check(PM_ENQ_USE))%>>���|�[�g����</nobr>
		</td>
	</tr>
	<tr>
		<td class="m3">���z��</td>
		<td class="n3">
			<nobr><input type="checkbox" name="pm<%=n%>" id="imp_number<%=n%>" value="<%=PM_IMP_NUMBER%>"<%=CheckDisable(pm.Check(PM_IMP_NUMBER), def.Check(PM_IMP_NUMBER))%> onclick="OnClick_imp_number(<%=n%>,this)">�ꗗ�̐��l�̂�</nobr>
			<nobr><input type="checkbox" name="pm<%=n%>" id="imp_10log<%=n%>" value="<%=PM_IMP_10LOG%>"<%=CheckDisable(pm.Check(PM_IMP_10LOG), True)%> onclick="OnClick_imp_10log(<%=n%>,this)">�ԐM���O�P�O�s�̂�</nobr>
			<nobr><input type="checkbox" name="pm<%=n%>" id="imp_log<%=n%>" value="<%=PM_IMP_LOG%>"<%=CheckDisable(pm.Check(PM_IMP_LOG), True)%> onclick="OnClick_imp_log(<%=n%>,this)">�S�ԐM���O�{��</nobr>
			<nobr><input type="checkbox" name="pm<%=n%>" value="<%=PM_IMP_CSV%>"<%=CheckDisable(pm.Check(PM_IMP_CSV), def.Check(PM_IMP_CSV))%>>�b�r�u�擾</nobr>
			<nobr><input type="checkbox" name="pm<%=n%>" value="<%=PM_IMP_REPORT%>"<%=CheckDisable(pm.Check(PM_IMP_REPORT), def.Check(PM_IMP_REPORT))%>>���|�[�g����</nobr>
		</td>
	</tr>
<%
If n = 1 And system_id = "center" Then
%>
	<tr>
		<td class="m3">���O���M�}��</td>
		<td class="n3">
			<input type="checkbox" name="pm<%=n%>" onclick="ShowHide('jogai_baitai_btn',checked)" value="<%=PM_JOGAI_BAITAI%>"<%=CheckDisable(pm.Check(PM_JOGAI_BAITAI), def.Check(PM_JOGAI_BAITAI))%>>�w�肷��
			<span id="jogai_baitai_btn" <%If Not pm.Check(PM_JOGAI_BAITAI) Then Response.Write "style='display:none'"%>>&nbsp;
				<font class="note">�i�}��ID����͂܂��͑I�����Ă��������j</font>&nbsp;
				<input type="button" value="�I��" onclick="BaitaiSelect('���O���M�}�̑I��', 'jogai_baitai')"><br>
				<textarea name="jogai_baitai" cols=60 rows=3><%=pm.jogai_baitai%></textarea>
			</span>
		</td>
	</tr>
	<tr>
		<td class="m3">�Ώ۔��M�}��</td>
		<td class="n3">
			<input type="checkbox" name="pm<%=n%>" onclick="ShowHide('taishou_baitai_btn',checked)" value="<%=PM_TAISHOU_BAITAI%>"<%=CheckDisable(pm.Check(PM_TAISHOU_BAITAI), def.Check(PM_TAISHOU_BAITAI))%>>�w�肷��
			<span id="taishou_baitai_btn" <%If Not pm.Check(PM_TAISHOU_BAITAI) Then Response.Write "style='display:none'"%>>&nbsp;
				<font class="note">�i�}��ID����͂܂��͑I�����Ă��������j</font>&nbsp;
				<input type="button" value="�I��" onclick="BaitaiSelect('�Ώ۔��M�}�̑I��', 'taishou_baitai')"><br>
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