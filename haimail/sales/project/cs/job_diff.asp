<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/sub.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/login.asp"-->
<!--#INCLUDE FILE="../../inc/search.asp"-->
<!--#INCLUDE FILE="job_sub.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局・営業共通ページ
' Content:ジョブ差分表示処理
'******************************************************

'=== 発信条件取得 ===
Private Sub ReadSendCondition(job_idn)
	Dim sql, ds, i, search, item

	sql = ""
	For Each item In job_idn
		If sql <> "" Then
			sql = sql & ","
		End If
		sql = sql & item
	Next

	sql = "SELECT * FROM T_JOB,T_SEND_MAIL WHERE JB_job_id IN (" & sql & ") AND JB_mail_id=SM_mail_id(+) ORDER BY JB_job_id"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	count = ds.RecordCount - 1

	ReDim job_id(count)
	ReDim job_name(count)
	ReDim job_type(count)
	ReDim start_date(count)
	ReDim end_date(count)
	ReDim send_num(count)
	ReDim bunkatsu_kankaku(count)
	ReDim bunkatsu_kaisuu(count)
	ReDim point_jushin(count)
	ReDim point_kansou(count)
	ReDim point_enquete(count)
	ReDim limit_date(count)
	ReDim imp_limit(count)
	ReDim virgin_mail(count)
	ReDim send_timing(count)
	ReDim send_timing_h(count)
	ReDim send_timing_d(count)
	ReDim teikei_qno(count)
	ReDim teikei_andor(count)
	ReDim teikei_ans(count)
	ReDim parent_job_id(count)
	ReDim ct_id(count)
	ReDim search(count)
	ReDim seibetsu_m(count)
	ReDim seibetsu_f(count)
	ReDim nenrei_from(count)
	ReDim nenrei_to(count)
	ReDim mikikon_mi(count)
	ReDim mikikon_ki(count)
	ReDim shokugyou(count)
	ReDim kyojuu_shitei(count)
	ReDim kyojuu(count)
	ReDim kinmu_shitei(count)
	ReDim kinmu_andor(count)
	ReDim kinmu(count)
	ReDim kyoumi_genre_shitei(count)
	ReDim kyoumi_genre_andor(count)
	ReDim kyoumi_genre1(count)
	ReDim kyoumi_genre2(count)
	ReDim yokuikumise_shitei(count)
	ReDim yokuikumise(count)
	ReDim docchiha_shitei(count)
	ReDim docchiha(count)
	ReDim sql_shitei(count)
	ReDim sql_text(count)
	ReDim zip_shitei(count)
	ReDim zip(count)
	ReDim jogai_hasshin_shitei(count)
	ReDim jogai_hasshin(count)
	ReDim jogai_response_shitei(count)
	ReDim jogai_response(count)
	ReDim taishou_hasshin_shitei(count)
	ReDim taishou_hasshin(count)
	ReDim taishou_response_shitei(count)
	ReDim taishou_response(count)
	ReDim member_list_shitei(count)
	ReDim member_list(count)
	ReDim html_mail_shitei(count)
	ReDim html_mail(count)
	ReDim oshirase_mail_shitei(count)
	ReDim oshirase_mail(count)
	ReDim jogai_baitai(count)
	ReDim taishou_baitai(count)
	ReDim jogai_domain(count)
	ReDim taishou_domain(count)

	i = 0
	Do Until ds.EOF
		job_id(i) = ds("JB_job_id").Value
		job_name(i) = ds("JB_job_name").Value
		job_type(i) = ds("JB_job_type").Value
		start_date(i) = ds("SM_start_date").Value
		end_date(i) = ds("SM_end_date").Value
		send_num(i) = ds("SM_send_num").Value
		bunkatsu_kankaku(i) = ds("SM_bunkatsu_kankaku").Value
		bunkatsu_kaisuu(i) = ds("SM_bunkatsu_kaisuu").Value
		point_jushin(i) = ds("SM_point_jushin").Value
		point_kansou(i) = ds("SM_point_kansou").Value
		point_enquete(i) = ds("SM_point_enquete").Value
		limit_date(i) = ds("SM_limit_date").Value
		imp_limit(i) = ds("SM_imp_limit").Value
		virgin_mail(i) = NVL(ds("SM_virgin_mail").Value, "0")
		send_timing(i) = ds("JB_send_timing").Value
		send_timing_h(i) = ds("JB_send_timing_h").Value
		send_timing_d(i) = ds("JB_send_timing_d").Value
		teikei_qno(i) = ds("JB_teikei_qno").Value
		teikei_andor(i) = ds("JB_teikei_andor").Value
		teikei_ans(i) = ds("JB_teikei_ans").Value
		parent_job_id(i) = ds("JB_parent_job_id").Value
		ct_id(i) = ds("JB_ct_id").Value

		Set search = New CSearch
		search.ReadDB ds("JB_search_id").Value
		seibetsu_m(i) = search.seibetsu_m
		seibetsu_f(i) = search.seibetsu_f
		nenrei_from(i) = search.nenrei_from
		nenrei_to(i) = search.nenrei_to
		mikikon_mi(i) = search.mikikon_mi
		mikikon_ki(i) = search.mikikon_ki
		shokugyou(i) = search.shokugyou
		kyojuu_shitei(i) = search.kyojuu_shitei
		kyojuu(i) = search.kyojuu
		kinmu_shitei(i) = search.kinmu_shitei
		kinmu_andor(i) = search.kinmu_andor
		kinmu(i) = search.kinmu
		kyoumi_genre_shitei(i) = search.kyoumi_genre_shitei
		kyoumi_genre_andor(i) = search.kyoumi_genre_andor
		kyoumi_genre1(i) = search.kyoumi_genre1
		kyoumi_genre2(i) = search.kyoumi_genre2
		yokuikumise_shitei(i) = search.yokuikumise_shitei
		yokuikumise(i) = search.yokuikumise
		docchiha_shitei(i) = search.docchiha_shitei
		docchiha(i) = search.docchiha
		sql_shitei(i) = search.sql_shitei
		sql_text(i) = search.sql_text
		zip_shitei(i) = search.zip_shitei
		zip(i) = search.zip
		jogai_hasshin_shitei(i) = search.jogai_hasshin_shitei
		jogai_hasshin(i) = search.jogai_hasshin
		jogai_response_shitei(i) = search.jogai_response_shitei
		jogai_response(i) = search.jogai_response
		taishou_hasshin_shitei(i) = search.taishou_hasshin_shitei
		taishou_hasshin(i) = search.taishou_hasshin
		taishou_response_shitei(i) = search.taishou_response_shitei
		taishou_response(i) = search.taishou_response
		member_list_shitei(i) = search.member_list_shitei
		member_list(i) = search.member_list
		html_mail_shitei(i) = search.html_mail_shitei
		html_mail(i) = search.html_mail
		oshirase_mail_shitei(i) = search.oshirase_mail_shitei
		oshirase_mail(i) = search.oshirase_mail
		jogai_baitai(i) = search.jogai_baitai
		taishou_baitai(i) = search.taishou_baitai
		jogai_domain(i) = search.jogai_domain
		taishou_domain(i) = search.taishou_domain

		i = i + 1
		ds.MoveNext
	Loop
