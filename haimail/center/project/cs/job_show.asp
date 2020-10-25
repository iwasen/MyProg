<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/sub.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/login.asp"-->
<!--#INCLUDE FILE="../../inc/search.asp"-->
<!--#INCLUDE FILE="job_sub.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����ǁE�c�Ƌ��ʃy�[�W
' Content:�W���u���e�\������
'******************************************************

'=== ���M�����擾 ===
Private Sub ReadSendCondition(job_id)
	Dim sql, ds

	sql = "SELECT * FROM T_JOB,T_SEND_MAIL WHERE JB_job_id=" & job_id & " AND JB_mail_id=SM_mail_id(+)"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		job_name = ds("JB_job_name").Value
		start_date = ds("SM_start_date").Value
		end_date = ds("SM_end_date").Value
		send_num = ds("SM_send_num").Value
		bunkatsu_kankaku = ds("SM_bunkatsu_kankaku").Value
		bunkatsu_kaisuu = ds("SM_bunkatsu_kaisuu").Value
		point_jushin = ds("SM_point_jushin").Value
		point_kansou = ds("SM_point_kansou").Value
		point_enquete = ds("SM_point_enquete").Value
		limit_date = ds("SM_limit_date").Value
		imp_limit = ds("SM_imp_limit").Value
		virgin_mail = NVL(ds("SM_virgin_mail").Value, "0")
		get_kansou = ds("JB_get_kansou").Value
		send_timing = ds("JB_send_timing").Value
		send_timing_h = ds("JB_send_timing_h").Value
		send_timing_d = ds("JB_send_timing_d").Value
		teikei_qno = ds("JB_teikei_qno").Value
		teikei_andor = ds("JB_teikei_andor").Value
		teikei_ans = ds("JB_teikei_ans").Value
		parent_job_id = ds("JB_parent_job_id").Value
		status = ds("JB_status").Value
		job_type = ds("JB_job_type").Value
		ct_id = ds("JB_ct_id").Value
		mail_type = ds("JB_mail_type").Value
		mail_format = NVL(ds("SM_mail_format").Value, "0")
		search.ReadDB ds("JB_search_id").Value
	End If
End Sub

'=== �qJOB�\�� ===
Private Sub ChildJob()
	Dim sql, ds

	sql = "SELECT JB_job_id,JB_job_name from T_JOB where JB_parent_job_id=" & job_id & " ORDER BY JB_job_id"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If ds.EOF Then
		Response.Write "����"
	Else
		Do Until ds.EOF
			Response.Write ds("JB_job_id").Value & " " & ReplaceTag(ds("JB_job_name").Value) & "<br>"
			ds.MoveNext
		Loop
	End If		
End Sub

'=== �eJOB�\�� ===
Private Sub ParentJob()
	Dim sql, ds

	If IsNull(parent_job_id) Then
		Response.Write "����܂���"
	Else
		sql = "SELECT JB_job_id,JB_job_name from T_JOB where JB_job_id=" & parent_job_id
		Set ds = CreateDynaset(sql, ORADYN_READONLY)
		If Not ds.EOF Then
			Response.Write ds("JB_job_id").Value & " " & ReplaceTag(ds("JB_job_name").Value) & "<br>"
		End If
	End If		
End Sub

'=== ��^����ԍ��\�� ===
Private Function TeikeiQuestionNo(qno)
	If NVL(qno, "") = "" Then
		TeikeiQuestionNo = "�w��Ȃ�"
	Else
		TeikeiQuestionNo = qno
	End If
End Function

'=== ��^�I�����ԍ��\�� ===
Private Function TeikeiAnswerNo(ano)
	Dim i

	TeikeiAnswerNo = ""
	For i = 1 To 15
		If Mid(ano,i, 1) = "1" Then
			If TeikeiAnswerNo <> "" Then
				TeikeiAnswerNo = TeikeiAnswerNo & ","
			End If
			TeikeiAnswerNo = TeikeiAnswerNo & i
		End If
	Next

	If TeikeiAnswerNo = "" Then
		TeikeiAnswerNo = "�w��Ȃ�"
	End If
End Function

'=== ���C������ ===
Dim job_id, job_name, search, a_type
Dim start_date, end_date
Dim send_num, point_jushin, point_kansou, point_enquete, limit_date, imp_limit
Dim bunkatsu_kankaku, bunkatsu_kaisuu
Dim send_timing, send_timing_h, send_timing_d, teikei_qno, teikei_andor, teikei_ans, ct_id
Dim parent_job_id, status, virgin_mail, get_kansou, job_type, mail_type, mail_format

job_id = Request.QueryString("job_id")
If IsEmpty(job_id) Then
	SystemError "job_id is empty"
End If

If CheckPermission(PM_REGIST_JOB_A) Then
	a_type = True
ElseIf CheckPermission(PM_REGIST_JOB_B) Then
	a_type = False
