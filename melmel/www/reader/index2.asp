<!--#INCLUDE FILE="../melmel_inc.asp"-->
<%
Dim DB, RS, SQL, WHERE
Dim cat_int, cat_pcc, cat_bns, cat_fsn, cat_lif, cat_shp, cat_res, cat_tvl, cat_spt, cat_mvl, cat_hlt, cat_art, cat_ent, cat_std, cat_nws, cat_other
Dim rec_count

'=== カテゴリ別の件数取得
Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")
WHERE = "MG_status=0 AND LEN(ISNULL(MG_koudoku_url,''))>7 AND ISNULL(MG_shoukai_text,'')<>'' AND MG_shoukai_flag1=1 AND MG_shoukai_flag2=1" & vbCrLf & _
			" AND 0+ISNULL(MG_cat_int,0)+ISNULL(MG_cat_pcc,0)+ISNULL(MG_cat_bns,0)+ISNULL(MG_cat_fsn,0)+ISNULL(MG_cat_lif,0)+" & vbCrLf & _
			" ISNULL(MG_cat_shp,0)+ISNULL(MG_cat_res,0)+ISNULL(MG_cat_tvl,0)+ISNULL(MG_cat_spt,0)+ISNULL(MG_cat_mvl,0)+" & vbCrLf & _
			" ISNULL(MG_cat_hlt,0)+ISNULL(MG_cat_art,0)+ISNULL(MG_cat_ent,0)+ISNULL(MG_cat_std,0)+ISNULL(MG_cat_nws,0)+" & vbCrLf & _
			" ISNULL(MG_cat_other,0)<=2"
SQL = "SELECT" & vbCrLf & _
      "COUNT(NULLIF(MG_cat_int, 0)) AS cat_int," & vbCrLf & _
      "COUNT(NULLIF(MG_cat_pcc, 0)) AS cat_pcc," & vbCrLf & _
      "COUNT(NULLIF(MG_cat_bns, 0)) AS cat_bns," & vbCrLf & _
      "COUNT(NULLIF(MG_cat_fsn, 0)) AS cat_fsn," & vbCrLf & _
      "COUNT(NULLIF(MG_cat_lif, 0)) AS cat_lif," & vbCrLf & _
      "COUNT(NULLIF(MG_cat_shp, 0)) AS cat_shp," & vbCrLf & _
      "COUNT(NULLIF(MG_cat_res, 0)) AS cat_res," & vbCrLf & _
      "COUNT(NULLIF(MG_cat_tvl, 0)) AS cat_tvl," & vbCrLf & _
      "COUNT(NULLIF(MG_cat_spt, 0)) AS cat_spt," & vbCrLf & _
      "COUNT(NULLIF(MG_cat_mvl, 0)) AS cat_mvl," & vbCrLf & _
      "COUNT(NULLIF(MG_cat_hlt, 0)) AS cat_hlt," & vbCrLf & _
      "COUNT(NULLIF(MG_cat_art, 0)) AS cat_art," & vbCrLf & _
      "COUNT(NULLIF(MG_cat_ent, 0)) AS cat_ent," & vbCrLf & _
      "COUNT(NULLIF(MG_cat_std, 0)) AS cat_std," & vbCrLf & _
      "COUNT(NULLIF(MG_cat_nws, 0)) AS cat_nws," & vbCrLf & _
      "COUNT(NULLIF(MG_cat_other, 0)) AS cat_other" & vbCrLf & _
			"FROM T_MELMAG" & vbCrLf & _
			"WHERE " & WHERE
RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
If Not RS.EOF Then
	cat_int = RS("cat_int")
	cat_pcc = RS("cat_pcc")
	cat_bns = RS("cat_bns")
	cat_fsn = RS("cat_fsn")
	cat_lif = RS("cat_lif")
	cat_shp = RS("cat_shp")
	cat_res = RS("cat_res")
	cat_tvl = RS("cat_tvl")
	cat_spt = RS("cat_spt")
	cat_mvl = RS("cat_mvl")
	cat_hlt = RS("cat_hlt")
	cat_art = RS("cat_art")
	cat_ent = RS("cat_ent")
	cat_std = RS("cat_std")
	cat_nws = RS("cat_nws")
	cat_other = RS("cat_other")
