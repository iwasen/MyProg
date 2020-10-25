<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<!--#INCLUDE FILE="../inc/search.asp"-->
<%
'******************************************************
' System :はいめーる・net営業共通ページ
' Content:詳細情報登録画面処理
'******************************************************

Const SEPARATOR1 = "$$1$$"
Const SEPARATOR2 = "$$2$$"

'=== 発信企業選択肢 ===
Private Function CompanyList()
	Dim sql, ds, list

	sql = "SELECT CM_company_id,CM_name" & vbCrLf & _
				"FROM T_COMPANY,T_OPERATOR" & vbCrLf & _
				"WHERE OP_operator_id='" & g_login_id & "' AND CM_dairiten_id=OP_dairiten_id" & vbCrLf & _
				"ORDER BY CM_company_id"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Do Until ds.EOF
		list = list & ds("CM_company_id").Value & SEPARATOR1 & ds("CM_name").Value & SEPARATOR2
		ds.MoveNext
	Loop

	CompanyList = HTMLEncode(list)
End Function

'=== 担当者リスト出力 ===
Private Function TantoushaList()
	Dim sql, ds, list, name

	sql = "SELECT TS_company_id,TS_tantousha_id,TS_name1,TS_name2" & vbCrLf & _
				"FROM T_TANTOUSHA,T_COMPANY,T_OPERATOR" & vbCrLf & _
				"WHERE OP_operator_id='" & g_login_id & "' AND CM_dairiten_id=OP_dairiten_id AND TS_company_id=CM_company_id" & vbCrLf & _
				"ORDER BY TS_company_id,TS_tantousha_id"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Do Until ds.EOF
		If IsNull(ds("TS_name1").Value) Then
			name = ds("TS_tantousha_id").Value
		Else
			name = ds("TS_name1").Value & " " & ds("TS_name2").Value
		End If

		list = list & ds("TS_company_id").Value & SEPARATOR1 & ds("TS_tantousha_id").Value & SEPARATOR1 & name & SEPARATOR2
		ds.MoveNext
	Loop

	TantoushaList = HTMLEncode(list)
End Function

'=== 企業名、担当者名取得 ===
Private Sub GetCompanyTantousha()
	Dim sql, ds

	sql = "SELECT CM_name,TS_name1,TS_name2" & vbCrLf & _
				"FROM T_COMPANY,T_TANTOUSHA" & vbCrLf & _
				"WHERE TS_tantousha_id='" & g_login_id & "' AND CM_company_id=TS_company_id"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		company_name = ds("CM_name").Value
		tantousha_name = ds("TS_name1").Value & " " & ds("TS_name2").Value
	End If
End Sub

'=== メイン処理 ===
Dim send_num, mail_format, mail_type, search, company_name, tantousha_name

send_num = Request.Form("send_num")
mail_format = Request.Form("mail_format")
mail_type = Request.Form("mail_type")
Set search = New CSearch
search.ReadCondition

If g_login_type = LOGIN_CLIENT Then
	GetCompanyTantousha
End If