End Sub

'=== 差分検出 ===
Private Function DiffCheck(arry)
	Dim i

	DiffCheck = False

	For i = 1 To count
		If NVL(arry(0), "") <> NVL(arry(i), "") Then
			DiffCheck = True
			Exit For
		End If
	Next
End Function

'=== 定型質問番号表示 ===
Private Function TeikeiQuestionNo(qno)
	If NVL(qno, "") = "" Then
		TeikeiQuestionNo = "指定なし"
	Else
		TeikeiQuestionNo = qno
	End If
End Function

'=== 定型選択肢番号表示 ===
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
		TeikeiAnswerNo = "指定なし"
	End If
End Function

'=== メイン処理 ===
Dim job_idn, job_id, job_name, count, i, job_type
Dim start_date, end_date
Dim send_num, point_jushin, point_kansou, point_enquete, limit_date, imp_limit
Dim bunkatsu_kankaku, bunkatsu_kaisuu
Dim send_timing, send_timing_h, send_timing_d, teikei_qno, teikei_andor, teikei_ans, ct_id
Dim parent_job_id, virgin_mail
Dim seibetsu_m
Dim seibetsu_f
Dim nenrei_from
Dim nenrei_to
Dim mikikon_mi
Dim mikikon_ki
Dim shokugyou
Dim kyojuu_shitei
Dim kyojuu
Dim kinmu_shitei
Dim kinmu_andor
Dim kinmu
Dim kyoumi_genre_shitei
Dim kyoumi_genre_andor
Dim kyoumi_genre1
Dim kyoumi_genre2
Dim yokuikumise_shitei
Dim yokuikumise
Dim docchiha_shitei
Dim docchiha
Dim sql_shitei
Dim sql_text
Dim zip_shitei
Dim zip
Dim jogai_hasshin_shitei
Dim jogai_hasshin
Dim jogai_response_shitei
Dim jogai_response
Dim taishou_hasshin_shitei
Dim taishou_hasshin
Dim taishou_response_shitei
Dim taishou_response
Dim member_list_shitei
Dim member_list
Dim html_mail_shitei
Dim html_mail
Dim oshirase_mail_shitei
Dim oshirase_mail
Dim jogai_baitai
Dim taishou_baitai
Dim jogai_domain
Dim taishou_domain