End If
RS.Close

'=== 瞬間お奨めメルマガ取得 ===
SQL = "SELECT MG_m_name,MG_hindo,MG_new_busuu,MG_koudoku_url,MG_shousai_url,MG_shoukai_text," & vbCrLf & _
      "MG_mms_mag,MG_mms_pubz,MG_mms_macky,MG_mms_kapu,MG_mms_emag,MG_mms_melma,MG_mms_tengoku,MG_mms_other" & vbCrLf & _
			"FROM T_MELMAG" & vbCrLf & _
			"WHERE " & WHERE
RS.Open SQL, DB, adOpenKeyset, adLockReadOnly
rec_count = RS.RecordCount
If rec_count > 0 Then
	Randomize
	RS.AbsolutePosition = Int(rnd * rec_count) + 1
End If
%>
<html>
<head>
<title>めるめる・net マガジンリスト</title>
<SCRIPT LANGUAGE=javascript>
<!--
function OnSubmit_form1(frm) {
  if (frm.keyword.value == "") {
    alert("キーワードを入力してください。");
    frm.keyword.focus();
    return false;
  }
  return true;
}
//-->
</SCRIPT>
</head>

<body bgcolor="#ff9999" text="#000000" link="#0033ff" vlink="#990099" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<div align="center">

<!-----  めるめる・net ロゴ  ----->
<table border="0" cellpadding="0" cellspacing="0" width="600">
<tr>
<td align="right"><a href="../index.html"><img src="images/head_logo.gif" width="252" height="96" border="0" alt="めるめる・net ロゴ" vspace="1" hspace="1"></a></td>
<td><img src="images/head_t1.gif" width="348" height="52" border="0" alt="Welcome to melmel-net !" vspace="1" hspace="1"></td></tr>
</table>

<!----  外枠・ブルー  ---->
<table border="0" cellpadding="0" cellspacing="0" width="600">

<!----  Top Line  ---->
<tr><td align="center" bgcolor="#0099ff"><img src="images/spacer.gif" width="600" height="7"></td></tr>

<!-----  ベース・ホワイト  ----->
<tr><td valign="top" bgcolor="#0099ff">
<table border="0" cellpadding="0" cellspacing="0" width="601" bgcolor="#ffffff">

<!----  テキスト  ---->
<tr><td align="center" width="599">
<table border="0" cellpadding="0" cellspacing="0" width="520">
<!----  コメント･トップ  ---->
<tr><td align="center">
<table border="0" cellpadding="3" cellspacing="0" width="504">
<tr><td width="496"><img src="images/spacer.gif" width="1" height="5"></td></tr>
<tr><td align="center" width="496"><font color="#0033ff" size="4">●<b>　めるめる・netは優良メルマガ満載サイトです。　</b>●</font></td></tr>
<tr><td width="496"><font size="2">めるめる・netに登録しているメルマガは、どれも読者の方が心待ちにしているものばかり。「いざとってみたら期待はずれ、あとはゴミ箱行き・・・」なんてことはありません。カテゴリーから、フリーワードから、あなたにもきっと「これは！」というメルマガが見つかるはずです。</font></td></tr>
</table></td></tr><!----  コメント･トップ/END  ---->
</table></td></tr><!----  テキスト/END  ---->
<%
If rec_count > 0 Then
%>
<!----  コメント・紹介  ---->
<tr><td align="center" height="10" width="599">
<table border="0" cellpadding="10" cellspacing="0">
<tr><td nowrap><b><font color="#FF3333" size="3">↓↓↓たとえば、この瞬間のお勧めメルマガはコレ！↓↓↓</font></b></td></tr>
</table></td></tr><!----  コメント・紹介/END  ---->

<!----  メルマガ・おすすめ  ---->
<tr><td align="center" height="121" width="599">
<!----  外枠  ---->
<table border="0" cellpadding="0" cellspacing="0" width="564">

<!----  内枠  ---->
<tr><td bgcolor="#ffffcc">
<table border="1" bordercolor="#666666"  cellpadding="2" cellspacing="1" width="564">