SetHeaderTitle "詳細情報登録"
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnLoad_body() {
<%If g_login_type = LOGIN_AGENT Then%>
	SetCompanyList(document.form1.company_sel.value);
	SetTantoushaList(document.form1.tantousha_sel.value);
<%End If%>
}
function OnSubmit_form1(f) {
	if (f.start_date_y.value == "") {
		alert("発信日を入力してください。");
		f.start_date_y.focus();
		return false;
	}
<%If g_login_type = LOGIN_AGENT Then%>
	if (f.company_id.value == "") {
		alert("発信企業名を選択してください。");
		f.company_id.focus();
		return false;
	}
	if (f.tantousha_id.value == "") {
		alert("担当者名を選択してください。");
		f.tantousha_id.focus();
		return false;
	}
<%End If%>
	if (f.job_name.value == "") {
		alert("ジョブ名を入力してください。");
		f.job_name.focus();
		return false;
	}
	if (f.subject.value == "") {
		alert("件名を入力してください。");
		f.subject.focus();
		return false;
	}
	if (f.subject.value == "（＾＾）！") {
		alert("件名を入力してください。");
		f.subject.focus();
		return false;
	}
	if (f.body.value == "") {
		alert("本文を入力してください。");
		f.body.focus();
		return false;
	}
	if (!f.click_counter[0].checked && !f.click_counter[1].checked) {
		alert("クリックカウンタの有無を選択してください。");
		f.click_counter[0].focus();
		return false;
	}
<%If g_login_type = LOGIN_AGENT Then%>
	f.company_sel.value = f.company_id.value;
	f.tantousha_sel.value = f.tantousha_id.value;
<%End If%>
	return confirm("配信予約を行います。よろしいですか？");
}
function OnClick_cancel() {
	if (confirm("配信予約をキャンセルします。よろしいですか？"))
		location.href = "../top/index.asp";
}
<%If g_login_type = LOGIN_AGENT Then%>
function OnChange_company_id() {
	SetTantoushaList("");
}
function SetCompanyList(def) {
	var txt, arry1, arry2, i, sel = 0;
	txt = "<select name='company_id' onchange='OnChange_company_id()'><option value=''>- 選択してください -</option>\n";
	arry1 = document.form1.company_list.value.split("<%=SEPARATOR2%>");
	for (i = 0; i < arry1.length; i++) {
		if (arry1[i] != "") {
			arry2 = arry1[i].split("<%=SEPARATOR1%>");
			txt += "<option value='" + arry2[0] + "'>" + arry2[1] + "</option>\n";
			if (arry2[0] == def)
				sel = i + 1;
		}
	}
	txt += "</select>";
	document.form1.company_id.outerHTML = txt;
	document.form1.company_id.selectedIndex = sel;
}
function SetTantoushaList(def) {
	var txt, i, j = 0, arry1, arry2, sel = 0;
	txt = "<select name='tantousha_id'><option value=''>- 選択してください -</option>\n";
	arry1 = document.form1.tantousha_list.value.split("<%=SEPARATOR2%>");
	for (i = 0; i < arry1.length; i++) {
		if (arry1[i] != "") {
			arry2 = arry1[i].split("<%=SEPARATOR1%>");
			if (arry2[0] == document.form1.company_id.value) {
				txt += "<option value='" + arry2[1] + "'>" + arry2[2] + "</option>\n";
				j++;
				if (arry2[1] == def)
					sel = j;
			}
		}
	}
	txt += "</select>";
	document.form1.tantousha_id.outerHTML = txt;
	document.form1.tantousha_id.selectedIndex = sel;
}
function AddCompany(company_id, company_name, tantousha_id, tantousha_name) {
	document.form1.company_list.value += company_id + "<%=SEPARATOR1%>" + company_name + "<%=SEPARATOR2%>";
	SetCompanyList(company_id);
	document.form1.tantousha_list.value += company_id + "<%=SEPARATOR1%>" + tantousha_id + "<%=SEPARATOR1%>" + tantousha_name + "<%=SEPARATOR2%>";
	SetTantoushaList(tantousha_id);
	document.form1.job_name.focus();
}
<%End If%>
//-->
</SCRIPT>
</head>
<body onload="OnLoad_body()">

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" name="form1" action="reserve2.asp" onsubmit="return OnSubmit_form1(this)">
	<table border=0 cellspacing=0 cellpadding=0 width='800'>
		<tr>
			<td class="m0">■配信内容をご入力の上、「コンテンツ表示」ボタンを押してください。規定のヘッダ・フッダ挿入後の最終コンテンツがプレビューされます。</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width='100%'>
					 <tr>
						<td class="m3" width="200">配信数</td>
						<td class="n3" width="600"><input type="hidden" name="send_num" value="<%=send_num%>"><%=NumFormat(send_num)%> 通</td>
					</tr>
					<tr>
						<td class="m3" width="200">発信日　※１</td>
						<td class="n3" width="600">西暦
							<select name="start_date_y"><%SelectYear2 ""%></select>年&nbsp;
							<select name="start_date_m"><%SelectMonth ""%></select>月&nbsp;
							<select name="start_date_d"><%SelectDay ""%></select>日&nbsp;
						</td>
					</tr>
