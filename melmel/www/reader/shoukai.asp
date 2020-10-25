<!--#INCLUDE FILE="../melmel_inc.asp"-->
<%
Const Sess_RS = "Rd1RS"
Const Sess_Title = "Rd1Title"
Const Page_Size = 10
Dim page, keyword, cat, title, i
Dim DB, RS, SQL, SUB1

page = Request("page")
If IsEmpty(page) Then
  page = 1
  If Not IsEmpty(Session(Sess_RS)) Then
    Set Session(Sess_RS) = Nothing
  End If
Else
  page = CLng(page)
  If Not IsEmpty(Session(Sess_RS)) Then
    Set RS = Session(Sess_RS)
  Else
    Response.Redirect "index.html"
  End If
End If

If Not IsObject(RS) Then
  Set DB = OpenDB()
  Set RS = Server.CreateObject("ADODB.RecordSet")
	SUB1 = "SELECT RP_mag_id,MAX(RP_date) AS MAX_date FROM T_REPORTLOG GROUP BY RP_mag_id"
  SQL = "SELECT MG_mag_id,MG_m_name,MG_hindo,MG_new_busuu,MG_koudoku_url,MG_shousai_url,MG_shoukai_text," & vbCrLf & _
        "MG_mms_melonpan,MG_mms_mag,MG_mms_pubz,MG_mms_melma,MG_mms_macky,MG_mms_kapu,MG_mms_emag,MG_mms_tengoku,MG_mms_tiara,MG_mms_merubox,MG_mms_dokuji,MG_mms_other" & vbCrLf & _
        "FROM T_MELMAG LEFt OUTER JOIN (" & SUB1 & ") AS SUB1 ON MG_mag_id=RP_mag_id" & vbCrLf & _
        "WHERE MG_status=0 AND LEN(ISNULL(MG_koudoku_url,''))>7 AND ISNULL(MG_shoukai_text,'')<>'' AND MG_shoukai_flag1=1 AND MG_shoukai_flag2=1" & vbCrLf & _
			  " AND 0+ISNULL(MG_cat_int,0)+ISNULL(MG_cat_pcc,0)+ISNULL(MG_cat_soft,0)+ISNULL(MG_cat_mvl,0)+ISNULL(MG_cat_bns,0)+" & vbCrLf & _
			  " ISNULL(MG_cat_seiji,0)+ISNULL(MG_cat_kabu,0)+ISNULL(MG_cat_fsn,0)+ISNULL(MG_cat_lif,0)+ISNULL(MG_cat_shumi,0)+" & vbCrLf & _
			  " ISNULL(MG_cat_res,0)+ISNULL(MG_cat_tvl,0)+ISNULL(MG_cat_spt,0)+ISNULL(MG_cat_gamble,0)+ISNULL(MG_cat_hlt,0)+" & vbCrLf & _
			  " ISNULL(MG_cat_art,0)+ISNULL(MG_cat_shp,0)+ISNULL(MG_cat_ent,0)+ISNULL(MG_cat_game,0)+ISNULL(MG_cat_movie,0)+" & vbCrLf & _
			  " ISNULL(MG_cat_uranai,0)+ISNULL(MG_cat_std,0)+ISNULL(MG_cat_shikaku,0)+ISNULL(MG_cat_gogaku,0)+ISNULL(MG_cat_nws,0)+" & vbCrLf & _
			  " ISNULL(MG_cat_zatsu,0)+ISNULL(MG_cat_kenshou,0)+ISNULL(MG_cat_homepage,0)+ISNULL(MG_cat_hitokoto,0)+ISNULL(MG_cat_other,0)<=2"
  keyword = Request("keyword")
  If IsEmpty(keyword) Or keyword = "" Then
    cat = Request("cat")
    If Not IsEmpty(cat) And cat <> "" Then
      Select Case cat
      Case "int"
        title = "インターネット"
      Case "pcc"
        title = "パソコン/コンピュータ全般"
      Case "soft"
        title = "ソフトウェア/プログラミング"
      Case "mvl"
        title = "携帯/モバイル"
      Case "bns"
        title = "ビジネス全般"
      Case "seiji"
        title = "政治/経済"
      Case "kabu"
        title = "株式/投資"
      Case "fsn"
        title = "ファッション/美容"
      Case "lif"
        title = "暮らし/日記"
      Case "shumi"
        title = "趣味"
      Case "res"
        title = "グルメ/レストラン"
      Case "tvl"
        title = "旅行/地域"
      Case "spt"
        title = "スポーツ/レジャー"
      Case "gamble"
        title = "ギャンブル"
      Case "hlt"
        title = "健康/医療/育児"
      Case "art"
        title = "アート/文芸"
      Case "shp"
        title = "ショッピング"
      Case "ent"
        title = "エンターテイメント"
      Case "game"
        title = "ゲーム/クイズ"
      Case "movie"
        title = "映画/音楽/芸能"
      Case "uranai"
        title = "占い"
      Case "std"
        title = "学習/教育"
      Case "shikaku"
        title = "資格"
      Case "gogaku"
        title = "語学"
      Case "nws"
        title = "ニュース"
      Case "zatsu"
        title = "雑学"
      Case "kenshou"
        title = "懸賞/プレゼント"
      Case "homepage"
        title = "ホームページ紹介"
      Case "hitokoto"
        title = "一日ひとこと系"
      Case "other"
        title = "その他"
      End Select

      SQL = SQL & " AND MG_cat_" & cat & "=1" & vbCrLf
    End If
  Else
    title = keyword
    SQL = SQL & " AND (MG_m_name Like '%" & Replace(keyword, "'", "''") & "%' OR MG_shoukai_text Like '%" & Replace(keyword, "'", "''") & "%')" & vbCrLf
  End If
  SQL = SQL & "ORDER BY MAX_date DESC"
  RS.Open SQL, DB, adOpenStatic, adLockReadOnly
  RS.PageSize = Page_Size
  Set Session(Sess_RS) = RS
  Session(Sess_Title) = title