Else
	NoPermission
End If

Set search = New CSearch

ReadSendCondition job_id

SetHeaderTitle "�W���u���e�\��"
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
	<table border=0 cellspacing=0 cellpadding=0 width='850'>
		<tr>
			<td class="m0">���W���u���</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width='100%'>
					<tr>
						<td class="m3" width="20%">�W���uID</td>
						<td class="n3" width="80%"><%=job_id%></td>
					</tr>
					<tr>
						<td class="m3">�W���u��</td>
						<td class="n3"><%=ReplaceTag(job_name)%></td>
					</tr>
					<tr>
						<td class="m3">���[���^�C�v</td>
						<td class="n3"><%=MailTypeText(mail_type)%></td>
					</tr>
					<tr>
						<td class="m3">���[���`��</td>
						<td class="n3"><%=MailFormatText(mail_format)%></td>
					</tr>
					<tr>
						<td class="m3">�W���u���</td>
						<td class="n3"><%ShowJobType job_type%></td>
					</tr>
<%If job_type = "0" Then%>
					<tr>
						<td class="m3">�q���X�|���XJOB</td>
						<td class="n3"><%ChildJob%></td>
					</tr>
<%Else%>
					<tr>
						<td class="m3">�e���MJOB</td>
						<td class="n3"><%ParentJob%></td>
					</tr>
<%End If%>
					<tr>
						<td class="m3">���F</td>
						<td class="n3"><%=ApproveStatus(status)%></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td><br></td>
		</tr>
		<tr>
			<td class="m0">�����M����</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=2 width='100%'>
					<tr>
						<td class="m3" width="20%"><%If a_type Then%>���M�J�n��<%Else%>���M��]��<%End If%></td>
						<td class="n3" width="80%"><%ShowDate start_date, False%></td>
					</tr>
					<tr<%Show a_type%>>
						<td class="m3">���M�I����</td>
						<td class="n3"><%ShowDate end_date, False%></td>
					</tr>
					<tr>
						<td class="m3">�ő唭�M��</td>
						<td class="n3"><%=NumFormat(send_num)%></td>
					</tr>
					<tr<%Show a_type Or CheckPermission(PM_JOB_BUNKATSU_SOUSHIN)%>>
						<td class="m3">�������M�Ԋu</td>
						<td class="n3"><%=NumFormat(bunkatsu_kankaku)%> ��</td>
					</tr>
					<tr<%Show a_type Or CheckPermission(PM_JOB_BUNKATSU_SOUSHIN)%>>
						<td class="m3">�������M��</td>
						<td class="n3"><%=NumFormat(bunkatsu_kaisuu)%> ��</td>
					</tr>
					<tr<%Show a_type%>>
						<td class="m3">��M�|�C���g</td>
						<td class="n3"><%=point_jushin%></td>
					</tr>
					<tr<%Show a_type%>>
						<td class="m3">���z���|�C���g</td>
						<td class="n3"><%=point_kansou%></td>
					</tr>
					<tr<%Show a_type Or CheckPermission(PM_JOB_HENSHIN_POINT)%>>
						<td class="m3">�ԐM�|�C���g</td>
						<td class="n3"><%=point_enquete%></td>
					</tr>
					<tr>
						<td class="m3">���؂�</td>
						<td class="n3"><%ShowDate limit_date, True%></td>
					</tr>
<%If get_kansou = "1" Then%>
					<tr>
						<td class="m3">���z�����؂�</td>
						<td class="n3"><%ShowDate imp_limit, True%></td>
					</tr>
<%End If%>
<%If job_type = "0" Then%>
					<tr<%Show a_type Or CheckPermission(PM_JOB_SOUSHIN_TAISHOU)%>>
						<td class="m3">���M�Ώۉ��</td>
						<td class="n3"><%ShowVirginMail virgin_mail%></td>
					</tr>
<%ElseIf job_type = "1" Then%>
					<tr>
						<td class="m3">���M�^�C�~���O</td>
						<td class="n3"><%ShowSendTiming send_timing, send_timing_h, send_timing_d%></td>
					</tr>
					<tr>
						<td class="m3">��^����</td>
						<td class="n3">����ԍ��F<%=TeikeiQuestionNo(teikei_qno)%>�@<%ShowAndOr teikei_andor%><br>�I��ԍ��F<%=TeikeiAnswerNo(teikei_ans)%></td>
					</tr>
<%Else%>
					<tr>
						<td class="m3">�N���b�N�J�E���^</td>
						<td class="n3"><%=ct_id%></td>
					</tr>
<%End If%>
				</table>
			</td>
		</tr>
		<tr>
			<td><br></td>
		</tr>
		<tr>
			<td class="m0">����������</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=2 width='100%'>
					<tr>
						<td class="m3">�����W������</td>
						<td class="n3">
							�W�������ԏ��� <%ShowAndOr search.kyoumi_genre_andor%><br>
							<%ShowKyoumiGenre search.kyoumi_genre_shitei, search.kyoumi_genre_andor, search.kyoumi_genre1, search.kyoumi_genre2%>
						</td>
					</tr>
					<tr>
						<td class="m3" width="20%">����</td>
						<td class="n3" width="80%"><%ShowSeibetsu search.seibetsu_m, search.seibetsu_f%></td>
					</tr>
					<tr>
						<td class="m3">�N��</td>
						<td class="n3"><%ShowNenrei search.nenrei_from, search.nenrei_to%></td>
					</tr>
					<tr>
						<td class="m3">������</td>
						<td class="n3"><%ShowMikikon search.mikikon_mi, search.mikikon_ki%></td>
					</tr>
					<tr>
						<td class="m3">�E��</td>
						<td class="n3"><%ShowShokugyou search.shokugyou%></td>
					</tr>
					<tr>
						<td class="m3">���Z�n</td>
						<td class="n3"><%ShowJuusho search.kyojuu_shitei, search.kyojuu%></td>
					</tr>
					<tr>
						<td class="m3">�Ζ��n</td>
						<td class="n3"><%ShowJuusho search.kinmu_shitei, search.kinmu%></td>
					</tr>
					<tr>
						<td class="m3">�悭�s���X</td>
						<td class="n3"><%ShowYokuikumise search.yokuikumise_shitei, search.yokuikumise%></td>
					</tr>
					<tr>
						<td class="m3">�ǂ����h</td>
						<td class="n3"><%ShowDocchiha search.docchiha_shitei, search.docchiha%></td>
					</tr>
					<tr>
						<td class="m3">�r�p�k</td>
						<td class="n3"><%ShowCondition search.sql_shitei, search.sql_text%></td>
					</tr>
					<tr>
						<td class="m3">�X�֔ԍ��w��</td>
						<td class="n3"><%ShowCondition search.zip_shitei, search.zip%></td>
					</tr>
					<tr<%Show a_type Or CheckPermission(PM_JOB_JOGAI_TAISHOU)%>>
						<td class="m3">���O���M�W���u</td>
						<td class="n3"><%ShowCondition search.jogai_hasshin_shitei, search.jogai_hasshin%></td>
					</tr>
					<tr<%Show a_type Or CheckPermission(PM_JOB_JOGAI_TAISHOU)%>>
						<td class="m3">���O���X�|���X�W���u</td>
						<td class="n3"><%ShowCondition search.jogai_response_shitei, search.jogai_response%></td>
					</tr>
					<tr<%Show a_type Or CheckPermission(PM_JOB_JOGAI_TAISHOU)%>>
						<td class="m3">�Ώ۔��M�W���u</td>
						<td class="n3"><%ShowCondition search.taishou_hasshin_shitei, search.taishou_hasshin%></td>
					</tr>
					<tr<%Show a_type Or CheckPermission(PM_JOB_JOGAI_TAISHOU)%>>
						<td class="m3">�Ώۃ��X�|���X�W���u</td>
						<td class="n3"><%ShowCondition search.taishou_response_shitei, search.taishou_response%></td>
					</tr>
					<tr<%Show a_type Or CheckPermission(PM_JOB_JOGAI_TAISHOU)%>>
						<td class="m3">�����o�[���X�g����</td>
						<td class="n3"><%ShowMemberList search.member_list_shitei, search.member_list%></td>
					</tr>
					<tr>
						<td class="m3">HTML���[����M��</td>
						<td class="n3"><%ShowHtmlMail search.html_mail_shitei, search.html_mail%></td>
					</tr>
					<tr<%Show CheckPermission(PM_JOGAI_BAITAI)%>>
						<td class="m3">���O���M�}��</td>
						<td class="n3"><%ShowCondition2 search.jogai_baitai%></td>
					</tr>
					<tr<%Show CheckPermission(PM_TAISHOU_BAITAI)%>>
						<td class="m3">�Ώ۔��M�}��</td>
						<td class="n3"><%ShowCondition2 search.taishou_baitai%></td>
					</tr>
					<tr<%Show system_id = "center"%>>
						<td class="m3">���O���M�h���C��</td>
						<td class="n3"><%ShowCondition2 search.jogai_domain%></td>
					</tr>
					<tr<%Show system_id = "center"%>>
						<td class="m3">�Ώ۔��M�h���C��</td>
						<td class="n3"><%ShowCondition2 search.taishou_domain%></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
<form>
	<input type="button" value="Ұٺ���\��" onclick="window.open('job_mail.asp?job_id=<%=job_id%>')">
	<input type="button" value=" �߂� " onclick="history.back()">
</form>
</center>

<!--#INCLUDE FILE="../../inc/footer.asp"-->

</body>
</html>