<%If g_login_type = LOGIN_AGENT Then%>
					<tr>
						<td class="m3" width="200">発信企業名　※５</td>
						<td class="n3" width="600"><select name="company_id"></select>
<%If Not CheckPermission(PM_REGIST_CLIENT) Then%>
							<input type="button" value="新規登録" onclick="window.open('regist_client.asp?id=<%=g_login_id%>', 'regist_client')">
<%End If%>
						</td>
					</tr>
					<tr>
						<td class="m3" width="200">担当者</td>
						<td class="n3" width="600"><select name="tantousha_id"></select></td>
					</tr>
<%ElseIf g_login_type = LOGIN_CLIENT Then%>
					<tr>
						<td class="m3" width="200">発信企業名</td>
						<td class="n3" width="600"><%=company_name%></td>
					</tr>
					<tr>
						<td class="m3" width="200">担当者</td>
						<td class="n3" width="600"><input type="hidden" name="tantousha_id" value="<%=g_login_id%>"><%=tantousha_name%></td>
					</tr>
<%End If%>
					<tr>
						<td class="m3" width="200">ジョブ名　※７</td>
						<td class="n3" width="600"><input type="text" name="job_name" size=80 maxlength=50></td>
					</tr>
					<tr>
						<td class="m3" width="200">件名（全角20文字以内）　※８</td>
						<td class="n3" width="600"><input type="text" name="subject" size=50 maxlength=25 value="(^^)！"></td>
					</tr>
					<tr>
						<td class="m3" width="200">
							<p>本文　※９</p>
							<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
								<tr>
									<td class="m3">
										<div align="right">■</div>
									</td>
									<td class="m3">チラシタイプ</td>
								</tr>
								<tr>
									<td class="m3">
										<div align="right"></div>
									</td>
									<td class="m3">（全角36文字×200行以内）</td>
								</tr>
								<tr>
									<td class="m3">
										<div align="right">■</div>
									</td>
									<td class="m3">電報タイプ</td>
								</tr>
								<tr>
									<td class="m3">
										<div align="right"></div>
									</td>
									<td class="m3">（全角36文字×20行以内）</td>
								</tr>
							</table>
						</td>
						<td class="n3" width="600">
<%If mail_format = "0" Then%>
							<textarea name="body" cols=78 rows=30 class="np"></textarea>
<%Else%>
							テキストＨＴＭＬ、ＨＴＭＬメールのご入稿の際には、<br>
							<a href="mailto:ad@haimail.net">ad@haimail.net</a>までメール添付にてご入稿ください。
							<input type="hidden" name="body" value="[メールにて入稿]">