Else
  title = Session(Sess_Title)
End If

If page < 1 Then
  page = 1
End If

If page > RS.PageCount Then
  page = RS.PageCount
End If

If RS.PageCount > 0 Then
  RS.AbsolutePage = page
End If
%>
<html>
<head>
<title>めるめる・net マガジンリスト</title>
<SCRIPT LANGUAGE=javascript>
<!--
function OnClick_melonpan(id) {
	window.open("melonpan.asp?id=" + id,"koudoku","width=480,height=150,resizable=yes");
}
//-->
</SCRIPT>
</head>

<body bgcolor="#ff9999" text="#000000" link="#0033ff" vlink="#990099" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<table border="0" cellpadding="0" cellspacing="0" width="99%" height="99%">
<!-----  めるめる・net ロゴ  ----->
<tr><td align="center">
<table border="0" cellpadding="0" cellspacing="0" width="600">
<tr><td align="right"><a href="../index.html"><img src="images/head_logo.gif" width="252" height="96" border="0" alt="めるめる・net ロゴ" vspace="1" hspace="1"></a></td>
<td><img src="images/head_t1.gif" width="348" height="52" border="0" alt="Welcome to melmel-net !" vspace="1" hspace="1"></td></tr>
</table>

<!----  外枠・ブルー  ---->
<table bgcolor="#0099ff" border="0" cellpadding="0" cellspacing="0" width="600">
<tr><td><img src="images/spacer.gif" width="1" height="7"></td></tr>
<tr><td align="center">
<!----  キャンパス・ホワイト  ---->
<table border="0" cellpadding="0" cellspacing="0" width="600" bgcolor="#ffffff">
<tr><td align="center" ><img src="images/spacer.gif" width="1" height="1" vspace="10"></td></tr>
<tr><td>
<table border="0" cellpadding="0" cellspacing="0">
<!-----  カテゴリ  ----->
<tr><td><img src="images/spacer.gif" width="25" height="1"></td>
<td><font size="3" color="#99ccff">■ </font><font size="4"><b><%=title%></b></font></td>
<td><img src="images/cara_s.gif" width="66" height="49" hspace="4"></td></tr>
</table></td></td></tr>
<tr><td ><img src="images/spacer.gif" width="1" height="10"></td></tr>
<tr>
<td align="right" >
<!-----  list table  ----->
<%
If RS.RecordCount = 0 Then
%>
  <center>
  <font size="3" color="#ff3333">該当するメールマガジンはみつかりませんでした。</font><br><br>
  <a href="index.asp"><font size="2" color="#0033ff">条件を変えてもう一度実行してください。</font></a>
  </center>