<!----  メルマガ・タイトル  ---->
<tr><td bgcolor="#99ccff"><font size="3" color="#0000ff"><b><%=ReplaceTag(RS("MG_m_name"))%></b></font></td></tr><!----  メルマガ・タイトル/END  ---->

<!----  発行部数・頻度＆ボタン  ---->
<tr><td bgcolor="#ffffcc">
<table border="0" cellpadding="0" cellspacing="0" width="564">
<!----  発行部数・頻度  ---->
<tr><td><font size="2"><font color="#ff3333">発行頻度:</font><font color="#333333"><%=RS("MG_hindo")%>　</font><font color="#ff3333">発行部数:</font><font size="1" color="#333333"><%=NumFormat(RS("MG_new_busuu"))%></font></font></td>
<!----  ボタン  ---->
<td align="right" nowrap>
<font size="2">
<%
  If Len(RS("MG_shousai_url")) > 7 Then
		Response.Write "<a href='" & RS("MG_shousai_url") & "' target='shousai'><img src='images/button2.gif' width='81' height='20' border='0' alt='詳細'></a>"
	End If
	Response.Write "<a href='" & RS("MG_koudoku_url") & "' target='koudoku'><img src='images/button1.gif' width='81' height='20' border='0' alt='購読'></a>"
%></font></td></tr>
</table></td></tr><!----  発行部数・頻度＆ボタン/END  ---->

<!----  コメント  ---->
<tr><td bgcolor="#ffffff" align="center">
<table border="0" cellpadding="8" cellspacing="0" width="100%">
<tr><td><font color="#333333"><%=ReplaceTag(RS("MG_shoukai_text"))%></font></td></tr>
</table></td></tr><!----  コメント/END  ---->

<!----  発行システム  ---->
<tr><td bgcolor="#ffffcc"><font size="2"><font color="#ff3333">発行システム:</font><font color="#333333">
</font></font><font size="1" color="#333333">
<%
  If RS("MG_mms_mag") Then Response.Write "まぐまぐ "
  If RS("MG_mms_melma") Then Response.Write "melma! "
  If RS("MG_mms_pubz") Then Response.Write "Pubzine "
  If RS("MG_mms_macky") Then Response.Write "Macky "
  If RS("MG_mms_kapu") Then Response.Write "カプライト "
  If RS("MG_mms_emag") Then Response.Write "E-MAGAZINE "
  If RS("MG_mms_tengoku") Then Response.Write "メルマガ天国 "
  If RS("MG_mms_other") Then Response.Write "その他"
%>
</font></td></tr><!----  発行システム/END  ---->

</table></td></tr><!----  内枠/END  ---->
</table><!----  外枠/END  ---->
</td></tr><!----  メルマガ・おすすめ/END  ---->
<%
End If
RS.Close
%>
<tr><td height="1" width="599"><img src="images/spacer.gif" width="1" height="5"></td></tr>

<!-----  Category・Form  ----->
<tr><td align="center" height="200" width="599">
<table border="0" cellpadding="0" cellspacing="0" width="572">
<tr>

<!-----  Category  ----->
<td align="center" valign="top">
<table border="0" cellpadding="5" cellspacing="0" width="500">

<!----  Category Bar Top  ---->
<tr><td bgcolor="#ff3333">
<form method="post" action="shoukai.asp" name="form1" onSubmit="return OnSubmit_form1(this)">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr><td><img src="images/category_t.gif" width="118" height="20" alt="カテゴリ一覧"></td>
<!----  Form  ---->
<td align="right">
<input type="text" size="40" name="keyword">
<input type="submit" value="検索"><br>
<font size="1" color="#ffffff">メルマガのタイトルと説明文から検索します</font>
</td></tr>
</table></td></tr>
</form><!----  Form/END  ---->

<!--
<tr><td><img src="images/spacer.gif" width="1" height="5"></td></tr>
-->
<tr>