<%End If%>
						</td>
					</tr>
					<tr>
						<td class="m3" width="200"><nobr>クリックカウンタの有無　※10</nobr></td>
						<td class="n3" width="600">
								<input type="radio" name="click_counter" value="1">あり
							<input type="radio" name="click_counter" value="0">なし
						</td>
				</table>
			</td>
		</tr>
	</table>
	<p>　</p>
    <table width="800" border="3" cellspacing="0" cellpadding="3" bordercolor="cd853f">
      <tr>
        <td>
          <table border="0" cellspacing="5" cellpadding="0" width="100%">
            <tr> 
              <td valign="top" nowrap class="np" colspan="2"> <font color="336699"><b>■配信予約〆切時間/配信時刻</b></font></td>
            </tr>
            <tr> 
              <td valign="top" width="15" class="info"> 
                <div align="right">１．</div>
              </td>
              <td class="info"><b>配信予約は、<font color="#FF0000">配信希望日の３営業日前１７：００まで</font>となります。配信予約〆切時間以降ご入稿いただきましても、ご希望日の配信は原則できかねます。 
                くれぐれもご注意下さい。 </b>（早めにご予約いただく分には、特に問題ございません）</td>
            </tr>
            <tr> 
              <td valign="top" width="15" class="info"> 
                <div align="right">２．</div>
              </td>
              <td class="info">配信開始時間は、原則配信希望日の午前中に配信させていただきます。混雑状況によりましては、この限りではないことを予めご了承下さい。 
              </td>
            </tr>
            <tr> 
              <td valign="top" width="15" class="info"> 
                <div align="right">３．</div>
              </td>
              <td class="info">配信希望日に万が一、複数のクライアント様からご発注いただいている場合、前もってご発注いただいたクライアント様から優先的に配信させていただきます。センターまで空枠状況をお問合せ下さい。 
              </td>
            </tr>
            <tr> 
              <td valign="top" width="15" class="info"> 
                <div align="right">４．</div>
              </td>
              <td class="info">土日の配信につきましては、不可とさせていただいております。<br>　<br></td>
            </tr>
            <tr> 
              <td valign="top" nowrap class="np" colspan="2"><font color="336699"><b>■原稿登録について</b></font></td>
            </tr>
            <tr> 
              <td valign="top" width="15" class="info"> 
                <div align="right">５．</div>
              </td>
              <td class="info">メールのヘッダへは<B>「発信企業名」</B>が表示されます。正確にご記入下さい。また、誤ってご登録いただいた場合は大変お手数ですが、センターまでご連絡下さい。 </td>
            </tr>
            <tr> 
              <td valign="top" width="15" class="info"> 
                <p align="right">６．</p>
              </td>
              <td class="info">メンバーに「はいめーる・net」からのメールだと認知させるために、<B>件名の文頭には「(^^)！」が必須で挿入されます。</B>
              </td>
            </tr>
            <tr> 
              <td valign="top" width="15" class="info"> 
                <div align="right">７．</div>
              </td>
              <td class="info">はいめーる・netでは、「メール配信」のプロジェクトが発生するたび「ジョブ名」を登録していただいています。「ジョブ」とは、複数発生するプロジェクトを管理するための機能です。 
              </td>
            </tr>
            <tr> 
              <td valign="top" width="15" class="info"> 
                <div align="right">８．</div>
              </td>
              <td class="info"><B>件名は全角２０文字以内で</B>ご登録下さい。（「(^^)！」含まず）</td>
            </tr>
            <tr> 
              <td valign="top" width="15" class="info"> 
                <div align="right">９．</div>
              </td>
              <td class="info">本文は横３６文字以内でお願いしております。原稿をご登録いただく際には列の最後で<font color="red"><b>「必ず改行設定」</b></font>を行ってください。</td>
            </tr>
            <tr>
            <tr> 
              <td valign="top" width="15" class="info"> 
                <div align="right">10．</div>
              </td>
              <td class="info">ＵＲＬの個数に特に制限は設けておりません。本文の行数範囲内でご登録下さい。</td>
            </tr>
            <tr>  
              <td valign="top" width="15" class="info"> 
                <div align="right">11．</div>
              </td>
              <td class="info">クリックカウンタは通常１ヶ月計測しております。長期計測をご希望の際には、センターまでお申し付け下さい。 
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
	<p>
		<input type="submit" value="　コンテンツ表示　">
		<input type="button" value="キャンセル" onclick="OnClick_cancel()">
		<%search.WriteCondition%>
		<input type="hidden" name="mail_format" value="<%=mail_format%>">
		<input type="hidden" name="mail_type" value="<%=mail_type%>">
		<input type="hidden" name="company_list" value="<%=CompanyList%>">
		<input type="hidden" name="company_sel">
		<input type="hidden" name="tantousha_list" value="<%=TantoushaList%>">
		<input type="hidden" name="tantousha_sel">
	</p>
</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