<%
Else
%>
<table border="0" cellpadding="0" cellspacing="0" width="600">
<tr>
<td>
<table border="0" cellpadding="0" cellspacing="0">
<tr>
<td><img src="images/spacer.gif" width="12" height="1"></td>
<td><img src="images/tri3.gif" width="16" height="16" border="0" hspace="2"></td>
<td><a href="index.asp"><font size="2" color="#0033ff">カテゴリ一覧</font></a><font size="2" color="#0033ff">｜</font><a href="../index.html"><font size="2" color="#0033ff">めるめる・netトップ</font></a></td>
<td><img src="images/tri4.gif" width="16" height="16" border="0" hspace="2"><font color="#006666" size="1">＊発行部数：「まぐまぐ」の部数を除く。</font></td></tr>
</table></td>
<td align="right">
<table border="0" cellpadding="0" cellspacing="0">
<tr>
<td><img src="images/tri1.gif" width="16" height="16" border="0" hspace="2"></td>
<td>
<%
If page > 1 Then
  Response.Write "<a href='shoukai.asp?page=" & page - 1 & "'><font size='2' color='#0033ff'>前の１０件</font></a>"
Else
  Response.Write "<font size='2' color='#aaaaaa'>前の１０件</font>"
End If
Response.Write "<font size='2' color='#0033ff'>｜</font>"
If page < RS.PageCount Then
  Response.Write "<a href='shoukai.asp?page=" & page + 1 & "'><font size='2' color='#0033ff'>次の１０件</font></a>"
Else
  Response.Write "<font size='2' color='#aaaaaa'>次の１０件</font>"
End If
%>
</td>
<td><img src="images/tri2.gif" width="16" height="16" border="0" hspace="2"></td>
<td><img src="images/spacer.gif" width="12" height="1"></td></tr>
</table></td></tr>
</table></td></tr>

<tr>
<td align="center" >
<table border="0" cellpadding="5" cellspacing="0" width="564">
<%
For i = 1 To Page_Size
  If RS.EOF Then
    Exit For
  End If
%>
<tr><td>
	<table border="0" cellpadding="0" cellspacing="0" bgcolor="#666666" width="564">
	<tr><td>
	<table border="0" cellpadding="2" cellspacing="1" bordercolor="#666666" width="564">
	<tr bgcolor="#99ccff"><td width="564"><font color="#0000ff"><b><%=ReplaceTag(RS("MG_m_name"))%></b></font></td></tr>
	<tr bgcolor="#ffffcc"><td>
	<table border="0" cellpadding="0" cellspacing="0" width="564">
	<tr><td><font size="-2" color="#ff3333">発行頻度:</font><font size="-2" color="#333333"><%=RS("MG_hindo")%>　</font><font size="-2" color="#ff3333">発行部数:</font><font size="-2" color="#333333"><%=NumFormat(RS("MG_new_busuu"))%></font></td>
	<td align="right" nowrap>