<!----  Category All  ---->
<td align="center">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr><td align="left" valign="top">
<!----  Category Left  ---->
<table border="0" cellpadding="0" cellspacing="0">
<tr><td height="25"><img src="images/stick.gif" width="23" height="23"><img src="images/spacer.gif" width="5" height="1"></td><td nowrap height="25"><a href="shoukai.asp?cat=int"><font size="2" color="#0033ff"><b>インターネット</b></font></a><font size="2" color="#0033ff"> (<%=NumFormat(cat_int)%>)</font></td></tr>
<tr><td colspan="2"><img src="images/spacer.gif" width="1" height="2"></td></tr>
<tr><td height="25"><img src="images/stick.gif" width="23" height="23"><img src="images/spacer.gif" width="5" height="1"></td><td nowrap height="25"><a href="shoukai.asp?cat=pcc"><font size="2" color="#0033ff"><b>パソコン/コンピュータ</b></font></a><font size="2" color="#0033ff"> (<%=NumFormat(cat_pcc)%>)</font></td></tr>
<tr><td colspan="2"><img src="images/spacer.gif" width="1" height="2"></td></tr>
<tr><td height="25"><img src="images/stick.gif" width="23" height="23"><img src="images/spacer.gif" width="5" height="1"></td><td nowrap height="25"><a href="shoukai.asp?cat=bns"><font size="2" color="#0033ff"><b>ビジネス</b></font></a><font size="2" color="#0033ff"> (<%=NumFormat(cat_bns)%>)</font></td></tr>
<tr><td colspan="2"><img src="images/spacer.gif" width="1" height="2"></td></tr>
<tr><td height="25"><img src="images/stick.gif" width="23" height="23"><img src="images/spacer.gif" width="5" height="1"></td><td nowrap height="25"><a href="shoukai.asp?cat=fsn"><font size="2" color="#0033ff"><b>ファッション/美容</b></font></a><font size="2" color="#0033ff"> (<%=NumFormat(cat_fsn)%>)</font></td></tr>
<tr><td colspan="2"><img src="images/spacer.gif" width="1" height="2"></td></tr>
<tr><td height="25"><img src="images/stick.gif" width="23" height="23"><img src="images/spacer.gif" width="5" height="1"></td><td nowrap height="25"><a href="shoukai.asp?cat=lif"><font size="2" color="#0033ff"><b>暮らし</b></font></a><font size="2" color="#0033ff"> (<%=NumFormat(cat_lif)%>)</font></td></tr>
<tr><td colspan="2"><img src="images/spacer.gif" width="1" height="2"></td></tr>
<tr><td height="25"><img src="images/stick.gif" width="23" height="23"><img src="images/spacer.gif" width="5" height="1"></td><td nowrap height="25"><a href="shoukai.asp?cat=other"><font size="2" color="#0033ff"><b>その他</b></font></a><font size="2" color="#0033ff"> (<%=NumFormat(cat_other)%>)</font></td></tr>
</table></td><!----  Category Left/END  ---->

<!----  余白  ---->
<td rowspan="15"><img src="images/spacer.gif" width="10" height="1"></td>