Set job_idn = Request.Form("job_id")
If IsEmpty(job_idn) Then
	SystemError "job_id is empty"
End If

ReadSendCondition job_idn

SetHeaderTitle "ジョブ差分表示"
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
	<table border=0 cellspacing=2 cellpadding=2 width='100%'>
		<tr>
			<td class="m3">ジョブID</td>
<%For i = 0 To count%>
			<td class="n3" valign="top"><a href="job_show.asp?job_id=<%=job_id(i)%>"><%=job_id(i)%></a></td>
<%Next%>
		</tr>
		<tr>
			<td class="m3">ジョブ名</td>
<%For i = 0 To count%>
			<td class="n3" valign="top"><%=job_name(i)%></td>
<%Next%>
		</tr>
<%If DiffCheck(job_type) Then%>
		<tr>
			<td class="m3">ジョブ種別</td>
<%For i = 0 To count%>
			<td class="n3" valign="top"><%ShowJobType job_type(i)%></td>
<%Next%>
		</tr>
<%End If%>
<%If DiffCheck(start_date) Then%>
		<tr>
			<td class="m3">発信開始日</td>
<%For i = 0 To count%>
			<td class="n3" valign="top"><%ShowDate start_date(i), False%></td>
<%Next%>
		</tr>
<%End If%>
<%If DiffCheck(end_date) Then%>
		<tr>
			<td class="m3">発信終了日</td>
<%For i = 0 To count%>
			<td class="n3" valign="top"><%ShowDate end_date(i), False%></td>
<%Next%>
		</tr>
<%End If%>
<%If DiffCheck(send_num) Then%>
		<tr>
			<td class="m3">最大発信数</td>
<%For i = 0 To count%>
			<td class="n3" valign="top"><%=NumFormat(send_num(i))%></td>
<%Next%>
		</tr>
<%End If%>
<%If DiffCheck(bunkatsu_kankaku) Then%>
		<tr>
			<td class="m3">分割発信間隔</td>
<%For i = 0 To count%>
			<td class="n3" valign="top"><%=NumFormat(bunkatsu_kankaku(i))%> 日</td>
<%Next%>
		</tr>
<%End If%>
<%If DiffCheck(bunkatsu_kaisuu) Then%>
		<tr>
			<td class="m3">分割発信回数</td>
<%For i = 0 To count%>
			<td class="n3" valign="top"><%=NumFormat(bunkatsu_kaisuu(i))%> 回</td>
<%Next%>
		</tr>
<%End If%>
<%If DiffCheck(point_jushin) Then%>
		<tr>
			<td class="m3">受信ポイント</td>
<%For i = 0 To count%>
			<td class="n3" valign="top"><%=point_jushin(i)%></td>
<%Next%>
		</tr>
<%End If%>
<%If DiffCheck(point_kansou) Then%>
		<tr>
			<td class="m3">感想文ポイント</td>
<%For i = 0 To count%>
			<td class="n3" valign="top"><%=point_kansou(i)%></td>
<%Next%>
		</tr>
<%End If%>
<%If DiffCheck(point_enquete) Then%>
		<tr>
			<td class="m3">返信ポイント</td>
<%For i = 0 To count%>
			<td class="n3" valign="top"><%=point_enquete(i)%></td>
<%Next%>
		</tr>
<%End If%>
<%If DiffCheck(limit_date) Then%>
		<tr>
			<td class="m3">締切り</td>
<%For i = 0 To count%>
			<td class="n3" valign="top"><%ShowDate limit_date(i), True%></td>
<%Next%>
		</tr>
<%End If%>
<%If DiffCheck(imp_limit) Then%>
		<tr>
			<td class="m3">感想文締切り</td>
<%For i = 0 To count%>
			<td class="n3" valign="top"><%ShowDate imp_limit(i), True%></td>
<%Next%>
		</tr>
<%End If%>
<%If DiffCheck(virgin_mail) Then%>
		<tr>
			<td class="m3">送信対象会員</td>
<%For i = 0 To count%>
			<td class="n3" valign="top"><%ShowVirginMail virgin_mail(i)%></td>