<%
	If RS("MG_mms_melonpan") Then
		Response.Write "<a href='JavaScript:OnClick_melonpan(""" & RS("MG_mag_id") & """)'><img src='images/button3.gif' width='140' height='20' border='0' alt='めろんぱんで購読'></a>"
	End If
  If Len(RS("MG_shousai_url")) > 7 Then
    Response.Write "<a href='" & RS("MG_shousai_url") & "' target='shousai'><img src='images/button2.gif' width='81' height='20' border='0'></a>"
  End If
	If NVL(RS("MG_mms_melonpan"), 0) = 0 Then
	  Response.Write "<a href='" & RS("MG_koudoku_url") & "' target='koudoku'><img src='images/button1.gif' width='81' height='20' border='0' hspace='4'></a>"
	End If
%></td></tr>
	</table></td></tr>
	<tr><td bgcolor="#ffffff" width="564">
	<table border="0" cellpadding="8" cellspacing="0" width="564">
	<tr><td bgcolor="#ffffff"><font size="-1" color="#333333"><%=ReplaceTag(RS("MG_shoukai_text"))%></font></td></tr>
	</table></td></tr>
	<tr bgcolor="#ffffcc" align="right"><td width="564"><font size="-2" color="#ff3333">発行システム:</font><font color="#333333" size="-2">
<%
  If RS("MG_mms_melonpan") Then Response.Write "めろんぱん "
  If RS("MG_mms_mag") Then Response.Write "まぐまぐ "
  If RS("MG_mms_pubz") Then Response.Write "Pubzine "
  If RS("MG_mms_melma") Then Response.Write "melma! "
  If RS("MG_mms_macky") Then Response.Write "Macky "
  If RS("MG_mms_kapu") Then Response.Write "カプライト "
  If RS("MG_mms_emag") Then Response.Write "E-MAGAZINE "
  If RS("MG_mms_tengoku") Then Response.Write "メルマガ天国 "
  If RS("MG_mms_tiara") Then Response.Write "ティアラオンライン "
  If RS("MG_mms_merubox") Then Response.Write "MERU-BOX "
  If RS("MG_mms_dokuji") Then Response.Write "独自配信 "
  If RS("MG_mms_other") Then Response.Write "その他 "
%>
  </font></td></tr>
	</table></td></tr>
	</table></td></tr>
<%
RS.MoveNext
Next
%>
</table></td></tr>

<tr><td>
<table border="0" cellpadding="0" cellspacing="0" width="600">
<tr><td>
<table border="0" cellpadding="0" cellspacing="0">
<tr>
<td><img src="images/spacer.gif" width="12" height="1"></td>
<td><img src="images/tri3.gif" width="16" height="16" border="0" hspace="2"></td>
<td><a href="index.asp"><font size="2" color="#0033ff">カテゴリ一覧</font></a><font size="2" color="#0033ff">｜</font><a href="../index.html"><font size="2" color="#0033ff">めるめる・netトップ</font></a></td>
<td><img src="images/tri4.gif" width="16" height="16" border="0" hspace="2"><font color="#006666" size="1">＊発行部数：「まぐまぐ」の部数を除く。</font></td></tr>
</table></td>
<td align="right">
<table border="0" cellpadding="0" cellspacing="0">
<tr>
<td><img src="images/tri1.gif" width="16" height="16" border="0" hspace="2"></td>
<td>
<%
If page > 1 Then
  Response.Write "<a href='shoukai.asp?page=" & page - 1 & "'><font size='2' color='#0033ff'>前の１０件</font></a>"
Else
  Response.Write "<font size='2' color='#aaaaaa'>前の１０件</font>"
End If
Response.Write "<font size='2' color='#0033ff'>｜</font>"
If page < RS.PageCount Then
  Response.Write "<a href='shoukai.asp?page=" & page + 1 & "'><font size='2' color='#0033ff'>次の１０件</font></a>"
Else
  Response.Write "<font size='2' color='#aaaaaa'>次の１０件</font>"
End If
%>
</td>
<td><img src="images/tri2.gif" width="16" height="16" border="0" hspace="2"></td>
<td><img src="images/spacer.gif" width="12" height="1"></td></tr>
</table></td></tr>
</table></td></tr>
<%
End If
%>
<!-----  mail  ----->
<tr><td align="center" ><img src="images/spacer.gif" width="470" height="20"></td></tr>
<tr><td align="center" ><font size="2">□ めるめる・net事務局 □</font><br><a href="mailto:info@melmel.net"><font size="2" color="#0033ff">info@melmel.net</font></a></td></tr>
<tr><td align="center" ><img src="images/spacer.gif" width="470" height="20"></td></tr>
</table></td></tr>
<tr><td><img src="images/spacer.gif" width="1" height="7"></td></tr>
</table>

<!-----  Copyright  ----->
<table border="0" cellpadding="0" cellspacing="0">
<tr>
<td rowspan="2" align="right" valign="bottom" width="135"><img src="images/cara_s.gif" width="66" height="49" border="0" alt="めるめる イメージ"></td>
<td valign="bottom" width="300" height="42"><img src="images/logo_s.gif" width="155" height="42" border="0" alt="めるめる・net ロゴ"></td>
<tr><td valign="top" height="22"><font size="1">Copyright (C) <%=Year(Date)%> xx xxxxx Inc. All rights reserved.</font></td></tr>
</table></td></tr>

</table>

</body>
</html>