<td align="center" valign="top">
<!----  Category Left  ---->
<table border="0" cellpadding="0" cellspacing="0">
<tr><td><img src="images/stick.gif" width="23" height="23"><img src="images/spacer.gif" width="5" height="1"></td><td nowrap><a href="shoukai.asp?cat=res"><font size="2" color="#0033ff"><b>グルメ/レストラン</b></font></a><font size="2" color="#0033ff"> (<%=NumFormat(cat_res)%>)</font></td></tr>
<tr><td colspan="2"><img src="images/spacer.gif" width="1" height="2"></td></tr>
<tr><td><img src="images/stick.gif" width="23" height="23"><img src="images/spacer.gif" width="5" height="1"></td><td nowrap><a href="shoukai.asp?cat=tvl"><font size="2" color="#0033ff"><b>旅行/地域</b></font></a><font size="2" color="#0033ff"> (<%=NumFormat(cat_tvl)%>)</font></td></tr>
<tr><td colspan="2"><img src="images/spacer.gif" width="1" height="2"></td></tr>
<tr><td><img src="images/stick.gif" width="23" height="23"><img src="images/spacer.gif" width="5" height="1"></td><td nowrap><a href="shoukai.asp?cat=spt"><font size="2" color="#0033ff"><b>スポーツ/レジャー</b></font></a><font size="2" color="#0033ff"> (<%=NumFormat(cat_spt)%>)</font></td></tr>
<tr><td colspan="2"><img src="images/spacer.gif" width="1" height="2"></td></tr>
<tr><td><img src="images/stick.gif" width="23" height="23"><img src="images/spacer.gif" width="5" height="1"></td><td nowrap><a href="shoukai.asp?cat=mvl"><font size="2" color="#0033ff"><b>携帯/モバイル</b></font></a><font size="2" color="#0033ff"> (<%=NumFormat(cat_mvl)%>)</font></td></tr>
<tr><td colspan="2"><img src="images/spacer.gif" width="1" height="2"></td></tr>
<tr><td><img src="images/stick.gif" width="23" height="23"><img src="images/spacer.gif" width="5" height="1"></td><td nowrap><a href="shoukai.asp?cat=hlt"><font size="2" color="#0033ff"><b>健康/医療/育児</b></font></a><font size="2" color="#0033ff"> (<%=NumFormat(cat_hlt)%>)</font></td></tr>

</table></td><!----  Category Left/END  ---->

<!----  余白  ---->
<td rowspan="15"><img src="images/spacer.gif" width="10" height="1"></td>

<!----  Category Right  ---->
<td align="right" valign="top">
<table border="0" cellpadding="0" cellspacing="0">
<tr><td><img src="images/stick.gif" width="23" height="23"><img src="images/spacer.gif" width="5" height="1"></td><td nowrap><a href="shoukai.asp?cat=art"><font size="2" color="#0033ff"><b>アート/文芸</b></font></a><font size="2" color="#0033ff"> (<%=NumFormat(cat_art)%>)</font></td></tr>
<tr><td colspan="2"><img src="images/spacer.gif" width="1" height="2"></td></tr>
<tr><td><img src="images/stick.gif" width="23" height="23"><img src="images/spacer.gif" width="5" height="1"></td><td nowrap><a href="shoukai.asp?cat=ent"><font size="2" color="#0033ff"><b>エンターテイメント</b></font></a><font size="2" color="#0033ff"> (<%=NumFormat(cat_ent)%>)</font></td></tr>
<tr><td colspan="2"><img src="images/spacer.gif" width="1" height="2"></td></tr>
<tr><td><img src="images/stick.gif" width="23" height="23"><img src="images/spacer.gif" width="5" height="1"></td><td nowrap><a href="shoukai.asp?cat=std"><font size="2" color="#0033ff"><b>学習/教育</b></font></a><font size="2" color="#0033ff"> (<%=NumFormat(cat_std)%>)</font></td></tr>
<tr><td colspan="2"><img src="images/spacer.gif" width="1" height="2"></td></tr>
<tr><td><img src="images/stick.gif" width="23" height="23"><img src="images/spacer.gif" width="5" height="1"></td><td nowrap><a href="shoukai.asp?cat=nws"><font size="2" color="#0033ff"><b>ニュース/情報提供</b></font></a><font size="2" color="#0033ff"> (<%=NumFormat(cat_nws)%>)</font></td></tr>
<tr><td colspan="2"><img src="images/spacer.gif" width="1" height="2"></td></tr>
<tr><td><img src="images/stick.gif" width="23" height="23"><img src="images/spacer.gif" width="5" height="1"></td><td nowrap><a href="shoukai.asp?cat=shp"><font size="2" color="#0033ff"><b>ショッピング</b></font></a><font size="2" color="#0033ff"> (<%=NumFormat(cat_shp)%>)</font></td></tr>
</table></td></tr><!----  Category Right/END  ---->
</table><!----  Category All/END  ---->

<!--<tr><td><img src="images/spacer.gif" width="1" height="5"></td></tr>      -->
<!----  Bar Bottom  ---->
<tr><td bgcolor="#ff3333"><img src="images/spacer.gif" width="1" height="10"></td></tr>
</table></td></tr><!-----  Category/END  ----->