<%Next%>
		</tr>
<%End If%>
<%If DiffCheck(send_timing) Or DiffCheck(send_timing_h) Or DiffCheck(send_timing_d) Then%>
		<tr>
			<td class="m3">発信タイミング</td>
<%For i = 0 To count%>
			<td class="n3" valign="top"><%ShowSendTiming send_timing(i), send_timing_h(i), send_timing_d(i)%></td>
<%Next%>
		</tr>
<%End If%>
<%If DiffCheck(teikei_qno) Or DiffCheck(teikei_andor) Or DiffCheck(teikei_ans) Then%>
		<tr>
			<td class="m3">定型処理</td>
<%For i = 0 To count%>
			<td class="n3" valign="top">質問番号：<%=TeikeiQuestionNo(teikei_qno(i))%>　<%ShowAndOr teikei_andor(i)%><br>選択番号：<%=TeikeiAnswerNo(teikei_ans(i))%></td>
<%Next%>
		</tr>
<%End If%>
<%If DiffCheck(ct_id) Then%>
		<tr>
			<td class="m3">クリックカウンタ</td>
<%For i = 0 To count%>
			<td class="n3" valign="top"><%=ct_id(i)%></td>
<%Next%>
		</tr>
<%End If%>
<%If DiffCheck(kyoumi_genre_shitei) Or DiffCheck(kyoumi_genre_andor) Or DiffCheck(kyoumi_genre1) Or DiffCheck(kyoumi_genre2) Then%>
		<tr>
			<td class="m3">興味ジャンル</td>
<%For i = 0 To count%>
			<td class="n3" valign="top">
				ジャンル間条件 <%ShowAndOr kyoumi_genre_andor(i)%><br>
				<%ShowKyoumiGenre kyoumi_genre_shitei(i), kyoumi_genre_andor(i), kyoumi_genre1(i), kyoumi_genre2(i)%>
			</td>
<%Next%>
		</tr>
<%End If%>
<%If DiffCheck(seibetsu_m) Or DiffCheck(seibetsu_f) Then%>
		<tr>
			<td class="m3">性別</td>
<%For i = 0 To count%>
			<td class="n3" valign="top"><%ShowSeibetsu seibetsu_m(i), seibetsu_f(i)%></td>
<%Next%>
		</tr>
<%End If%>
<%If DiffCheck(nenrei_from) Or DiffCheck(nenrei_to) Then%>
		<tr>
			<td class="m3">年齢</td>
<%For i = 0 To count%>
			<td class="n3" valign="top"><%ShowNenrei nenrei_from(i), nenrei_to(i)%></td>
<%Next%>
		</tr>
<%End If%>
<%If DiffCheck(mikikon_mi) Or DiffCheck(mikikon_ki) Then%>
		<tr>
			<td class="m3">未既婚</td>
<%For i = 0 To count%>
			<td class="n3" valign="top"><%ShowMikikon mikikon_mi(i), mikikon_ki(i)%></td>
<%Next%>
		</tr>
<%End If%>
<%If DiffCheck(shokugyou) Then%>
		<tr>
			<td class="m3">職業</td>
<%For i = 0 To count%>
			<td class="n3" valign="top"><%ShowShokugyou shokugyou(i)%></td>
<%Next%>
		</tr>
<%End If%>
<%If DiffCheck(kyojuu_shitei) Or DiffCheck(kyojuu) Then%>
		<tr>
			<td class="m3">居住地</td>
<%For i = 0 To count%>
			<td class="n3" valign="top"><%ShowJuusho kyojuu_shitei(i), kyojuu(i)%></td>
<%Next%>
		</tr>
<%End If%>
<%If DiffCheck(kinmu_shitei) Or DiffCheck(kinmu) Then%>
		<tr>
			<td class="m3">勤務地</td>
<%For i = 0 To count%>
			<td class="n3" valign="top"><%ShowJuusho kinmu_shitei(i), kinmu(i)%></td>
<%Next%>
		</tr>
<%End If%>
<%If DiffCheck(yokuikumise_shitei) Or DiffCheck(yokuikumise) Then%>
		<tr>
			<td class="m3">よく行く店</td>
<%For i = 0 To count%>
			<td class="n3" valign="top"><%ShowYokuikumise yokuikumise_shitei(i), yokuikumise(i)%></td>
<%Next%>
		</tr>
<%End If%>
<%If DiffCheck(docchiha_shitei) Or DiffCheck(docchiha) Then%>
		<tr>
			<td class="m3">どっち派</td>
