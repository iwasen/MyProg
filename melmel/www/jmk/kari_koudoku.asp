<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :‚ß‚é‚ß‚éEnet––±‹Ç—pƒy[ƒW
' Content:‰¼“o˜^ƒƒ‹ƒ}ƒKw“Çè‘±‚«ˆê——‰æ–Ê
'******************************************************

SetHeaderTitle "‰¼“o˜^ƒƒ‹ƒ}ƒKw“Çè‘±‚«", TC_HAKKOUSHA
Response.CacheControl = "no-cache"
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<meta name="GENERATOR" Content="Microsoft Visual Studio 6.0">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="admin.css">
<script LANGUAGE="javascript">
<!--
function OnClick_id(id) {
  var win;
  win = window.open("kari_info.asp?id=" + id, "kari_info", "resizable=yes,scrollbars=yes,width=740");
  win.focus();
}
function OnClick_melonpan(m_name) {
  document.form_melonpan.keyword.value = m_name;
  document.form_melonpan.submit();
}
function OnClick_magmag(m_name) {
  document.form_mag2.keyword.value = m_name;
  document.form_mag2.submit();
}
function OnClick_pubzine(m_name) {
  document.form_pubzine.keyword.value = m_name;
  document.form_pubzine.submit();
}
function OnClick_melma(m_name) {
  document.form_melma.query.value = m_name;
  document.form_melma.submit();
}
function OnClick_macky(m_name) {
  document.form_macky.COMMENT.value = m_name;
  document.form_macky.submit();
}
function OnClick_kapu(m_name) {
  document.form_kapu.key.value = m_name;
  document.form_kapu.submit();
}
function OnClick_emag(m_name) {
  document.form_emag.word.value = m_name;
  document.form_emag.submit();
}
function OnClick_tiara(m_name) {
  document.form_tiara.keyword.value = m_name;
  document.form_tiara.submit();
}
function OnClick_koudoku(id) {
  if (confirm("w’è‚µ‚½ƒƒ‹ƒ}ƒK[ID=" + id + "]‚ğw“Çè‘±‚«Ï‚İ‚É‚µ‚Ü‚·‚©H")) {
    location.href = "kari_koudoku2.asp?id=" + id;
  }
}
//-->
</script>
</head>
<body>

<!--#INCLUDE FILE="admin_header.asp"-->

<%
Dim DB, RS, SQL, id, line, keyword, kensaku

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")
SQL = "SELECT PR_seq_no,PR_m_name,PR_h_name,PR_regist_date,PR_melonpan_id,PR_mgmg_id,PR_mms_melonpan,PR_mms_mag,PR_mms_pubz,PR_mms_melma,PR_mms_macky,PR_mms_kapu,PR_mms_emag,PR_mms_tiara" & vbCrLf & _
      "FROM T_PROVREG" & vbCrLf & _
      "WHERE PR_status=0 AND PR_koudoku_flag=0" & vbCrLf & _
      "ORDER BY PR_seq_no"
RS.Open SQL, DB, adOpenKeyset, adLockReadOnly
%>

<table width="100%" border=0 cellpadding=0 cellspacing=0><tr><td>
<form>

<table width="100%">
  <tr>
    <td align="left">¡‰¼“o˜^ŒãA‚Ü‚¾w“Çè‘±‚«‚ªs‚í‚ê‚Ä‚¢‚È‚¢ƒƒ‹ƒ}ƒK‚Ìˆê—— <font size="-1">iID‚ğƒNƒŠƒbƒN‚·‚é‚Æ‰¼“o˜^î•ñ‚ğ•\¦‚µ‚Ü‚·j</font></td>
    <td align="right">Œ”F<%=RS.RecordCount%></td>
  </tr>
</table>

<table border=1 cellpadding=1 cellspacing=1 width="100%" align="center">
  <tr bgcolor="#FFE0E0">
    <th>‚h‚c</th>
    <th>ƒƒ‹ƒ}ƒK–¼</th>
    <th>”­sÒ–¼</th>
    <th>“o˜^“ú</th>
    <th>ŒŸõ</th>
    <th>w“Ç</th>
  </tr>