</table></td></tr><!-----  Category・Form/END  ----->

<tr><td height="1" width="599"><img src="images/spacer.gif" width="1" height="10"></td></tr>

<!----  バナー  ---->
<tr><td align="center" width="599">
<table border="1" bordercolor="#999999" cellpadding="5" cellspacing="0" width="420">
<tr><td>
<table border="0" cellpadding="10" cellspacing="0" width="100%">

<!----  コメント  ---->
<tr><td align="center"><font size="2" color="#000099"><a href="http://www.xxxxxxx.co.jp">株式会社○○○○○○</a>が運営するデジタル＆ネットワークサービスです。<br>あなたも興味のあるサービスに登録してみませんか？</font></td></tr>
<tr><td align="center">
<table border="0" cellpadding="10" cellspacing="0">
<!----  ひとびとネット  ---->
<tr><td bgcolor="#ffffff" align="center" nowrap>
<a href="cgi-bin/ntadtrack.exe?http://www.hitobito.net/ct/m1123.html">
<img src="images/hitobito02u.gif" width="143" height="60" border="0"><br>
</a><font size="1">人と人、おしえられたりおしえたり</font></td>

<!----  きかせてネット  ---->
<td bgcolor="#ffffff" align="center">
<a href="cgi-bin/ntadtrack.exe?http://www.kikasete.ne.jp/melhp.html">
<img src="images/logo_foot.gif" width="139" height="45" border="0"></a><br>
<font size="1">１０万人のアンケートモニターネットワーク</font></td>

<!----  ecHelp  ---->
<td bgcolor="#ffffff" align="center">
<a href="cgi-bin/ntadtrack.exe?http://www.echelp.net/">
<img src="images/bannar_girlsgate.gif" width="114" height="40" border="0"></a><br>
<font size="1">イーシーヘルプはインターネットの「良品見聞録」</font></td></tr>
</table>

<!--
<table border="0" cellpadding="10" cellspacing="0" width="320">

<tr><td bgcolor="#ffffff" align="center">
<img src="images/hitobito02u.gif" width="143" height="60" border="0"><br>
<font size="1">人と人、教えられたり教えたり</font></td>


<td bgcolor="#ffffff" align="center">
<img src="images/bannar_girlsgate.gif" width="114" height="40" border="0"><br>
<font size="1">イーシーヘルプはインターネットの「良品見聞録」</font></td></tr>
</table>
-->
</td></tr>
</table></td></tr>
</table></td></tr><!----  バナー/END  ---->

<!-----  mail  ----->
<tr><td align="center" height="13" width="599"><font size="1" color="#FF9999"><a href="present.html">めるめる･net読者向けページ開始謝恩プレゼント実施中！</a></font></td></tr>
<tr><td align="center" height="28" width="599"><font size="2">□ めるめる・net事務局 □</font><br><a href="mailto:info@melmel.net"><font size="2">info@melmel.net</font></a></td></tr>
<tr><td align="center" height="22" width="599"><img src="images/spacer.gif" width="470" height="20"></td></tr><!-----  mail/END  ----->

</table></td></tr><!-----  ベース・ホワイト/END  ----->

<!----  Bottom Line  ---->
<tr><td align="center" bgcolor="#0099ff"><img src="images/spacer.gif" width="600" height="7"></td></tr>
</table><!----  外枠・ブルー/END  ---->

<!-----  bottom logo・(c)  ----->
<table border="0" cellpadding="0" cellspacing="0">
<tr>
<td rowspan="2" align="right" valign="bottom" width="135"><a href="http://www.melmel.net"><img src="images/cara_s.gif" width="66" height="49" border="0" alt="めるめる イメージ"></a></td>
<td valign="bottom" width="300" height="42"><a href="http://www.melmel.net"><img src="images/logo_s.gif" width="155" height="42" border="0" alt="めるめる・net ロゴ"></a></td>
<tr><td valign="top" height="22"><font size="1">Copyright (C) <%=Year(Date)%> xx xxxxx Inc. All rights reserved.</font></td></tr>
</table><!-----  bottom logo・(c)/END  ----->
</div>

</body>
</html>