<%For i = 0 To count%>
			<td class="n3" valign="top"><%ShowDocchiha docchiha_shitei(i), docchiha(i)%></td>
<%Next%>
		</tr>
<%End If%>
<%If DiffCheck(sql_shitei) Or DiffCheck(sql_text) Then%>
		<tr>
			<td class="m3">ＳＱＬ</td>
<%For i = 0 To count%>
			<td class="n3" valign="top"><%ShowCondition sql_shitei(i), sql_text(i)%></td>
<%Next%>
		</tr>
<%End If%>
<%If DiffCheck(zip_shitei) Or DiffCheck(zip) Then%>
		<tr>
			<td class="m3">郵便番号指定</td>
<%For i = 0 To count%>
			<td class="n3" valign="top"><%ShowCondition zip_shitei(i), zip(i)%></td>
<%Next%>
		</tr>
<%End If%>
<%If DiffCheck(jogai_hasshin_shitei) Or DiffCheck(jogai_hasshin) Then%>
		<tr>
			<td class="m3">除外発信ジョブ</td>
<%For i = 0 To count%>
			<td class="n3" valign="top"><%ShowCondition jogai_hasshin_shitei(i), jogai_hasshin(i)%></td>
<%Next%>
		</tr>
<%End If%>
<%If DiffCheck(jogai_response_shitei) Or DiffCheck(jogai_response) Then%>
		<tr>
			<td class="m3">除外レスポンスジョブ</td>
<%For i = 0 To count%>
			<td class="n3" valign="top"><%ShowCondition jogai_response_shitei(i), jogai_response(i)%></td>
<%Next%>
		</tr>
<%End If%>
<%If DiffCheck(taishou_hasshin_shitei) Or DiffCheck(taishou_hasshin) Then%>
		<tr>
			<td class="m3">対象発信ジョブ</td>
<%For i = 0 To count%>
			<td class="n3" valign="top"><%ShowCondition taishou_hasshin_shitei(i), taishou_hasshin(i)%></td>
<%Next%>
		</tr>
<%End If%>
<%If DiffCheck(taishou_response_shitei) Or DiffCheck(taishou_response) Then%>
		<tr>
			<td class="m3">対象レスポンスジョブ</td>
<%For i = 0 To count%>
			<td class="n3" valign="top"><%ShowCondition taishou_response_shitei(i), taishou_response(i)%></td>
<%Next%>
		</tr>
<%End If%>
<%If DiffCheck(member_list_shitei) Or DiffCheck(member_list) Then%>
		<tr>
			<td class="m3">メンバーリスト生成</td>
<%For i = 0 To count%>
			<td class="n3" valign="top"><%ShowMemberList member_list_shitei(i), member_list(i)%></td>
<%Next%>
		</tr>
<%End If%>
<%If DiffCheck(html_mail_shitei) Or DiffCheck(html_mail) Then%>
		<tr>
			<td class="m3">HTMLメール受信可否</td>
<%For i = 0 To count%>
			<td class="n3" valign="top"><%ShowHtmlMail html_mail_shitei(i), html_mail(i)%></td>
<%Next%>
		</tr>
<%End If%>
<%If DiffCheck(jogai_baitai) Then%>
		<tr>
			<td class="m3">除外発信媒体</td>
<%For i = 0 To count%>
			<td class="n3" valign="top"><%ShowCondition2 jogai_baitai(i)%></td>
<%Next%>
		</tr>
<%End If%>
<%If DiffCheck(taishou_baitai) Then%>
		<tr>
			<td class="m3">対象発信媒体</td>
<%For i = 0 To count%>
			<td class="n3" valign="top"><%ShowCondition2 taishou_baitai(i)%></td>
<%Next%>
		</tr>
<%End If%>
<%If DiffCheck(jogai_domain) Then%>
		<tr>
			<td class="m3">除外発信ドメイン</td>
<%For i = 0 To count%>
			<td class="n3" valign="top"><%ShowCondition2 jogai_domain(i)%></td>
<%Next%>
		</tr>
<%End If%>
<%If DiffCheck(taishou_domain) Then%>
		<tr>
			<td class="m3">対象発信ドメイン</td>
<%For i = 0 To count%>
			<td class="n3" valign="top"><%ShowCondition2 taishou_domain(i)%></td>
<%Next%>
		</tr>
<%End If%>
	</table>
<form>
	<input type="button" value=" 戻る " onclick="history.back()">
</form>
</center>

<!--#INCLUDE FILE="../../inc/footer.asp"-->

</body>
</html>