<%
line = 1
Do Until RS.EOF
  id = Right("0000" & RS("PR_seq_no"), 5)

  If line Mod 2 = 0 Then
    Writeln "<tr bgcolor='#FFFFFF'>"
  Else
    Writeln "<tr bgcolor='#E0FFE0'>"
  End If
  Writeln "<td align='center'><a href='JavaScript:OnClick_id(""" & id & """)'>" & id & "</a></td>"
  Writeln "<td>" & ReplaceTag(RS("PR_m_name")) & "</td>"
  Writeln "<td>" & ReplaceTag(RS("PR_h_name")) & "</td>"
  Writeln "<td align='center'>" & DateFormat(RS("PR_regist_date")) & "</td>"

  Response.Write "<td><font size='-2'>"
  If RS("PR_mms_melonpan") Then
    keyword = RS("PR_melonpan_id")
    If IsNull(keyword) Then
      keyword = RS("PR_m_name")
    End If
    Writeln "<nobr><a href='JavaScript:OnClick_melonpan(""" & keyword & """)'>‚ß‚ë‚ñ‚Ï‚ñ</a></nobr>"
    kensaku = True
  End If

  If RS("PR_mms_mag") Then
    keyword = RS("PR_mgmg_id")
    If IsNull(keyword) Then
      keyword = RS("PR_m_name")
    End If
    Writeln "<nobr><a href='JavaScript:OnClick_magmag(""" & keyword & """)'>‚Ü‚®‚Ü‚®</a></nobr>"
    kensaku = True
  End If

  kensaku = False
  If RS("PR_mms_pubz") Then
    keyword = RS("PR_m_name")
    Writeln "<nobr><a href='JavaScript:OnClick_pubzine(""" & keyword & """)'>Pubzine</a></nobr>"
    kensaku = True
  End If

  If RS("PR_mms_melma") Then
    keyword = RS("PR_m_name")
    Writeln "<nobr><a href='JavaScript:OnClick_melma(""" & keyword & """)'>melma!</a></nobr>"
    kensaku = True
  End If

  If RS("PR_mms_macky") Then
    keyword = RS("PR_m_name")
    Writeln "<nobr><a href='JavaScript:OnClick_macky(""" & keyword & """)'>Macky</a></nobr>"
    kensaku = True
  End If

  If RS("PR_mms_kapu") Then
    keyword = RS("PR_m_name")
    Writeln "<nobr><a href='JavaScript:OnClick_kapu(""" & keyword & """)'>¶Ìß×²Ä</a></nobr>"
    kensaku = True
  End If

  If RS("PR_mms_emag") Then
    keyword = RS("PR_m_name")
    Writeln "<nobr><a href='JavaScript:OnClick_emag(""" & keyword & """)'>E-Maga</a></nobr>"
    kensaku = True
  End If

  If RS("PR_mms_tiara") Then
    keyword = RS("PR_m_name")
    Writeln "<nobr><a href='JavaScript:OnClick_tiara(""" & keyword & """)'>Ã¨±×</a></nobr>"
    kensaku = True
  End If

  If kensaku = False Then
    Response.Write "<br>"
  End If

  Writeln "</font></td>"

  Writeln "<td align='center'><input type='button' value='Ï‚İ' onClick='OnClick_koudoku(""" & id & """)'></td>"
  Writeln "</tr>"

  RS.MoveNext
  line = line + 1
Loop
RS.close
%>

</table>

</form>

<FORM method="post" action="http://www.melonpan.net/melonpa/search_result.php" target="kensaku" name="form_melonpan">
<INPUT type="hidden" name="mode" value="word">
<INPUT type="hidden" name="keyword">
</FORM>

<form action="http://blues.tegami.com/mag2/scripts/magsearch.dll" method="POST" target="kensaku" name="form_mag2">
<input type="hidden" name="MfcISAPICommand" value="MagSearch">
<input type="hidden" name="keyword">
</form>

<form method="post" action="http://www.pubzine.com/pubzy/tool/srchfree.asp" target="kensaku" name="form_pubzine">
<input type="hidden" value="0" name="genre">
<input type="hidden" name="keyword">
</form>

<FORM ACTION="http://search.melma.com/cgi-bin/search.pl" METHOD="get" target="kensaku" name="form_melma">
<INPUT TYPE="hidden" NAME="query">
</FORM>

<form action="http://macky.nifty.com/cgi-bin/search_by_category.cgi" method="get" target="kensaku" name="form_macky">
<input type=hidden name=cmd value=list>
<input type=hidden name=COMMENT>
</form>

<FORM METHOD="GET" ACTION="http://search.netplaza.biglobe.ne.jp/cgi-bin/search-kapu.cgi" target="kensaku" name="form_kapu">
<INPUT TYPE="hidden" NAME="key">
<INPUT TYPE="hidden" NAME="condition" VALUE="and">
<INPUT TYPE="hidden" NAME="skip" VALUE="0">
</FORM>

<form action="http://www.emaga.com/search/index.cgi" method="GET" target="kensaku" name="form_emag">
<input type="hidden" name="mode" value="search">
<input type="hidden" name="word">
<input type="hidden" name="cond" value="and">
<input type="hidden" name="adult" value="on">
</form>

<FORM action="http://www.tiaraonline.com/magazine/findmaglist.asp" target="kensaku" method="post" name="form_tiara">
<INPUT type="hidden" name="keyword">
<INPUT type="hidden" name="submit22" value="ŒŸõ">
</FORM>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
